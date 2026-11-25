<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SendTelegramMessage implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 3;

    public array $backoff = [10, 30, 120];

    public function __construct(
        public readonly string $message,
    ) {
        $this->afterCommit();
    }

    public function handle(): void
    {
        $token = (string) config('services.telegram.bot_token');
        $chatId = (string) config('services.telegram.chat_id');

        if ($token === '' || $chatId === '') {
            throw new RuntimeException('Telegram is enabled but the bot token or chat ID is missing.');
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $this->message,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]);
        } catch (ConnectionException) {
            throw new RuntimeException('Unable to connect to the Telegram Bot API.');
        }

        if (! $response->successful() || $response->json('ok') !== true) {
            throw new RuntimeException(
                'Telegram rejected the message (HTTP '.$response->status().').'
            );
        }
    }
}
