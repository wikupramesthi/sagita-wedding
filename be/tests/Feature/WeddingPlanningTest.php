<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WeddingPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WeddingPlanningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'user', 'guard_name' => 'web']);
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'vendor', 'guard_name' => 'web']);
    }

    private function member(): User
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        return $user;
    }

    public function test_member_can_save_their_wedding_date_and_budget(): void
    {
        $member = $this->member();

        $this->actingAs($member)
            ->put(route('wedding-planning.update'), [
                'event_date' => '2027-08-21',
                'total_budget' => 120000000,
                'budget_breakdown' => [
                    'venue' => 30000000,
                    'catering' => 40000000,
                    'decoration' => 20000000,
                    'documentation' => 10000000,
                    'attire' => 15000000,
                    'other' => 5000000,
                ],
            ])
            ->assertRedirect();

        $plan = WeddingPlan::find($member->uuid);

        $this->assertSame('2027-08-21', $plan->event_date->format('Y-m-d'));
        $this->assertSame(120000000.0, (float) $plan->total_budget);
        $this->assertSame(30000000, (int) $plan->budget_breakdown['venue']);
    }

    public function test_member_can_update_only_their_own_planning_checklist(): void
    {
        $member = $this->member();
        $otherMember = $this->member();

        $this->actingAs($member)
            ->patch(route('wedding-planning.tasks.update', 'date_venue'), ['completed' => true])
            ->assertRedirect();

        $this->assertSame(['date_venue'], WeddingPlan::find($member->uuid)->completed_tasks);
        $this->assertNull(WeddingPlan::find($otherMember->uuid));
    }

    public function test_non_members_cannot_update_wedding_planning(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->put(route('wedding-planning.update'), [
                'event_date' => null,
                'total_budget' => 0,
                'budget_breakdown' => array_fill_keys(array_keys(WeddingPlan::BUDGET_CATEGORIES), 0),
            ])
            ->assertForbidden();
    }

    public function test_dashboard_renders_the_desktop_planning_hub_for_members(): void
    {
        $this->actingAs($this->member())
            ->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36')
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Wedding Planning Hub')
            ->assertSee('Checklist persiapan')
            ->assertSee('Rencana anggaran');
    }
}
