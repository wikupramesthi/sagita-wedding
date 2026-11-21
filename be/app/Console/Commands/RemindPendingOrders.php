<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Payment\Order;
use App\Models\User;
use App\Notifications\OrderSlaReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

class RemindPendingOrders extends Command
{
    protected $signature = 'orders:remind-sla {--hours=24}';

    protected $description = 'Mengirim pengingat SLA untuk pesanan yang menunggu verifikasi';

    public function handle(): int
    {
        $hoursThreshold = max(1, (int) $this->option('hours'));
        $now = now();
        $sent = 0;

        $orders = Order::packageOnly()
            ->where('status', 'pending')
            ->whereNotNull('created_at')
            ->where('created_at', '<=', $now->copy()->subHours($hoursThreshold))
            ->where(function ($query) use ($now, $hoursThreshold) {
                $query->whereNull('sla_reminded_at')
                    ->orWhere('sla_reminded_at', '<=', $now->copy()->subHours($hoursThreshold));
            })
            ->with(['user', 'assignedAdmin'])
            ->limit(500)
            ->get();

        $roles = Role::whereIn('name', ['super-admin', 'admin'])->pluck('name');
        $fallbackRecipients = $roles->isNotEmpty() ? User::role($roles->all())->get() : collect();

        foreach ($orders as $order) {
            $ageHours = (int) $order->created_at->diffInHours($now);
            $level = $ageHours >= 48 ? 48 : $hoursThreshold;

            if (
                $order->sla_reminded_at
                && $order->created_at->diffInHours($order->sla_reminded_at) >= $level
            ) {
                continue;
            }

            $recipients = $order->assignedAdmin
                ? collect([$order->assignedAdmin])
                : $fallbackRecipients;

            if ($recipients->isEmpty()) {
                Log::warning('orders.sla.no_recipient', ['order' => $order->uuid]);

                continue;
            }

            $delivered = 0;

            foreach ($recipients as $recipient) {
                try {
                    Notification::send($recipient, new OrderSlaReminderNotification(
                        $order->uuid,
                        $order->order_number,
                        $order->user?->name ?? '-',
                        $ageHours,
                    ));
                    $delivered++;
                    $sent++;
                } catch (\Throwable $exception) {
                    Log::warning('orders.sla.failed', [
                        'order' => $order->uuid,
                        'recipient' => $recipient->uuid ?? null,
                        'error' => $exception->getMessage(),
                    ]);
                }
            }

            if ($delivered === 0) {
                continue;
            }

            $order->update(['sla_reminded_at' => $now]);
            AuditLog::create([
                'user_name' => 'Sistem',
                'event' => 'sla_reminded',
                'auditable_type' => Order::class,
                'auditable_id' => (string) $order->uuid,
                'auditable_label' => $order->order_number,
                'new_values' => ['hours' => $ageHours, 'level' => $level],
                'url' => 'cli',
            ]);
        }

        Log::info('orders.sla.finished', ['notifications' => $sent]);
        $this->info("Terkirim {$sent} pengingat SLA pesanan.");

        return self::SUCCESS;
    }
}
