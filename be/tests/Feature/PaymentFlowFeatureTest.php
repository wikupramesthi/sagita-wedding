<?php

namespace Tests\Feature;

use App\Mail\PaymentProofReceivedMail;
use App\Models\Package\Package;
use App\Models\Package\PackageOption;
use App\Models\Package\PackagePromo;
use App\Models\Payment\EventDateReservation;
use App\Models\Payment\Order;
use App\Models\Payment\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
            'package.index', 'package.store', 'package.update', 'package.destroy', 'package.member',
            'package.promo.index', 'package.promo.store', 'package.promo.update', 'package.promo.destroy',
            'order.index', 'order.report', 'order.update',
            'orders.index', 'orders.store', 'orders.approve',
        ] as $perm) {
            Permission::findOrCreate($perm, 'web');
        }
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        Role::findByName('admin', 'web')->syncPermissions(Permission::all());
        $this->member = User::factory()->create();
        $memberRole = Role::findOrCreate('user', 'web');
        $memberRole->givePermissionTo(Permission::findByName('orders.store', 'web'));
        $this->member->assignRole($memberRole);

        foreach ([
            'orders.approve' => 'orders.approve',
            'orders.reorder' => 'orders.index',
            'orders.payments.store' => 'orders.store',
            'orders.payments.approve' => 'orders.approve',
        ] as $routeName => $permissionName) {
            DB::table('routes')->updateOrInsert(
                ['route' => $routeName],
                [
                    'permission_name' => $permissionName,
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        Cache::flush();
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

    private function makePendingOrder(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'order_number' => 'ORD-TEST-'.uniqid(),
            'user_uuid' => $this->member->uuid,
            'package_uuid' => $this->package->uuid,
            'package_option_uuid' => $this->option->uuid,
            'event_date' => today()->addMonths(6)->toDateString(),
            'amount' => 10000000,
            'total_amount' => 10000000,
            'amount_paid' => 0,
            'remaining_amount' => 10000000,
            'payment_scheme' => 'full',
            'status' => 'pending',
        ], $overrides));
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
        Mail::fake();
        Storage::fake('local');
        $order = Order::create([
            'order_number' => 'ORD-TEST-'.uniqid(),
            'user_uuid' => $this->member->uuid,
            'package_uuid' => $this->package->uuid,
            'package_option_uuid' => $this->option->uuid,
            'event_date' => today()->addMonths(6)->toDateString(),
            'amount' => 3000000,
            'total_amount' => 10000000,
            'amount_paid' => 0,
            'remaining_amount' => 10000000,
            'payment_scheme' => 'dp30',
            'status' => 'pending',
        ]);
        EventDateReservation::create([
            'event_date' => $order->event_date->toDateString(),
            'order_uuid' => $order->uuid,
            'status' => 'held',
            'expires_at' => now()->addHours(24),
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
        $this->assertDatabaseHas('event_date_reservations', [
            'event_date' => $order->event_date->toDateString(),
            'order_uuid' => $order->uuid,
            'status' => 'confirmed',
            'expires_at' => null,
        ]);

        $this->actingAs($this->member)
            ->post(route('orders.payments.store', $order->uuid), [
                'amount' => 2000000,
                'proof' => UploadedFile::fake()->image('i.jpg'),
            ])->assertRedirect();

        Mail::assertSent(
            PaymentProofReceivedMail::class,
            function (PaymentProofReceivedMail $mail) use ($order): bool {
                $mail->assertSeeInHtml('Status pembayaran akan diperbarui setelah proses pemeriksaan selesai.');

                return $mail->hasTo($this->member->email)
                    && $mail->orderNumber === $order->order_number
                    && $mail->amount === 2000000.0;
            }
        );

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

    public function test_admin_can_mark_remaining_balance_as_received(): void
    {
        Storage::fake('local');
        $order = Order::create([
            'order_number' => 'ORD-TEST-'.uniqid(),
            'user_uuid' => $this->member->uuid,
            'package_uuid' => $this->package->uuid,
            'package_option_uuid' => $this->option->uuid,
            'amount' => 10000000,
            'total_amount' => 10000000,
            'amount_paid' => 0,
            'remaining_amount' => 10000000,
            'payment_scheme' => 'full',
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)
            ->post(route('orders.payments.store', $order->uuid), [
                'amount' => 3000000,
                'record_only' => 1,
                'admin_note' => 'Pembayaran pertama diterima.',
                'proof' => UploadedFile::fake()->image('transfer.jpg'),
            ])
            ->assertRedirect();

        $order->refresh();
        $this->assertSame('partially_paid', $order->status);
        $this->assertEquals(3000000.0, (float) $order->amount_paid_value);
        $this->assertEquals(7000000.0, (float) $order->remaining_amount_value);
        $firstPayment = Payment::where('order_uuid', $order->uuid)->firstOrFail();
        $this->assertSame('approved', $firstPayment->verification_status);
        $this->assertNotNull($firstPayment->proof_image_path);
        Storage::disk('local')->assertExists($firstPayment->proof_image_path);

        $this->actingAs($this->admin)
            ->post(route('orders.payments.store', $order->uuid), [
                'amount' => 7000000,
                'record_only' => 1,
                'admin_note' => 'Pelunasan diterima tanpa bukti unggahan.',
            ])
            ->assertRedirect();

        $order->refresh();
        $this->assertSame('paid', $order->status);
        $this->assertEquals(10000000.0, (float) $order->amount_paid_value);
        $this->assertEquals(0.0, (float) $order->remaining_amount_value);
        $this->assertSame(2, Payment::where('order_uuid', $order->uuid)->count());
        $this->assertDatabaseHas('payments', [
            'order_uuid' => $order->uuid,
            'amount' => 3000000,
            'status' => 'paid',
            'verification_status' => 'approved',
            'kind' => 'dp',
            'payment_type' => 'Pencatatan Admin',
        ]);
        $this->assertDatabaseHas('payments', [
            'order_uuid' => $order->uuid,
            'amount' => 7000000,
            'status' => 'paid',
            'verification_status' => 'approved',
            'kind' => 'balance',
            'payment_type' => 'Pencatatan Admin',
        ]);
    }

    public function test_admin_cannot_record_payment_over_remaining_amount(): void
    {
        $order = $this->makePendingOrder();

        $this->actingAs($this->admin)
            ->post(route('orders.payments.store', $order->uuid), [
                'amount' => 10000001,
                'record_only' => 1,
            ])
            ->assertSessionHasErrors('amount');

        $this->assertDatabaseMissing('payments', ['order_uuid' => $order->uuid]);
    }

    public function test_member_cannot_use_admin_record_only_payment(): void
    {
        $order = $this->makePendingOrder();

        $this->actingAs($this->member)
            ->post(route('orders.payments.store', $order->uuid), [
                'amount' => 1000000,
                'record_only' => 1,
            ])
            ->assertSessionHasErrors('proof');

        $this->assertDatabaseMissing('payments', ['order_uuid' => $order->uuid]);
    }

    public function test_overpaid_pending_payment_is_rejected_on_approval(): void
    {
        $order = $this->makePendingOrder();
        $payment = Payment::create([
            'order_uuid' => $order->uuid,
            'kind' => 'dp',
            'status' => 'pending',
            'verification_status' => 'pending',
            'amount' => 10000001,
            'gross_amount' => 10000001,
        ]);

        $this->actingAs($this->admin)
            ->post(route('orders.payments.approve', [$order->uuid, $payment->uuid]))
            ->assertSessionHas('error', 'Nominal pembayaran melebihi sisa tagihan.');

        $this->assertSame('pending', $payment->fresh()->verification_status);
        $this->assertEquals(0.0, (float) $order->fresh()->amount_paid);
    }

    public function test_reorder_revalidates_event_date_lead_time(): void
    {
        $this->package->update(['is_published' => true, 'lead_time_days' => 30]);
        $order = $this->makePendingOrder([
            'status' => 'failed',
            'event_date' => today()->addDays(7)->toDateString(),
        ]);

        $this->actingAs($this->admin)
            ->post(route('orders.reorder', $order->uuid))
            ->assertSessionHasErrors('event_date');

        $this->assertSame(1, Order::where('user_uuid', $this->member->uuid)->count());
    }

    public function test_reorder_rejects_unpublished_package(): void
    {
        $this->package->update(['is_published' => false, 'lead_time_days' => 0]);
        $order = $this->makePendingOrder([
            'status' => 'failed',
            'event_date' => today()->addDays(7)->toDateString(),
        ]);

        $this->actingAs($this->admin)
            ->post(route('orders.reorder', $order->uuid))
            ->assertSessionHas('error', __('flash.package_unavailable_now'));

        $this->assertSame(1, Order::where('user_uuid', $this->member->uuid)->count());
    }
}
