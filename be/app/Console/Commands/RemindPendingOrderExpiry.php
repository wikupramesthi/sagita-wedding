<?php

namespace App\Console\Commands;

use App\Models\Payment\Order;
use App\Notifications\PendingOrderPaymentReminderNotification;
use App\Services\TelegramAdminNotifier;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RemindPendingOrderExpiry extends Command
{
    protected $signature = 'orders:remind-pending-expiry';

    protected $description = 'Send a one-time customer reminder two hours before a pending order expires';

    public function handle(): int
    {
        $sent = 0;
        $failed = 0;

        Order::packageOnly()
            ->where('status', 'pending')
            ->whereNotNull('expired_at')
            ->where('expired_at', '>', now())
            ->where('expired_at', '<=', now()->addHours(2))
            ->whereNull('pending_payment_reminded_at')
            ->chunkById(100, function ($orders) use (&$sent, &$failed): void {
                foreach ($orders as $order) {
                    try {
                        $wasSent = DB::transaction(function () use ($order): bool {
                            $locked = Order::packageOnly()
                                ->with('user')
                                ->where('uuid', $order->uuid)
                                ->lockForUpdate()
                                ->first();

                            if (
                                ! $locked ||
                                $locked->status !== 'pending' ||
                                $locked->pending_payment_reminded_at ||
                                ! $locked->expired_at?->isFuture() ||
                                $locked->expired_at->gt(now()->addHours(2)) ||
                                ! $locked->user
                            ) {
                                return false;
                            }

                            $locked->user->notify(new PendingOrderPaymentReminderNotification(
                                $locked->uuid,
                                $locked->order_number,
                                $locked->expired_at,
                            ));
                            $locked->forceFill(['pending_payment_reminded_at' => now()])->save();

                            return true;
                        });

                        if ($wasSent) {
                            $sent++;

                            try {
                                app(TelegramAdminNotifier::class)->orderExpiringSoon($order);
                            } catch (Throwable $exception) {
                                Log::warning('Pending order expiry Telegram notification failed.', [
                                    'order_uuid' => $order->uuid,
                                    'message' => $exception->getMessage(),
                                ]);
                            }
                        }
                    } catch (Throwable $exception) {
                        $failed++;
                        Log::error('Pending order payment reminder failed.', [
                            'order_uuid' => $order->uuid,
                            'exception' => get_class($exception),
                            'message' => $exception->getMessage(),
                        ]);
                    }
                }
            }, 'uuid');

        $this->info("Sent {$sent} pending order payment reminders; {$failed} failed.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
