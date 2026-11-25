<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PendingOrderPaymentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $orderUuid,
        public string $orderNumber,
        public Carbon $expiresAt,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Batas pembayaran pesanan | '.$this->orderNumber)
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Pesanan '.$this->orderNumber.' belum dibayar dan akan kedaluwarsa pada '.$this->expiresAt->translatedFormat('d F Y, H:i').'.')
            ->line('Selesaikan pembayaran dan unggah bukti sebelum batas waktu agar tanggal acara tetap dipesan.')
            ->action('Buka pesanan', route('orders.show', $this->orderUuid));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'pending_order_payment_deadline',
            'title' => 'Batas pembayaran segera berakhir',
            'message' => $this->orderNumber.' belum dibayar dan akan kedaluwarsa pada '.$this->expiresAt->translatedFormat('d F Y, H:i').'.',
            'path' => route('orders.show', $this->orderUuid, false),
            'order_uuid' => $this->orderUuid,
            'order_number' => $this->orderNumber,
            'expires_at' => $this->expiresAt->toIso8601String(),
            'icon' => 'bi-alarm',
        ];
    }
}
