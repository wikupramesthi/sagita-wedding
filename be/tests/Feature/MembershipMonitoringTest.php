<?php

namespace Tests\Feature;

use App\Models\Package\Package;
use App\Models\Payment\Order;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MembershipMonitoringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['user', 'admin', 'super-admin', 'vendor'] as $role) {
            Role::create(['name' => $role, 'guard_name' => 'web']);
        }
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    private function package(): Package
    {
        return Package::create([
            'name' => 'Paket Wedding',
            'slug' => 'paket-wedding-'.Str::lower(Str::random(6)),
            'price' => 10000000,
        ]);
    }

    private function membershipWithOrder(User $user, Package $package, array $overrides = []): UserPackage
    {
        $order = Order::create(array_merge([
            'user_uuid' => $user->uuid,
            'order_number' => 'OD-'.Str::upper(Str::random(8)),
            'type' => 'package',
            'package_uuid' => $package->uuid,
            'amount' => 10000000,
            'total_amount' => 10000000,
            'amount_paid' => 10000000,
            'remaining_amount' => 0,
            'status' => 'paid',
            'event_date' => now()->addDays(10)->toDateString(),
        ], $overrides));

        return UserPackage::create([
            'user_uuid' => $user->uuid,
            'package_uuid' => $package->uuid,
            'order_uuid' => $order->uuid,
            'quota' => 4,
            'started_at' => now(),
            'expired_at' => now()->addMonths(3),
            'status' => 'active',
        ]);
    }

    public function test_member_only_sees_own_membership_and_payment_summary(): void
    {
        $member = $this->userWithRole('user');
        $other = $this->userWithRole('user');
        $package = $this->package();

        $this->membershipWithOrder($member, $package);
        $this->membershipWithOrder($other, $package, [
            'amount' => 20000000,
            'total_amount' => 20000000,
            'amount_paid' => 20000000,
        ]);

        $this->actingAs($member)
            ->get('/backend/memberships')
            ->assertOk()
            ->assertViewIs('pages.memberships.index')
            ->assertViewHas('memberships', fn ($memberships) => $memberships->total() === 1)
            ->assertViewHas('finance', fn ($finance) => (float) $finance['total'] === 10000000.0
                && (float) $finance['paid'] === 10000000.0
                && (float) $finance['remaining'] === 0.0
                && $finance['fully_paid'] === 1)
            ->assertSee('Ringkasan Pembayaran')
            ->assertSee('Pengingat Acara');
    }

    public function test_admin_sees_all_memberships_and_upcoming_events(): void
    {
        $admin = $this->userWithRole('admin');
        $member = $this->userWithRole('user');
        $other = $this->userWithRole('user');
        $package = $this->package();

        $this->membershipWithOrder($member, $package, [
            'event_date' => now()->addDays(5)->toDateString(),
        ]);
        $this->membershipWithOrder($other, $package, [
            'event_date' => now()->addDays(12)->toDateString(),
            'amount' => 5000000,
            'total_amount' => 10000000,
            'amount_paid' => 5000000,
            'remaining_amount' => 5000000,
            'status' => 'partially_paid',
        ]);

        $this->actingAs($admin)
            ->get('/backend/memberships')
            ->assertOk()
            ->assertViewHas('stats', fn ($stats) => $stats['total'] === 2 && $stats['events_soon'] === 2)
            ->assertViewHas('finance', fn ($finance) => (float) $finance['paid'] === 15000000.0
                && (float) $finance['remaining'] === 5000000.0
                && $finance['partial'] === 1)
            ->assertViewHas('upcomingEvents', fn ($events) => count($events) === 2
                && $events[0]['days'] === 5)
            ->assertSee('Sisa');
    }

    public function test_member_cannot_open_other_membership_detail(): void
    {
        $member = $this->userWithRole('user');
        $other = $this->userWithRole('user');
        $membership = $this->membershipWithOrder($other, $this->package());

        $this->actingAs($member)
            ->get(route('memberships.show', $membership->uuid))
            ->assertForbidden();
    }

    public function test_membership_detail_shows_payment_history_and_event(): void
    {
        $member = $this->userWithRole('user');
        $membership = $this->membershipWithOrder($member, $this->package(), [
            'amount_paid' => 4000000,
            'remaining_amount' => 6000000,
            'status' => 'partially_paid',
        ]);

        Payment::create([
            'order_uuid' => $membership->order_uuid,
            'kind' => 'dp',
            'amount' => 4000000,
            'gross_amount' => 4000000,
            'status' => 'paid',
            'verification_status' => 'verified',
            'paid_at' => now(),
        ]);

        $this->actingAs($member)
            ->get(route('memberships.show', $membership->uuid))
            ->assertOk()
            ->assertSee('Ringkasan Pembayaran')
            ->assertSee('Riwayat Pembayaran')
            ->assertSee('Tanggal Acara')
            ->assertSee('Sisa Tagihan');
    }

    public function test_membership_detail_uses_existing_avatar_and_falls_back_to_initial(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('avatars/member.png', 'image');

        $member = $this->userWithRole('user');
        $member->forceFill(['avatar' => 'avatars/member.png'])->save();
        $membership = $this->membershipWithOrder($member, $this->package());

        $this->actingAs($member)
            ->get(route('memberships.show', $membership->uuid))
            ->assertSee(asset('storage/avatars/member.png'), false);

        $member->forceFill(['avatar' => 'avatars/missing.png'])->save();

        $this->actingAs($member)
            ->get(route('memberships.show', $membership->uuid))
            ->assertDontSee('avatars/missing.png', false)
            ->assertSee(strtoupper(substr($member->name, 0, 1)));
    }

    public function test_mobile_membership_page_shows_payment_and_event_monitoring(): void
    {
        $member = $this->userWithRole('user');
        $this->membershipWithOrder($member, $this->package(), [
            'amount_paid' => 4000000,
            'remaining_amount' => 6000000,
            'status' => 'partially_paid',
        ]);

        $this->actingAs($member)
            ->withHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 13) Mobile')
            ->get('/backend/memberships')
            ->assertOk()
            ->assertViewIs('pages.mobile.memberships')
            ->assertSee('Ringkasan Pembayaran')
            ->assertSee('Tanggal Acara');
    }
}
