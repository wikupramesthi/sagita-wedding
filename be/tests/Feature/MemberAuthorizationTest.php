<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RouteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MemberAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'user', 'guard_name' => 'web']);
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
        Role::create(['name' => 'vendor', 'guard_name' => 'web']);

        $this->seed(RouteSeeder::class);
        Cache::flush();

        Role::findByName('admin', 'web')->givePermissionTo(Permission::firstOrCreate(
            ['name' => 'dashboard.index', 'guard_name' => 'web']
        ));
        foreach (['dashboard.index', 'orders.index'] as $permission) {
            Role::findByName('user', 'web')->givePermissionTo(Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            ));
        }

        // Jangan panggil API Art of Living saat test dashboard.
        Cache::put('dashboard.artofliving.courses', [], now()->addHour());
    }

    private function member(): User
    {
        $user = User::factory()->create(['avatar' => 'test.png']);
        $user->assignRole('user');

        return $user;
    }

    private function admin(): User
    {
        $user = User::factory()->create(['avatar' => 'test.png']);
        $user->assignRole('admin');

        return $user;
    }

    public function test_member_cannot_open_admin_user_list(): void
    {
        $this->actingAs($this->member())
            ->get('/backend/pengguna')
            ->assertNotFound();
    }

    public function test_member_cannot_open_admin_articles(): void
    {
        $this->actingAs($this->member())
            ->get('/backend/articles')
            ->assertNotFound();
    }

    public function test_member_cannot_store_event(): void
    {
        $this->actingAs($this->member())
            ->post('/backend/events', ['judul' => 'XSS Event'])
            ->assertNotFound();
    }

    public function test_member_cannot_use_global_search(): void
    {
        $this->actingAs($this->member())
            ->get('/backend/search?q=user')
            ->assertNotFound();
    }

    public function test_member_cannot_use_dashboard_insight_endpoints(): void
    {
        $member = $this->member();

        $this->actingAs($member)
            ->getJson('/backend/dashboard/refresh')
            ->assertNotFound();

        $this->actingAs($member)
            ->get('/backend/dashboard/export')
            ->assertNotFound();

        $this->actingAs($member)
            ->get('/backend/dashboard/export-pdf')
            ->assertNotFound();
    }

    public function test_admin_can_use_global_search(): void
    {
        $this->actingAs($this->admin())
            ->get('/backend/search?q=test')
            ->assertOk();
    }

    public function test_unregistered_backend_route_is_denied(): void
    {
        DB::table('routes')->where('route', 'orders.analytics')->delete();
        Cache::forget('route.permission.orders.analytics');

        $this->actingAs($this->admin())
            ->get('/backend/orders/analytics')
            ->assertNotFound();
    }

    public function test_public_route_without_registration_remains_accessible(): void
    {
        $this->get('/')->assertRedirect();
    }

    public function test_admin_dashboard_clamps_inverted_date_range(): void
    {
        $this->actingAs($this->admin())
            ->get('/backend/dashboard?start_date=2026-11-20&end_date=2026-11-01')
            ->assertOk()
            ->assertViewHas('endDate', '2026-11-01')
            ->assertViewHas('startDate', Carbon::parse('2026-11-01')->subDays(29)->toDateString());
    }

    public function test_dashboard_hides_consultation_data_without_permission(): void
    {
        Consultation::create([
            'full_name' => 'Private Dashboard Lead',
            'email' => 'private-dashboard-lead@example.test',
            'whatsapp' => '081234567890',
            'lead_status' => 'New',
            'follow_up_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($this->admin())
            ->get('/backend/dashboard')
            ->assertOk()
            ->assertViewHas('canViewConsultations', false)
            ->assertViewHas('consultationPipeline', [])
            ->assertViewHas('consultationHighlights', null)
            ->assertViewHas('stats', fn (array $stats) => collect($stats)->every(fn (array $stat) => ! in_array($stat['label'], ['Lead baru', 'Follow-up terlewat'], true)))
            ->assertViewHas('funnel', fn (array $funnel) => collect($funnel)->every(fn (array $step) => ! in_array($step['label'], ['Lead konsultasi', 'Lead panas', 'Terkonversi'], true)))
            ->assertViewHas('actionQueue', fn (array $queue) => $queue['messageCount'] === 0 && $queue['messages']->isEmpty())
            ->assertDontSee('Private Dashboard Lead')
            ->assertDontSee('private-dashboard-lead@example.test')
            ->assertDontSee('081234567890')
            ->assertDontSee('Lead konsultasi')
            ->assertDontSee('Lead baru');

        $response->assertViewMissing('recentConsultations');
    }

    public function test_dashboard_shows_consultation_data_with_permission(): void
    {
        $role = Role::findByName('admin');
        $role->givePermissionTo(Permission::findOrCreate('konsultasi.index', 'web'));

        Consultation::create([
            'full_name' => 'Authorized Dashboard Lead',
            'email' => 'authorized-dashboard-lead@example.test',
            'whatsapp' => '081234567891',
            'lead_status' => 'New',
            'follow_up_at' => now()->subDay(),
        ]);

        $this->actingAs($this->admin())
            ->get('/backend/dashboard')
            ->assertOk()
            ->assertViewHas('canViewConsultations', true)
            ->assertSee('Authorized Dashboard Lead')
            ->assertSee('authorized-dashboard-lead@example.test')
            ->assertSee('081234567891');
    }

    public function test_member_dashboard_is_mobile_on_phone(): void
    {
        $this->actingAs($this->member())
            ->withHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 13; Pixel 7) AppleWebKit/537.36 Mobile Safari/537.36')
            ->get('/backend/dashboard')
            ->assertOk()
            ->assertViewIs('pages.mobile.home');
    }

    public function test_member_dashboard_stays_desktop_on_narrow_laptop_viewport(): void
    {
        $this->actingAs($this->member())
            ->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36')
            ->withHeader('Sec-CH-Viewport-Width', '390')
            ->get('/backend/dashboard')
            ->assertOk()
            ->assertViewIs('pages.dashboard.index');
    }

    public function test_member_dashboard_is_desktop_on_laptop(): void
    {
        $this->actingAs($this->member())
            ->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36')
            ->get('/backend/dashboard')
            ->assertOk()
            ->assertViewIs('pages.dashboard.index');
    }

    public function test_member_view_mobile_query_forces_mobile_on_laptop(): void
    {
        $this->actingAs($this->member())
            ->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36')
            ->get('/backend/dashboard?view=mobile')
            ->assertOk()
            ->assertViewIs('pages.mobile.home');
    }

    public function test_phone_never_falls_back_to_desktop_view(): void
    {
        $this->actingAs($this->member())
            ->withHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 13; Pixel 7) Mobile Safari/537.36')
            ->get('/backend/dashboard?view=desktop')
            ->assertOk()
            ->assertViewIs('pages.mobile.home');
    }

    public function test_member_own_orders_are_accessible(): void
    {
        $this->actingAs($this->member())
            ->withHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 13) Mobile')
            ->get('/backend/orders')
            ->assertOk()
            ->assertViewIs('pages.mobile.orders');
    }
}
