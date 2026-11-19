<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BatchApiTest extends TestCase
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

    public function test_batch_requires_api_key(): void
    {
        $this->getJson('/api/batch/home')->assertUnauthorized();
    }

    public function test_unknown_batch_page_returns_404(): void
    {
        $this->api()
            ->getJson('/api/batch/tidak-ada')
            ->assertNotFound()
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('data', null);
    }

    public function test_home_batch_returns_all_home_sections(): void
    {
        $response = $this->api()->getJson('/api/batch/home');

        $response
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('page', 'home')
            ->assertJsonStructure([
                'data' => [
                    'testimonials',
                    'classes',
                    'packages',
                    'events',
                ],
            ]);

        $this->assertStringContainsString(
            'no-cache',
            (string) $response->headers->get('Cache-Control'),
        );
        $this->assertNotEmpty($response->headers->get('ETag'));
    }

    public function test_detail_batch_requires_slug(): void
    {
        $this->api()
            ->getJson('/api/batch/class-detail')
            ->assertStatus(422)
            ->assertJsonPath('status', 'error')
            ->assertJsonStructure(['errors' => ['slug']]);
    }

    public function test_schedules_batch_returns_schedules_and_studios(): void
    {
        $this->api()
            ->getJson('/api/batch/schedules')
            ->assertOk()
            ->assertJsonPath('page', 'schedules')
            ->assertJsonStructure([
                'data' => [
                    'date',
                    'schedules',
                    'studios',
                    'meta',
                ],
            ]);
    }

    public function test_unchanged_batch_returns_304_with_etag(): void
    {
        $first = $this->api()->getJson('/api/batch/home');
        $etag = $first->headers->get('ETag');

        $this->assertNotEmpty($etag);

        $this->api()
            ->withHeader('If-None-Match', $etag)
            ->getJson('/api/batch/home')
            ->assertStatus(304);
    }
}
