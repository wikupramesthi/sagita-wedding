<?php

namespace Tests\Feature;

use App\Models\Package\Package;
use App\Models\Package\PackageOption;
use App\Models\Payment\Order;
use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OrderAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $member;

    private PackageOption $option;

    /** @var array<int, string> */
    private array $orderUuids = [];

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['user', 'admin', 'super-admin'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        $permissions = [
            'orders.index',
            'orders.approve',
            'orders.reject',
            'orders.proof',
            'orders.bulk',
            'orders.exportCsv',
        ];

        $adminRole = Role::findByName('admin', 'web');
        foreach ($permissions as $permission) {
            $adminRole->givePermissionTo(Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            ));
        }

        $this->admin = User::factory()->create(['email_verified_at' => now()]);
        $this->admin->assignRole($adminRole);

        $this->member = User::factory()->create(['email_verified_at' => now()]);
        $this->member->assignRole('user');

        $package = Package::create([
            'name' => 'Paket Wedding',
            'slug' => 'paket-wedding',
            'is_active' => 'active',
        ]);

        $this->option = PackageOption::create([
            'package_uuid' => $package->uuid,
            'name' => 'Standar',
            'quota' => 8,
            'price' => 500000,
            'duration' => 1,
            'duration_unit' => 'month',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        for ($i = 0; $i < 2; $i++) {
            $order = Order::create([
                'user_uuid' => $this->member->uuid,
                'order_number' => 'ORD-'.strtoupper(Str::random(10)),
                'type' => 'package',
                'package_uuid' => $package->uuid,
                'package_option_uuid' => $this->option->uuid,
                'amount' => 500000,
                'status' => 'pending',
            ]);

            $this->orderUuids[] = $order->uuid;
        }
    }

    public function test_order_pages_are_rendered_in_indonesian_without_legacy_brand(): void
    {
        $this->actingAs($this->admin)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee('Daftar Pesanan')
            ->assertSee('| Admin Sagita Wedding Organizer', false)
            ->assertSee('noindex, nofollow', false)
            ->assertSee('Menunggu Verifikasi')
            ->assertSee('Pendapatan (Lunas)')
            ->assertDontSee('Total Orders')
            ->assertDontSee('Order List')
            ->assertDontSee('YogaRoots')
            ->assertDontSee('Yogaroots');

        $this->actingAs($this->admin)
            ->get(route('orders.report'))
            ->assertOk()
            ->assertSee('Laporan Pesanan')
            ->assertSee('Dicetak oleh')
            ->assertSee('Semua Periode')
            ->assertDontSee('Order Report')
            ->assertDontSee('YogaRoots');
    }

    public function test_admin_can_export_filtered_orders_to_csv(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('orders.exportCsv', ['status' => 'pending']));

        $response->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $csv = $response->streamedContent();

        $this->assertStringContainsString('No. Pesanan', $csv);
        $this->assertStringContainsString('Menunggu', $csv);
        $this->assertStringContainsString('Paket Wedding', $csv);
        $this->assertStringContainsString($this->member->name, $csv);
    }

    public function test_admin_can_bulk_approve_pending_orders(): void
    {
        $this->actingAs($this->admin)
            ->post(route('orders.bulk'), [
                'action' => 'approve',
                'ids' => $this->orderUuids,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        foreach ($this->orderUuids as $uuid) {
            $this->assertDatabaseHas('orders', [
                'uuid' => $uuid,
                'status' => 'paid',
            ]);
        }

        $userPackage = UserPackage::where('user_uuid', $this->member->uuid)->firstOrFail();
        $this->assertSame(1, UserPackage::where('user_uuid', $this->member->uuid)->count());
        $this->assertSame(16, (int) $userPackage->quota);
    }

    public function test_admin_can_bulk_reject_pending_orders(): void
    {
        $this->actingAs($this->admin)
            ->post(route('orders.bulk'), [
                'action' => 'reject',
                'ids' => $this->orderUuids,
                'admin_note' => 'Bukti transfer tidak valid',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        foreach ($this->orderUuids as $uuid) {
            $this->assertDatabaseHas('orders', [
                'uuid' => $uuid,
                'status' => 'failed',
                'admin_note' => 'Bukti transfer tidak valid',
            ]);
        }
    }

    public function test_bulk_action_is_admin_only(): void
    {
        $this->actingAs($this->member)
            ->post(route('orders.bulk'), [
                'action' => 'approve',
                'ids' => $this->orderUuids,
            ])
            ->assertNotFound();

        foreach ($this->orderUuids as $uuid) {
            $this->assertDatabaseHas('orders', [
                'uuid' => $uuid,
                'status' => 'pending',
            ]);
        }
    }
}
