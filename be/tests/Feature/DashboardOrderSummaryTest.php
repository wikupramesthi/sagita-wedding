<?php

namespace Tests\Feature;

use App\Models\Package\Package;
use App\Models\Package\PackageOption;
use App\Models\Payment\Order;
use App\Models\Payment\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardOrderSummaryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['user', 'admin', 'super-admin', 'vendor'] as $role) {
            Role::findOrCreate($role, 'web');
        }
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->assignRole($role);

        return $user;
    }

    private function packageOrder(User $member): Order
    {
        $package = $this->publishedPackage('Paket Wedding', 120000000);
        $packageOption = $package->options()->firstOrFail();

        return Order::create([
            'user_uuid' => $member->uuid,
            'order_number' => 'ORD-'.Str::upper(Str::random(10)),
            'type' => 'package',
            'package_uuid' => $package->uuid,
            'package_option_uuid' => $packageOption->uuid,
            'event_date' => '2027-08-21',
            'amount' => 120000000,
            'total_amount' => 120000000,
            'amount_paid' => 30000000,
            'remaining_amount' => 90000000,
            'status' => 'partially_paid',
        ]);
    }

    private function membershipOrder(User $member): Order
    {
        $package = $this->publishedMembershipPackage('Studio Class Membership', 250000);
        $packageOption = $package->options()->firstOrFail();

        return Order::create([
            'user_uuid' => $member->uuid,
            'order_number' => 'ORD-'.Str::upper(Str::random(10)),
            'type' => 'package',
            'package_uuid' => $package->uuid,
            'package_option_uuid' => $packageOption->uuid,
            'event_date' => '2027-08-21',
            'amount' => 250000,
            'total_amount' => 250000,
            'amount_paid' => 0,
            'remaining_amount' => 250000,
            'status' => 'pending',
        ]);
    }

    private function publishedPackage(string $name, int $price, ?int $discountPrice = null): Package
    {
        $package = Package::create([
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(5)),
            'description' => 'Paket wedding untuk pengujian.',
            'price' => $price,
            'guest_capacity' => 100,
            'event_duration_hours' => 6,
            'is_active' => 'active',
            'is_published' => true,
            'published_at' => now(),
        ]);

        PackageOption::create([
            'package_uuid' => $package->uuid,
            'name' => 'Standar',
            'quota' => 1,
            'price' => $price,
            'discount_price' => $discountPrice,
            'duration' => 1,
            'duration_unit' => 'month',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        return $package;
    }

    private function publishedMembershipPackage(string $name, int $price): Package
    {
        $package = Package::create([
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(5)),
            'description' => 'Paket kelas membership.',
            'price' => $price,
            'is_active' => 'active',
            'is_published' => true,
            'published_at' => now(),
        ]);

        PackageOption::create([
            'package_uuid' => $package->uuid,
            'name' => 'Kelas',
            'quota' => 10,
            'price' => $price,
            'duration' => 1,
            'duration_unit' => 'month',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        return $package;
    }

    private function paymentProof(Order $order, string $status, int $amount): Payment
    {
        $statusValues = [
            'accepted' => ['paid', 'approved'],
            'pending' => ['pending', 'pending'],
            'rejected' => ['failed', 'rejected'],
        ];
        [$paymentStatus, $verificationStatus] = $statusValues[$status];

        return Payment::create([
            'order_uuid' => $order->uuid,
            'kind' => 'installment',
            'payment_gateway' => 'manual_transfer',
            'payment_type' => 'Transfer Manual',
            'amount' => $amount,
            'gross_amount' => $amount,
            'status' => $paymentStatus,
            'verification_status' => $verificationStatus,
            'proof_image_path' => 'proof/'.$status.'.jpg',
            'proof_uploaded_at' => now(),
        ]);
    }

    public function test_member_dashboard_always_shows_timeline_and_package_recommendations(): void
    {
        $member = $this->userWithRole('user');

        $response = $this->actingAs($member)
            ->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36')
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Timeline persiapan')
            ->assertSee('12 bulan sebelum acara')
            ->assertSee('Batas anggaran paket')
            ->assertSee('name="budget_max"', false)
            ->assertDontSee('dashboard-language-switcher', false)
            ->assertDontSee('Checklist persiapan');

        $html = $response->getContent();
        $planningGrid = strpos($html, 'member-planning-grid');
        $planningEnd = $planningGrid === false ? false : strpos($html, '</section>', $planningGrid);
        $recommendation = strpos($html, 'member-recommendation-card');
        $timeline = strpos($html, 'Timeline persiapan');
        $timelineEnd = $timeline === false ? false : strpos($html, '</section>', $timeline);
        $actions = strpos($html, 'member-actions-card');

        $this->assertNotFalse($planningEnd);
        $this->assertNotFalse($recommendation);
        $this->assertNotFalse($timeline);
        $this->assertNotFalse($timelineEnd);
        $this->assertNotFalse($actions);
        $this->assertTrue($planningEnd < $recommendation && $recommendation < $timeline);
        $this->assertTrue($timelineEnd < $actions);
    }

    public function test_new_member_can_get_matching_packages_without_leaving_dashboard(): void
    {
        $member = $this->userWithRole('user');
        $recommendedPackage = $this->publishedPackage('Paket Anggrek', 7000000, 5500000);
        $this->publishedPackage('Paket Mewah', 9000000);
        $this->publishedMembershipPackage('Studio Class Membership', 250000);

        $this->actingAs($member)
            ->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36')
            ->get(route('dashboard.index', ['budget_max' => 6000000]))
            ->assertOk()
            ->assertSee('Belum ada pesanan paket aktif')
            ->assertSee('Paket Anggrek')
            ->assertSee('Rp 5.500.000')
            ->assertDontSee('Paket Mewah')
            ->assertDontSee('Studio Class Membership')
            ->assertSee(route('checkout.package', $recommendedPackage->uuid), false);
    }

    public function test_ajax_recommendations_return_matching_packages_for_members(): void
    {
        $member = $this->userWithRole('user');
        $recommendedPackage = $this->publishedPackage('Paket Anggrek', 7000000, 5500000);
        $this->publishedPackage('Paket Mewah', 9000000);
        $this->publishedMembershipPackage('Studio Class Membership', 250000);

        $this->actingAs($member)
            ->getJson(route('dashboard.recommendations', ['budget_max' => 6000000]))
            ->assertOk()
            ->assertJsonPath('budget_max', 6000000)
            ->assertJsonCount(1, 'recommendations')
            ->assertJsonPath('recommendations.0.name', 'Paket Anggrek')
            ->assertJsonPath('recommendations.0.formatted_price', '5.500.000')
            ->assertJsonMissing(['name' => 'Studio Class Membership'])
            ->assertJsonPath('recommendations.0.checkout_url', route('checkout.package', $recommendedPackage->uuid));
    }

    public function test_ajax_recommendations_are_not_available_to_admins(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->getJson(route('dashboard.recommendations', ['budget_max' => 6000000]))
            ->assertForbidden();
    }

    public function test_mobile_member_dashboard_shows_existing_orders_and_package_search(): void
    {
        $member = $this->userWithRole('user');
        $order = $this->packageOrder($member);
        $this->paymentProof($order, 'pending', 10000000);
        $recommendedPackage = $this->publishedPackage('Paket Harmoni', 5000000);
        $whatsappMessage = rawurlencode(implode("\n\n", [
            'Halo Sagita Wedding Planner 👋',
            'Saya mendapatkan informasi mengenai Sagita Wedding Planner melalui website dan tertarik dengan layanan Wedding Organizer yang ditawarkan.',
            'Saya ingin mengetahui lebih lanjut mengenai pilihan paket, layanan, serta estimasi biaya untuk pernikahan saya.',
            'Terima kasih 😊',
        ]));

        $this->actingAs($member)
            ->withHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 13; Pixel 7) AppleWebKit/537.36 Mobile Safari/537.36')
            ->get(route('dashboard.index', ['budget_max' => 6000000]))
            ->assertOk()
            ->assertViewIs('pages.mobile.home')
            ->assertSee('Rencana pernikahan')
            ->assertDontSee('Art of Living')
            ->assertDontSee('Yoga')
            ->assertDontSee('YogaRoots')
            ->assertSee($order->order_number)
            ->assertSee('Timeline persiapan')
            ->assertSee('Paket Harmoni')
            ->assertSee('Status bukti pembayaran')
            ->assertSee('Menunggu')
            ->assertSee($whatsappMessage, false)
            ->assertDontSee('more information about your classes')
            ->assertSee(route('consultation.form'), false)
            ->assertSee('bi-telephone', false)
            ->assertSee(route('checkout.package', $recommendedPackage->uuid), false);
    }

    public function test_new_mobile_member_sees_timeline_and_budget_search_without_orders(): void
    {
        $member = $this->userWithRole('user');

        $this->actingAs($member)
            ->withHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 13; Pixel 7) AppleWebKit/537.36 Mobile Safari/537.36')
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertViewIs('pages.mobile.home')
            ->assertSee('Belum ada pesanan paket')
            ->assertSee('Timeline persiapan')
            ->assertSee('name="budget_max"', false)
            ->assertSee(route('dashboard.recommendations'), false);
    }

    public function test_mobile_profile_modal_uses_wedding_context_instead_of_yoga_copy(): void
    {
        $member = $this->userWithRole('user');
        $member->forceFill(['no_hp' => null])->save();

        $this->actingAs($member)
            ->withHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 13; Pixel 7) AppleWebKit/537.36 Mobile Safari/537.36')
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertViewIs('pages.mobile.home')
            ->assertSee('Lengkapi profil')
            ->assertSee('Dari mana Anda mengenal kami?')
            ->assertDontSee('Yoga Community')
            ->assertDontSee('Yoga Event / Workshop');
    }

    public function test_member_dashboard_reads_date_and_payment_from_existing_orders(): void
    {
        $member = $this->userWithRole('user');
        $order = $this->packageOrder($member);
        $acceptedProof = $this->paymentProof($order, 'accepted', 30000000);
        $pendingProof = $this->paymentProof($order, 'pending', 10000000);
        $rejectedProof = $this->paymentProof($order, 'rejected', 5000000);
        $recommendedPackage = $this->publishedPackage('Paket Harmoni', 5000000);

        $this->actingAs($member)
            ->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36')
            ->get(route('dashboard.index', ['budget_max' => 6000000]))
            ->assertOk()
            ->assertSee('21 Agustus 2027')
            ->assertSee('Rp 120.000.000')
            ->assertSee('Rp 30.000.000')
            ->assertSee($order->order_number)
            ->assertSee('Paket Harmoni')
            ->assertSee('Rekap Pembayaran dan Bukti Transfer')
            ->assertSee('Diterima')
            ->assertSee('Pending')
            ->assertSee('Ditolak')
            ->assertSee(route('orders.payments.proof', [$order->uuid, $acceptedProof->uuid]), false)
            ->assertSee(route('orders.payments.proof', [$order->uuid, $pendingProof->uuid]), false)
            ->assertSee(route('orders.payments.proof', [$order->uuid, $rejectedProof->uuid]), false)
            ->assertSee(route('checkout.package', $recommendedPackage->uuid), false);
    }

    public function test_admin_dashboard_reads_the_same_upcoming_member_orders(): void
    {
        $member = $this->userWithRole('user');
        $admin = $this->userWithRole('admin');
        $order = $this->packageOrder($member);

        $this->actingAs($admin)
            ->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36')
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Pesanan wedding mendatang')
            ->assertSee($member->name)
            ->assertSee($order->order_number)
            ->assertSee('21 Agt 2027');
    }

    public function test_membership_orders_are_excluded_from_wedding_planning_and_admin_upcoming_orders(): void
    {
        $member = $this->userWithRole('user');
        $admin = $this->userWithRole('admin');
        $membershipOrder = $this->membershipOrder($member);

        $this->actingAs($member)
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertViewHas('planningOrders', fn ($orders) => $orders->isEmpty())
            ->assertSee($membershipOrder->order_number);

        $this->actingAs($admin)
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertViewHas('upcomingWeddingOrders', fn ($orders) => $orders->isEmpty());
    }
}
