<?php

namespace Tests\Feature;

use App\Models\Package\Package;
use App\Models\Package\PackageOption;
use App\Models\Package\PackagePromo;
use App\Models\Payment\Order;
use App\Models\Payment\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PaymentFlowFeatureTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;
    private User $member;
    private Package $package;
    private PackageOption $option;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        foreach ([
            'package.index','package.store','package.update','package.destroy','package.member',
            'package.promo.index','package.promo.store','package.promo.update','package.promo.destroy',
            'order.index','order.report','order.update',
        ] as $perm) {
            Permission::findOrCreate($perm, 'web');
        }
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        Role::findByName('admin', 'web')->syncPermissions(Permission::all());
        $this->member = User::factory()->create();
        $this->package = Package::create([
            'name' => 'Paket Test',
            'slug' => 'paket-test-payment-'.uniqid(),
            'description' => 'desc',
            'price' => 10000000,
            'is_active' => 'active',
            'is_popular' => false,
        ]);
        $this->option = PackageOption::create([
            'package_uuid' => $this->package->uuid,
            'name' => 'Default',
            'price' => 10000000,
            'discount_price' => null,
            'duration' => 1,
            'duration_unit' => 'month',
            'quota' => 1,
        ]);
    }

    public function test_admin_can_crud_promo(): void
    {
        $this->actingAs($this->admin)
            ->post(route('package-promos.store'), [
                'package_uuid' => $this->package->uuid,
                'code' => 'PROMO10',
                'type' => 'percent',
                'value' => 10,
                'min_purchase' => 0,
                'max_discount' => 1000000,
                'max_uses' => 5,
                'is_active' => 1,
            ])
            ->assertRedirect();

        $promo = PackagePromo::where('code', 'PROMO10')->firstOrFail();

        $this->actingAs($this->admin)
            ->put(route('package-promos.update', $promo->uuid), [
                'package_uuid' => $this->package->uuid,
                'code' => 'PROMO20',
                'type' => 'fixed',
                'value' => 500000,
                'min_purchase' => 0,
                'is_active' => 1,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('package_promos', ['uuid' => $promo->uuid, 'code' => 'PROMO20', 'type' => 'fixed']);

        $this->actingAs($this->admin)
            ->delete(route('package-promos.destroy', $promo->uuid))
            ->assertRedirect();

        $this->assertDatabaseMissing('package_promos', ['uuid' => $promo->uuid]);
    }

    public function test_dp_then_installment_then_balance_marks_paid(): void
    {
        Storage::fake('public');
        $order = Order::create([
            'order_number' => 'ORD-TEST-'.uniqid(),
            'user_uuid' => $this->member->uuid,
            'package_uuid' => $this->package->uuid,
            'package_option_uuid' => $this->option->uuid,
            'amount' => 3000000,
            'total_amount' => 10000000,
            'amount_paid' => 0,
            'remaining_amount' => 10000000,
            'payment_scheme' => 'dp30',
            'status' => 'pending',
        ]);

        Payment::create([
            'order_uuid' => $order->uuid,
            'payment_type' => 'manual_transfer',
            'payment_gateway' => 'manual_transfer',
            'kind' => 'dp',
            'status' => 'pending',
            'verification_status' => 'pending',
            'amount' => 3000000,
            'gross_amount' => 3000000,
            'proof_image_path' => 'proofs/dp.jpg',
        ]);

        $this->actingAs($this->admin)->post(route('orders.approve', $order->uuid), ['admin_note' => 'ok'])->assertRedirect();
        $order->refresh();
        $this->assertSame('partially_paid', $order->status);
        $this->assertEquals(3000000.0, (float) $order->amount_paid);
        $this->assertEquals(7000000.0, (float) $order->remaining_amount_value);

        $this->actingAs($this->member)
            ->post(route('orders.payments.store', $order->uuid), [
                'amount' => 2000000,
                'proof' => UploadedFile::fake()->image('i.jpg'),
            ])->assertRedirect();

        $inst = Payment::where('order_uuid', $order->uuid)->whereIn('verification_status', ['pending'])->latest()->firstOrFail();
        $this->actingAs($this->admin)->post(route('orders.payments.approve', [$order->uuid, $inst->uuid]), ['admin_note' => 'ok'])->assertRedirect();
        $order->refresh();
        $this->assertSame('partially_paid', $order->status);
        $this->assertEquals(5000000.0, (float) $order->amount_paid);

        $this->actingAs($this->member)
            ->post(route('orders.payments.store', $order->uuid), [
                'amount' => 5000000,
                'proof' => UploadedFile::fake()->image('f.jpg'),
            ])->assertRedirect();

        $bal = Payment::where('order_uuid', $order->uuid)->where('verification_status', 'pending')->latest()->firstOrFail();
        $this->actingAs($this->admin)->post(route('orders.payments.approve', [$order->uuid, $bal->uuid]), ['admin_note' => 'ok'])->assertRedirect();
        $order->refresh();
        $this->assertSame('paid', $order->status);
        $this->assertEquals(0.0, (float) $order->remaining_amount_value);
    }
}
