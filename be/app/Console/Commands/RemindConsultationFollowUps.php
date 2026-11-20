<?php

namespace App\Console\Commands;

use App\Models\Consultation;
use App\Models\User;
use App\Notifications\ConsultationFollowUpNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class RemindConsultationFollowUps extends Command
{
    protected $signature = 'consultations:remind-followups';

    protected $description = 'Mengirim pengingat tindak lanjut konsultasi yang jatuh tempo';

    public function handle(): int
    {
        $now = now();
        $total = 0;

        $consultations = Consultation::query()
            ->whereNotNull('follow_up_at')
            ->where('follow_up_at', '<=', $now)
            ->whereNotIn('lead_status', ['Converted', 'Lost'])
            ->where(function ($query) use ($now) {
                $query->whereNull('reminder_sent_at')
                    ->orWhere('reminder_sent_at', '<', $now->copy()->subDay());
            })
            ->with('handler')
            ->limit(500)
            ->get();

        foreach ($consultations as $consultation) {
            $recipients = $consultation->handler
                ? collect([$consultation->handler])
                : User::role(['super-admin', 'admin'])->get();

            if ($recipients->isEmpty()) {
                continue;
            }

            foreach ($recipients as $recipient) {
                try {
                    Notification::send($recipient, new ConsultationFollowUpNotification($consultation));
                    $total++;
                } catch (\Throwable $e) {
                    Log::warning('Gagal mengirim pengingat tindak lanjut konsultasi '.$consultation->uuid.': '.$e->getMessage());
                }
            }

            try {
                $consultation->update(['reminder_sent_at' => $now]);
            } catch (\Throwable $e) {
                Log::warning('Gagal memperbarui reminder_sent_at konsultasi '.$consultation->uuid.': '.$e->getMessage());
            }
        }

        $this->info("Terkirim {$total} notifikasi pengingat tindak lanjut konsultasi.");

        return self::SUCCESS;
    }
}
