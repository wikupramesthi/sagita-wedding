<?php

namespace Tests\Feature;

use App\Models\Package\Package;
use App\Models\Package\PackageAddon;
use App\Models\Package\PackageBlackoutDate;
use App\Models\Package\PackageOption;
use App\Models\Package\PackagePromo;
use App\Models\Payment\Order;
use App\Notifications\OrderBalanceDueNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PackageWeddingFeatureTest extends TestCase
{
    use RefreshDatabase;

    private const API_KEY = 'test-frontend-key';

    private User $admin;

    private User $member;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.frontend_api.key' => self::API_KEY]);

        foreach (['user', 'admin', 'super-admin'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        $adminRole = Role::findByName('admin', 'web');
        foreach (['package.store', 'package.update', 'package.destroy'] as $permission) {
            $adminRole->givePermissionTo(Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            ));
        }

        $this->admin = User::factory()->create(['email_verified_at' => now()]);
        $this->admin->assignRole($adminRole);

        $this->member = User::factory()->create(['email_verified_at' => now()]);
        $this->member->assignRole('user');
    }

    private function api()
    {
        return $this->withHeader('X-Api-Key', self::API_KEY);
    }

    private function packagePayload(string $name, int $price, ?int $discount = null): array
    {
        return [
            'name' => $name,
            'description' => 'Paket layanan wedding organizer.',
            'guest_capacity' => 150,
            'event_duration_hours' => 6,
            'venue_note' => 'Jakarta Selatan dan sekitarnya',
            'cover_url' => 'https://example.com/images/paket-wedding.jpg',
            'is_active' => 'active',
            'is_popular' => true,
            'options' => [
                [
                    'name' => 'Standar',
                    'quota' => 1,
                    'price' => $price,
                    'discount_price' => $discount,
                    'duration' => 1,
                    'duration_unit' => 'month',
                ],
            ],
            'addons' => [
                [
                    'name' => 'Dekorasi tambahan',
                    'description' => 'Pelaminan bunga segar',
                    'price' => 750000,
                    'discount_price' => 650000,
                    'sort_order' => 0,
                    'is_active' => 1,
                ],
            ],
            'features' => ['Dekorasi pelaminan'],
        ];
    }

    private function createPackage(
        string $name,
        string $slug,
        int $price,
        ?int $discount,
        int $guestCapacity,
        bool $isPopular = false
    ): Package {
        $package = Package::create([
            'name' => $name,
            'slug' => $slug,
            'description' => 'Paket layanan wedding organizer.',
            'price' => $price,
            'guest_capacity' => $guestCapacity,
            'event_duration_hours' => 6,
            'venue_note' => 'Jakarta Selatan dan sekitarnya',
            'cover_url' => 'https://example.com/images/'.$slug.'.jpg',
            'is_active' => 'active',
            'is_popular' => $isPopular,
        ]);

        PackageOption::create([
            'package_uuid' => $package->uuid,
            'name' => 'Standar',
            'quota' => 1,
            'price' => $price,
            'discount_price' => $discount,
            'duration' => 1,
            'duration_unit' => 'month',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        return $package;
    }

    private function eventDate(int $days = 14): string
    {
        return now()->startOfDay()->addDays($days)->toDateString();
    }

    public function test_admin_can_save_wedding_package_details_and_addons(): void
    {
        $this->actingAs($this->admin)
            ->post(route('packages.store'), $this->packagePayload('Paket WO Lengkap', 5000000, 4500000))
            ->assertRedirect(route('packages.index'))
            ->assertSessionHas('success', 'Paket berhasil dibuat.');

        $package = Package::where('slug', 'paket-wo-lengkap')->firstOrFail();

        $this->assertDatabaseHas('packages', [
            'uuid' => $package->uuid,
            'guest_capacity' => 150,
            'event_duration_hours' => 6,
            'venue_note' => 'Jakarta Selatan dan sekitarnya',
            'cover_url' => 'https://example.com/images/paket-wedding.jpg',
        ]);

        $this->assertDatabaseHas('package_addons', [
            'package_uuid' => $package->uuid,
            'name' => 'Dekorasi tambahan',
            'price' => 750000,
            'discount_price' => 650000,
        ]);

        $this->assertCount(1, $package->addons);

        $this->actingAs($this->admin)
            ->get(route('packages.index'))
            ->assertOk()
            ->assertSee('Paket WO Lengkap')
            ->assertSee('Dekorasi tambahan');

        $this->actingAs($this->admin)
            ->get(route('packages.edit', $package->uuid))
            ->assertOk()
            ->assertSee('Detail Acara')
            ->assertSee('Layanan Tambahan (Opsional)')
            ->assertSee('150')
            ->assertSee('Jakarta Selatan dan sekitarnya')
            ->assertSee('https://example.com/images/paket-wedding.jpg')
            ->assertSee('Dekorasi tambahan');
    }

    public function test_addon_discount_validation_message_is_indonesian(): void
    {
        $payload = $this->packagePayload('Paket Addon Diskon', 5000000);
        $payload['addons'][0]['price'] = 100000;
        $payload['addons'][0]['discount_price'] = 150000;

        $this->actingAs($this->admin)
            ->post(route('packages.store'), $payload)
            ->assertSessionHasErrors([
                'addons.0.discount_price' => 'Harga diskon harus lebih rendah daripada harga reguler.',
            ]);

        $this->assertDatabaseMissing('packages', ['slug' => 'paket-addon-diskon']);
    }

    public function test_member_catalog_shows_wedding_copy_and_filters_guests_and_budget(): void
    {
        $this->createPackage('Paket Intimate', 'paket-intimate', 5000000, 4500000, 100, true);
        $this->createPackage('Paket Grand', 'paket-grand', 15000000, null, 500);

        $this->actingAs($this->member)
            ->get(route('packages.member', ['view' => 'desktop']))
            ->assertOk()
            ->assertViewIs('pages.package.member')
            ->assertSee('Paling Dipilih')
            ->assertSee('Kapasitas Tamu')
            ->assertSee('Durasi Acara')
            ->assertSee('Lokasi Acara')
            ->assertSee('Bandingkan Paket')
            ->assertSee('Penawaran')
            ->assertDontSee('Tanya Paket Ini')
            ->assertSee('Paket Intimate')
            ->assertSee('Paket Grand');

        $this->actingAs($this->member)
            ->get(route('packages.member', [
                'view' => 'desktop',
                'guests' => 60,
                'budget_max' => 6000000,
            ]))
            ->assertOk()
            ->assertSee('Paket Intimate')
            ->assertDontSee('Paket Grand');

        $this->actingAs($this->member)
            ->get(route('packages.member', [
                'view' => 'desktop',
                'guests' => 400,
                'budget_max' => 6000000,
            ]))
            ->assertOk()
            ->assertDontSee('Paket Intimate')
            ->assertDontSee('Paket Grand');
    }

    public function test_desktop_catalog_does_not_offer_checkout_for_packages_without_active_options(): void
    {
        Package::create([
            'name' => 'Paket Tanpa Opsi',
            'slug' => 'paket-tanpa-opsi',
            'description' => 'Paket yang belum memiliki pilihan harga aktif.',
            'price' => 100000,
            'guest_capacity' => 100,
            'is_active' => 'active',
            'is_published' => true,
        ]);

        $this->actingAs($this->member)
            ->get(route('packages.member', ['view' => 'desktop']))
            ->assertOk()
            ->assertSee('Paket Tanpa Opsi')
            ->assertSee('Harga belum tersedia')
            ->assertSee('Paket belum tersedia')
            ->assertDontSee('Rp 0');
    }

    public function test_mobile_catalog_has_filter_form_and_applies_filters(): void
    {
        $this->createPackage('Paket Intimate', 'paket-intimate', 5000000, 4500000, 100, true);
        $this->createPackage('Paket Grand', 'paket-grand', 15000000, null, 500);

        $this->actingAs($this->member)
            ->withHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 13) Mobile')
            ->get(route('packages.member'))
            ->assertOk()
            ->assertViewIs('pages.mobile.package')
            ->assertSee('Jumlah tamu')
            ->assertSee('Anggaran maksimal')
            ->assertSee('Terapkan')
            ->assertSee('name="guests"', false)
            ->assertSee('name="budget_max"', false);

        $this->actingAs($this->member)
            ->withHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 13) Mobile')
            ->get(route('packages.member', [
                'guests' => 60,
                'budget_max' => 6000000,
            ]))
            ->assertOk()
            ->assertViewIs('pages.mobile.package')
            ->assertSee('Paket Intimate')
            ->assertDontSee('Paket Grand');
    }

    public function test_package_api_returns_wedding_fields_and_addons(): void
    {
        $package = $this->createPackage('Paket API', 'paket-api', 5000000, 4500000, 200, true);

        $addon = PackageAddon::create([
            'package_uuid' => $package->uuid,
            'name' => 'Fotografer tambahan',
            'description' => 'Dokumentasi acara',
            'price' => 750000,
            'discount_price' => 650000,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $this->api()
            ->getJson('/api/packages')
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'uuid',
                        'name',
                        'slug',
                        'guest_capacity',
                        'event_duration_hours',
                        'venue_note',
                        'cover_url',
                        'addons' => ['*' => ['name']],
                    ],
                ],
            ])
            ->assertJsonPath('data.0.guest_capacity', 200)
            ->assertJsonPath('data.0.event_duration_hours', 6)
            ->assertJsonPath('data.0.venue_note', 'Jakarta Selatan dan sekitarnya')
            ->assertJsonPath('data.0.cover_url', 'https://example.com/images/paket-api.jpg')
            ->assertJsonPath('data.0.addons.0.name', 'Fotografer tambahan');

        $this->api()
            ->getJson('/api/packages/'.$package->slug)
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'data' => [
                    'uuid',
                    'name',
                    'slug',
                    'guest_capacity',
                    'event_duration_hours',
                    'venue_note',
                    'cover_url',
                    'addons' => ['*' => ['name']],
                ],
            ])
            ->assertJsonPath('data.uuid', $package->uuid)
            ->assertJsonPath('data.guest_capacity', 200)
            ->assertJsonPath('data.event_duration_hours', 6)
            ->assertJsonPath('data.venue_note', 'Jakarta Selatan dan sekitarnya')
            ->assertJsonPath('data.cover_url', 'https://example.com/images/paket-api.jpg')
            ->assertJsonPath('data.addons.0.uuid', $addon->uuid)
            ->assertJsonPath('data.addons.0.name', 'Fotografer tambahan');
    }

    public function test_checkout_total_includes_selected_addons(): void
    {
        $package = $this->createPackage('Paket Checkout', 'paket-checkout', 5000000, 4500000, 150, true);
        $option = $package->options()->firstOrFail();

        $decoration = PackageAddon::create([
            'package_uuid' => $package->uuid,
            'name' => 'Dekorasi tambahan',
            'description' => 'Pelaminan bunga segar',
            'price' => 750000,
            'discount_price' => 650000,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $photography = PackageAddon::create([
            'package_uuid' => $package->uuid,
            'name' => 'Fotografer tambahan',
            'description' => 'Dokumentasi acara',
            'price' => 200000,
            'discount_price' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->member)
            ->post(route('orders.store'), [
                'type' => 'package',
                'package_option_uuid' => $option->uuid,
                'event_date' => $this->eventDate(),
                'addon_uuids' => [$decoration->uuid, $photography->uuid],
            ]);

        $response->assertSessionHasNoErrors();

        $order = Order::where('user_uuid', $this->member->uuid)
            ->where('package_option_uuid', $option->uuid)
            ->firstOrFail();

        $response->assertRedirect(route('orders.show', $order->uuid));

        $this->assertSame(2000000, (int) $order->amount);
        $this->assertSame(5350000, (int) $order->total_amount);
        $this->assertSame([
            [
                'uuid' => $decoration->uuid,
                'name' => 'Dekorasi tambahan',
                'price' => 650000,
                'quantity' => 1,
                'line_total' => 650000,
            ],
            [
                'uuid' => $photography->uuid,
                'name' => 'Fotografer tambahan',
                'price' => 200000,
                'quantity' => 1,
                'line_total' => 200000,
            ],
        ], $order->addons);
        $this->assertDatabaseHas('orders', [
            'uuid' => $order->uuid,
            'amount' => 2000000,
            'total_amount' => 5350000,
        ]);

        $this->actingAs($this->admin)
            ->get(route('orders.show', $order->uuid))
            ->assertOk()
            ->assertSee('Layanan tambahan')
            ->assertSee('Dekorasi tambahan')
            ->assertSee('Fotografer tambahan');
    }

    public function test_checkout_can_choose_down_payment_scheme(): void
    {
        $package = $this->createPackage('Paket DP', 'paket-dp', 5000000, 4500000, 150, true);
        $option = $package->options()->firstOrFail();

        $checkoutResponse = $this->actingAs($this->member)
            ->get(route('checkout.package', $package->uuid))
            ->assertOk();

        $checkoutResponse
            ->assertSee('DP 30%')
            ->assertSee('DP 50%')
            ->assertSee('Bayar Lunas')
            ->assertSee('id="summaryRemainingAmount"', false)
            ->assertSee('const BOOKING_FEE = 2000000;', false);

        $this->actingAs($this->member)
            ->post(route('orders.store'), [
                'type' => 'package',
                'package_option_uuid' => $option->uuid,
                'event_date' => $this->eventDate(),
                'payment_scheme' => 'dp30',
                'amount' => 1,
                'total_amount' => 1,
            ])
            ->assertSessionHasNoErrors();

        $order = Order::where('user_uuid', $this->member->uuid)
            ->where('package_option_uuid', $option->uuid)
            ->firstOrFail();

        $this->assertSame(2750000, (int) $order->amount);
        $this->assertSame(4500000, (int) $order->total_amount);
        $this->assertSame('dp30', $order->promo_snapshot['payment_scheme']);
        $this->assertSame('DP 30%', $order->promo_snapshot['scheme_label']);
        $this->assertSame(4500000, (int) $order->promo_snapshot['full_amount']);
        $this->assertSame(2750000, (int) $order->promo_snapshot['due_now']);
        $this->assertSame(1750000, array_sum(array_column($order->installment_schedule['installments'], 'amount')));
        $this->assertSame(now()->toDateString(), $order->installment_schedule['installments'][0]['due_at']);

        $this->actingAs($this->admin)
            ->get(route('orders.show', $order->uuid))
            ->assertOk()
            ->assertSee('DP 30%')
            ->assertSee('4.500.000')
            ->assertSee('Jadwal sisa pembayaran')
            ->assertSee('Progres Pesanan');

        $this->actingAs($this->member)
            ->get(route('orders.show', $order->uuid).'?view=mobile')
            ->assertOk()
            ->assertViewIs('pages.mobile.order-show')
            ->assertSee('Jadwal sisa pembayaran')
            ->assertSee('Progres Pesanan');

        $this->actingAs($this->member)
            ->get(route('checkout.package', ['packageUuid' => $package->uuid, 'view' => 'desktop']))
            ->assertOk()
            ->assertSee('Anda masih memiliki checkout aktif untuk paket ini.')
            ->assertSee('Lihat riwayat checkout');

        $this->actingAs($this->member)
            ->get(route('checkout.package', ['packageUuid' => $package->uuid, 'view' => 'mobile']))
            ->assertOk()
            ->assertViewIs('pages.mobile.checkout')
            ->assertSee('Checkout aktif untuk paket ini');
    }

    public function test_checkout_saves_three_rounded_installments_for_event_more_than_ninety_days_away(): void
    {
        $package = $this->createPackage('Paket Jadwal', 'paket-jadwal', 4500001, null, 150);
        $option = $package->options()->firstOrFail();
        $eventDate = $this->eventDate(120);

        $this->actingAs($this->member)
            ->post(route('orders.store'), [
                'type' => 'package',
                'package_option_uuid' => $option->uuid,
                'event_date' => $eventDate,
                'payment_scheme' => 'no_dp',
            ])
            ->assertSessionHasNoErrors();

        $order = Order::where('user_uuid', $this->member->uuid)
            ->where('package_option_uuid', $option->uuid)
            ->firstOrFail();
        $installments = $order->installment_schedule['installments'];

        $this->assertCount(3, $installments);
        $this->assertSame([833334, 833334, 833333], array_column($installments, 'amount'));
        $this->assertSame([
            now()->startOfDay()->addDays(30)->toDateString(),
            now()->startOfDay()->addDays(60)->toDateString(),
            now()->startOfDay()->addDays(90)->toDateString(),
        ], array_column($installments, 'due_at'));
        $this->assertSame(2500001, array_sum(array_column($installments, 'amount')));
    }

    public function test_installment_reminders_are_sent_once_at_thirty_seven_and_one_day(): void
    {
        $package = $this->createPackage('Paket Pengingat', 'paket-pengingat', 4500001, null, 150);
        $option = $package->options()->firstOrFail();

        $this->actingAs($this->member)
            ->post(route('orders.store'), [
                'type' => 'package',
                'package_option_uuid' => $option->uuid,
                'event_date' => $this->eventDate(120),
                'payment_scheme' => 'no_dp',
            ])
            ->assertSessionHasNoErrors();

        $order = Order::where('user_uuid', $this->member->uuid)
            ->where('package_option_uuid', $option->uuid)
            ->firstOrFail();
        $order->update(['status' => 'partially_paid']);
        Notification::fake();

        $firstDueDate = Carbon::parse($order->installment_schedule['installments'][0]['due_at']);
        foreach ([30, 7, 1] as $daysBeforeDue) {
            Carbon::setTestNow($firstDueDate->copy()->subDays($daysBeforeDue)->setTime(8, 0));
            $this->artisan('orders:remind-balance-due')->assertExitCode(0);
        }

        Carbon::setTestNow();
        $sent = Notification::sent($this->member, OrderBalanceDueNotification::class);
        $this->assertCount(3, $sent);
        $this->assertSame([30, 7, 1], $sent->map(fn ($notification) => $notification->reminderDays)->all());

        Carbon::setTestNow($firstDueDate->copy()->subDay()->setTime(8, 0));
        $this->artisan('orders:remind-balance-due')->assertExitCode(0);
        Carbon::setTestNow();
        $this->assertCount(3, Notification::sent($this->member, OrderBalanceDueNotification::class));
    }

    public function test_checkout_rejects_invalid_payment_scheme(): void
    {
        $package = $this->createPackage('Paket Skema', 'paket-skema', 5000000, null, 150);
        $option = $package->options()->firstOrFail();

        $this->actingAs($this->member)
            ->post(route('orders.store'), [
                'type' => 'package',
                'package_option_uuid' => $option->uuid,
                'event_date' => $this->eventDate(),
                'payment_scheme' => 'dp99',
            ])
            ->assertSessionHasErrors('payment_scheme');

        $this->assertDatabaseMissing('orders', [
            'user_uuid' => $this->member->uuid,
            'package_option_uuid' => $option->uuid,
        ]);
    }

    public function test_draft_package_is_hidden_from_catalog_checkout_and_api(): void
    {
        $package = $this->createPackage('Paket Draft', 'paket-draft', 5000000, null, 150);

        $package->update([
            'is_published' => false,
            'published_at' => null,
        ]);

        $this->actingAs($this->member)
            ->get(route('packages.member'))
            ->assertOk()
            ->assertDontSee('Paket Draft');

        $this->actingAs($this->member)
            ->get(route('checkout.package', $package->uuid))
            ->assertNotFound();

        $this->api()
            ->getJson('/api/packages/'.$package->slug)
            ->assertNotFound();
    }

    public function test_checkout_validates_lead_time_and_blackout_date(): void
    {
        $package = $this->createPackage('Paket Tanggal', 'paket-tanggal', 5000000, null, 150);
        $option = $package->options()->firstOrFail();

        $package->update(['lead_time_days' => 7]);

        PackageBlackoutDate::create([
            'package_uuid' => $package->uuid,
            'date' => $this->eventDate(10),
            'reason' => 'Libur acara',
        ]);

        $this->actingAs($this->member)
            ->post(route('orders.store'), [
                'type' => 'package',
                'package_option_uuid' => $option->uuid,
                'event_date' => $this->eventDate(3),
            ])
            ->assertSessionHasErrors('event_date');

        $this->actingAs($this->member)
            ->post(route('orders.store'), [
                'type' => 'package',
                'package_option_uuid' => $option->uuid,
                'event_date' => $this->eventDate(10),
            ])
            ->assertSessionHasErrors('event_date');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_applies_promo_and_addon_quantities(): void
    {
        $package = $this->createPackage('Paket Promo', 'paket-promo', 5000000, 4500000, 150);
        $option = $package->options()->firstOrFail();

        $addon = PackageAddon::create([
            'package_uuid' => $package->uuid,
            'name' => 'Fotografer tambahan',
            'description' => 'Dokumentasi acara',
            'price' => 100000,
            'discount_price' => null,
            'min_quantity' => 2,
            'max_quantity' => 3,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        PackagePromo::create([
            'package_uuid' => $package->uuid,
            'code' => 'HEMAT',
            'type' => 'fixed',
            'value' => 500000,
            'min_purchase' => 0,
            'max_uses' => 5,
            'is_active' => true,
        ]);

        $this->actingAs($this->member)
            ->post(route('orders.store'), [
                'type' => 'package',
                'package_option_uuid' => $option->uuid,
                'event_date' => $this->eventDate(),
                'addon_uuids' => [$addon->uuid],
                'addon_quantities' => [$addon->uuid => 2],
                'promo_code' => 'hemat',
            ])
            ->assertSessionHasNoErrors();

        $order = Order::where('user_uuid', $this->member->uuid)->firstOrFail();

        $this->assertSame(2000000, (int) $order->amount);
        $this->assertSame(4200000, (int) $order->total_amount);
        $this->assertSame('HEMAT', $order->promo_code);
        $this->assertSame(500000, (int) $order->promo_discount);
        $this->assertSame(500000, (int) $order->promo_snapshot['discount']);
        $this->assertSame(2000000, (int) $order->promo_snapshot['due_now']);
        $this->assertSame(2, $order->addons[0]['quantity']);
        $this->assertSame(200000, $order->addons[0]['line_total']);
        $this->assertDatabaseHas('package_promos', [
            'code' => 'HEMAT',
            'used_count' => 1,
        ]);
    }

    public function test_addon_dependency_is_enforced(): void
    {
        $package = $this->createPackage('Paket Dependency', 'paket-dependency', 5000000, null, 150);
        $option = $package->options()->firstOrFail();

        $required = PackageAddon::create([
            'package_uuid' => $package->uuid,
            'name' => 'Dekorasi dasar',
            'price' => 500000,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $dependent = PackageAddon::create([
            'package_uuid' => $package->uuid,
            'name' => 'Bunga segar',
            'price' => 250000,
            'requires_addon_uuid' => $required->uuid,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($this->member)
            ->post(route('orders.store'), [
                'type' => 'package',
                'package_option_uuid' => $option->uuid,
                'event_date' => $this->eventDate(),
                'addon_uuids' => [$dependent->uuid],
            ])
            ->assertSessionHasErrors('addon_uuids');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_member_can_toggle_wishlist_and_compare_packages(): void
    {
        $package = $this->createPackage('Paket Favorit', 'paket-favorit', 5000000, null, 150);

        $this->actingAs($this->member)
            ->post(route('packages.wishlist.toggle', $package->uuid))
            ->assertRedirect();

        $this->assertDatabaseHas('package_wishlists', [
            'user_uuid' => $this->member->uuid,
            'package_uuid' => $package->uuid,
        ]);

        $this->actingAs($this->member)
            ->get(route('packages.wishlist.index'))
            ->assertOk()
            ->assertSee('Paket Favorit');

        $this->actingAs($this->member)
            ->post(route('packages.compare.add', $package->uuid))
            ->assertRedirect();

        $this->actingAs($this->member)
            ->get(route('packages.compare.index'))
            ->assertOk()
            ->assertSee('Paket Favorit');

        $this->actingAs($this->member)
            ->delete(route('packages.compare.remove', $package->uuid))
            ->assertRedirect();
    }

    public function test_quotation_pdf_can_be_previewed_and_downloaded(): void
    {
        $package = $this->createPackage('Paket Penawaran', 'paket-penawaran', 5000000, null, 150);

        $preview = $this->actingAs($this->member)
            ->get(route('packages.quotation', ['package' => $package->uuid]))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');

        $this->assertStringContainsString(
            'inline',
            (string) $preview->headers->get('Content-Disposition')
        );

        $download = $this->actingAs($this->member)
            ->get(route('packages.quotation.download', ['package' => $package->uuid]))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');

        $this->assertStringContainsString(
            'attachment',
            (string) $download->headers->get('Content-Disposition')
        );
    }
}
