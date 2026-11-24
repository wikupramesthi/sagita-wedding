<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CustomPackageRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $requestUuid,
        public string $title,
        public string $message,
        public string $icon = 'bi-chat-square-text',
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
            'path' => route('package-offer-requests.show', $this->requestUuid, false),
            'icon' => $this->icon,
        ];
    }
}
