<?php

namespace Tests\Feature;

use App\Jobs\SendTelegramMessage;
use App\Models\User;
use App\Services\TelegramAdminNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\TestCase;

class TelegramAdminNotifierTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_user_message_is_queued_and_html_is_escaped(): void
    {
        Queue::fake();
        config([
            'services.telegram.enabled' => true,
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '-100123',
        ]);

        app(TelegramAdminNotifier::class)->newUser(
            User::factory()->make([
                'name' => 'Rani & Dewi',
                'email' => 'rani@example.test',
            ])
        );

        Queue::assertPushed(SendTelegramMessage::class, fn (SendTelegramMessage $job): bool => str_contains(
            $job->message,
            '<b>Pelanggan Baru Terdaftar</b>'
        ) && str_contains($job->message, 'Rani &amp; Dewi'));
    }

    public function test_telegram_job_sends_html_message_to_configured_chat(): void
    {
        config([
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '-100123',
        ]);
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []]),
        ]);

        (new SendTelegramMessage('<b>Pesanan Baru</b>'))->handle();

        Http::assertSent(fn (Request $request): bool => str_ends_with($request->url(), '/sendMessage')
            && $request['chat_id'] === '-100123'
            && $request['text'] === '<b>Pesanan Baru</b>'
            && $request['parse_mode'] === 'HTML');
    }

    public function test_unregistered_email_login_alert_is_rate_limited(): void
    {
        Queue::fake();
        config([
            'services.telegram.enabled' => true,
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '-100123',
        ]);
        $email = Str::uuid().'@example.test';

        foreach (range(1, 4) as $_) {
            $this->post('/auth/login', [
                'email' => $email,
                'password' => 'wrong-password',
            ]);
        }

        Queue::assertPushed(SendTelegramMessage::class, 1);
        Queue::assertPushed(SendTelegramMessage::class, fn (SendTelegramMessage $job): bool => str_contains(
            $job->message,
            '<b>Percobaan Masuk Tidak Dikenal</b>'
        ) && ! str_contains($job->message, $email));
    }
}
