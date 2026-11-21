<?php

namespace Tests\Feature;

use App\Models\Package\Package;
use App\Models\Package\PackageAddon;
use App\Models\Package\PackageOption;
use App\Models\Payment\Order;
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
            ->assertSee('Tanya Paket Ini')
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
                'addon_uuids' => [$decoration->uuid, $photography->uuid],
            ]);

        $response->assertSessionHasNoErrors();

        $order = Order::where('user_uuid', $this->member->uuid)
            ->where('package_option_uuid', $option->uuid)
            ->firstOrFail();

        $response->assertRedirect(route('orders.show', $order->uuid));

        $this->assertSame(5350000, (int) $order->amount);
        $this->assertSame([
            ['uuid' => $decoration->uuid, 'name' => 'Dekorasi tambahan', 'price' => 650000],
            ['uuid' => $photography->uuid, 'name' => 'Fotografer tambahan', 'price' => 200000],
        ], $order->addons);
        $this->assertDatabaseHas('orders', [
            'uuid' => $order->uuid,
            'amount' => 5350000,
        ]);

        $this->actingAs($this->admin)
            ->get(route('orders.show', $order->uuid))
            ->assertOk()
            ->assertSee('Layanan tambahan')
            ->assertSee('Dekorasi tambahan')
            ->assertSee('Fotografer tambahan');
    }
}
