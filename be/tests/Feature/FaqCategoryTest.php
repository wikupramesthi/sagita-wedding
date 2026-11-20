<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\User;
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
    }

    public function test_faqs_table_has_kategori_column_and_expected_categories(): void
    {
        $this->assertTrue(Schema::hasColumn('faqs', 'kategori'));

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

        $this->assertSame(Faq::CATEGORIES, Faq::orderByKategori()->pluck('kategori')->all());
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
                'data' => [['uuid', 'kategori', 'question', 'answer']],
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
                'jawaban' => 'Pilih paket lalu kirim formulir pemesanan.',
                'urutan' => 1,
                'status' => 'active',
            ])
            ->assertRedirect(route('faq.index'));

        $this->assertDatabaseHas('faqs', [
            'pertanyaan' => 'Bagaimana saya bisa memesan paket?',
            'kategori' => 'Paket & Layanan',
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
}
