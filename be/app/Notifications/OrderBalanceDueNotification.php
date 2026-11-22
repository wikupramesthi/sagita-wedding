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
        public ?float $installmentAmount = null,
        public ?string $installmentLabel = null,
        public ?int $reminderDays = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $due = $this->dueAt ? \Carbon\Carbon::parse($this->dueAt)->translatedFormat('d F Y') : 'segera';
        $mail = (new MailMessage)
            ->subject('Pengingat Pembayaran - ' . $this->orderNumber)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Sisa pembayaran untuk pesanan ' . $this->orderNumber . ' belum lunas.')
            ->line('Sisa tagihan saat ini: Rp ' . number_format($this->remainingAmount, 0, ',', '.'));

        if ($this->installmentAmount !== null) {
            $mail->line(($this->installmentLabel ?: 'Termin pembayaran') . ': Rp ' . number_format($this->installmentAmount, 0, ',', '.'));
            $mail->line('Pengingat H-' . $this->reminderDays . ', jatuh tempo ' . $due . '.');
        } else {
            $mail->line('Batas pelunasan: ' . $due . ' (maks. 30 hari sebelum acara).');
        }

        return $mail
            ->action('Lihat Pesanan', route('orders.show', $this->orderUuid))
            ->line('Silakan lakukan pembayaran melalui halaman detail pesanan.');
    }

    public function toArray(object $notifiable): array
    {
        $due = $this->dueAt ? \Carbon\Carbon::parse($this->dueAt)->translatedFormat('d F Y') : null;
        $message = 'Sisa pembayaran ' . $this->orderNumber . ' sebesar Rp ' . number_format($this->remainingAmount, 0, ',', '.');
        if ($this->installmentAmount !== null) {
            $message .= '. ' . ($this->installmentLabel ?: 'Termin pembayaran') . ' Rp ' . number_format($this->installmentAmount, 0, ',', '.') . ' jatuh tempo ' . ($due ?: 'segera') . ' (pengingat H-' . $this->reminderDays . ')';
        } elseif ($due) {
            $message .= ' jatuh tempo ' . $due;
        }

        return [
            'type' => 'order_balance_due',
            'title' => $this->installmentAmount !== null ? 'Pengingat Cicilan' : 'Pengingat Pelunasan',
            'message' => $message . '.',
            'icon' => 'bi-alarm',
            'url' => route('orders.show', $this->orderUuid),
            'order_uuid' => $this->orderUuid,
            'order_number' => $this->orderNumber,
            'installment_label' => $this->installmentLabel,
            'reminder_days' => $this->reminderDays,
        ];
    }
}
