<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Package\Package;
use App\Models\Package\PackageOption;
use App\Models\Payment\Order;
use App\Models\Payment\OrderVendorAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class OrderVendorAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $member;

    private User $vendor;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        foreach (['admin', 'vendor', 'user'] as $role) {
            Role::findOrCreate($role, 'web');
        }
        $permission = Permission::findOrCreate('orders.manageVendors', 'web');
        Role::findByName('admin', 'web')->givePermissionTo($permission);

        $this->admin = User::factory()->create(['email_verified_at' => now()]);
        $this->admin->assignRole('admin');
        $this->member = User::factory()->create(['email_verified_at' => now()]);
        $this->member->assignRole('user');
        $this->vendor = User::factory()->create(['email_verified_at' => now(), 'name' => 'Vendor Foto']);
        $this->vendor->assignRole('vendor');

        $package = Package::create([
            'name' => 'Paket Test',
            'slug' => 'paket-test',
            'description' => 'Test',
            'price' => 1000000,
            'is_active' => 'active',
            'is_popular' => false,
        ]);
        $option = PackageOption::create([
            'package_uuid' => $package->uuid,
            'name' => 'Default',
            'price' => 1000000,
            'duration' => 1,
            'duration_unit' => 'month',
            'quota' => 1,
        ]);
        $this->order = Order::create([
            'user_uuid' => $this->member->uuid,
            'order_number' => 'ORD-VENDOR-001',
            'type' => 'package',
            'package_uuid' => $package->uuid,
            'package_option_uuid' => $option->uuid,
            'event_date' => today()->addMonth()->toDateString(),
            'amount' => 1000000,
            'payment_scheme' => 'full',
            'total_amount' => 1000000,
            'amount_paid' => 0,
            'remaining_amount' => 1000000,
            'status' => 'pending',
            'expired_at' => now()->addDay(),
        ]);
    }

    public function test_admin_can_assign_update_and_unassign_vendor(): void
    {
        $this->actingAs($this->admin)
            ->post(route('orders.vendors.assign', $this->order->uuid), [
                'vendor_uuid' => $this->vendor->uuid,
                'scope' => 'Fotografi',
                'notes' => 'Tim datang pagi',
            ])
            ->assertRedirect();

        $assignment = OrderVendorAssignment::firstOrFail();
        $this->assertDatabaseHas('order_vendor_assignments', [
            'order_uuid' => $this->order->uuid,
            'vendor_uuid' => $this->vendor->uuid,
            'scope' => 'Fotografi',
            'status' => 'assigned',
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $this->order->uuid,
            'event' => 'vendor_assigned',
        ]);

        $this->actingAs($this->admin)
            ->patch(route('orders.vendors.status', [$this->order->uuid, $assignment->uuid]), [
                'status' => 'completed',
            ])
            ->assertRedirect();

        $assignment->refresh();
        $this->assertSame('completed', $assignment->status);
        $this->assertNotNull($assignment->accepted_at);
        $this->assertNotNull($assignment->completed_at);
        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $this->order->uuid,
            'event' => 'vendor_status_changed',
        ]);

        $this->actingAs($this->admin)
            ->delete(route('orders.vendors.destroy', [$this->order->uuid, $assignment->uuid]))
            ->assertRedirect();

        $this->assertDatabaseMissing('order_vendor_assignments', ['uuid' => $assignment->uuid]);
        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $this->order->uuid,
            'event' => 'vendor_unassigned',
        ]);
    }

    public function test_member_cannot_manage_vendor_assignments(): void
    {
        $assignment = OrderVendorAssignment::create([
            'order_uuid' => $this->order->uuid,
            'vendor_uuid' => $this->vendor->uuid,
            'scope' => 'Dekorasi',
            'status' => 'assigned',
            'assigned_by' => $this->admin->uuid,
        ]);

        $this->actingAs($this->member)
            ->post(route('orders.vendors.assign', $this->order->uuid), [
                'vendor_uuid' => $this->vendor->uuid,
                'scope' => 'Fotografi',
            ])
            ->assertForbidden();

        $this->actingAs($this->member)
            ->patch(route('orders.vendors.status', [$this->order->uuid, $assignment->uuid]), [
                'status' => 'accepted',
            ])
            ->assertForbidden();

        $this->actingAs($this->member)
            ->delete(route('orders.vendors.destroy', [$this->order->uuid, $assignment->uuid]))
            ->assertForbidden();

        $this->assertSame(1, OrderVendorAssignment::count());
        $this->assertSame(0, AuditLog::whereIn('event', ['vendor_assigned', 'vendor_status_changed', 'vendor_unassigned'])->count());
    }
}
