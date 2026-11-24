<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\User;
use Database\Seeders\RouteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FaqCategoryTest extends TestCase
{
    use RefreshDatabase;

    private const API_KEY = 'test-frontend-key';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.frontend_api.key' => self::API_KEY]);
        $this->seed(RouteSeeder::class);
    }

    public function test_faqs_table_has_kategori_column_and_expected_categories(): void
    {
        $this->assertTrue(Schema::hasColumn('faqs', 'kategori'));
        $this->assertTrue(Schema::hasColumn('faqs', 'locale'));

        $this->assertSame([
            'Perencanaan Pernikahan',
            'Paket & Layanan',
            'Pemesanan & Pembayaran',
            'Vendor & Mitra',
            'Pelaksanaan Acara',
            'Perubahan & Pembatalan',
        ], Faq::CATEGORIES);

        foreach (Faq::CATEGORIES as $category) {
            $this->assertDatabaseHas('faqs', ['kategori' => $category]);
        }

        $this->assertSame(
            Faq::CATEGORIES,
            Faq::orderByKategori()->pluck('kategori')->unique()->values()->all(),
        );
    }

    public function test_public_api_can_filter_faqs_by_kategori(): void
    {
        $vendorFaq = Faq::create([
            'pertanyaan' => 'Siapa vendor yang tersedia?',
            'kategori' => 'Vendor & Mitra',
            'jawaban' => 'Kami bekerja sama dengan berbagai vendor pernikahan.',
            'urutan' => 1,
            'status' => 'active',
        ]);

        Faq::create([
            'pertanyaan' => 'Bagaimana cara pembayaran?',
            'kategori' => 'Pemesanan & Pembayaran',
            'jawaban' => 'Pembayaran dapat dilakukan melalui transfer.',
            'urutan' => 2,
            'status' => 'active',
        ]);

        $response = $this->withHeader('X-Api-Key', self::API_KEY)
            ->getJson('/api/faqs?kategori='.urlencode('Vendor & Mitra'))
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [['uuid', 'kategori', 'locale', 'question', 'answer']],
                'meta' => ['total', 'kategori', 'categories'],
            ])
            ->assertJsonPath('meta.kategori', 'Vendor & Mitra');

        $data = $response->json('data');

        $this->assertNotEmpty($data);
        $this->assertTrue(collect($data)->contains('uuid', $vendorFaq->uuid));
        $this->assertSame(Faq::CATEGORIES, $response->json('meta.categories'));
        $response->assertJsonPath('meta.total', count($data));

        foreach ($data as $entry) {
            $this->assertSame('Vendor & Mitra', $entry['kategori']);
            $this->assertArrayNotHasKey('id', $entry);
            $this->assertArrayNotHasKey('status', $entry);
            $this->assertArrayNotHasKey('urutan', $entry);
        }
    }

    public function test_public_api_tolerates_unknown_kategori(): void
    {
        $response = $this->withHeader('X-Api-Key', self::API_KEY)
            ->getJson('/api/faqs?kategori=Unknown')
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('meta.kategori', null);

        $this->assertNotEmpty($response->json('data'));
    }

    public function test_admin_index_renders_category_filters(): void
    {
        Faq::create([
            'pertanyaan' => 'Apakah bisa mengubah tanggal acara?',
            'kategori' => 'Perubahan & Pembatalan',
            'jawaban' => 'Silakan hubungi kami.',
            'urutan' => 1,
            'status' => 'active',
        ]);

        $user = User::factory()->create();
        $role = Role::findOrCreate('admin', 'web');
        $role->givePermissionTo(Permission::findOrCreate('faq.index', 'web'));
        $user->assignRole($role);

        $this->actingAs($user)
            ->get(route('faq.index'))
            ->assertOk()
            ->assertSee('Perencanaan Pernikahan')
            ->assertSee('Perubahan &amp; Pembatalan', false)
            ->assertSee('Apakah bisa mengubah tanggal acara?');
    }

    public function test_admin_can_prepare_an_inactive_faq_draft_from_a_sita_question(): void
    {
        $user = User::factory()->create();
        $role = Role::findOrCreate('admin', 'web');
        $role->givePermissionTo(Permission::findOrCreate('faq.index', 'web'));
        $role->givePermissionTo(Permission::findOrCreate('faq.create', 'web'));
        $role->givePermissionTo(Permission::findOrCreate('faq.store', 'web'));
        $user->assignRole($role);

        $this->actingAs($user)
            ->get(route('faq.create', ['pertanyaan' => 'Apakah ada layanan dekorasi?']))
            ->assertOk()
            ->assertSee('Draft dari pertanyaan Sita')
            ->assertSee('value="Apakah ada layanan dekorasi?"', false)
            ->assertSee('value="inactive" selected', false)
            ->assertSee('Isi jawaban berdasarkan kebijakan Sagita yang telah diverifikasi');
    }

    public function test_admin_can_create_faq_with_kategori(): void
    {
        $user = User::factory()->create();
        $role = Role::findOrCreate('admin', 'web');
        $role->givePermissionTo(Permission::findOrCreate('faq.store', 'web'));
        $user->assignRole($role);

        $this->actingAs($user)
            ->post(route('faq.store'), [
                'pertanyaan' => 'Bagaimana saya bisa memesan paket?',
                'kategori' => 'Paket & Layanan',
                'locale' => 'id',
                'jawaban' => 'Pilih paket lalu kirim formulir pemesanan.',
                'urutan' => 1,
                'status' => 'active',
            ])
            ->assertRedirect(route('faq.index'));

        $this->assertDatabaseHas('faqs', [
            'pertanyaan' => 'Bagaimana saya bisa memesan paket?',
            'kategori' => 'Paket & Layanan',
            'locale' => 'id',
            'urutan' => 1,
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->post(route('faq.store'), [
                'pertanyaan' => 'Kategori salah',
                'kategori' => 'Kategori Tidak Ada',
                'jawaban' => 'Jawaban',
                'urutan' => 2,
                'status' => 'active',
            ])
            ->assertSessionHasErrors('kategori');
    }

    public function test_verified_policies_are_seeded_as_editable_faqs_in_both_languages(): void
    {
        $this->assertDatabaseHas('faqs', [
            'uuid' => 'e1a00001-1111-4aaa-8aaa-000000000003',
            'locale' => 'id',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('faqs', [
            'uuid' => 'e1a00001-1111-4aaa-8aaa-000000000004',
            'locale' => 'en',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('faqs', [
            'uuid' => '9f25d34e-2a60-42cf-8379-ed069dc50ac0',
            'status' => 'inactive',
        ]);
        $this->assertDatabaseHas('faqs', [
            'uuid' => '95b200f1-69c7-4f1b-a76b-172bcf5fcc55',
            'status' => 'inactive',
        ]);
    }

    public function test_admin_can_filter_faqs_by_language(): void
    {
        $user = User::factory()->create();
        $role = Role::findOrCreate('admin', 'web');
        $role->givePermissionTo(Permission::findOrCreate('faq.index', 'web'));
        $user->assignRole($role);

        $this->actingAs($user)
            ->get(route('faq.index', ['locale' => 'id']))
            ->assertOk()
            ->assertSee('Bahasa Indonesia')
            ->assertDontSee('What are Sagita’s operating hours?');
    }

    public function test_admin_can_edit_a_verified_sita_policy_without_a_deploy(): void
    {
        $user = User::factory()->create();
        $role = Role::findOrCreate('admin', 'web');
        $role->givePermissionTo(Permission::findOrCreate('faq.update', 'web'));
        $user->assignRole($role);

        $uuid = 'e1a00001-1111-4aaa-8aaa-000000000003';
        $faq = Faq::query()->findOrFail($uuid);

        $this->actingAs($user)
            ->put(route('faq.update', $uuid), [
                'pertanyaan' => $faq->pertanyaan,
                'kategori' => $faq->kategori,
                'locale' => 'id',
                'jawaban' => 'Jawaban yang diperbarui admin.',
                'urutan' => $faq->urutan,
                'status' => 'active',
            ])
            ->assertRedirect(route('faq.index'));

        $this->assertDatabaseHas('faqs', [
            'uuid' => $uuid,
            'locale' => 'id',
            'jawaban' => 'Jawaban yang diperbarui admin.',
        ]);
    }
}
