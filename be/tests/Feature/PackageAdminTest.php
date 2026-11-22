<?php

namespace Tests\Feature;

use App\Models\Package\Package;
use App\Models\Package\PackageAddon;
use App\Models\Package\PackageBlackoutDate;
use App\Models\Package\PackageFeature;
use App\Models\Package\PackageOption;
use App\Models\Package\PackagePromo;
use App\Models\User;
use Database\Seeders\RouteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PackageAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['user', 'admin', 'super-admin'] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }
        $this->seed(RouteSeeder::class);
        Cache::flush();

        $adminRole = Role::findByName('admin', 'web');
        foreach (['package.index', 'package.store', 'package.update', 'package.destroy'] as $permission) {
            $adminRole->givePermissionTo(Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            ));
        }

        $this->admin = User::factory()->create(['email_verified_at' => now()]);
        $this->admin->assignRole($adminRole);
    }

    public function test_package_pages_are_rendered_in_indonesian_for_wedding_organizer(): void
    {
        $package = Package::create([
            'name' => 'Paket Silver',
            'slug' => 'paket-silver',
            'description' => 'Dekorasi pelaminan dan dokumentasi foto.',
            'price' => 100000,
            'guest_capacity' => 120,
            'event_duration_hours' => 6,
            'venue_note' => 'Jakarta Selatan dan sekitarnya',
            'cover_url' => 'https://example.com/images/paket-silver.jpg',
            'is_active' => 'active',
            'is_popular' => true,
        ]);

        PackageOption::create([
            'package_uuid' => $package->uuid,
            'name' => 'Silver',
            'quota' => 1,
            'price' => 3500000,
            'duration' => 1,
            'duration_unit' => 'month',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        PackageFeature::create([
            'package_uuid' => $package->uuid,
            'feature' => 'Dekorasi pelaminan',
            'sort_order' => 0,
        ]);

        PackageAddon::create([
            'package_uuid' => $package->uuid,
            'name' => 'Pelaminan bunga segar',
            'description' => 'Dekorasi tambahan',
            'price' => 750000,
            'discount_price' => 650000,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)
            ->get(route('packages.index'))
            ->assertOk()
            ->assertSee('Daftar Paket')
            ->assertSee('Total Paket')
            ->assertSee('Tambah Paket')
            ->assertSee('· 1')
            ->assertSee('Dekorasi pelaminan')
            ->assertSee('Paling Dipilih')
            ->assertSee('Pelaminan bunga segar')
            ->assertDontSee('>No.</th>')
            ->assertDontSee('Packages')
            ->assertDontSee('No packages found.')
            ->assertDontSee('Classes')
            ->assertDontSee('Kelas')
            ->assertDontSee('keanggotaan');

        $this->actingAs($this->admin)
            ->get(route('packages.create'))
            ->assertOk()
            ->assertSee('Tambah Paket')
            ->assertSee('Buat paket layanan wedding organizer baru.')
            ->assertSee('Informasi Paket')
            ->assertSee('Detail Acara')
            ->assertSee('Kapasitas Tamu')
            ->assertSee('Durasi Acara (jam)')
            ->assertSee('Lokasi Acara')
            ->assertSee('Foto Paket (URL)')
            ->assertSee('Pilihan Paket')
            ->assertSee('Manfaat Paket')
            ->assertSee('Layanan Tambahan (Opsional)')
            ->assertSee('Paling Dipilih')
            ->assertSee('Contoh: Paket Silver')
            ->assertSee('Dekorasi pelaminan')
            ->assertDontSee('Classes')
            ->assertDontSee('Kelas')
            ->assertDontSee('keanggotaan')
            ->assertDontSee('yoga');

        $this->actingAs($this->admin)
            ->get(route('packages.edit', $package->uuid))
            ->assertOk()
            ->assertSee('Ubah Paket')
            ->assertSee('Perbarui paket layanan wedding organizer.')
            ->assertSee('Detail Acara')
            ->assertSee('Kapasitas Tamu')
            ->assertSee('Durasi Acara (jam)')
            ->assertSee('Lokasi Acara')
            ->assertSee('Foto Paket (URL)')
            ->assertSee('Layanan Tambahan (Opsional)')
            ->assertSee('Paling Dipilih')
            ->assertSee('120')
            ->assertSee('Jakarta Selatan dan sekitarnya')
            ->assertSee('https://example.com/images/paket-silver.jpg')
            ->assertSee('Pelaminan bunga segar')
            ->assertSee('Perbarui Paket')
            ->assertDontSee('Classes')
            ->assertDontSee('Kelas')
            ->assertDontSee('keanggotaan')
            ->assertDontSee('yoga');
    }

    public function test_package_index_search_hint_and_pagination(): void
    {
        foreach (range(1, 12) as $number) {
            Package::create([
                'name' => 'Paket Ujian '.str_pad((string) $number, 2, '0', STR_PAD_LEFT),
                'slug' => 'paket-ujian-'.$number,
                'price' => 100000,
                'is_active' => 'active',
            ]);
        }

        $this->actingAs($this->admin)
            ->get(route('packages.index', ['per_page' => 25]))
            ->assertOk()
            ->assertViewHas('perPage', 25)
            ->assertViewHas('searchTooShort', false)
            ->assertSee('Menampilkan');

        $pagedResponse = $this->actingAs($this->admin)
            ->get(route('packages.index', ['per_page' => 999]))
            ->assertOk()
            ->assertViewHas('perPage', 10)
            ->assertSee('package-list-footer', false)
            ->assertSee('Halaman 1 dari 2')
            ->assertSee('package-pagination', false);

        $this->assertStringContainsString('<ul class="pagination', $pagedResponse->getContent());

        $this->actingAs($this->admin)
            ->get(route('packages.index', ['search' => 'P']))
            ->assertOk()
            ->assertViewHas('searchTooShort', true)
            ->assertSee('Masukkan minimal 2 karakter untuk mencari paket.');

        $this->actingAs($this->admin)
            ->get(route('packages.index', ['search' => 'Ujian 01']))
            ->assertOk()
            ->assertSee('Paket Ujian 01')
            ->assertDontSee('Paket Ujian 02');
    }

    public function test_package_flash_messages_are_indonesian(): void
    {
        $payload = $this->payload('Paket Gold', 5000000, 4500000);

        $this->actingAs($this->admin)
            ->post(route('packages.store'), $payload)
            ->assertRedirect(route('packages.index'))
            ->assertSessionHas('success', 'Paket berhasil dibuat.');

        $package = Package::where('slug', 'paket-gold')->firstOrFail();

        $this->actingAs($this->admin)
            ->put(route('packages.update', $package->uuid), $this->payload('Paket Platinum', 6000000))
            ->assertRedirect(route('packages.index'))
            ->assertSessionHas('success', 'Paket berhasil diperbarui.');

        $this->actingAs($this->admin)
            ->delete(route('packages.destroy', $package->uuid))
            ->assertRedirect(route('packages.index'))
            ->assertSessionHas('success', 'Paket berhasil dihapus.');
    }

    public function test_discount_validation_message_is_indonesian(): void
    {
        $this->actingAs($this->admin)
            ->post(route('packages.store'), $this->payload('Paket Diskon', 100000, 150000))
            ->assertSessionHasErrors([
                'options.0.discount_price' => 'Harga diskon harus lebih rendah daripada harga reguler.',
            ]);
    }

    public function test_package_index_supports_sorting(): void
    {
        $first = Package::create([
            'name' => 'Paket Alpha',
            'slug' => 'paket-alpha',
            'price' => 100000,
            'is_active' => 'active',
        ]);
        $second = Package::create([
            'name' => 'Paket Beta',
            'slug' => 'paket-beta',
            'price' => 100000,
            'is_active' => 'active',
        ]);

        PackageOption::create([
            'package_uuid' => $first->uuid,
            'name' => 'Alpha',
            'quota' => 1,
            'price' => 5000000,
            'duration' => 1,
            'duration_unit' => 'month',
            'sort_order' => 0,
            'is_active' => true,
        ]);
        PackageOption::create([
            'package_uuid' => $second->uuid,
            'name' => 'Beta',
            'quota' => 1,
            'price' => 2500000,
            'duration' => 1,
            'duration_unit' => 'month',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $nameResponse = $this->actingAs($this->admin)
            ->get(route('packages.index', ['sort' => 'name', 'direction' => 'asc']))
            ->assertOk()
            ->assertViewHas('sort', 'name')
            ->assertViewHas('direction', 'asc')
            ->assertSee('Urutkan')
            ->assertSee('Detail cepat');

        $this->assertSame(
            ['Paket Alpha', 'Paket Beta'],
            $nameResponse->viewData('packages')->pluck('name')->all()
        );

        $priceResponse = $this->actingAs($this->admin)
            ->get(route('packages.index', ['sort' => 'price', 'direction' => 'asc']))
            ->assertOk();

        $this->assertSame(
            ['Paket Beta', 'Paket Alpha'],
            $priceResponse->viewData('packages')->pluck('name')->all()
        );
    }

    public function test_package_table_keeps_action_buttons(): void
    {
        $package = Package::create([
            'name' => 'Paket Aksi',
            'slug' => 'paket-aksi',
            'price' => 100000,
            'is_active' => 'active',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('packages.index'))
            ->assertOk()
            ->assertSee('pkg-col-action', false)
            ->assertSee('bi-three-dots', false)
            ->assertSee('Detail cepat')
            ->assertSee('Ubah')
            ->assertSee('Hapus')
            ->assertSee(route('packages.edit', $package->uuid), false);

        $this->assertStringContainsString('table-layout: fixed', $response->getContent());
    }

    public function test_admin_can_bulk_update_and_delete_packages(): void
    {
        $packages = collect(range(1, 3))->map(function (int $number) {
            return Package::create([
                'name' => 'Paket Massal '.$number,
                'slug' => 'paket-massal-'.$number,
                'price' => 100000,
                'is_active' => 'inactive',
            ]);
        });

        $this->actingAs($this->admin)
            ->post(route('packages.bulk'), [
                'action' => 'activate',
                'ids' => [$packages[0]->uuid, $packages[1]->uuid],
            ])
            ->assertRedirect(route('packages.index'))
            ->assertSessionHas('success', '2 paket berhasil diaktifkan.');

        $this->assertSame('active', $packages[0]->fresh()->is_active);
        $this->assertSame('active', $packages[1]->fresh()->is_active);
        $this->assertSame('inactive', $packages[2]->fresh()->is_active);

        $this->actingAs($this->admin)
            ->post(route('packages.bulk'), [
                'action' => 'delete',
                'ids' => [$packages[0]->uuid, $packages[2]->uuid],
            ])
            ->assertRedirect(route('packages.index'))
            ->assertSessionHas('success', '2 paket berhasil dihapus.');

        $this->assertDatabaseMissing('packages', ['uuid' => $packages[0]->uuid]);
        $this->assertDatabaseMissing('packages', ['uuid' => $packages[2]->uuid]);
        $this->assertDatabaseHas('packages', ['uuid' => $packages[1]->uuid]);
    }

    public function test_admin_can_duplicate_package_with_all_children(): void
    {
        $source = Package::create([
            'name' => 'Paket Sumber',
            'slug' => 'paket-sumber',
            'price' => 100000,
            'is_active' => 'active',
            'is_published' => true,
            'published_at' => now(),
            'is_popular' => true,
        ]);

        PackageOption::create([
            'package_uuid' => $source->uuid,
            'name' => 'Standar',
            'quota' => 1,
            'price' => 5000000,
            'duration' => 1,
            'duration_unit' => 'month',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        PackageFeature::create([
            'package_uuid' => $source->uuid,
            'feature' => 'Dekorasi pelaminan',
            'sort_order' => 0,
        ]);

        $required = PackageAddon::create([
            'package_uuid' => $source->uuid,
            'name' => 'Addon Pokok',
            'price' => 500000,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        PackageAddon::create([
            'package_uuid' => $source->uuid,
            'name' => 'Addon Turunan',
            'price' => 250000,
            'requires_addon_uuid' => $required->uuid,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        PackageBlackoutDate::create([
            'package_uuid' => $source->uuid,
            'date' => now()->addDays(10)->toDateString(),
            'reason' => 'Libur acara',
        ]);

        PackagePromo::create([
            'package_uuid' => $source->uuid,
            'code' => 'HEMAT',
            'type' => 'fixed',
            'value' => 500000,
            'min_purchase' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('packages.duplicate', $source->uuid));

        $copy = Package::where('name', 'Salinan Paket Sumber')->firstOrFail();

        $response->assertRedirect(route('packages.edit', $copy->uuid))
            ->assertSessionHas('success', 'Paket berhasil diduplikasi.');

        $this->assertSame('inactive', $copy->is_active);
        $this->assertFalse((bool) $copy->is_published);
        $this->assertNull($copy->published_at);
        $this->assertFalse((bool) $copy->is_popular);
        $this->assertNotSame($source->slug, $copy->slug);
        $this->assertCount(1, $copy->options);
        $this->assertCount(1, $copy->features);
        $this->assertCount(2, $copy->addons);
        $this->assertCount(1, $copy->blackoutDates);
        $this->assertCount(1, $copy->promos);
        $this->assertNotSame('HEMAT', $copy->promos->first()->code);

        $newRequired = $copy->addons->firstWhere('name', 'Addon Pokok');
        $newDependent = $copy->addons->firstWhere('name', 'Addon Turunan');

        $this->assertNotNull($newRequired);
        $this->assertNotNull($newDependent);
        $this->assertSame($newRequired->uuid, $newDependent->requires_addon_uuid);
    }

    public function test_admin_can_toggle_status_preview_export_and_reorder(): void
    {
        $package = Package::create([
            'name' => 'Paket Kelola',
            'slug' => 'paket-kelola',
            'price' => 100000,
            'is_active' => 'inactive',
            'is_published' => false,
        ]);

        $this->actingAs($this->admin)
            ->patchJson(route('packages.toggle-status', $package->uuid), ['field' => 'is_active'])
            ->assertOk()
            ->assertJson([
                'ok' => true,
                'field' => 'is_active',
                'value' => 'active',
                'label' => 'Aktif',
            ]);

        $this->actingAs($this->admin)
            ->patchJson(route('packages.toggle-status', $package->uuid), ['field' => 'is_published'])
            ->assertOk()
            ->assertJsonPath('field', 'is_published')
            ->assertJsonPath('value', true)
            ->assertJsonPath('label', 'Dipublikasikan');

        $this->assertSame('active', $package->fresh()->is_active);
        $this->assertTrue((bool) $package->fresh()->is_published);
        $this->assertNotNull($package->fresh()->published_at);

        $this->actingAs($this->admin)
            ->get(route('packages.preview', $package->uuid))
            ->assertOk()
            ->assertViewIs('pages.package.member')
            ->assertViewHas('previewMode', true)
            ->assertSee('Pratinjau paket')
            ->assertSee('Harga belum tersedia')
            ->assertSee('Paket Kelola');

        $export = $this->actingAs($this->admin)
            ->get(route('packages.export'))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $this->assertStringContainsString(
            'attachment; filename="paket-',
            (string) $export->headers->get('Content-Disposition')
        );
        $this->assertStringContainsString('Paket Kelola', $export->streamedContent());

        $first = PackageOption::create([
            'package_uuid' => $package->uuid,
            'name' => 'Pertama',
            'quota' => 1,
            'price' => 5000000,
            'duration' => 1,
            'duration_unit' => 'month',
            'sort_order' => 0,
            'is_active' => true,
        ]);
        $second = PackageOption::create([
            'package_uuid' => $package->uuid,
            'name' => 'Kedua',
            'quota' => 1,
            'price' => 6000000,
            'duration' => 1,
            'duration_unit' => 'month',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)
            ->postJson(route('packages.reorder', $package->uuid), [
                'type' => 'options',
                'uuids' => [$second->uuid, $first->uuid],
            ])
            ->assertOk()
            ->assertJson(['ok' => true]);

        $this->assertSame(0, (int) $second->fresh()->sort_order);
        $this->assertSame(1, (int) $first->fresh()->sort_order);

        $this->actingAs($this->admin)
            ->postJson(route('packages.reorder', $package->uuid), [
                'type' => 'options',
                'uuids' => [$second->uuid],
            ])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Urutan tidak valid.');
    }

    public function test_cleanup_migration_updates_legacy_package_name_and_slug(): void
    {
        $package = Package::create([
            'name' => 'First Timer Package',
            'slug' => 'first-timer-package',
            'description' => 'Paket lama.',
            'price' => 100000,
            'is_active' => 'active',
        ]);

        $migration = require database_path('migrations/2026_11_22_000001_cleanup_legacy_wedding_copy.php');
        $migration->up();

        $this->assertDatabaseHas('packages', [
            'uuid' => $package->uuid,
            'name' => 'Paket Rencana Pernikahan',
            'slug' => 'paket-rencana-pernikahan',
        ]);
    }

    /** @return array<string, mixed> */
    private function payload(string $name, int $price, ?int $discount = null): array
    {
        return [
            'name' => $name,
            'description' => 'Paket layanan wedding organizer.',
            'guest_capacity' => 150,
            'event_duration_hours' => 6,
            'venue_note' => 'Jakarta Selatan dan sekitarnya',
            'cover_url' => 'https://example.com/images/paket-wedding.jpg',
            'is_active' => 'active',
            'options' => [
                [
                    'name' => 'Gold',
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
}
