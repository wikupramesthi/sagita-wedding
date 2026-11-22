<?php

namespace App\Services\WhatsApp;

use App\Contracts\WhatsAppGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class HttpWhatsAppGateway implements WhatsAppGateway
{
    public function send(string $to, string $message, array $options = []): bool
    {
        $url = (string) config('whatsapp.api_url', '');

        if ($url === '') {
            Log::error('WhatsApp HTTP driver is missing api_url.');

            return false;
        }

        $payload = [
            'to' => self::normalize($to),
            'message' => $message,
        ];

        $from = (string) ($options['from'] ?? config('whatsapp.from', ''));

        if ($from !== '') {
            $payload['from'] = $from;
        }

        try {
            $response = Http::withToken((string) config('whatsapp.token', ''))
                ->timeout((int) config('whatsapp.timeout', 10))
                ->retry((int) config('whatsapp.retry', 2))
                ->post($url, $payload);

            if ($response->successful()) {
                Log::info('WhatsApp HTTP request succeeded.', [
                    'status' => $response->status(),
                    'to_hash' => hash_hmac('sha256', $to, (string) config('app.key')),
                    'message_length' => strlen($message),
                ]);

                return true;
            }

            $body = $response->body();
            Log::warning('WhatsApp HTTP request failed.', [
                'status' => $response->status(),
                'to_hash' => hash_hmac('sha256', $to, (string) config('app.key')),
                'message_length' => strlen($message),
                'body_length' => strlen($body),
                'body_hash' => hash_hmac('sha256', $body, (string) config('app.key')),
            ]);
        } catch (Throwable $exception) {
            Log::error('WhatsApp HTTP request threw an exception.', [
                'exception' => get_class($exception),
                'code' => $exception->getCode(),
                'to_hash' => hash_hmac('sha256', $to, (string) config('app.key')),
                'message_length' => strlen($message),
            ]);
        }

        return false;
    }

    public function link(string $to, string $message): ?string
    {
        $number = self::normalize($to);

        if ($number === '') {
            return null;
        }

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }

    private static function normalize(string $number): string
    {
        $digits = preg_replace('/\D+/', '', $number) ?? '';

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (str_starts_with($digits, '8')) {
            return '62'.$digits;
        }

        return str_starts_with($digits, '62') ? $digits : '';
    }
}
