<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $className,
        public string $scheduleLabel,
        public string $dateLabel,
        public string $window = '24h',
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
        $when = $this->window === '2h'
            ? 'akan dimulai sekitar 2 jam lagi'
            : 'akan berlangsung besok';

        return [
            'judul_kegiatan' => 'Pengingat kelas',
            'message' => $this->className.' ('.$this->scheduleLabel.', '.$this->dateLabel.') '.$when.'. Sampai jumpa di kelas!',
            'path' => route('bookings.my', [], false),
            'icon' => 'bi-alarm',
        ];
    }
}
