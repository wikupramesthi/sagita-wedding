<?php

namespace Tests\Feature;

use App\Models\Package\Package;
use App\Models\Package\PackageAddon;
use App\Models\Package\PackageOption;
use App\Models\Payment\CheckoutSession;
use App\Models\Payment\EventDateReservation;
use App\Models\Payment\Order;
use App\Models\Payment\OrderChangeRequest;
use App\Models\Payment\OrderDocument;
use App\Models\Payment\OrderWorkflowSetting;
use App\Models\Payment\Payment;
use App\Models\Payment\WeddingChecklistItem;
use App\Models\User;
use App\Services\EventDateReservationService;
use App\Services\OrderWorkflowProvisioningService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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
            ->assertSee('Checkout ditinggalkan')
            ->assertSee('Paket Workflow Test')
            ->assertSee('50,0%');
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

    public function test_expired_pending_order_releases_its_date_reservation(): void
    {
        $this->order->update([
            'status' => 'pending',
            'amount_paid' => 0,
            'remaining_amount' => 10000000,
            'expired_at' => now()->subMinute(),
        ]);
        EventDateReservation::create([
            'event_date' => $this->order->event_date->toDateString(),
            'order_uuid' => $this->order->uuid,
            'status' => 'held',
            'expires_at' => now()->subMinute(),
        ]);

        Artisan::call('records:expire');

        $this->assertSame('expired', $this->order->fresh()->status);
        $this->assertDatabaseHas('event_date_reservations', [
            'event_date' => $this->order->event_date->toDateString(),
            'order_uuid' => null,
            'status' => 'available',
        ]);
    }
}
