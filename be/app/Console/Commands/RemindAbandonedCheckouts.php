<?php

namespace App\Console\Commands;

use App\Models\Payment\CheckoutSession;
use App\Notifications\CheckoutAbandonedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RemindAbandonedCheckouts extends Command
{
    protected $signature = 'checkouts:remind-abandoned {--hours=24}';

    protected $description = 'Mengirim satu pengingat untuk pemesanan pelanggan yang belum selesai';

    public function handle(): int
    {
        $hours = max(1, (int) $this->option('hours'));
        $cutoff = now()->subHours($hours);
        $sessions = CheckoutSession::query()
            ->where('status', 'started')
            ->whereNotNull('user_uuid')
            ->whereNull('reminder_sent_at')
            ->where('started_at', '<=', $cutoff)
            ->whereHas('package', fn ($query) => $query->published())
            ->orderBy('started_at')
            ->limit(500)
            ->pluck('uuid');

        $sent = 0;
        $failed = 0;

        foreach ($sessions as $sessionUuid) {
            try {
                $wasSent = DB::transaction(function () use ($sessionUuid, $cutoff): bool {
                    $session = CheckoutSession::where('uuid', $sessionUuid)
                        ->lockForUpdate()
                        ->first();

                    if (
                        ! $session
                        || $session->status !== 'started'
                        || $session->reminder_sent_at
                        || $session->started_at->gt($cutoff)
                    ) {
                        return false;
                    }

                    $session->load(['user', 'package']);
                    if (! $session->user || ! $session->package) {
                        return false;
                    }

                    $session->user->notify(new CheckoutAbandonedNotification(
                        $session->package->uuid,
                        $session->package->name,
                    ));
                    $session->update(['reminder_sent_at' => now()]);

                    return true;
                });

                if ($wasSent) {
                    $sent++;
                }
            } catch (Throwable $exception) {
                $failed++;
                Log::error('Abandoned checkout reminder failed.', [
                    'checkout_session_uuid' => $sessionUuid,
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        $this->info("Terkirim {$sent} pengingat checkout; gagal {$failed}.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
