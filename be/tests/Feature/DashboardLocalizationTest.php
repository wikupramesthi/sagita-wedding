<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardLocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_backend_is_fixed_to_indonesian(): void
    {
        $this->assertSame('id', config('app.locale'));
        $this->assertSame('id', config('app.fallback_locale'));
        $this->assertSame('id_ID', config('app.faker_locale'));
        $this->assertDirectoryDoesNotExist(lang_path('en'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['user', 'admin', 'super-admin', 'vendor'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        Permission::findOrCreate('konsultasi.index', 'web');
        Role::findByName('admin', 'web')->givePermissionTo('konsultasi.index');
    }

    public function test_admin_dashboard_uses_indonesian_regardless_of_session_locale(): void
    {
        $admin = User::factory()->create(['email_verified_at' => now()]);
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('dist/assets/bundles/admin.css')
            ->assertSee('dist/assets/bundles/admin.js')
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
            ->assertDontSee('Quick actions')
            ->assertDontSee('Action queue')
            ->assertDontSee('Admin overview')
            ->assertDontSee('Last updated')
            ->assertDontSee('Language')
            ->assertDontSee('Bahasa');
    }

    public function test_member_dashboard_uses_indonesian_regardless_of_session_locale(): void
    {
        $member = User::factory()->create(['email_verified_at' => now()]);
        $member->assignRole('user');

        $this->actingAs($member)
            ->withSession(['locale' => 'en'])
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Perencanaan')
            ->assertSee('Rekap Pembayaran dan Bukti Transfer')
            ->assertSee('Checklist')
            ->assertSee('Rekomendasi')
            ->assertSee('Cari paket')
            ->assertSee('Cek pembayaran')
            ->assertDontSee('Planning')
            ->assertDontSee('Payment and Transfer Proof Summary')
            ->assertDontSee('Find packages')
            ->assertDontSee('Check payment')
            ->assertDontSee('Language')
            ->assertDontSee('Bahasa');
    }

    public function test_backend_locale_route_is_not_available(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->assignRole('user');

        $this->assertFalse(Route::has('locale'));

        $this->actingAs($user)
            ->get('/lang/en')
            ->assertNotFound();
    }
}
