<?php

namespace App\Notifications;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ConsultationFollowUpNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Consultation $consultation,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'judul_kegiatan' => 'Tindak lanjut konsultasi jatuh tempo',
            'message' => $this->consultation->full_name.' - jadwal tindak lanjut: '.$this->consultation->follow_up_date_label,
            'path' => route('konsultasi.show', $this->consultation->uuid, false),
            'icon' => 'bi-clock-history',
        ];
    }
}
