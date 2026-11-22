<?php

namespace Tests\Feature;

use App\Http\Controllers\GoogleController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

class ApiSecurityTest extends TestCase
{
    use RefreshDatabase;

    private const API_KEY = 'test-frontend-key';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.frontend_api.key' => self::API_KEY]);
    }

    private function api()
    {
        return $this->withHeader('X-Api-Key', self::API_KEY);
    }

    public function test_vendor_validation_failure_returns_422_not_500(): void
    {
        $this->api()
            ->getJson('/api/vendors?per_page=999')
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
            ->postJson('/api/analytics/visits', $payload)
            ->assertAccepted()
            ->assertJsonPath('deduplicated', false);

        $this->api()
            ->postJson('/api/analytics/visits', $payload)
            ->assertAccepted()
            ->assertJsonPath('deduplicated', true);

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

    public function test_unknown_api_route_uses_the_consistent_error_envelope(): void
    {
        $this->api()
            ->getJson('/api/entah')
            ->assertNotFound()
            ->assertJsonPath('success', false)
            ->assertJsonPath('status', 'error')
            ->assertJsonStructure(['message']);
    }

    public function test_api_key_is_still_required_for_new_security_endpoints(): void
    {
        $this->postJson('/api/analytics/visits', [
            'visitor_hash' => str_repeat('b', 64),
            'path' => '/',
            'device' => 'mobile',
        ])->assertUnauthorized();
    }
}
