<?php

namespace App\Console\Commands;

use App\Models\Payment\Order;
use App\Models\UserPackage;
use App\Notifications\OrderExpiredNotification;
use App\Services\EventDateReservationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireStaleRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'records:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending orders and memberships past their expiry time';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $staleOrders = Order::packageOnly()->with('user')
            ->where('status', 'pending')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->limit(500)
            ->get();

        $expiredOrders = 0;
        $reservations = app(EventDateReservationService::class);

        foreach ($staleOrders as $order) {
            $expired = \Illuminate\Support\Facades\DB::transaction(function () use ($order, $reservations): bool {
                $locked = Order::packageOnly()
                    ->where('uuid', $order->uuid)
                    ->lockForUpdate()
                    ->first();

                if (! $locked || $locked->status !== 'pending' || ! $locked->expired_at?->isPast()) {
                    return false;
                }

                $locked->update(['status' => 'expired']);
                $reservations->releaseForOrder($locked->uuid);

                return true;
            });

            if (! $expired) {
                continue;
            }

            $expiredOrders++;
            try {
                $order->user?->notify(new OrderExpiredNotification($order->order_number));
            } catch (\Throwable $e) {
                Log::warning('Order expiry notification failed for ' . $order->order_number . ': ' . $e->getMessage());
            }
        }

        $expiredPackages = UserPackage::where('status', 'active')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->update(['status' => 'expired']);

        $message = "Expired {$expiredOrders} orders and {$expiredPackages} memberships.";

        $this->info($message);
        Log::info('records:expire: ' . $message);

        return self::SUCCESS;
    }
}
