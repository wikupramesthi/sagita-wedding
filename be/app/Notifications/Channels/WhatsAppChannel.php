<?php

namespace App\Notifications\Channels;

use App\Contracts\WhatsAppGateway;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Throwable;

class WhatsAppChannel
{
    public function __construct(private WhatsAppGateway $gateway) {}

    public function send($notifiable, Notification $notification): array
    {
        if (! config('whatsapp.enabled') || ! method_exists($notification, 'toWhatsApp')) {
            return [];
        }

        $payload = $notification->toWhatsApp($notifiable);

        $to = is_array($payload) ? ($payload['to'] ?? null) : null;
        $message = is_array($payload) ? ($payload['message'] ?? '') : $payload;

        if (blank($to) && method_exists($notifiable, 'routeNotificationForWhatsApp')) {
            $to = $notifiable->routeNotificationForWhatsApp();
        }

        if (blank($to) || blank($message)) {
            return [];
        }

        $data = [
            'to' => (string) $to,
            'message' => (string) $message,
        ];

        try {
            $sent = $this->gateway->send($data['to'], $data['message'], [
                'notifiable' => $notifiable,
                'notification' => $notification,
            ]);

            if (! $sent) {
                Log::warning('WhatsApp notification was not sent.', ['to' => $data['to']]);
            }
        } catch (Throwable $exception) {
            Log::error('WhatsApp notification failed.', [
                'to' => $data['to'],
                'message' => $exception->getMessage(),
            ]);
        }

        return $data;
    }
}
