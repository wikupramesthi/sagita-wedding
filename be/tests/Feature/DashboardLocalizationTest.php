<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardLocalizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['user', 'admin', 'super-admin', 'vendor'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }
    }

    public function test_admin_dashboard_follows_session_locale(): void
    {
        $admin = User::factory()->create(['email_verified_at' => now()]);
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Quick actions')
            ->assertSee('Action queue')
            ->assertSee('Admin overview')
            ->assertSee('Last updated')
            ->assertSee('Live')
            ->assertSee('Revenue')
            ->assertSee('Orders')
            ->assertSee('Consultations')
            ->assertSee('Events')
            ->assertSee('Users')
            ->assertSee('Language');

        $this->actingAs($admin)
            ->withSession(['locale' => 'id'])
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Aksi cepat')
            ->assertSee('Perlu tindakan')
            ->assertSee('Ringkasan admin')
            ->assertSee('Data diperbarui')
            ->assertSee('Langsung')
            ->assertSee('Pendapatan')
            ->assertSee('Pesanan')
            ->assertSee('Konsultasi')
            ->assertSee('Acara')
            ->assertSee('Pengguna')
            ->assertSee('Bahasa');
    }

    public function test_member_dashboard_follows_session_locale(): void
    {
        $member = User::factory()->create(['email_verified_at' => now()]);
        $member->assignRole('user');

        $this->actingAs($member)
            ->withSession(['locale' => 'en'])
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Planning')
            ->assertSee('Payment')
            ->assertSee('Checklist')
            ->assertSee('Recommendations')
            ->assertSee('Find packages')
            ->assertSee('Check payment')
            ->assertSee('Language');

        $this->actingAs($member)
            ->withSession(['locale' => 'id'])
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Perencanaan')
            ->assertSee('Pembayaran')
            ->assertSee('Checklist')
            ->assertSee('Rekomendasi')
            ->assertSee('Cari paket')
            ->assertSee('Cek pembayaran')
            ->assertSee('Bahasa');
    }

    public function test_locale_route_switches_session_locale(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->assignRole('user');

        $response = $this->actingAs($user)
            ->withSession(['locale' => 'id'])
            ->get(route('locale', 'en'));

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');
    }
}
