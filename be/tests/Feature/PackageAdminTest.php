<?php

namespace Tests\Feature;

use App\Models\Package\Package;
use App\Models\Package\PackageAddon;
use App\Models\Package\PackageFeature;
use App\Models\Package\PackageOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $adminRole = Role::findByName('admin', 'web');
        foreach (['package.store', 'package.update', 'package.destroy'] as $permission) {
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
            ->assertSee('Kuota: 1')
            ->assertSee('Dekorasi pelaminan')
            ->assertSee('Paling Dipilih')
            ->assertSee('Pelaminan bunga segar')
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
                'is_active' => 'active',
            ]);
        }

        $this->actingAs($this->admin)
            ->get(route('packages.index', ['per_page' => 25]))
            ->assertOk()
            ->assertViewHas('perPage', 25)
            ->assertViewHas('searchTooShort', false)
            ->assertSee('Menampilkan');

        $this->actingAs($this->admin)
            ->get(route('packages.index', ['per_page' => 999]))
            ->assertOk()
            ->assertViewHas('perPage', 10);

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
