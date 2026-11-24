<?php

namespace App\Console\Commands;

use App\Models\Package\CustomPackageOffer;
use App\Notifications\CustomPackageOfferNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemindCustomPackageOfferExpiry extends Command
{
    protected $signature = 'package-offers:remind-expiry';

    protected $description = 'Send a one-time reminder one day before a custom package offer expires';

    public function handle(): int
    {
        $sent = 0;
        $windowStart = now()->addHours(23);
        $windowEnd = now()->addHours(25);

        CustomPackageOffer::query()
            ->with('user')
            ->where('status', 'sent')
            ->whereNull('expiry_reminded_at')
            ->whereBetween('expires_at', [$windowStart, $windowEnd])
            ->orderBy('uuid')
            ->chunkById(100, function ($offers) use (&$sent) {
                foreach ($offers as $offer) {
                    $wasSent = DB::transaction(function () use ($offer) {
                        $locked = CustomPackageOffer::with('user')
                            ->where('uuid', $offer->uuid)
                            ->lockForUpdate()
                            ->first();

                        if (
                            ! $locked ||
                            $locked->status !== 'sent' ||
                            $locked->expiry_reminded_at ||
                            ! $locked->expires_at?->between(now()->addHours(23), now()->addHours(25)) ||
                            ! $locked->user
                        ) {
                            return false;
                        }

                        $locked->user->notify(new CustomPackageOfferNotification(
                            $locked->uuid,
                            'Penawaran akan segera berakhir',
                            'Penawaran '.$locked->title.' akan berakhir pada '.$locked->expires_at->translatedFormat('d F Y, H:i').'.',
                            'bi-alarm'
                        ));
                        $locked->update(['expiry_reminded_at' => now()]);

                        return true;
                    });

                    if ($wasSent) {
                        $sent++;
                    }
                }
            }, 'uuid');

        $this->info("Sent {$sent} custom offer expiry reminders.");

        return self::SUCCESS;
    }
}
