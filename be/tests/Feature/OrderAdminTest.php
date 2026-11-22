<?php

namespace Tests\Feature;

use App\Models\OrderSavedFilter;
use App\Models\Package\Package;
use App\Models\Package\PackageOption;
use App\Models\Payment\Order;
use App\Models\User;
use App\Models\UserPackage;
use App\Notifications\OrderRejectedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
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

    private PackageOption $option2;

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
        $this->admin->assignRole($adminRole);

        $this->member = User::factory()->create(['email_verified_at' => now()]);
        $this->member->assignRole('user');

        $package = Package::create([
            'name' => 'Paket Wedding',
            'slug' => 'paket-wedding',
            'price' => 100000,
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

        $this->option2 = PackageOption::create([
            'package_uuid' => $package->uuid,
            'name' => 'Premium',
            'quota' => 8,
            'price' => 500000,
            'duration' => 1,
            'duration_unit' => 'month',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        foreach ([$this->option, $this->option2] as $option) {
            $order = Order::create([
                'user_uuid' => $this->member->uuid,
                'order_number' => 'ORD-'.strtoupper(Str::random(10)),
                'type' => 'package',
                'package_uuid' => $package->uuid,
                'package_option_uuid' => $option->uuid,
                'amount' => 500000,
                'remaining_amount' => 500000,
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

    public function test_admin_can_limit_export_columns_to_visible_columns(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('orders.exportCsv', [
                'status' => 'pending',
                'columns' => 'order,status,invalid-column',
            ]));

        $response->assertOk();

        $csv = $response->streamedContent();

        $this->assertStringContainsString('No. Pesanan', $csv);
        $this->assertStringContainsString('Status', $csv);
        $this->assertStringContainsString('Menunggu', $csv);
        $this->assertStringNotContainsString('Pelanggan', $csv);
        $this->assertStringNotContainsString('Paket Wedding', $csv);
        $this->assertStringNotContainsString('invalid-column', $csv);
    }

    public function test_report_hides_columns_not_requested(): void
    {
        $this->actingAs($this->admin)
            ->get(route('orders.report', ['columns' => 'order,status']))
            ->assertOk()
            ->assertSee('No. Pesanan')
            ->assertSee('Status')
            ->assertDontSee('<th>Pelanggan</th>', false)
            ->assertDontSee('<th>Paket</th>', false);
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
        $this->assertSame(end($this->orderUuids), $userPackage->order_uuid);
    }

    public function test_bulk_approve_accepts_empty_admin_note_from_browser(): void
    {
        $this->actingAs($this->admin)
            ->post(route('orders.bulk'), [
                'action' => 'approve',
                'ids' => $this->orderUuids,
                'admin_note' => '',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');
    }

    public function test_order_validation_messages_are_indonesian(): void
    {
        $this->actingAs($this->admin)
            ->post(route('orders.bulk'), [
                'action' => 'approve',
                'ids' => $this->orderUuids,
                'admin_note' => ['bukan teks'],
            ])
            ->assertSessionHasErrors(['admin_note' => 'catatan admin harus berupa teks.']);

        $this->actingAs($this->admin)
            ->post(route('orders.bulk'), [
                'action' => 'reject',
                'ids' => $this->orderUuids,
                'admin_note' => '',
            ])
            ->assertSessionHasErrors(['admin_note' => 'Alasan penolakan wajib diisi.']);
    }

    public function test_index_uses_pagination_for_large_order_lists(): void
    {
        foreach (range(1, 12) as $index) {
            Order::create([
                'user_uuid' => $this->member->uuid,
                'order_number' => 'ORD-'.Str::upper(Str::random(10)).'-'.$index,
                'type' => 'package',
                'package_uuid' => $this->option->package_uuid,
                'package_option_uuid' => $this->option->uuid,
                'amount' => 500000,
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        $this->actingAs($this->admin)
            ->get(route('orders.index', ['per_page' => 10]))
            ->assertOk()
            ->assertSee('Menampilkan 1–10 dari')
            ->assertSee('Halaman 1 dari')
            ->assertSee('ordersPerPageQuick')
            ->assertSee('ordersPageJump')
            ->assertViewHas('orders', fn ($orders) => $orders->total() === 14 && $orders->perPage() === 10);

        $this->actingAs($this->admin)
            ->get(route('orders.index', ['per_page' => 100]))
            ->assertOk()
            ->assertViewHas('filters', fn (array $filters) => $filters['per_page'] === 100);
    }

    public function test_index_explains_short_search_keyword(): void
    {
        $this->actingAs($this->admin)
            ->get(route('orders.index', ['search' => 'A']))
            ->assertOk()
            ->assertSee('Kata kunci pencarian minimal 2 karakter')
            ->assertViewHas('orders', fn ($orders) => $orders->total() === 0);
    }

    public function test_index_shows_sla_claim_presets_and_card_view(): void
    {
        Order::where('uuid', $this->orderUuids[0])->update(['created_at' => now()->subHours(30)]);
        Order::where('uuid', $this->orderUuids[1])->update(['assigned_admin_uuid' => $this->admin->uuid]);

        $this->actingAs($this->admin)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee('Periode cepat')
            ->assertSee('SLA &gt;24 jam', false)
            ->assertSee('Diklaim')
            ->assertSee('Langkah: minta bukti transfer')
            ->assertSee('Tekan')
            ->assertSee('id="orderStickyBar"', false)
            ->assertViewHas('queueCounts', fn ($counts) => $counts['sla'] === 1 && $counts['claimed'] === 1);

        $this->actingAs($this->admin)
            ->get(route('orders.index', ['search' => 'TIDAKADAHASIL']))
            ->assertOk()
            ->assertSee('Tidak ada hasil untuk filter ini')
            ->assertSee('Lihat Antrean');

        $this->actingAs($this->admin)
            ->get(route('orders.index', ['view' => 'cards']))
            ->assertOk()
            ->assertSee('view-cards', false)
            ->assertSee('Kartu');
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

    public function test_index_supports_queue_filters_sorting_and_per_page(): void
    {
        $this->actingAs($this->admin)
            ->get(route('orders.index', [
                'status' => 'pending',
                'proof' => 'missing',
                'per_page' => 25,
                'sort' => 'amount_desc',
            ]))
            ->assertOk()
            ->assertSee('Belum upload')
            ->assertSee('Urutkan')
            ->assertSee('amount_desc', false)
            ->assertViewHas('queueCounts', fn (array $counts) => $counts['all'] === 2 && $counts['missing'] === 2)
            ->assertViewHas('filters', fn (array $filters) => $filters['per_page'] === 25 && $filters['sort'] === 'amount_desc');
    }

    public function test_reject_requires_reason_and_notifies_member(): void
    {
        Notification::fake();

        $this->actingAs($this->admin)
            ->post(route('orders.reject', $this->orderUuids[0]), [])
            ->assertSessionHasErrors('admin_note');

        $this->actingAs($this->admin)
            ->post(route('orders.reject', $this->orderUuids[0]), [
                'admin_note' => 'Bukti transfer tidak terbaca',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        Notification::assertSentTo($this->member, OrderRejectedNotification::class);

        $this->assertDatabaseHas('orders', [
            'uuid' => $this->orderUuids[0],
            'status' => 'failed',
            'admin_note' => 'Bukti transfer tidak terbaca',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => Order::class,
            'auditable_id' => $this->orderUuids[0],
            'event' => 'rejected',
        ]);
    }

    public function test_expired_pending_order_cannot_be_approved(): void
    {
        Order::where('uuid', $this->orderUuids[0])->update([
            'expired_at' => now()->subDay(),
        ]);

        $this->actingAs($this->admin)
            ->post(route('orders.approve', $this->orderUuids[0]))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('orders', [
            'uuid' => $this->orderUuids[0],
            'status' => 'pending',
        ]);

        $this->assertDatabaseMissing('audit_logs', [
            'auditable_id' => $this->orderUuids[0],
            'event' => 'approved',
        ]);
    }

    public function test_admin_sees_verification_ui_and_audit_timeline(): void
    {
        $this->actingAs($this->admin)
            ->post(route('orders.approve', $this->orderUuids[0]))
            ->assertRedirect();

        $this->actingAs($this->admin)
            ->get(route('orders.show', $this->orderUuids[0]))
            ->assertOk()
            ->assertSee('Ringkasan Admin')
            ->assertSee('Riwayat Verifikasi')
            ->assertSee('Pesanan Disetujui');

        $this->actingAs($this->admin)
            ->get(route('orders.show', $this->orderUuids[1]))
            ->assertOk()
            ->assertSee('Verifikasi Admin')
            ->assertSee('Alasan penolakan');
    }

    public function test_member_cannot_open_admin_reports_or_pdf_exports(): void
    {
        $this->actingAs($this->member)
            ->get(route('orders.report'))
            ->assertNotFound();

        $this->actingAs($this->member)
            ->get(route('orders.exportPdf'))
            ->assertNotFound();
    }

    public function test_admin_can_save_and_apply_saved_filters(): void
    {
        $this->actingAs($this->admin)
            ->post(route('orders.saved-filters.store'), [
                'name' => 'Antrean pending',
                'filters' => json_encode(['status' => 'pending', 'per_page' => 25, 'sort' => 'amount_desc']),
                'is_default' => true,
            ])
            ->assertRedirect();

        $savedFilter = OrderSavedFilter::where('user_uuid', $this->admin->uuid)->firstOrFail();
        $this->assertTrue((bool) $savedFilter->is_default);
        $this->assertSame('pending', $savedFilter->filters['status']);

        $this->actingAs($this->admin)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee('Antrean pending');

        $this->actingAs($this->admin)
            ->get(route('orders.saved-filters.apply', $savedFilter))
            ->assertRedirect(route('orders.index', [
                'status' => 'pending',
                'per_page' => 25,
                'sort' => 'amount_desc',
            ]));
    }

    public function test_admin_can_pin_reorder_and_unset_saved_filter_default(): void
    {
        $this->actingAs($this->admin)
            ->post(route('orders.saved-filters.store'), [
                'name' => 'Filter A',
                'filters' => json_encode(['status' => 'pending']),
            ])
            ->assertRedirect();

        $this->actingAs($this->admin)
            ->post(route('orders.saved-filters.store'), [
                'name' => 'Filter B',
                'filters' => json_encode(['status' => 'paid']),
                'is_default' => true,
            ])
            ->assertRedirect();

        $first = OrderSavedFilter::where('name', 'Filter A')->firstOrFail();
        $second = OrderSavedFilter::where('name', 'Filter B')->firstOrFail();

        $this->actingAs($this->admin)
            ->post(route('orders.saved-filters.pin', $second))
            ->assertRedirect()
            ->assertSessionHas('success');
        $this->assertNotNull($second->fresh()->pinned_at);

        $this->actingAs($this->admin)
            ->post(route('orders.saved-filters.pin', $first))
            ->assertRedirect()
            ->assertSessionHas('success');
        $this->assertNotNull($first->fresh()->pinned_at);

        $this->actingAs($this->admin)
            ->post(route('orders.saved-filters.move', $first), ['direction' => 'down'])
            ->assertRedirect()
            ->assertSessionHas('success');
        $this->assertTrue($first->fresh()->sort_order > $second->fresh()->sort_order);

        $this->actingAs($this->admin)
            ->post(route('orders.saved-filters.default', $second), ['is_default' => 0])
            ->assertRedirect()
            ->assertSessionHas('success');
        $this->assertFalse((bool) $second->fresh()->is_default);
    }

    public function test_admin_can_undo_bulk_rejection(): void
    {
        $this->actingAs($this->admin)
            ->post(route('orders.bulk'), [
                'action' => 'reject',
                'ids' => [$this->orderUuids[0]],
                'admin_note' => 'Salah transfer',
            ])
            ->assertRedirect()
            ->assertSessionHas('undo_token');

        $undoToken = session('undo_token');

        $this->assertDatabaseHas('orders', [
            'uuid' => $this->orderUuids[0],
            'status' => 'failed',
        ]);

        $this->actingAs($this->admin)
            ->post(route('orders.bulk-undo'), ['undo_token' => $undoToken])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'uuid' => $this->orderUuids[0],
            'status' => 'pending',
            'admin_note' => null,
        ]);
    }

    public function test_admin_can_export_audit_and_open_whatsapp(): void
    {
        $this->member->forceFill(['no_hp' => '081234567890'])->save();

        $this->actingAs($this->admin)
            ->post(route('orders.approve', $this->orderUuids[0]))
            ->assertRedirect();

        $this->actingAs($this->admin)
            ->get(route('orders.audit'))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $this->actingAs($this->admin)
            ->get(route('orders.whatsapp', $this->orderUuids[1]))
            ->assertRedirect()
            ->assertHeader('location', 'https://wa.me/6281234567890?text='.rawurlencode('Halo '.$this->member->name.', kami dari Sagita Wedding Organizer ingin mengonfirmasi pesanan '.Order::where('uuid', $this->orderUuids[1])->value('order_number').'. Mohon ditindaklanjuti, terima kasih.'));

        $this->assertDatabaseHas('audit_logs', [
            'auditable_id' => $this->orderUuids[1],
            'event' => 'whatsapp_opened',
        ]);
    }

    public function test_report_shows_verification_kpis(): void
    {
        $this->actingAs($this->admin)
            ->post(route('orders.approve', $this->orderUuids[0]))
            ->assertRedirect();

        $this->actingAs($this->admin)
            ->get(route('orders.report'))
            ->assertOk()
            ->assertSee('Pendapatan (Lunas)')
            ->assertSee('Tingkat persetujuan')
            ->assertSee('Bukti terunggah')
            ->assertSee('Rata-rata verifikasi')
            ->assertSee('Melewati SLA 24 jam');
    }

    public function test_index_hanya_menampilkan_pesanan_paket(): void
    {
        $classOrder = Order::create([
            'user_uuid' => $this->member->uuid,
            'order_number' => 'ORD-CLASS',
            'type' => 'class',
            'package_uuid' => $this->option->package_uuid,
            'package_option_uuid' => $this->option->uuid,
            'amount' => 500000,
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertDontSee('ORD-CLASS')
            ->assertDontSee('Semua Tipe')
            ->assertViewHas('orders', fn ($orders) => ! $orders->contains(fn ($order) => $order->uuid === $classOrder->uuid))
            ->assertViewHas('queueCounts', fn (array $counts) => $counts['all'] === 2);
    }

    public function test_member_cannot_open_admin_audit_or_whatsapp(): void
    {
        $this->actingAs($this->member)
            ->get(route('orders.audit'))
            ->assertNotFound();

        $this->actingAs($this->member)
            ->get(route('orders.whatsapp', $this->orderUuids[0]))
            ->assertNotFound();
    }
}
