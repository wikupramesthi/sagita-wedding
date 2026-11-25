<?php

namespace Tests\Feature;

use App\Jobs\SendTelegramMessage;
use App\Models\AuditLog;
use App\Models\Package\Package;
use App\Models\Package\PackageOption;
use App\Models\Payment\Order;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Models\UserPackage;
use App\Notifications\OrderSlaReminderNotification;
use Database\Seeders\RouteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OrderOpsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $otherAdmin;

    private User $member;

    private PackageOption $option;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['user', 'admin', 'super-admin'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }
        $this->seed(RouteSeeder::class);

        $permissions = [
            'orders.approve',
            'orders.reject',
            'orders.bulk',
            'orders.exportCsv',
            'orders.audit',
            'orders.whatsapp',
            'orders.assign',
            'orders.savedFilters',
            'orders.bulkUndo',
            'orders.quickView',
        ];

        $adminRole = Role::findByName('admin', 'web');
        foreach ($permissions as $permission) {
            $adminRole->givePermissionTo(Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            ));
        }

        $this->admin = User::factory()->create(['email_verified_at' => now()]);
        $this->otherAdmin = User::factory()->create(['email_verified_at' => now()]);
        $this->member = User::factory()->create(['email_verified_at' => now()]);

        $this->admin->assignRole($adminRole);
        $this->otherAdmin->assignRole($adminRole);
        $this->member->assignRole('user');

        $package = Package::create([
            'name' => 'Paket Ops',
            'slug' => 'paket-ops',
            'price' => 100000,
            'is_active' => 'active',
        ]);

        $this->option = PackageOption::create([
            'package_uuid' => $package->uuid,
            'name' => 'Standar',
            'quota' => 8,
            'price' => 750000,
            'duration' => 1,
            'duration_unit' => 'month',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->order = Order::create([
            'user_uuid' => $this->member->uuid,
            'order_number' => 'ORD-OPS-'.strtoupper(Str::random(6)),
            'type' => 'package',
            'package_uuid' => $package->uuid,
            'package_option_uuid' => $this->option->uuid,
            'amount' => 750000,
            'remaining_amount' => 750000,
            'status' => 'pending',
        ]);
    }

    public function test_approve_with_same_idempotency_key_is_processed_once(): void
    {
        Queue::fake();
        config([
            'services.telegram.enabled' => true,
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '-100123',
        ]);
        $payload = ['idempotency_key' => 'approve-once'];

        $this->actingAs($this->admin)
            ->post(route('orders.approve', $this->order->uuid), $payload)
            ->assertRedirect(route('orders.show', $this->order->uuid));

        $this->actingAs($this->admin)
            ->post(route('orders.approve', $this->order->uuid), $payload)
            ->assertRedirect(route('orders.show', $this->order->uuid));

        $this->assertSame(1, Payment::where('order_uuid', $this->order->uuid)->count());
        $this->assertSame(1, UserPackage::where('order_uuid', $this->order->uuid)->count());
        Queue::assertPushed(SendTelegramMessage::class, 1);
        Queue::assertPushed(SendTelegramMessage::class, fn (SendTelegramMessage $job): bool => str_contains(
            $job->message,
            '<b>Pembayaran Berhasil Diverifikasi</b>'
        ));
    }

    public function test_admin_can_claim_pending_order(): void
    {
        $this->actingAs($this->admin)
            ->post(route('orders.claim', $this->order->uuid))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'uuid' => $this->order->uuid,
            'assigned_admin_uuid' => $this->admin->uuid,
        ]);

        $this->actingAs($this->otherAdmin)
            ->post(route('orders.claim', $this->order->uuid))
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_quick_view_renders_order_summary(): void
    {
        $this->actingAs($this->admin)
            ->get(route('orders.quick-view', $this->order->uuid))
            ->assertOk()
            ->assertSee($this->order->order_number)
            ->assertSee($this->member->name);
    }

    public function test_admin_can_open_order_activity_drawer(): void
    {
        AuditLog::create([
            'user_name' => 'Sistem',
            'event' => 'proof_uploaded',
            'auditable_type' => Order::class,
            'auditable_id' => (string) $this->order->uuid,
            'auditable_label' => $this->order->order_number,
        ]);

        $this->actingAs($this->admin)
            ->get(route('orders.activity', $this->order->uuid))
            ->assertOk()
            ->assertSee('Riwayat aktivitas')
            ->assertSee('Bukti transfer diunggah');
    }

    public function test_admin_can_send_bulk_sla_reminder(): void
    {
        Notification::fake();

        $this->order->forceFill(['created_at' => now()->subHours(30)])->save();

        $this->actingAs($this->admin)
            ->post(route('orders.remind-sla'), ['ids' => [$this->order->uuid]])
            ->assertRedirect()
            ->assertSessionHas('success');

        Notification::assertSentTo($this->admin, OrderSlaReminderNotification::class);
        $this->assertNotNull($this->order->fresh()->sla_reminded_at);
        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $this->order->uuid,
            'event' => 'sla_reminded',
        ]);
    }

    public function test_bulk_approval_can_be_undone(): void
    {
        $this->actingAs($this->admin)
            ->post(route('orders.bulk'), [
                'action' => 'approve',
                'ids' => [$this->order->uuid],
            ])
            ->assertRedirect()
            ->assertSessionHas('undo_token');

        $undoToken = session('undo_token');

        $this->assertDatabaseHas('orders', [
            'uuid' => $this->order->uuid,
            'status' => 'paid',
        ]);

        $this->actingAs($this->admin)
            ->post(route('orders.bulk-undo'), ['undo_token' => $undoToken])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'uuid' => $this->order->uuid,
            'status' => 'pending',
        ]);
        $this->assertSame(0, Payment::where('order_uuid', $this->order->uuid)->count());
        $this->assertSame(0, UserPackage::where('order_uuid', $this->order->uuid)->count());
        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $this->order->uuid,
            'event' => 'approval_reverted',
        ]);
    }

    public function test_bulk_undo_supports_json_toast_request(): void
    {
        $this->actingAs($this->admin)
            ->post(route('orders.bulk'), [
                'action' => 'approve',
                'ids' => [$this->order->uuid],
            ])
            ->assertRedirect()
            ->assertSessionHas('undo_token');

        $this->actingAs($this->admin)
            ->postJson(route('orders.bulk-undo'), [
                'undo_token' => session('undo_token'),
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('orders', [
            'uuid' => $this->order->uuid,
            'status' => 'pending',
        ]);
    }

    public function test_bulk_undo_restores_payment_status_before_rejection(): void
    {
        $payment = Payment::create([
            'order_uuid' => $this->order->uuid,
            'payment_gateway' => 'manual',
            'gross_amount' => 750000,
            'status' => 'challenge',
        ]);

        $this->actingAs($this->admin)
            ->post(route('orders.bulk'), [
                'action' => 'reject',
                'ids' => [$this->order->uuid],
                'admin_note' => 'Transfer tidak diterima',
            ])
            ->assertRedirect()
            ->assertSessionHas('undo_token');

        $this->assertDatabaseHas('payments', [
            'uuid' => $payment->uuid,
            'status' => 'failed',
        ]);

        $this->actingAs($this->admin)
            ->post(route('orders.bulk-undo'), ['undo_token' => session('undo_token')])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'uuid' => $this->order->uuid,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('payments', [
            'uuid' => $payment->uuid,
            'status' => 'challenge',
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $this->order->uuid,
            'event' => 'rejection_reverted',
        ]);
    }

    public function test_bulk_idempotency_normalizes_duplicate_and_reordered_ids(): void
    {
        $secondMember = User::factory()->create(['email_verified_at' => now()]);

        $second = Order::create([
            'user_uuid' => $secondMember->uuid,
            'order_number' => 'ORD-OPS-'.strtoupper(Str::random(6)),
            'type' => 'package',
            'package_uuid' => $this->option->package_uuid,
            'package_option_uuid' => $this->option->uuid,
            'amount' => 750000,
            'remaining_amount' => 750000,
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)
            ->post(route('orders.bulk'), [
                'idempotency_key' => 'bulk-normalized',
                'action' => 'approve',
                'ids' => [$second->uuid, $this->order->uuid, $second->uuid],
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->actingAs($this->admin)
            ->post(route('orders.bulk'), [
                'idempotency_key' => 'bulk-normalized',
                'action' => 'approve',
                'ids' => [$this->order->uuid, $second->uuid, $this->order->uuid],
            ])
            ->assertRedirect()
            ->assertSessionHas('success')
            ->assertSessionHas('undo_token');

        $this->assertSame(2, Payment::whereIn('order_uuid', [
            $this->order->uuid,
            $second->uuid,
        ])->count());
    }

    public function test_stalled_idempotency_processing_is_recovered(): void
    {
        $key = 'orders.idempotency.'.$this->admin->uuid.'.bulk-approve.'
            .sha1($this->order->uuid.'|stalled');

        Cache::put($key, [
            'status' => 'processing',
            'started_at' => now()->subMinutes(3)->getTimestamp(),
        ], now()->addMinutes(10));

        $this->actingAs($this->admin)
            ->post(route('orders.bulk'), [
                'idempotency_key' => 'stalled',
                'action' => 'approve',
                'ids' => [$this->order->uuid],
            ])
            ->assertRedirect()
            ->assertSessionHas('success')
            ->assertSessionMissing('error');

        $this->assertDatabaseHas('orders', [
            'uuid' => $this->order->uuid,
            'status' => 'paid',
        ]);
    }

    public function test_sla_command_notifies_admin_and_marks_order(): void
    {
        Notification::fake();

        $this->order->forceFill(['created_at' => now()->subHours(30)])->save();

        $this->artisan('orders:remind-sla')
            ->assertExitCode(0);

        Notification::assertSentTo($this->admin, OrderSlaReminderNotification::class);
        $this->assertNotNull($this->order->fresh()->sla_reminded_at);
    }

    public function test_audit_prune_command_removes_old_order_logs(): void
    {
        $recent = AuditLog::create([
            'user_name' => 'Sistem',
            'event' => 'approved',
            'auditable_type' => Order::class,
            'auditable_id' => (string) $this->order->uuid,
            'auditable_label' => $this->order->order_number,
        ]);
        $old = AuditLog::create([
            'user_name' => 'Sistem',
            'event' => 'rejected',
            'auditable_type' => Order::class,
            'auditable_id' => (string) $this->order->uuid,
            'auditable_label' => $this->order->order_number,
        ]);
        $old->forceFill(['created_at' => now()->subDays(200)])->save();

        $this->artisan('orders:prune-audit', ['--days' => 180])
            ->assertExitCode(0);

        $this->assertDatabaseHas('audit_logs', ['id' => $recent->id]);
        $this->assertDatabaseMissing('audit_logs', ['id' => $old->id]);
    }

    public function test_admin_cannot_claim_expired_pending_order(): void
    {
        $this->order->update(['expired_at' => now()->subMinute()]);

        $this->actingAs($this->admin)
            ->post(route('orders.claim', $this->order->uuid))
            ->assertRedirect()
            ->assertSessionHas('error', __('flash.order_expired_claim'));

        $this->assertDatabaseHas('orders', [
            'uuid' => $this->order->uuid,
            'assigned_admin_uuid' => null,
        ]);
    }
}
