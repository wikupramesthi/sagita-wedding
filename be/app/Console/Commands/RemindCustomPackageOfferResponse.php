<?php

namespace App\Console\Commands;

use App\Models\Package\CustomPackageOffer;
use App\Services\CustomPackageWorkflowAudit;
use App\Notifications\CustomPackageOfferNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemindCustomPackageOfferResponse extends Command
{
    protected $signature = 'package-offers:remind-response';

    protected $description = 'Send a one-time reminder when a customer has not responded to an offer for three days';

    public function handle(CustomPackageWorkflowAudit $audit): int
    {
        $sent = 0;

        CustomPackageOffer::query()
            ->where('status', 'sent')
            ->whereNotNull('sent_at')
            ->where('sent_at', '<=', now()->subDays(3))
            ->where('expires_at', '>', now())
            ->whereNull('response_reminded_at')
            ->orderBy('uuid')
            ->chunkById(100, function ($offers) use (&$sent, $audit) {
                foreach ($offers as $offer) {
                    $wasSent = DB::transaction(function () use ($offer, $audit) {
                        $locked = CustomPackageOffer::with('user')
                            ->where('uuid', $offer->uuid)
                            ->lockForUpdate()
                            ->first();

                        if (
                            ! $locked ||
                            $locked->status !== 'sent' ||
                            $locked->response_reminded_at ||
                            ! $locked->sent_at?->lte(now()->subDays(3)) ||
                            ! $locked->expires_at?->isFuture() ||
                            ! $locked->user
                        ) {
                            return false;
                        }

                        $locked->user->notify(new CustomPackageOfferNotification(
                            $locked->uuid,
                            'Pengingat tanggapan penawaran',
                            'Penawaran '.$locked->title.' masih menunggu tanggapan Anda dan berlaku hingga '.$locked->expires_at->translatedFormat('d F Y, H:i').'.',
                            'bi-bell'
                        ));
                        $locked->update(['response_reminded_at' => now()]);
                        $audit->record(
                            null,
                            'offer_response_reminder_sent',
                            $locked->source_request_uuid,
                            $locked->uuid,
                            'sent',
                            'sent',
                            ['sent_at' => $locked->sent_at->toIso8601String()]
                        );

                        return true;
                    });

                    if ($wasSent) {
                        $sent++;
                    }
                }
            }, 'uuid');

        $this->info("Sent {$sent} customer offer response reminders.");

        return self::SUCCESS;
    }
}
