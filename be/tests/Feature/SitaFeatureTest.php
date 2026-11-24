<?php

namespace Tests\Feature;

use App\Models\Sita\SitaLead;
use App\Models\Sita\SitaSynonym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class SitaFeatureTest extends TestCase
{
    use RefreshDatabase;

    private const API_KEY = 'test-sita-key';

    private const PERMISSIONS = [
        'sita.analytics.view',
        'sita.leads.view',
        'sita.leads.update',
        'sita.synonyms.manage',
    ];

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.frontend_api.key' => self::API_KEY]);

        foreach (['user', 'admin', 'super-admin'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        $migration = require database_path('migrations/2026_11_23_130400_register_sita_admin_pages.php');
        $migration->up();

        Cache::flush();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->admin = User::factory()->create(['email_verified_at' => now()]);
        $this->admin->assignRole('admin');
    }

    private function api()
    {
        return $this->withHeader('X-Api-Key', self::API_KEY);
    }

    public function test_migrations_routes_and_menu_are_registered(): void
    {
        foreach (['sita_analytics_events', 'sita_leads', 'sita_synonyms'] as $table) {
            $this->assertTrue(Schema::hasTable($table), $table);
        }

        foreach ([
            'sita.analytics.index',
            'sita.leads.index',
            'sita.leads.show',
            'sita.leads.update',
            'sita.leads.destroy',
            'sita.synonyms.index',
            'sita.synonyms.store',
            'sita.synonyms.update',
            'sita.synonyms.destroy',
        ] as $routeName) {
            $this->assertTrue(\Route::has($routeName), $routeName);
            $this->assertDatabaseHas('routes', ['route' => $routeName, 'status' => 1]);
        }

        foreach (self::PERMISSIONS as $permission) {
            $this->assertDatabaseHas('permissions', ['name' => $permission, 'guard_name' => 'web']);
            $this->assertTrue(Role::findByName('admin', 'web')->hasPermissionTo($permission));
            $this->assertTrue(Role::findByName('super-admin', 'web')->hasPermissionTo($permission));
        }

        $group = DB::table('menu_groups')->where('permission_name', 'sita.analytics.view')->first();
        $this->assertNotNull($group);

        foreach (['sita.analytics.index', 'sita.leads.index', 'sita.synonyms.index'] as $routeName) {
            $this->assertDatabaseHas('menu_items', [
                'menu_group_id' => $group->id,
                'route' => $routeName,
                'status' => 1,
            ]);
        }

        $indexes = collect(DB::select('SHOW INDEX FROM sita_analytics_events'))->pluck('Key_name')->all();

        foreach (['sita_analytics_events_name_occurred_at_index', 'sita_analytics_events_topic_occurred_at_index', 'sita_analytics_events_query_hash_occurred_at_index', 'sita_analytics_events_name_turn_id_index'] as $index) {
            $this->assertContains($index, $indexes);
        }
    }

    public function test_api_endpoints_require_api_key(): void
    {
        $this->postJson('/api/sita/events', [
            'kind' => 'event',
            'name' => 'sita.answer',
        ])->assertUnauthorized();

        $this->getJson('/api/sita/analytics')->assertUnauthorized();

        $this->postJson('/api/sita/leads', [
            'name' => 'Client',
            'phone' => '081234567890',
            'score' => 50,
        ])->assertUnauthorized();

        $this->getJson('/api/sita/synonyms')->assertUnauthorized();
    }

    public function test_event_validation_and_turn_id_deduplication(): void
    {
        $this->api()->postJson('/api/sita/events', [
            'kind' => 'event',
            'name' => 'sita.secret',
            'path' => '/',
        ])->assertUnprocessable();

        $this->api()->postJson('/api/sita/events', [
            'kind' => 'event',
            'name' => 'sita.answer',
            'question' => 'rahasia',
        ])->assertUnprocessable();

        $this->assertDatabaseCount('sita_analytics_events', 0);

        $payload = [
            'kind' => 'event',
            'name' => 'sita.answer',
            'topic' => 'packages',
            'confidence' => 0.9,
            'fallback' => false,
            'clarify' => false,
            'responseMs' => 320,
            'turnId' => 'turn-1',
            'path' => '/',
        ];

        $this->api()->postJson('/api/sita/events', $payload)
            ->assertAccepted()
            ->assertJsonPath('success', true)
            ->assertJsonPath('deduplicated', false);

        $this->assertDatabaseHas('sita_analytics_events', [
            'name' => 'sita.answer',
            'turn_id' => 'turn-1',
            'response_ms' => 320,
        ]);

        $this->api()->postJson('/api/sita/events', $payload)->assertNoContent();
        $this->assertDatabaseCount('sita_analytics_events', 1);

        $this->api()->postJson('/api/sita/events', [
            ...$payload,
            'name' => 'sita.feedback',
            'helpful' => true,
        ])->assertAccepted();

        $this->assertDatabaseCount('sita_analytics_events', 2);
    }

    public function test_analytics_shape_matches_frontend_contract(): void
    {
        $this->api()->postJson('/api/sita/events', [
            'kind' => 'event',
            'name' => 'sita.answer',
            'topic' => 'packages',
            'confidence' => 0.9,
            'fallback' => false,
            'clarify' => false,
            'responseMs' => 300,
            'turnId' => 'turn-1',
        ])->assertAccepted();

        $this->api()->postJson('/api/sita/events', [
            'kind' => 'event',
            'name' => 'sita.answer',
            'topic' => 'vendors',
            'confidence' => 0.15,
            'fallback' => true,
            'clarify' => false,
            'responseMs' => 200,
            'turnId' => 'turn-2',
        ])->assertAccepted();

        $this->api()->postJson('/api/sita/events', [
            'kind' => 'event',
            'name' => 'sita.feedback',
            'topic' => 'packages',
            'helpful' => true,
            'turnId' => 'turn-1',
        ])->assertAccepted();

        $this->api()->postJson('/api/sita/events', [
            'kind' => 'event',
            'name' => 'sita.feedback',
            'topic' => 'vendors',
            'helpful' => false,
            'turnId' => 'turn-3',
        ])->assertAccepted();

        foreach ([1, 2] as $repeat) {
            $this->api()->postJson('/api/sita/events', [
                'kind' => 'event',
                'name' => 'sita.no_match',
                'topic' => 'packages',
                'queryHash' => 'abc123',
                'querySample' => 'fotobooth dong 081234567890',
                'fallback' => true,
            ])->assertAccepted();
        }

        $response = $this->api()->getJson('/api/sita/analytics')
            ->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private')
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'generatedAt',
                'window' => ['events', 'maxEvents'],
                'totals' => ['answers', 'feedback', 'helpful', 'unhelpful', 'noMatch', 'fallback', 'clarify', 'lowConfidence'],
                'rates' => ['helpful', 'fallback', 'noMatch', 'clarify'],
                'performance' => ['avgResponseMs'],
                'topics' => [['topic', 'answers', 'helpful', 'unhelpful']],
                'noMatchPatterns' => [['queryHash', 'count', 'topic', 'sample', 'fallback', 'lastAt']],
                'recent' => [['at', 'name']],
            ]);

        $response->assertJsonPath('window.events', 6)
            ->assertJsonPath('window.maxEvents', 500)
            ->assertJsonPath('totals.answers', 2)
            ->assertJsonPath('totals.feedback', 2)
            ->assertJsonPath('totals.helpful', 1)
            ->assertJsonPath('totals.unhelpful', 1)
            ->assertJsonPath('totals.noMatch', 2)
            ->assertJsonPath('totals.fallback', 1)
            ->assertJsonPath('totals.clarify', 0)
            ->assertJsonPath('totals.lowConfidence', 1)
            ->assertJsonPath('rates.helpful', 50)
            ->assertJsonPath('rates.fallback', 50)
            ->assertJsonPath('performance.avgResponseMs', 250)
            ->assertJsonPath('noMatchPatterns.0.queryHash', 'abc123')
            ->assertJsonPath('noMatchPatterns.0.count', 2)
            ->assertJsonPath('noMatchPatterns.0.sample', 'fotobooth dong [nomor]')
            ->assertJsonPath('noMatchPatterns.0.fallback', true);

        $this->assertCount(3, $response->json('topics'));
        $this->assertCount(6, $response->json('recent'));
        $this->assertArrayNotHasKey('turn_id', $response->json('recent.0'));
        $this->assertArrayNotHasKey('path', $response->json('recent.0'));
        $this->assertStringNotContainsString('rahasia', json_encode($response->json()));
    }

    public function test_lead_creation_validates_and_stores_allowed_criteria_only(): void
    {
        $this->api()->postJson('/api/sita/leads', [
            'name' => 'Client',
            'phone' => '081234567890',
            'score' => 70,
            'criteria' => ['unknownKey' => 'rahasia'],
        ])->assertUnprocessable()
            ->assertJsonStructure(['errors' => ['criteria']]);

        $response = $this->api()->postJson('/api/sita/leads', [
            'name' => 'Rina',
            'phone' => '0812-3456-7890',
            'topic' => 'packages',
            'criteria' => [
                'budgetLimit' => 50000000,
                'guestCount' => 150,
                'city' => 'Jakarta',
                'eventDate' => '2027-06-20',
                'vendorCategory' => 'fotografi',
            ],
            'score' => 85,
            'message' => 'Ingin paket lengkap.',
        ])->assertCreated()
            ->assertJsonPath('success', true);

        $uuid = $response->json('data.uuid');
        $this->assertNotEmpty($uuid);

        $lead = SitaLead::findOrFail($uuid);
        $this->assertSame('new', $lead->status);
        $this->assertSame(85, $lead->score);
        $this->assertSame([
            'budgetLimit' => 50000000,
            'guestCount' => 150,
            'city' => 'Jakarta',
            'eventDate' => '2027-06-20',
            'vendorCategory' => 'fotografi',
        ], $lead->criteria);
    }

    public function test_synonyms_are_seeded_and_listed_without_inactive_rows(): void
    {
        $response = $this->api()->getJson('/api/sita/synonyms')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => [['locale', 'term', 'canonical', 'topic', 'sort_order']]]);

        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));

        $synonyms = collect($response->json('data'));
        $this->assertTrue($synonyms->contains(fn (array $item): bool => $item['locale'] === 'id' && $item['term'] === 'photographer' && $item['canonical'] === 'fotografi'));
        $this->assertTrue($synonyms->contains(fn (array $item): bool => $item['locale'] === 'en' && $item['term'] === 'photographer' && $item['canonical'] === 'photography'));
        $this->assertTrue(Cache::has(SitaSynonym::CACHE_KEY));

        SitaSynonym::create([
            'locale' => 'id',
            'term' => 'hiddenterm',
            'canonical' => 'tersembunyi',
            'is_active' => false,
        ]);
        Cache::forget(SitaSynonym::CACHE_KEY);

        $terms = collect($this->api()->getJson('/api/sita/synonyms')->assertOk()->json('data'))->pluck('term')->all();
        $this->assertNotContains('hiddenterm', $terms);
    }

    public function test_admin_pages_require_permission_and_support_management(): void
    {
        $member = User::factory()->create();
        $member->assignRole('user');

        foreach ([
            route('sita.analytics.index'),
            route('sita.leads.index'),
            route('sita.synonyms.index'),
        ] as $url) {
            $this->actingAs($member)->get($url)->assertNotFound();
            $this->actingAs($this->admin)->get($url)->assertOk();
        }

        $lead = SitaLead::create([
            'name' => 'Lead',
            'phone' => '081234567890',
            'score' => 60,
            'criteria' => ['city' => 'Bandung'],
            'status' => 'new',
        ]);

        $this->actingAs($this->admin)->get(route('sita.leads.show', $lead->uuid))->assertOk();
        $this->actingAs($this->admin)
            ->put(route('sita.leads.update', $lead->uuid), ['status' => 'contacted'])
            ->assertRedirect();
        $this->assertSame('contacted', $lead->fresh()->status);

        $this->actingAs($this->admin)
            ->post(route('sita.synonyms.store'), [
                'locale' => 'id',
                'term' => 'wo',
                'canonical' => 'wedding organizer',
                'topic' => 'packages',
                'sort_order' => 99,
                'is_active' => '1',
            ])->assertRedirect();

        $synonym = SitaSynonym::where('term', 'wo')->firstOrFail();
        $this->assertDatabaseHas('sita_synonyms', ['uuid' => $synonym->uuid, 'is_active' => 1]);

        $this->actingAs($this->admin)
            ->put(route('sita.synonyms.update', $synonym->uuid), [
                'locale' => 'id',
                'term' => 'wo',
                'canonical' => 'wedding organizer',
                'topic' => 'vendors',
                'sort_order' => 99,
            ])->assertRedirect();

        $this->assertSame('vendors', $synonym->fresh()->topic);
        $this->assertFalse($synonym->fresh()->is_active);

        $this->actingAs($this->admin)
            ->delete(route('sita.synonyms.destroy', $synonym->uuid))
            ->assertRedirect();

        $this->assertDatabaseMissing('sita_synonyms', ['uuid' => $synonym->uuid]);

        $this->actingAs($this->admin)
            ->delete(route('sita.leads.destroy', $lead->uuid))
            ->assertRedirect();

        $this->assertDatabaseMissing('sita_leads', ['uuid' => $lead->uuid]);
    }
}
