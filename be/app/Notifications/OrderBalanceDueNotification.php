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
        $channels = ['database', 'mail'];

        if (config('whatsapp.enabled')
            && method_exists($notifiable, 'routeNotificationForWhatsApp')
            && $notifiable->routeNotificationForWhatsApp()) {
            $channels[] = 'whatsapp';
        }

        return $channels;
    }

    public function toWhatsApp(object $notifiable): array
    {
        $due = $this->dueAt ? \Carbon\Carbon::parse($this->dueAt)->translatedFormat('d F Y') : 'segera';
        $amount = 'Rp '.number_format($this->remainingAmount, 0, ',', '.');
        $message = 'Pengingat '.$this->orderNumber.': sisa '.$amount.', jatuh tempo '.$due.'. Bayar via '.route('orders.show', $this->orderUuid);

        return [
            'to' => method_exists($notifiable, 'routeNotificationForWhatsApp') ? $notifiable->routeNotificationForWhatsApp() : null,
            'message' => $message,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengingat pembayaran | '.$this->orderNumber)
            ->view('emails.notifications.order-balance-due', [
                'customerName' => $notifiable->name,
                'orderNumber' => $this->orderNumber,
                'remainingAmount' => $this->remainingAmount,
                'dueLabel' => $this->dueAt
                    ? \Carbon\Carbon::parse($this->dueAt)->translatedFormat('d F Y')
                    : 'Segera',
                'installmentAmount' => $this->installmentAmount,
                'installmentLabel' => $this->installmentLabel,
                'reminderDays' => $this->reminderDays,
                'orderUrl' => route('orders.show', $this->orderUuid),
            ]);
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
