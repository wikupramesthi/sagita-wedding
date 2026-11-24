<?php

namespace Tests\Feature;

use App\Http\Controllers\GoogleController;
use App\Models\Event;
use App\Models\User;
use Database\Seeders\RouteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use ReflectionMethod;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiSecurityTest extends TestCase
{
    use RefreshDatabase;

    private const API_KEY = 'test-frontend-key';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.frontend_api.key' => self::API_KEY]);
        $this->seed(RouteSeeder::class);
    }

    private function api()
    {
        return $this->withHeader('X-Api-Key', self::API_KEY);
    }

    public function test_vendor_validation_failure_returns_422_not_500(): void
    {
        $this->api()
            ->getJson('/api/v1/vendors?per_page=999')
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['message', 'errors' => ['per_page']]);
    }

    public function test_analytics_visit_is_deduplicated_per_visitor_path_and_device(): void
    {
        $payload = [
            'visitor_hash' => str_repeat('a', 64),
            'path' => '/venues',
            'device' => 'desktop',
        ];

        $this->api()
            ->postJson('/api/v1/analytics/visits', $payload)
            ->assertAccepted()
            ->assertJsonPath('data.deduplicated', false);

        $this->api()
            ->postJson('/api/v1/analytics/visits', $payload)
            ->assertAccepted()
            ->assertJsonPath('data.deduplicated', true);

        $this->assertDatabaseCount('site_visits', 1);
    }

    public function test_google_redirect_only_accepts_relative_or_exact_frontend_urls(): void
    {
        config(['frontend.url' => 'https://frontend.test']);

        $method = new ReflectionMethod(GoogleController::class, 'isSafeRedirect');

        foreach ([
            '/venues',
            'https://frontend.test',
            'https://frontend.test/venues?lang=id',
            'https://frontend.test:443/venues',
        ] as $url) {
            $this->assertTrue($method->invoke(app(GoogleController::class), $url), $url);
        }

        foreach ([
            '',
            '//evil.test',
            '/\\evil.test',
            'https://frontend.test.evil.test',
            'https://evil.test/?next=https://frontend.test',
            'http://frontend.test/venues',
            'https://frontend.test:8443/venues',
            'javascript:alert(1)',
        ] as $url) {
            $this->assertFalse($method->invoke(app(GoogleController::class), $url), $url);
        }
    }

    public function test_google_redirect_generates_oauth_state_and_keeps_safe_redirect(): void
    {
        config([
            'frontend.url' => 'https://frontend.test',
            'services.google' => [
                'client_id' => 'test-client',
                'client_secret' => 'test-secret',
                'redirect' => 'http://localhost/auth/google/callback',
            ],
        ]);

        $response = $this->get('/auth/google?redirect='.rawurlencode('https://frontend.test/venues'));

        $response->assertRedirect();
        parse_str((string) parse_url((string) $response->headers->get('Location'), PHP_URL_QUERY), $query);

        $this->assertNotEmpty($query['state'] ?? null);
        $this->assertSame($query['state'], session('state'));
        $this->assertSame('https://frontend.test/venues', session('google_redirect'));
    }

    public function test_google_callback_uses_safe_redirect_after_fake_login(): void
    {
        config(['frontend.url' => 'https://frontend.test']);
        Role::findOrCreate('user', 'web');

        $googleUser = (new SocialiteUser)->map([
            'id' => 'google-123',
            'nickname' => 'tester',
            'name' => 'Tester',
            'email' => 'google-user@example.test',
            'avatar' => 'https://example.test/avatar.png',
        ]);
        Socialite::fake('google', $googleUser);

        $this->withSession(['google_redirect' => 'https://frontend.test/venues'])
            ->get('/auth/google/callback')
            ->assertRedirect('https://frontend.test/venues');

        $this->assertDatabaseHas('users', [
            'email' => 'google-user@example.test',
            'google_id' => 'google-123',
        ]);
    }

    public function test_event_description_is_sanitized_and_rendered_escaped(): void
    {
        Storage::fake('public');
        Cache::put('dashboard.artofliving.courses', [], now()->addHour());

        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('super-admin', 'web');
        Role::findOrCreate('user', 'web');
        Role::findOrCreate('vendor', 'web');

        $rolePermissions = [
            'admin' => ['events.store'],
            'user' => ['events.index', 'dashboard.index'],
        ];

        foreach ($rolePermissions as $roleName => $permissionNames) {
            foreach ($permissionNames as $permissionName) {
                Role::findByName($roleName, 'web')->givePermissionTo(
                    Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web'])
                );
            }
        }

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $member = User::factory()->create();
        $member->assignRole('user');

        $payload = '<script>alert("xss")</script><img src=x onerror=alert(1)>';

        $this->actingAs($admin)->post('/backend/events', [
            'judul' => 'Acara XSS',
            'deskripsi' => $payload,
            'gambar' => UploadedFile::fake()->image('acara.jpg'),
            'tanggal' => now()->addDay()->toDateString(),
            'status' => 'published',
        ])->assertRedirect();

        $event = Event::where('judul', 'Acara XSS')->firstOrFail();
        $this->assertStringNotContainsString('<script', $event->deskripsi);
        $this->assertStringNotContainsString('<img', $event->deskripsi);
        $this->assertStringContainsString('alert("xss")', $event->deskripsi);

        $this->actingAs($member)
            ->get(route('events.show', $event->uuid))
            ->assertOk()
            ->assertDontSee('<script>alert("xss")</script>', false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false)
            ->assertSee(e('alert("xss")'), false);

        $this->actingAs($member)
            ->withHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 13; Pixel 7) AppleWebKit/537.36 Mobile Safari/537.36')
            ->get('/backend/dashboard')
            ->assertOk()
            ->assertViewIs('pages.mobile.home')
            ->assertDontSee('<script>alert("xss")</script>', false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false)
            ->assertSee(e('alert("xss")'), false);
    }

    public function test_unknown_api_route_uses_the_consistent_error_envelope(): void
    {
        $this->api()
            ->getJson('/api/v1/entah')
            ->assertNotFound()
            ->assertJsonPath('success', false)
            ->assertJsonPath('status', 'error')
            ->assertJsonStructure(['message']);
    }

    public function test_api_key_is_still_required_for_new_security_endpoints(): void
    {
        $this->postJson('/api/v1/analytics/visits', [
            'visitor_hash' => str_repeat('b', 64),
            'path' => '/',
            'device' => 'mobile',
        ])->assertUnauthorized();
    }

    public function test_bearer_token_is_accepted_for_api_v1(): void
    {
        $this->withHeader('Authorization', 'Bearer '.self::API_KEY)
            ->getJson('/api/v1/site-stats')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure(['message', 'data']);
    }

    public function test_rotated_api_key_is_accepted(): void
    {
        config(['services.frontend_api.keys' => 'rotated-key']);

        $this->withHeader('X-Api-Key', 'rotated-key')
            ->getJson('/api/v1/site-stats')
            ->assertOk();
    }
}
