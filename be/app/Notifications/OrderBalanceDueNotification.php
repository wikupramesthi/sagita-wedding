<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderBalanceDueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $orderUuid,
        public string $orderNumber,
        public float $remainingAmount,
        public $dueAt = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $due = $this->dueAt ? \Carbon\Carbon::parse($this->dueAt)->translatedFormat('d F Y') : 'segera';
        return (new MailMessage)
            ->subject('Pengingat Pelunasan - ' . $this->orderNumber)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Sisa pembayaran untuk pesanan ' . $this->orderNumber . ' belum lunas.')
            ->line('Sisa tagihan: Rp ' . number_format($this->remainingAmount, 0, ',', '.'))
            ->line('Batas pelunasan: ' . $due . ' (maks. 30 hari sebelum acara).')
            ->action('Lihat Pesanan', route('orders.show', $this->orderUuid))
            ->line('Silakan lakukan cicilan 20% atau pelunasan agar pesanan tetap aktif.');
    }

    public function toArray(object $notifiable): array
    {
        $due = $this->dueAt ? \Carbon\Carbon::parse($this->dueAt)->translatedFormat('d F Y') : null;
        return [
            'type' => 'order_balance_due',
            'title' => 'Pengingat Pelunasan',
            'message' => 'Sisa pembayaran ' . $this->orderNumber . ' sebesar Rp ' . number_format($this->remainingAmount, 0, ',', '.') . ($due ? ' jatuh tempo ' . $due : '') . '.',
            'icon' => 'bi-alarm',
            'url' => route('orders.show', $this->orderUuid),
            'order_uuid' => $this->orderUuid,
            'order_number' => $this->orderNumber,
        ];
    }
}
