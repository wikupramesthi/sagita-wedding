<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CheckoutAbandonedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $packageUuid,
        public string $packageName,
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

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Lanjutkan pemesanan paket Anda')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Anda sempat memulai pemesanan '.$this->packageName.', tetapi pesanan belum dibuat.')
            ->action('Lanjutkan pemesanan', route('checkout.package', $this->packageUuid))
            ->line('Jika sudah tidak berminat, abaikan pesan ini.');
    }

    public function toWhatsApp(object $notifiable): array
    {
        return [
            'to' => $notifiable->routeNotificationForWhatsApp(),
            'message' => 'Halo '.$notifiable->name.', Anda sempat memulai pemesanan '.$this->packageName.'. Lanjutkan di '.route('checkout.package', $this->packageUuid),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'checkout_abandoned',
            'title' => 'Pemesanan belum selesai',
            'message' => 'Anda sempat memulai pemesanan '.$this->packageName.'. Lanjutkan jika masih berminat.',
            'icon' => 'bi-cart',
            'url' => route('checkout.package', $this->packageUuid),
            'package_uuid' => $this->packageUuid,
        ];
    }
}
