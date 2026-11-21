<?php

namespace Tests\Feature;

use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
