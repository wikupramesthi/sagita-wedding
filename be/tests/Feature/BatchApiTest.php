<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\Venue;
use App\Services\BatchVersionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BatchApiTest extends TestCase
{
    use RefreshDatabase;

    private const API_KEY = 'test-frontend-key';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.frontend_api.key' => self::API_KEY,
            'frontend.home_wedding_content_ready' => false,
        ]);
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
                    'weddingContentReady',
                    'testimonials',
                    'venues',
                    'packages',
                    'articles',
                ],
            ])
            ->assertJsonPath('data.weddingContentReady', false);

        $this->assertStringContainsString(
            'no-cache',
            (string) $response->headers->get('Cache-Control'),
        );
        $this->assertNotEmpty($response->headers->get('ETag'));
    }

    public function test_home_batch_reports_wedding_content_readiness(): void
    {
        config(['frontend.home_wedding_content_ready' => true]);

        $this->api()
            ->getJson('/api/batch/home')
            ->assertOk()
            ->assertJsonPath('data.weddingContentReady', true);
    }

    public function test_home_batch_limits_testimonials_to_ten_items(): void
    {
        foreach (range(1, 12) as $order) {
            Testimonial::query()->create([
                'nama' => "Pasangan {$order}",
                'jabatan' => 'Pasangan pengantin',
                'isi_testimoni' => "Cerita {$order}.",
                'urutan' => $order,
                'is_active' => 'active',
            ]);
        }

        $this->api()
            ->getJson('/api/batch/home')
            ->assertOk()
            ->assertJsonCount(10, 'data.testimonials')
            ->assertJsonPath('data.testimonials.9.nama', 'Pasangan 10');
    }

    public function test_venues_batch_returns_active_venue_directory(): void
    {
        Venue::query()->create([
            'name' => 'Garden Hall',
            'address' => 'Jl. Garden 1',
            'status' => 'active',
        ]);
        Venue::query()->create([
            'name' => 'Private Hall',
            'address' => 'Jl. Private 2',
            'status' => 'inactive',
        ]);

        $this->api()
            ->getJson('/api/batch/venues')
            ->assertOk()
            ->assertJsonPath('page', 'venues')
            ->assertJsonCount(1, 'data.venues')
            ->assertJsonPath('data.venues.0.name', 'Garden Hall')
            ->assertJsonPath('data.venues.0.address', 'Jl. Garden 1');
    }

    public function test_detail_batch_requires_slug(): void
    {
        $this->api()
            ->getJson('/api/batch/package-detail')
            ->assertStatus(422)
            ->assertJsonPath('status', 'error')
            ->assertJsonStructure(['errors' => ['slug']]);
    }

    public function test_events_batch_returns_events_and_meta(): void
    {
        $this->api()
            ->getJson('/api/batch/events')
            ->assertOk()
            ->assertJsonPath('page', 'events')
            ->assertJsonStructure([
                'data' => [
                    'events',
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

    public function test_etag_changes_immediately_when_batch_content_changes(): void
    {
        $version = BatchVersionService::current();
        $venue = Venue::query()->create([
            'name' => 'Garden Hall',
            'address' => 'Jl. Garden 1',
            'status' => 'active',
        ]);

        $this->assertGreaterThan($version, BatchVersionService::current());

        $first = $this->api()->getJson('/api/batch/venues');
        $etag = $first->headers->get('ETag');
        $this->assertNotEmpty($etag);
        $contentUpdatedAt = BatchVersionService::contentUpdatedAt();
        $this->assertNotNull($contentUpdatedAt);
        $first->assertJsonPath('data.contentUpdatedAt', $contentUpdatedAt);

        $venue->update(['name' => 'Garden Hall Revisi']);

        $this->api()
            ->withHeader('If-None-Match', $etag)
            ->getJson('/api/batch/venues')
            ->assertOk()
            ->assertJsonPath('data.venues.0.name', 'Garden Hall Revisi');
    }

    public function test_all_supported_batch_pages_are_reachable(): void
    {
        $detailPages = ['blog-detail', 'package-detail', 'page-detail'];

        foreach ([
            'home',
            'venues',
            'about',
            'blog',
            'blog-detail',
            'packages',
            'package-detail',
            'vendors',
            'events',
            'gallery',
            'contact',
            'faq',
            'page-detail',
        ] as $page) {
            $isDetail = in_array($page, $detailPages, true);
            $response = $this->api()->getJson('/api/batch/'.$page);

            self::assertSame($isDetail ? 422 : 200, $response->status(), $page);
            $response->assertJsonPath('status', $isDetail ? 'error' : 'success');
        }
    }
}
