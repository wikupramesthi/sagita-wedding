<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderSlaReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $orderUuid,
        public string $orderNumber,
        public string $customerName,
        public int $hours,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'judul_kegiatan' => 'Pesanan menunggu terlalu lama',
            'message' => $this->orderNumber.' — '.$this->customerName
                .' sudah menunggu verifikasi sekitar '.$this->hours.' jam.',
            'path' => route('orders.show', $this->orderUuid, false),
            'icon' => 'bi-hourglass-split',
        ];
    }
}
