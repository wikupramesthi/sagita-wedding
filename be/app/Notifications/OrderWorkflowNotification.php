<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderWorkflowNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $orderUuid,
        public string $title,
        public string $message,
        public string $icon = 'bi-calendar2-check',
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
            'path' => route('orders.show', $this->orderUuid, false),
            'icon' => $this->icon,
        ];
    }
}
