<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\VenueController;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class VenueModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_venue_module_schema_and_routes_exist(): void
    {
        $this->assertTrue(Schema::hasTable('venues'));
        $this->assertFalse(Schema::hasTable('studios'));
        $this->assertTrue(Route::has('venues.index'));
        $this->assertFalse(Route::has('studios.index'));

        $venue = Venue::create([
            'uuid' => 'd6d4fe8f-1695-47cb-8b05-30b1fb39e32e',
            'name' => 'Downtown Venue',
            'slug' => 'downtown-venue',
            'address' => '1 Main Street',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('venues', ['uuid' => $venue->uuid, 'name' => 'Downtown Venue']);
    }

    public function test_venue_index_controller_no_longer_loads_removed_schedule_relationship(): void
    {
        Venue::create([
            'uuid' => '7dc201a5-1ae7-4f85-aaf3-e1f8a2b8b7d1',
            'name' => 'Test Venue',
            'slug' => 'test-venue',
            'address' => 'Test Address',
            'status' => 'active',
        ]);

        $response = app(VenueController::class)->index(Request::create('/backend/venues', 'GET'));

        $this->assertSame('pages.venues.index', $response->name());
        $this->assertFalse(method_exists(Venue::class, 'schedules'));
    }
}
