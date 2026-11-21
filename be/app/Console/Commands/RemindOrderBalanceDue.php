<?php

namespace App\Console\Commands;

use App\Models\Payment\Order;
use App\Notifications\OrderBalanceDueNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemindOrderBalanceDue extends Command
{
    protected $signature = 'orders:remind-balance-due';
    protected $description = 'Send H-30 reminder for partially paid orders with outstanding balance';

    public function handle(): int
    {
        $orders = Order::packageOnly()
            ->with(['user','package'])
            ->where('status', 'partially_paid')
            ->whereNull('balance_reminder_sent_at')
            ->get();

        $sent = 0;
        foreach ($orders as $order) {
            $remaining = (float) $order->remaining_amount_value;
            if ($remaining <= 0 || ! $order->user) continue;
            $due = $order->balance_due_at ?: ($order->event_date ? $order->event_date->copy()->startOfDay() : null);
            if ($due && $due->isAfter(now()->addDays(30)->endOfDay())) continue;
            if (! $due && ! $order->event_date) continue;
            try {
                $order->user->notify(new OrderBalanceDueNotification($order->uuid, $order->order_number, $remaining, $due));
                $order->forceFill(['balance_reminder_sent_at' => now()])->save();
                $sent++;
            } catch (\Throwable $e) {
                Log::error('Order balance reminder failed: '.$e->getMessage(), ['order_uuid' => $order->uuid]);
            }
        }
        $this->info("Sent {$sent} balance reminders.");
        return self::SUCCESS;
    }
}
