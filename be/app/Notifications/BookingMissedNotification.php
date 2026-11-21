<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingMissedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $className,
        public string $scheduleLabel,
        public string $dateLabel,
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
        return [
            'judul_kegiatan' => 'Kelas terlewat',
            'message' => $this->className.' ('.$this->scheduleLabel.', '.$this->dateLabel.') ditandai tidak hadir karena tidak ada pencatatan kehadiran. Kamu bisa memesan lagi kapan saja.',
            'path' => route('schedules.index', [], false),
            'icon' => 'bi-calendar-x',
        ];
    }
}
