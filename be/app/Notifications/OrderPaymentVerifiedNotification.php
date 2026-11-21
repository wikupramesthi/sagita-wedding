<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderPaymentVerifiedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $orderUuid,
        public string $orderNumber,
        public string $message,
        public string $icon = 'bi-check-circle',
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'judul_kegiatan' => 'Pembayaran diverifikasi',
            'message' => $this->orderNumber.' — '.$this->message,
            'path' => route('orders.show', $this->orderUuid, false),
            'icon' => $this->icon,
        ];
    }
}
