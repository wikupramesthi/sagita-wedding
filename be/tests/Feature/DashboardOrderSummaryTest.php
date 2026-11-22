<?php

namespace Tests\Feature;

use App\Models\Payment\Order;
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
        return Order::create([
            'user_uuid' => $member->uuid,
            'order_number' => 'ORD-'.Str::upper(Str::random(10)),
            'type' => 'package',
            'event_date' => '2027-08-21',
            'amount' => 120000000,
            'total_amount' => 120000000,
            'amount_paid' => 30000000,
            'remaining_amount' => 90000000,
            'status' => 'partially_paid',
        ]);
    }

    public function test_member_dashboard_always_shows_timeline_and_package_recommendations(): void
    {
        $member = $this->userWithRole('user');

        $this->actingAs($member)
            ->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36')
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Timeline persiapan')
            ->assertSee('12 bulan sebelum acara')
            ->assertSee('Budget maksimal untuk paket')
            ->assertSee('name="budget_max"', false)
            ->assertDontSee('Checklist persiapan');
    }

    public function test_member_dashboard_reads_date_and_payment_from_existing_orders(): void
    {
        $member = $this->userWithRole('user');
        $order = $this->packageOrder($member);

        $this->actingAs($member)
            ->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36')
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('21 Agustus 2027')
            ->assertSee('Rp 120.000.000')
            ->assertSee('Rp 30.000.000')
            ->assertSee($order->order_number);
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
}
