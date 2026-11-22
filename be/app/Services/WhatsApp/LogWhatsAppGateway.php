<?php

namespace App\Services\WhatsApp;

use App\Contracts\WhatsAppGateway;
use Illuminate\Support\Facades\Log;

class LogWhatsAppGateway implements WhatsAppGateway
{
    public function send(string $to, string $message, array $options = []): bool
    {
        Log::info('WhatsApp message via log driver', [
            'status' => 'sent',
            'to_hash' => hash_hmac('sha256', $to, (string) config('app.key')),
            'message_length' => strlen($message),
            'message_hash' => hash_hmac('sha256', $message, (string) config('app.key')),
        ]);

        return true;
    }

    public function link(string $to, string $message): ?string
    {
        $number = self::normalize($to);

        if ($number === '') {
            return null;
        }

        $link = 'https://wa.me/'.$number.'?text='.rawurlencode($message);

        Log::info('WhatsApp link via log driver', [
            'status' => 'link_created',
            'to_hash' => hash_hmac('sha256', $to, (string) config('app.key')),
            'message_length' => strlen($message),
            'message_hash' => hash_hmac('sha256', $message, (string) config('app.key')),
        ]);

        return $link;
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
