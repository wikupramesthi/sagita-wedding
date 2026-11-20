<?php

namespace App\Console\Commands;

use App\Models\Consultation;
use App\Models\User;
use App\Notifications\ConsultationFollowUpNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

class RemindConsultationFollowUps extends Command
{
    protected $signature = 'consultations:remind-followups';

    protected $description = 'Mengirim pengingat tindak lanjut konsultasi yang jatuh tempo';

    public function handle(): int
    {
        $now = now();
        $total = 0;
        $roles = Role::whereIn('name', ['super-admin', 'admin'])->pluck('name');
        $fallbackRecipients = $roles->isNotEmpty() ? User::role($roles->all())->get() : collect();

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
                : $fallbackRecipients;

            if ($recipients->isEmpty()) {
                Log::warning('konsultasi.reminder.no_recipient', ['uuid' => $consultation->uuid]);

                continue;
            }

            $sent = 0;

            foreach ($recipients as $recipient) {
                try {
                    Notification::send($recipient, new ConsultationFollowUpNotification($consultation));
                    $sent++;
                    $total++;
                    Log::info('konsultasi.reminder.sent', [
                        'uuid' => $consultation->uuid,
                        'recipient_id' => $recipient->id,
                    ]);
                } catch (\Throwable $exception) {
                    Log::warning('konsultasi.reminder.failed', [
                        'uuid' => $consultation->uuid,
                        'recipient_id' => $recipient->id,
                        'error' => $exception->getMessage(),
                    ]);
                }
            }

            if ($sent === 0) {
                Log::warning('konsultasi.reminder.not_marked', ['uuid' => $consultation->uuid]);

                continue;
            }

            try {
                $consultation->update(['reminder_sent_at' => $now]);
                Log::info('konsultasi.reminder.marked', [
                    'uuid' => $consultation->uuid,
                    'sent' => $sent,
                ]);
            } catch (\Throwable $exception) {
                Log::warning('konsultasi.reminder.mark_failed', [
                    'uuid' => $consultation->uuid,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        Log::info('konsultasi.reminder.finished', ['notifications' => $total]);
        $this->info("Terkirim {$total} notifikasi pengingat tindak lanjut konsultasi.");

        return self::SUCCESS;
    }
}
