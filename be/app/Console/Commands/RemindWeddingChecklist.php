<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Payment\WeddingChecklistItem;
use App\Models\User;
use App\Notifications\WeddingChecklistDueNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

class RemindWeddingChecklist extends Command
{
    protected $signature = 'checklists:remind-due';

    protected $description = 'Mengirim pengingat dan eskalasi checklist persiapan yang jatuh tempo';

    public function handle(): int
    {
        $now = now();
        $today = $now->copy()->startOfDay();
        $end = $today->copy()->addDays(7);
        $sent = 0;

        $items = DB::transaction(function () use ($today, $end) {
            return WeddingChecklistItem::query()
                ->where('status', 'pending')
                ->whereNotNull('due_at')
                ->where(function ($query) use ($today, $end) {
                    $query->where(function ($query) use ($today, $end) {
                        $query->whereBetween('due_at', [$today->toDateString(), $end->toDateString()])
                            ->whereNull('reminder_sent_at');
                    })->orWhere(function ($query) use ($today) {
                        $query->where('due_at', '<', $today->toDateString())
                            ->whereNull('escalation_sent_at');
                    });
                })
                ->whereHas('order', function ($query): void {
                    $query->whereIn('status', ['partially_paid', 'paid']);
                })
                ->with(['order.user', 'order.assignedAdmin'])
                ->orderBy('due_at')
                ->lockForUpdate()
                ->limit(500)
                ->get();
        });

        $roles = Role::whereIn('name', ['super-admin', 'admin'])->pluck('name');
        $fallbackRecipients = $roles->isNotEmpty() ? User::role($roles->all())->get() : collect();

        foreach ($items as $item) {
            try {
                $sent += DB::transaction(function () use ($item, $today, $end, $now, $fallbackRecipients): int {
                    $locked = WeddingChecklistItem::query()
                        ->where($item->getKeyName(), $item->getKey())
                        ->lockForUpdate()
                        ->first();

                    if (! $locked || $locked->status !== 'pending' || ! $locked->due_at) {
                        return 0;
                    }

                    $dueAt = Carbon::parse($locked->due_at)->startOfDay();
                    $isEscalation = $dueAt->lt($today);
                    $marker = $isEscalation ? 'escalation_sent_at' : 'reminder_sent_at';

                    if (! $isEscalation && ! $dueAt->between($today, $end)) {
                        return 0;
                    }

                    if ($locked->{$marker} !== null) {
                        return 0;
                    }

                    $locked->load(['order.user', 'order.assignedAdmin']);
                    $order = $locked->order;

                    if (! $order || ! in_array($order->status, ['partially_paid', 'paid'], true)) {
                        return 0;
                    }

                    $recipients = $isEscalation
                        ? ($order->assignedAdmin ? collect([$order->assignedAdmin]) : $fallbackRecipients)
                        : ($order->user ? collect([$order->user]) : collect());

                    if ($recipients->isEmpty()) {
                        Log::warning('checklists.remind_due.no_recipient', [
                            'item' => $locked->uuid,
                            'escalation' => $isEscalation,
                        ]);

                        return 0;
                    }

                    $notification = new WeddingChecklistDueNotification(
                        $order->uuid,
                        $order->order_number,
                        $locked->title,
                        $dueAt,
                        $isEscalation,
                    );
                    $delivered = 0;

                    foreach ($recipients as $recipient) {
                        try {
                            Notification::send($recipient, $notification);
                            $delivered++;
                        } catch (\Throwable $exception) {
                            Log::warning('checklists.remind_due.failed', [
                                'item' => $locked->uuid,
                                'recipient' => $recipient->uuid ?? null,
                                'error' => $exception->getMessage(),
                            ]);
                        }
                    }

                    if ($delivered === 0) {
                        return 0;
                    }

                    if ($delivered < $recipients->count()) {
                        Log::warning('checklists.remind_due.partial', [
                            'item' => $locked->uuid,
                            'delivered' => $delivered,
                            'recipients' => $recipients->count(),
                        ]);

                        return $delivered;
                    }

                    $locked->update([$marker => $now->toDateTimeString()]);

                    AuditLog::create([
                        'user_name' => 'Sistem',
                        'event' => $isEscalation ? 'checklist_escalated' : 'checklist_reminded',
                        'auditable_type' => WeddingChecklistItem::class,
                        'auditable_id' => (string) $locked->uuid,
                        'auditable_label' => $order->order_number.' — '.$locked->title,
                        'old_values' => [$marker => null],
                        'new_values' => [$marker => $now->toDateTimeString()],
                        'url' => 'cli',
                    ]);

                    return $delivered;
                });
            } catch (\Throwable $exception) {
                Log::warning('checklists.remind_due.item_failed', [
                    'item' => $item->uuid,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        Log::info('checklists.remind_due.finished', ['notifications' => $sent]);
        $this->info("Terkirim {$sent} notifikasi checklist persiapan.");

        return self::SUCCESS;
    }
}
