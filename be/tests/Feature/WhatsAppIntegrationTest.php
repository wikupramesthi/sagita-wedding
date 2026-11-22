<?php

namespace Tests\Feature;

use App\Contracts\WhatsAppGateway;
use App\Models\User;
use App\Notifications\Channels\WhatsAppChannel;
use App\Notifications\OrderBalanceDueNotification;
use App\Services\WhatsApp\HttpWhatsAppGateway;
use App\Services\WhatsApp\LinkWhatsAppGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Mockery;
use RuntimeException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WhatsAppIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('user', 'web');
        config()->set('whatsapp.enabled', true);
    }

    public function test_http_driver_posts_to_configured_endpoint_with_token(): void
    {
        Http::fake([
            'https://wa.example.test/send' => Http::response(['ok' => true], 200),
        ]);

        config()->set([
            'whatsapp.driver' => 'http',
            'whatsapp.api_url' => 'https://wa.example.test/send',
            'whatsapp.token' => 'test-token',
            'whatsapp.from' => '6200000000000',
        ]);

        $gateway = app(WhatsAppGateway::class);

        $this->assertInstanceOf(HttpWhatsAppGateway::class, $gateway);
        $this->assertTrue($gateway->send('081234567890', 'Pesan tagihan'));

        Http::assertSent(function (Request $request) {
            return $request->url() === 'https://wa.example.test/send'
                && $request->hasHeader('Authorization', 'Bearer test-token')
                && $request['to'] === '6281234567890'
                && $request['message'] === 'Pesan tagihan'
                && $request['from'] === '6200000000000';
        });
    }

    public function test_http_driver_returns_false_for_non_success_response(): void
    {
        Http::fake([
            'https://wa.example.test/send' => Http::response(['error' => 'nope'], 422),
        ]);

        config()->set([
            'whatsapp.driver' => 'http',
            'whatsapp.api_url' => 'https://wa.example.test/send',
            'whatsapp.retry' => 0,
        ]);

        $this->assertFalse(app(WhatsAppGateway::class)->send('6281234567890', 'Pesan'));
    }

    public function test_link_driver_builds_whatsapp_link(): void
    {
        config()->set('whatsapp.driver', 'link');

        $gateway = app(WhatsAppGateway::class);
        $message = 'Halo, sisa pembayaran Anda Rp 50.000';

        $this->assertInstanceOf(LinkWhatsAppGateway::class, $gateway);
        $this->assertSame(
            'https://wa.me/6281234567890?text='.rawurlencode($message),
            $gateway->link('0812-3456-7890', $message)
        );
        $this->assertTrue($gateway->send('081234567890', $message));
    }

    public function test_log_driver_logs_only_safe_metadata(): void
    {
        $phone = '081234567890';
        $message = 'Pesan rahasia ORD-123';

        Log::shouldReceive('info')->twice()->withArgs(function (string $level, array $context) use ($phone, $message): bool {
            $serialized = $level.json_encode($context);

            return ! str_contains($serialized, $phone)
                && ! str_contains($serialized, $message)
                && isset($context['status'], $context['to_hash'], $context['message_length'], $context['message_hash']);
        });

        config()->set('whatsapp.driver', 'log');
        $gateway = app(WhatsAppGateway::class);

        $this->assertTrue($gateway->send($phone, $message));
        $this->assertNotNull($gateway->link($phone, $message));
    }

    public function test_http_driver_logs_success_without_phone_or_message(): void
    {
        $phone = '081234567890';
        $message = 'Pesan rahasia ORD-123';

        Http::fake([
            'https://wa.example.test/send' => Http::response(['ok' => true], 200),
        ]);
        Log::shouldReceive('info')->once()->withArgs(function (string $level, array $context) use ($phone, $message): bool {
            $serialized = $level.json_encode($context);

            return $level === 'WhatsApp HTTP request succeeded.'
                && $context['status'] === 200
                && ! str_contains($serialized, $phone)
                && ! str_contains($serialized, $message)
                && isset($context['to_hash'], $context['message_length']);
        });

        config()->set([
            'whatsapp.driver' => 'http',
            'whatsapp.api_url' => 'https://wa.example.test/send',
            'whatsapp.token' => 'test-token',
        ]);

        $this->assertTrue(app(WhatsAppGateway::class)->send($phone, $message));
    }

    public function test_http_driver_logs_failure_without_body_or_pii(): void
    {
        $phone = '081234567890';
        $message = 'Pesan rahasia ORD-123';
        $bodyMarker = 'provider_secret_marker_'.$phone;

        Http::fake([
            'https://wa.example.test/send' => Http::response(['error' => $bodyMarker], 422),
        ]);
        Log::shouldReceive('warning')->once()->withArgs(function (string $level, array $context) use ($phone, $message, $bodyMarker): bool {
            $serialized = $level.json_encode($context);

            return $context['status'] === 422
                && ! str_contains($serialized, $phone)
                && ! str_contains($serialized, $message)
                && ! str_contains($serialized, $bodyMarker)
                && isset($context['to_hash'], $context['message_length'], $context['body_length'], $context['body_hash']);
        });

        config()->set([
            'whatsapp.driver' => 'http',
            'whatsapp.api_url' => 'https://wa.example.test/send',
            'whatsapp.retry' => 0,
        ]);

        $this->assertFalse(app(WhatsAppGateway::class)->send($phone, $message));
    }

    public function test_channel_logs_unsent_notification_without_pii(): void
    {
        $gateway = $this->mock(WhatsAppGateway::class);
        $gateway->shouldReceive('send')->once()->andReturn(false);

        $user = User::factory()->create(['no_hp' => '081234567890']);
        $notification = new OrderBalanceDueNotification('order-uuid', 'ORD-123', 50000);
        $message = $notification->toWhatsApp($user)['message'];

        Log::shouldReceive('warning')->once()->withArgs(function (string $level, array $context) use ($user, $message): bool {
            $serialized = $level.json_encode($context);

            return $context['status'] === 'not_sent'
                && ! str_contains($serialized, (string) $user->routeNotificationForWhatsApp())
                && ! str_contains($serialized, $message)
                && isset($context['to_hash'], $context['message_length'], $context['message_hash']);
        });

        app(WhatsAppChannel::class)->send($user, $notification);
    }

    public function test_channel_logs_exception_without_phone_message_or_exception_text(): void
    {
        $gateway = $this->mock(WhatsAppGateway::class);
        $gateway->shouldReceive('send')->once()
            ->andThrow(new RuntimeException('secret exception 081234567890'));

        $user = User::factory()->create(['no_hp' => '081234567890']);
        $notification = new OrderBalanceDueNotification('order-uuid', 'ORD-123', 50000);
        $message = $notification->toWhatsApp($user)['message'];

        Log::shouldReceive('error')->once()->withArgs(function (string $level, array $context) use ($user, $message): bool {
            $serialized = $level.json_encode($context);

            return $context['exception'] === RuntimeException::class
                && $context['status'] === 'failed'
                && ! str_contains($serialized, (string) $user->routeNotificationForWhatsApp())
                && ! str_contains($serialized, $message)
                && ! str_contains($serialized, 'secret exception')
                && isset($context['to_hash'], $context['message_length'], $context['message_hash']);
        });

        app(WhatsAppChannel::class)->send($user, $notification);
    }

    public function test_user_route_normalizes_no_hp(): void
    {
        $user = User::factory()->create(['no_hp' => '0812-3456-7890']);

        $this->assertSame('6281234567890', $user->routeNotificationForWhatsApp());
        $this->assertSame('6281234567890', $user->fresh()->routeNotificationForWhatsApp());

        $user->no_hp = '81234567890';
        $this->assertSame('6281234567890', $user->routeNotificationForWhatsApp());

        $user->no_hp = '+62 812 3456 7890';
        $this->assertSame('6281234567890', $user->routeNotificationForWhatsApp());

        $user->no_hp = null;
        $this->assertNull($user->routeNotificationForWhatsApp());
    }

    public function test_channel_sends_notification_payload_through_gateway(): void
    {
        $gateway = $this->mock(WhatsAppGateway::class);
        $gateway->shouldReceive('send')->once()
            ->with(
                '6281234567890',
                Mockery::on(fn (string $message) => str_contains($message, 'ORD-123')
                    && str_contains($message, route('orders.show', 'order-uuid'))),
                Mockery::type('array')
            )
            ->andReturn(true);

        $user = User::factory()->create(['no_hp' => '081234567890']);
        $notification = new OrderBalanceDueNotification('order-uuid', 'ORD-123', 50000);

        $payload = app(WhatsAppChannel::class)->send($user, $notification);

        $this->assertSame('6281234567890', $payload['to']);
        $this->assertStringContainsString('ORD-123', $payload['message']);
        $this->assertStringContainsString(route('orders.show', 'order-uuid'), $payload['message']);
    }

    public function test_disabled_config_means_channel_is_noop(): void
    {
        config()->set('whatsapp.enabled', false);

        $gateway = $this->mock(WhatsAppGateway::class);
        $gateway->shouldNotReceive('send');

        $user = User::factory()->create(['no_hp' => '081234567890']);
        $notification = new OrderBalanceDueNotification('order-uuid', 'ORD-123', 50000);

        $this->assertFalse(config('whatsapp.enabled'));
        $this->assertSame([], app(WhatsAppChannel::class)->send($user, $notification));
    }

    public function test_order_balance_due_adds_whatsapp_only_when_enabled_and_routable(): void
    {
        $user = User::factory()->create(['no_hp' => '081234567890']);
        $notification = new OrderBalanceDueNotification('order-uuid', 'ORD-123', 50000);

        $this->assertInstanceOf(WhatsAppChannel::class, Notification::channel('whatsapp'));
        $this->assertContains('whatsapp', $notification->via($user));

        $payload = $notification->toWhatsApp($user);
        $this->assertSame('6281234567890', $payload['to']);
        $this->assertStringContainsString(route('orders.show', 'order-uuid'), $payload['message']);

        $user->no_hp = null;
        $this->assertNotContains('whatsapp', $notification->via($user));

        $user->no_hp = '081234567890';
        config()->set('whatsapp.enabled', false);
        $this->assertNotContains('whatsapp', $notification->via($user));
    }

    public function test_notification_fake_avoids_external_http(): void
    {
        config()->set([
            'whatsapp.driver' => 'http',
            'whatsapp.api_url' => 'https://wa.example.test/send',
            'whatsapp.token' => 'test-token',
        ]);

        Http::fake();
        Notification::fake();

        $user = User::factory()->create(['no_hp' => '081234567890']);
        $user->notify(new OrderBalanceDueNotification('order-uuid', 'ORD-123', 50000));

        Notification::assertSentTo($user, OrderBalanceDueNotification::class);
        Http::assertNothingSent();
    }
}
