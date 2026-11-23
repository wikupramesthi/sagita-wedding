<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Payment\Order;
use App\Models\UserPackage;
use App\Notifications\OrderExpiredNotification;
use App\Services\EventDateReservationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpireStaleRecords extends Command
{
    private const BATCH_SIZE = 500;

    private const MAX_PER_RUN = 10000;

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
        $expiredOrders = 0;
        $reservations = app(EventDateReservationService::class);

        while ($expiredOrders < self::MAX_PER_RUN) {
            $staleOrders = Order::packageOnly()->with('user')
                ->where('status', 'pending')
                ->whereNotNull('expired_at')
                ->where('expired_at', '<', now())
                ->limit(min(self::BATCH_SIZE, self::MAX_PER_RUN - $expiredOrders))
                ->get();

            if ($staleOrders->isEmpty()) {
                break;
            }

            $processed = 0;

            foreach ($staleOrders as $order) {
                $expired = DB::transaction(function () use ($order, $reservations): bool {
                    $locked = Order::packageOnly()
                        ->where('uuid', $order->uuid)
                        ->lockForUpdate()
                        ->first();

                    if (! $locked || $locked->status !== 'pending' || ! $locked->expired_at?->isPast()) {
                        return false;
                    }

                    $locked->update(['status' => 'expired']);
                    $locked->payments()
                        ->where(function ($query) {
                            $query->where('status', 'pending')
                                ->orWhere('verification_status', 'pending');
                        })
                        ->update([
                            'status' => 'failed',
                            'verification_status' => 'rejected',
                        ]);
                    $reservations->releaseForOrder($locked->uuid);

                    AuditLog::create([
                        'user_name' => 'Sistem',
                        'event' => 'order_expired',
                        'auditable_type' => Order::class,
                        'auditable_id' => (string) $locked->uuid,
                        'auditable_label' => $locked->order_number,
                        'old_values' => ['status' => 'pending'],
                        'new_values' => ['status' => 'expired'],
                        'url' => 'cli',
                    ]);

                    return true;
                });

                if (! $expired) {
                    continue;
                }

                $expiredOrders++;
                $processed++;
                try {
                    $order->user?->notify(new OrderExpiredNotification($order->order_number));
                } catch (\Throwable $e) {
                    Log::warning('Order expiry notification failed for '.$order->order_number.': '.$e->getMessage());
                }
            }

            if ($processed === 0) {
                break;
            }
        }

        $expiredPackages = UserPackage::where('status', 'active')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->update(['status' => 'expired']);

        $message = "Expired {$expiredOrders} orders and {$expiredPackages} memberships.";

        $this->info($message);
        Log::info('records:expire: '.$message);

        return self::SUCCESS;
    }
}
