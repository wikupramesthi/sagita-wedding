<?php

namespace Tests\Feature;

use App\Jobs\SendTelegramMessage;
use App\Models\User;
use App\Services\TelegramAdminNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TelegramAdminNotifierTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_user_message_is_sent_immediately_and_html_is_escaped(): void
    {
        Queue::fake();
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []]),
        ]);
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

        Http::assertSent(fn (Request $request): bool => str_contains(
            $request['text'],
            '<b>Pelanggan Baru Terdaftar</b>'
        ) && str_contains($request['text'], 'Rani &amp; Dewi'));
        Queue::assertNothingPushed();
    }

    public function test_admin_login_sends_telegram_notification_with_login_details(): void
    {
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $user = User::factory()->create([
            'name' => 'Ari & Dewi',
            'email' => 'admin@example.test',
        ]);
        $user->assignRole('admin');
        Queue::fake();
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []]),
        ]);
        config([
            'services.telegram.enabled' => true,
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '-100123',
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.10'])
            ->post('/auth/login', [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertRedirect(route('dashboard.index', absolute: false));

        Http::assertSent(fn (Request $request): bool => str_contains(
            $request['text'],
            '<b>Login Berhasil</b>'
        ) && str_contains($request['text'], 'Ari &amp; Dewi')
            && str_contains($request['text'], 'admin@example.test')
            && str_contains($request['text'], 'admin')
            && str_contains($request['text'], '192.0.2.10'));
        Queue::assertNothingPushed();
    }

    public function test_user_role_login_also_sends_telegram_notification(): void
    {
        Role::create(['name' => 'user', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('user');
        Queue::fake();
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []]),
        ]);
        Notification::fake();
        config([
            'services.telegram.enabled' => true,
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '-100123',
        ]);

        $this->post('/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        Http::assertSent(fn (Request $request): bool => str_contains(
            $request['text'],
            '<b>Login Berhasil</b>'
        ) && str_contains($request['text'], $user->email)
            && str_contains($request['text'], '<b>Peran:</b> user'));
        Queue::assertNothingPushed();
        Notification::assertNothingSent();
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
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []]),
        ]);
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

        Http::assertSentCount(1);
        Http::assertSent(fn (Request $request): bool => str_contains(
            $request['text'],
            '<b>Percobaan Masuk Tidak Dikenal</b>'
        ) && ! str_contains($request['text'], $email));
    }
}
