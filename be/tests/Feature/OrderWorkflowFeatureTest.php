<?php

namespace Tests\Feature;

use App\Models\ManagementAccess\Route as RouteRecord;
use App\Models\Package\Package;
use App\Models\Package\PackageAddon;
use App\Models\Package\PackageOption;
use App\Models\Payment\CheckoutSession;
use App\Models\Payment\EventDateReservation;
use App\Models\Payment\Order;
use App\Models\Payment\OrderChangeRequest;
use App\Models\Payment\OrderDocument;
use App\Models\Payment\OrderFeedback;
use App\Models\Payment\OrderWorkflowSetting;
use App\Models\Payment\Payment;
use App\Models\Payment\WeddingChecklistItem;
use App\Models\User;
use App\Notifications\CheckoutAbandonedNotification;
use App\Services\EventDateReservationService;
use App\Services\OrderWorkflowProvisioningService;
use Database\Seeders\RouteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OrderWorkflowFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $member;

    private Package $package;

    private PackageOption $option;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $this->seed(RouteSeeder::class);
        Cache::flush();

        foreach (['orders.index', 'orders.show', 'orders.store'] as $permission) {
            $permission = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            Role::findByName('admin', 'web')->givePermissionTo($permission);
            Role::findByName('user', 'web')->givePermissionTo($permission);
        }

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        $this->member = User::factory()->create();
        $this->member->assignRole('user');

        $this->package = Package::create([
            'name' => 'Paket Workflow Test',
            'slug' => 'paket-workflow-'.uniqid(),
            'description' => 'Paket test alur pesanan.',
            'price' => 10000000,
            'is_active' => 'active',
            'is_popular' => false,
        ]);
        $this->option = PackageOption::create([
            'package_uuid' => $this->package->uuid,
            'name' => 'Paket Utama',
            'price' => 10000000,
            'duration' => 1,
            'duration_unit' => 'month',
            'quota' => 1,
        ]);
        $this->order = Order::create([
            'user_uuid' => $this->member->uuid,
            'order_number' => 'ORD-WORKFLOW-'.strtoupper(uniqid()),
            'type' => 'package',
            'package_uuid' => $this->package->uuid,
            'package_option_uuid' => $this->option->uuid,
            'event_date' => today()->addMonths(6)->toDateString(),
            'amount' => 2000000,
            'payment_scheme' => 'dp30',
            'total_amount' => 10000000,
            'amount_paid' => 2000000,
            'remaining_amount' => 8000000,
            'status' => 'partially_paid',
            'expired_at' => now()->addHours(24),
            'addons' => [],
        ]);
    }

    public function test_admin_can_update_contract_and_checklist_templates(): void
    {
        $this->actingAs($this->admin)
            ->put(route('orders.workflow-settings.update'), [
                'contract_template' => 'Kontrak untuk {{customer_name}}',
                'checklist' => [
                    ['title' => 'Konfirmasi lokasi', 'description' => 'Pastikan venue.', 'weeks_before_event' => 8],
                ],
            ])
            ->assertRedirect();

        $this->assertSame(
            'Kontrak untuk {{customer_name}}',
            OrderWorkflowSetting::current()->contract_template
        );
        $this->assertSame('Konfirmasi lokasi', OrderWorkflowSetting::current()->checklist_template[0]['title']);
    }

    public function test_admin_approval_reprices_addon_and_updates_order_balance(): void
    {
        $addon = PackageAddon::create([
            'package_uuid' => $this->package->uuid,
            'name' => 'Dekorasi tambahan',
            'price' => 900000,
            'discount_price' => 750000,
            'min_quantity' => 1,
            'max_quantity' => 5,
            'is_active' => true,
        ]);

        $this->actingAs($this->member)
            ->post(route('orders.change-requests.store', $this->order->uuid), [
                'change_type' => 'addon',
                'addon_uuid' => $addon->uuid,
                'addon_quantity' => 2,
                'customer_note' => 'Mohon tambahkan.',
            ])
            ->assertRedirect();

        $change = OrderChangeRequest::firstOrFail();
        $this->assertSame(1500000, $change->requested_price);

        $this->actingAs($this->admin)
            ->post(route('orders.change-requests.review', $change->uuid), [
                'decision' => 'approved',
            ])
            ->assertRedirect();

        $this->order->refresh();
        $this->assertSame(11500000.0, (float) $this->order->total_amount);
        $this->assertSame(9500000.0, (float) $this->order->remaining_amount);
        $this->assertSame(2, $this->order->addons[0]['quantity']);
        $this->assertDatabaseHas('order_change_requests', ['uuid' => $change->uuid, 'status' => 'approved']);
    }

    public function test_owner_can_update_checklist_but_other_members_cannot(): void
    {
        $item = WeddingChecklistItem::create([
            'order_uuid' => $this->order->uuid,
            'title' => 'Konfirmasi detail acara',
            'due_at' => today()->addMonths(4)->toDateString(),
        ]);

        $this->actingAs($this->member)
            ->post(route('orders.checklist.update', [$this->order->uuid, $item->uuid]), ['status' => 'completed'])
            ->assertRedirect();
        $this->assertDatabaseHas('wedding_checklist_items', [
            'uuid' => $item->uuid,
            'status' => 'completed',
            'completed_by' => $this->member->uuid,
        ]);

        $otherMember = User::factory()->create();
        $otherMember->assignRole('user');
        $this->actingAs($otherMember)
            ->post(route('orders.checklist.update', [$this->order->uuid, $item->uuid]), ['status' => 'pending'])
            ->assertForbidden();
    }

    public function test_verified_payment_provisions_documents_and_checklist_once(): void
    {
        OrderWorkflowSetting::current()->update([
            'contract_template' => 'Layanan {{order_number}}',
            'checklist_template' => [
                ['title' => 'Finalisasi acara', 'description' => '', 'weeks_before_event' => 4],
            ],
        ]);
        $payment = Payment::create([
            'order_uuid' => $this->order->uuid,
            'kind' => 'dp',
            'payment_gateway' => 'manual_transfer',
            'payment_type' => 'Transfer',
            'amount' => 2000000,
            'gross_amount' => 2000000,
            'status' => 'paid',
            'verification_status' => 'verified',
            'paid_at' => now(),
            'verified_at' => now(),
        ]);

        app(OrderWorkflowProvisioningService::class)->afterVerifiedPayment($this->order, $payment);
        app(OrderWorkflowProvisioningService::class)->afterVerifiedPayment($this->order, $payment);

        $this->assertSame(2, OrderDocument::where('order_uuid', $this->order->uuid)->count());
        $this->assertSame(1, WeddingChecklistItem::where('order_uuid', $this->order->uuid)->count());

        $nextPayment = Payment::create([
            'order_uuid' => $this->order->uuid,
            'kind' => 'installment',
            'payment_gateway' => 'manual_transfer',
            'payment_type' => 'Transfer',
            'amount' => 1000000,
            'gross_amount' => 1000000,
            'status' => 'paid',
            'verification_status' => 'verified',
            'paid_at' => now(),
            'verified_at' => now(),
        ]);
        app(OrderWorkflowProvisioningService::class)->afterVerifiedPayment($this->order, $nextPayment);
        app(OrderWorkflowProvisioningService::class)->afterVerifiedPayment($this->order, $nextPayment);

        $this->assertSame(3, OrderDocument::where('order_uuid', $this->order->uuid)->count());
        $this->assertSame(1, OrderDocument::where('order_uuid', $this->order->uuid)->where('type', 'receipt')->count());
    }

    public function test_reserved_date_cannot_be_locked_by_a_different_order(): void
    {
        $reservations = app(EventDateReservationService::class);
        $reservation = $reservations->lockDate($this->order->event_date, $this->order->uuid);
        $reservations->hold($reservation, $this->order);

        $this->expectException(ValidationException::class);
        $reservations->lockDate($this->order->event_date, 'another-order-uuid');
    }

    public function test_approved_date_change_moves_reservation_and_checklist_deadlines(): void
    {
        $checklist = WeddingChecklistItem::create([
            'order_uuid' => $this->order->uuid,
            'title' => 'Konfirmasi detail acara',
            'due_at' => $this->order->event_date->copy()->subWeeks(4)->toDateString(),
        ]);
        $newDate = $this->order->event_date->copy()->addWeeks(2)->toDateString();

        $this->actingAs($this->member)
            ->post(route('orders.change-requests.store', $this->order->uuid), [
                'change_type' => 'event_date',
                'requested_event_date' => $newDate,
            ])
            ->assertRedirect();
        $change = OrderChangeRequest::firstOrFail();

        $this->actingAs($this->admin)
            ->post(route('orders.change-requests.review', $change->uuid), [
                'decision' => 'approved',
            ])
            ->assertRedirect();

        $this->order->refresh();
        $this->assertSame($newDate, $this->order->event_date->toDateString());
        $this->assertSame($this->order->event_date->copy()->subWeeks(4)->toDateString(), $checklist->fresh()->due_at->toDateString());
        $this->assertDatabaseHas('event_date_reservations', [
            'event_date' => $newDate,
            'order_uuid' => $this->order->uuid,
            'status' => 'confirmed',
        ]);
    }

    public function test_checkout_analytics_counts_mature_abandoned_sessions_without_customer_data(): void
    {
        CheckoutSession::create([
            'visitor_key' => (string) Str::uuid(),
            'package_uuid' => $this->package->uuid,
            'status' => 'started',
            'started_at' => now()->subHours(25),
        ]);
        CheckoutSession::create([
            'visitor_key' => (string) Str::uuid(),
            'package_uuid' => $this->package->uuid,
            'order_uuid' => $this->order->uuid,
            'status' => 'completed',
            'started_at' => now()->subHour(),
            'completed_at' => now(),
        ]);

        $this->actingAs($this->admin)
            ->get(route('orders.analytics'))
            ->assertOk()
            ->assertSee('Pemesanan belum selesai')
            ->assertSee('Paket Workflow Test')
            ->assertSee('50,0%');
    }

    public function test_abandoned_checkout_reminder_is_sent_once_only_to_an_identified_customer(): void
    {
        Notification::fake();

        $identified = CheckoutSession::create([
            'visitor_key' => (string) Str::uuid(),
            'package_uuid' => $this->package->uuid,
            'user_uuid' => $this->member->uuid,
            'status' => 'started',
            'started_at' => now()->subHours(25),
        ]);
        $anonymous = CheckoutSession::create([
            'visitor_key' => (string) Str::uuid(),
            'package_uuid' => $this->package->uuid,
            'status' => 'started',
            'started_at' => now()->subHours(25),
        ]);

        Artisan::call('checkouts:remind-abandoned');

        Notification::assertSentTo($this->member, CheckoutAbandonedNotification::class);
        $this->assertNotNull($identified->fresh()->reminder_sent_at);
        $this->assertNull($anonymous->fresh()->reminder_sent_at);

        Artisan::call('checkouts:remind-abandoned');

        Notification::assertSentToTimes($this->member, CheckoutAbandonedNotification::class, 1);
    }

    public function test_order_documents_cannot_be_downloaded_by_another_member(): void
    {
        $document = OrderDocument::create([
            'order_uuid' => $this->order->uuid,
            'document_number' => 'CTR-'.uniqid(),
            'document_key' => $this->order->uuid.':contract',
            'type' => 'contract',
            'snapshot' => [],
            'issued_at' => now(),
        ]);
        $otherMember = User::factory()->create();
        $otherMember->assignRole('user');

        $this->actingAs($otherMember)
            ->get(route('orders.documents.download', [$this->order->uuid, $document->uuid]))
            ->assertForbidden();

        $this->actingAs($this->member)
            ->get(route('orders.documents.download', [$this->order->uuid, $document->uuid]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_customer_can_accept_a_contract_and_the_approved_snapshot_is_preserved(): void
    {
        $snapshot = [
            'customer_name' => $this->member->name,
            'contract_template' => 'Kontrak versi pertama',
            'total_amount' => 10000000,
        ];
        $document = OrderDocument::create([
            'order_uuid' => $this->order->uuid,
            'document_number' => 'CTR-'.uniqid(),
            'document_key' => $this->order->uuid.':contract',
            'type' => 'contract',
            'snapshot' => $snapshot,
            'issued_at' => now(),
        ]);

        $this->actingAs($this->member)
            ->post(route('orders.documents.accept', [$this->order->uuid, $document->uuid]), ['agreement' => '1'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $accepted = $document->fresh();
        $this->assertNotNull($accepted->accepted_at);
        $this->assertSame($this->member->uuid, $accepted->accepted_by_uuid);
        $this->assertSame($snapshot, $accepted->accepted_snapshot);

        $this->actingAs($this->admin)
            ->get(route('orders.show', $this->order->uuid))
            ->assertOk()
            ->assertSee('Pelanggan menyetujui kontrak')
            ->assertSee($this->member->name);

        $this->actingAs($this->admin)
            ->post(route('orders.documents.accept', [$this->order->uuid, $document->uuid]), ['agreement' => '1'])
            ->assertForbidden();
    }

    public function test_customer_feedback_is_only_allowed_after_the_event_and_can_be_updated(): void
    {
        $this->order->update([
            'status' => 'paid',
            'event_date' => today()->subDay()->toDateString(),
        ]);

        $this->actingAs($this->member)
            ->post(route('orders.feedback.store', $this->order->uuid), [
                'rating' => 5,
                'comment' => 'Pelayanan sangat baik.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('order_feedback', [
            'order_uuid' => $this->order->uuid,
            'user_uuid' => $this->member->uuid,
            'rating' => 5,
        ]);

        $this->actingAs($this->member)
            ->post(route('orders.feedback.store', $this->order->uuid), [
                'rating' => 4,
                'comment' => 'Sudah diperbarui.',
            ])
            ->assertRedirect();

        $this->assertSame(1, OrderFeedback::where('order_uuid', $this->order->uuid)->count());
        $this->assertDatabaseHas('order_feedback', [
            'order_uuid' => $this->order->uuid,
            'rating' => 4,
            'comment' => 'Sudah diperbarui.',
        ]);

        $this->actingAs($this->admin)
            ->get(route('orders.show', $this->order->uuid))
            ->assertOk()
            ->assertSee('Masukan pelanggan')
            ->assertSee('Sudah diperbarui.');
        $this->get(route('orders.analytics'))
            ->assertOk()
            ->assertSee('4,0 / 5')
            ->assertSee('1 tanggapan');

        $this->order->update(['event_date' => today()->addDay()->toDateString()]);
        $this->actingAs($this->member)
            ->post(route('orders.feedback.store', $this->order->uuid), ['rating' => 5])
            ->assertForbidden();
    }

    public function test_order_document_preview_streams_inline_pdf_without_route_registration(): void
    {
        $document = OrderDocument::create([
            'order_uuid' => $this->order->uuid,
            'document_number' => 'INV-'.uniqid(),
            'document_key' => $this->order->uuid.':invoice',
            'type' => 'invoice',
            'snapshot' => [
                'customer_name' => $this->member->name,
                'order_number' => $this->order->order_number,
                'total_amount' => 10000000,
                'amount_paid' => 3000000,
                'remaining_amount' => 7000000,
                'payment_amount' => 3000000,
            ],
            'issued_at' => now(),
        ]);

        $this->actingAs($this->admin)
            ->get(route('orders.show', $this->order->uuid))
            ->assertOk()
            ->assertSee('target="_blank"', false)
            ->assertSee(route('orders.documents.preview', [$this->order->uuid, $document->uuid]), false);

        RouteRecord::whereIn('route', ['orders.documents.preview', 'orders.documents.download'])->delete();
        Cache::flush();

        $preview = $this->actingAs($this->member)
            ->get(route('orders.documents.preview', [$this->order->uuid, $document->uuid]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->assertStringContainsString('inline', (string) $preview->headers->get('Content-Disposition'));

        $download = $this->actingAs($this->admin)
            ->get(route('orders.documents.download', [$this->order->uuid, $document->uuid]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->assertStringContainsString('attachment', (string) $download->headers->get('Content-Disposition'));
    }

    public function test_order_workflow_panel_renders_for_desktop_and_mobile_members(): void
    {
        WeddingChecklistItem::create([
            'order_uuid' => $this->order->uuid,
            'title' => 'Konfirmasi detail acara',
            'due_at' => today()->addMonths(4)->toDateString(),
        ]);
        OrderDocument::create([
            'order_uuid' => $this->order->uuid,
            'document_number' => 'CTR-'.uniqid(),
            'document_key' => $this->order->uuid.':contract',
            'type' => 'contract',
            'snapshot' => [],
            'issued_at' => now(),
        ]);

        $this->actingAs($this->member)
            ->get(route('orders.show', $this->order->uuid))
            ->assertOk()
            ->assertSee('Checklist persiapan')
            ->assertSee('Kontrak & dokumen pembayaran', false)
            ->assertSee('Ajukan perubahan pesanan');

        $this->withHeaders(['User-Agent' => 'iPhone'])
            ->get(route('orders.show', $this->order->uuid))
            ->assertOk()
            ->assertSee('DP Terverifikasi')
            ->assertDontSee('mobile.order_status.partially_paid')
            ->assertSee('Checklist persiapan')
            ->assertSee('Ajukan perubahan pesanan');
    }

    public function test_admin_workflow_configuration_and_review_pages_render(): void
    {
        $this->actingAs($this->admin)
            ->get(route('orders.workflow-settings.edit'))
            ->assertOk()
            ->assertSee('Template kontrak digital')
            ->assertSee('Checklist persiapan');

        $this->get(route('orders.change-requests.index'))
            ->assertOk()
            ->assertSee('Permintaan perubahan pesanan');
    }

    public function test_member_gets_a_clear_forbidden_page_for_admin_workflow_settings(): void
    {
        $this->actingAs($this->member)
            ->get(route('orders.workflow-settings.edit'))
            ->assertForbidden()
            ->assertSee('Akses Ditolak')
            ->assertSee('admin yang sudah memverifikasi email')
            ->assertDontSee('Internal Server Error');
    }

    public function test_expire_command_processes_more_than_one_batch(): void
    {
        for ($i = 1; $i <= 501; $i++) {
            $batchOption = PackageOption::create([
                'package_uuid' => $this->package->uuid,
                'name' => 'Batch '.$i,
                'price' => 1000000,
                'duration' => 1,
                'duration_unit' => 'month',
                'quota' => 1,
                'is_active' => true,
            ]);

            Order::create([
                'user_uuid' => $this->member->uuid,
                'order_number' => 'ORD-BATCH-'.$i,
                'type' => 'package',
                'package_uuid' => $this->package->uuid,
                'package_option_uuid' => $batchOption->uuid,
                'event_date' => today()->addMonth()->toDateString(),
                'amount' => 1000000,
                'payment_scheme' => 'full',
                'total_amount' => 1000000,
                'amount_paid' => 0,
                'remaining_amount' => 1000000,
                'status' => 'pending',
                'expired_at' => now()->subMinute(),
            ]);
        }

        Artisan::call('records:expire');

        $this->assertSame(501, Order::packageOnly()->where('status', 'expired')->count());
    }

    public function test_expired_pending_order_releases_its_date_reservation(): void
    {
        $this->order->update([
            'status' => 'pending',
            'amount_paid' => 0,
            'remaining_amount' => 10000000,
            'expired_at' => now()->subMinute(),
        ]);
        $payment = Payment::create([
            'order_uuid' => $this->order->uuid,
            'kind' => 'dp',
            'payment_gateway' => 'manual_transfer',
            'payment_type' => 'Transfer',
            'gross_amount' => 2000000,
            'amount' => 2000000,
            'status' => 'pending',
            'verification_status' => 'pending',
        ]);

        EventDateReservation::create([
            'event_date' => $this->order->event_date->toDateString(),
            'order_uuid' => $this->order->uuid,
            'status' => 'held',
            'expires_at' => now()->subMinute(),
        ]);

        Artisan::call('records:expire');

        $this->assertSame('expired', $this->order->fresh()->status);
        $this->assertDatabaseHas('payments', [
            'uuid' => $payment->uuid,
            'status' => 'failed',
            'verification_status' => 'rejected',
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $this->order->uuid,
            'event' => 'order_expired',
        ]);
        $this->assertDatabaseHas('event_date_reservations', [
            'event_date' => $this->order->event_date->toDateString(),
            'order_uuid' => null,
            'status' => 'available',
        ]);
    }
}
