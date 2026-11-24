<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CustomPackageOfferNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $offerUuid,
        public string $title,
        public string $message,
        public string $icon = 'bi-box-seam',
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'judul_kegiatan' => $this->title,
            'message' => $this->message,
            'path' => route('package-offers.show', $this->offerUuid, false),
            'icon' => $this->icon,
        ];
    }
}
