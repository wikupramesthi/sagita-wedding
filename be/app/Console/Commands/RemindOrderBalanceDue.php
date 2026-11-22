<?php

namespace App\Console\Commands;

use App\Models\Payment\Order;
use App\Notifications\OrderBalanceDueNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemindOrderBalanceDue extends Command
{
    protected $signature = 'orders:remind-balance-due';
    protected $description = 'Send installment reminders at H-30, H-7, and H-1';

    public function handle(): int
    {
        $orders = Order::packageOnly()
            ->with(['user','package'])
            ->where('status', 'partially_paid')
            ->get();

        $sent = 0;
        $failed = 0;
        foreach ($orders as $order) {
            $remaining = (float) $order->remaining_amount_value;
            if ($remaining <= 0 || ! $order->user) {
                continue;
            }

            try {
                $installments = data_get($order->installment_schedule, 'installments', []);
                if ($installments !== []) {
                    $markers = $order->installment_reminders_sent ?? [];
                    $changed = false;

                    foreach ($installments as $index => $installment) {
                        if (empty($installment['due_at']) || (int) ($installment['amount'] ?? 0) <= 0) {
                            continue;
                        }

                        $dueAt = Carbon::parse($installment['due_at'])->startOfDay();
                        $daysUntilDue = now()->startOfDay()->diffInDays($dueAt, false);
                        $reminderDay = match (true) {
                            $daysUntilDue <= 1 && $daysUntilDue >= 0 => 1,
                            $daysUntilDue <= 7 && $daysUntilDue > 1 => 7,
                            $daysUntilDue <= 30 && $daysUntilDue > 7 => 30,
                            default => null,
                        };

                        if ($reminderDay === null) {
                            continue;
                        }

                        $marker = $index.':h-'.$reminderDay;
                        if (! empty($markers[$marker])) {
                            continue;
                        }

                        $order->user->notify(new OrderBalanceDueNotification(
                            $order->uuid,
                            $order->order_number,
                            $remaining,
                            $dueAt,
                            (float) $installment['amount'],
                            (string) ($installment['label'] ?? 'Cicilan'),
                            $reminderDay
                        ));
                        $markers[$marker] = now()->toDateTimeString();
                        $changed = true;
                        $sent++;
                    }

                    if ($changed) {
                        $order->forceFill(['installment_reminders_sent' => $markers])->save();
                    }

                    continue;
                }

                // Preserve the single H-30 reminder for orders created before installment schedules.
                if ($order->balance_reminder_sent_at) {
                    continue;
                }

                $due = $order->balance_due_at ?: ($order->event_date ? $order->event_date->copy()->startOfDay() : null);
                if ($due && $due->isAfter(now()->addDays(30)->endOfDay())) {
                    continue;
                }
                if (! $due && ! $order->event_date) {
                    continue;
                }

                $order->user->notify(new OrderBalanceDueNotification($order->uuid, $order->order_number, $remaining, $due));
                $order->forceFill(['balance_reminder_sent_at' => now()])->save();
                $sent++;
            } catch (\Throwable $e) {
                $failed++;
                Log::error('Order balance reminder failed: '.$e->getMessage(), [
                    'order_uuid' => $order->uuid,
                    'exception' => $e,
                ]);
            }
        }
        $this->info("Sent {$sent} balance reminders; {$failed} failed.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
