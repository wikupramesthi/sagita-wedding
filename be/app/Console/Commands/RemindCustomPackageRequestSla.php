<?php

namespace App\Console\Commands;

use App\Models\Package\CustomPackageOfferRequest;
use App\Models\User;
use App\Notifications\CustomPackageRequestNotification;
use App\Services\CustomPackageWorkflowAudit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemindCustomPackageRequestSla extends Command
{
    protected $signature = 'package-offer-requests:remind-sla';

    protected $description = 'Notify the assigned admin when a package request reaches its review SLA';

    public function handle(CustomPackageWorkflowAudit $audit): int
    {
        $sent = 0;

        CustomPackageOfferRequest::query()
            ->whereIn('status', ['submitted', 'in_review'])
            ->whereNotNull('review_due_at')
            ->where('review_due_at', '<=', now())
            ->whereNull('sla_notified_at')
            ->orderBy('uuid')
            ->chunkById(100, function ($requests) use (&$sent, $audit) {
                foreach ($requests as $packageRequest) {
                    $wasSent = DB::transaction(function () use ($packageRequest, $audit) {
                        $locked = CustomPackageOfferRequest::with(['assignedAdmin', 'user'])
                            ->where('uuid', $packageRequest->uuid)
                            ->lockForUpdate()
                            ->first();

                        if (
                            ! $locked ||
                            ! in_array($locked->status, ['submitted', 'in_review'], true) ||
                            $locked->sla_notified_at ||
                            ! $locked->review_due_at?->isPast()
                        ) {
                            return false;
                        }

                        $recipients = $locked->assignedAdmin
                            ? collect([$locked->assignedAdmin])
                            : User::role(['super-admin', 'admin'])->get();
                        if ($recipients->isEmpty()) {
                            return false;
                        }

                        foreach ($recipients as $recipient) {
                            $recipient->notify(new CustomPackageRequestNotification(
                                $locked->uuid,
                                'Tenggat tinjauan permintaan tercapai',
                                'Permintaan paket dari '.($locked->user?->name ?? 'pelanggan').' telah menunggu lebih dari 24 jam.',
                                'bi-alarm'
                            ));
                        }

                        $locked->update(['sla_notified_at' => now()]);
                        $audit->record(
                            null,
                            'request_sla_reminder_sent',
                            $locked->uuid,
                            null,
                            $locked->status,
                            $locked->status,
                            ['recipient_count' => $recipients->count()]
                        );

                        return true;
                    });

                    if ($wasSent) {
                        $sent++;
                    }
                }
            }, 'uuid');

        $this->info("Sent {$sent} package request SLA reminders.");

        return self::SUCCESS;
    }
}
