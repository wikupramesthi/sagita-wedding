<?php

namespace Tests\Feature;

use App\Models\Class\ClassModel;
use App\Models\Class\ClassSchedule;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VenueModuleTest extends TestCase
{
    use RefreshDatabase;

    private const API_KEY = 'test-frontend-key';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.frontend_api.key' => self::API_KEY]);
    }

    public function test_venue_schema_and_schedule_api_use_venue_names(): void
    {
        $this->assertTrue(Schema::hasTable('venues'));
        $this->assertFalse(Schema::hasTable('studios'));
        $this->assertTrue(Schema::hasColumn('class_schedules', 'venue_uuid'));
        $this->assertFalse(Schema::hasColumn('class_schedules', 'studio_uuid'));
        $this->assertTrue(Route::has('venues.index'));
        $this->assertFalse(Route::has('studios.index'));

        $class = ClassModel::create([
            'name' => 'Morning Flow',
            'slug' => 'morning-flow',
            'level' => 'foundation',
            'is_active' => 'active',
        ]);
        $venue = Venue::create([
            'uuid' => 'd6d4fe8f-1695-47cb-8b05-30b1fb39e32e',
            'name' => 'Downtown Venue',
            'slug' => 'downtown-venue',
            'address' => '1 Main Street',
            'status' => 'active',
        ]);
        $schedule = ClassSchedule::create([
            'class_uuid' => $class->uuid,
            'venue_uuid' => $venue->uuid,
            'day' => strtolower(now()->format('l')),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'capacity' => 10,
            'status' => 'active',
        ]);

        $this->withHeader('X-Api-Key', self::API_KEY)
            ->getJson('/api/class-schedules?date='.now()->toDateString().'&venue_uuid='.$venue->uuid)
            ->assertOk()
            ->assertJsonPath('data.0.uuid', $schedule->uuid)
            ->assertJsonPath('data.0.venue.uuid', $venue->uuid)
            ->assertJsonPath('data.0.venue.name', 'Downtown Venue');
    }

    public function test_venue_migration_updates_menu_and_permissions_without_losing_role_access(): void
    {
        $now = now();
        $groupId = DB::table('menu_groups')->insertGetId([
            'name' => 'Studios',
            'status' => true,
            'permission_name' => 'studios.index',
            'position' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('menu_items')->insert([
            'name' => 'All Studios',
            'route' => 'studios.index',
            'status' => true,
            'permission_name' => 'studios.index',
            'menu_group_id' => $groupId,
            'position' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('menu_items')->insert([
            'name' => 'Add Studio',
            'route' => 'studios.create',
            'status' => true,
            'permission_name' => 'studios.create',
            'menu_group_id' => $groupId,
            'position' => 2,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $permissionId = DB::table('permissions')->insertGetId([
            'name' => 'studios.index',
            'guard_name' => 'web',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $role = Role::create(['name' => 'venue-test-role', 'guard_name' => 'web']);
        DB::table('role_has_permissions')->insert([
            'permission_id' => $permissionId,
            'role_id' => $role->id,
        ]);
        DB::table('routes')->insert([
            'route' => 'studios.index',
            'permission_name' => 'studios.index',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $migration = require database_path('migrations/2026_11_19_064303_rename_studio_module_to_venues.php');
        $migration->up();

        $this->assertDatabaseHas('menu_groups', [
            'id' => $groupId,
            'name' => 'Venues',
            'permission_name' => 'venues.index',
        ]);
        $this->assertDatabaseHas('menu_items', [
            'menu_group_id' => $groupId,
            'name' => 'All Venues',
            'route' => 'venues.index',
            'permission_name' => 'venues.index',
        ]);
        $this->assertDatabaseHas('menu_items', [
            'menu_group_id' => $groupId,
            'name' => 'Add Venue',
            'route' => 'venues.create',
            'permission_name' => 'venues.create',
        ]);
        $this->assertDatabaseHas('permissions', [
            'id' => $permissionId,
            'name' => 'venues.index',
        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'permission_id' => $permissionId,
            'role_id' => $role->id,
        ]);
        $this->assertDatabaseHas('routes', [
            'route' => 'venues.index',
            'permission_name' => 'venues.index',
        ]);
    }
}
