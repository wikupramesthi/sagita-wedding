<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MemberActiveNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $orderUuid,
        public string $orderNumber,
        public $expiredAt,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * Keys follow the admin header bell contract:
     * judul_kegiatan + message (+ optional path/icon).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $expiry = $this->expiredAt
            ? ' Aktif hingga '.$this->expiredAt->format('d M Y').'.'
            : '';

        return [
            'judul_kegiatan' => 'Keanggotaan diaktifkan',
            'message' => 'Pembayaran untuk '.$this->orderNumber
                .' telah diverifikasi. Keanggotaan kamu sekarang aktif.'.$expiry,
            'path' => route('orders.show', $this->orderUuid, false),
            'icon' => 'bi-check-circle',
        ];
    }
}
