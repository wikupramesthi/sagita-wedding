<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VendorsCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VendorsCategoryModuleTest extends TestCase
{
    use RefreshDatabase;

    private const API_KEY = 'test-frontend-key';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.frontend_api.key' => self::API_KEY]);
    }

    public function test_vendors_category_schema_routes_and_instructor_api_use_new_names(): void
    {
        $this->assertTrue(Schema::hasTable('vendors_categories'));
        $this->assertFalse(Schema::hasTable('specializations'));
        $this->assertTrue(Schema::hasTable('user_vendors_category'));
        $this->assertFalse(Schema::hasTable('user_specialization'));
        $this->assertTrue(Schema::hasColumn('user_vendors_category', 'vendors_category_uuid'));
        $this->assertFalse(Schema::hasColumn('user_vendors_category', 'specialization_uuid'));
        $this->assertTrue(Route::has('vendors_categories.index'));
        $this->assertFalse(Route::has('specializations.index'));

        foreach ([
            'user_vendors_category_user_uuid_vendors_category_uuid_unique',
            'user_vendors_category_vendors_category_uuid_foreign',
            'user_vendors_category_user_uuid_foreign',
        ] as $index) {
            $this->assertTrue(
                Schema::hasIndex('user_vendors_category', $index) ||
                DB::table('information_schema.TABLE_CONSTRAINTS')
                    ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
                    ->where('TABLE_NAME', 'user_vendors_category')
                    ->where('CONSTRAINT_NAME', $index)
                    ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
                    ->exists(),
                "Missing index or constraint: {$index}"
            );
        }

        Role::create(['name' => 'instruktur', 'guard_name' => 'web']);

        $user = User::create([
            'name' => 'Vendor Instructor',
            'email' => 'vendor-instructor@example.com',
            'password' => 'password',
        ]);
        $user->assignRole('instruktur');

        $category = VendorsCategory::create([
            'name' => 'Wedding Vendor',
            'slug' => 'wedding-vendor',
            'description' => 'Wedding services',
        ]);
        $user->vendors_categories()->attach($category->uuid);

        $this->withHeader('X-Api-Key', self::API_KEY)
            ->getJson('/api/instructors')
            ->assertOk()
            ->assertJsonPath('data.0.vendors_categories.0.uuid', $category->uuid)
            ->assertJsonPath('data.0.vendors_categories.0.name', 'Wedding Vendor');
    }

    public function test_vendors_category_migration_updates_menu_and_permissions_without_losing_role_access(): void
    {
        $now = now();
        $groupId = DB::table('menu_groups')->insertGetId([
            'name' => 'Specialization',
            'status' => true,
            'permission_name' => 'specializations.index',
            'position' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('menu_items')->insert([
            'name' => 'All Specializations',
            'route' => 'specializations.index',
            'status' => true,
            'permission_name' => 'specializations.index',
            'menu_group_id' => $groupId,
            'position' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('menu_items')->insert([
            'name' => 'Add Specialization',
            'route' => 'specializations.create',
            'status' => true,
            'permission_name' => 'specializations.create',
            'menu_group_id' => $groupId,
            'position' => 2,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $permissionId = DB::table('permissions')->insertGetId([
            'name' => 'specializations.index',
            'guard_name' => 'web',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $role = Role::create(['name' => 'vendors-category-test-role', 'guard_name' => 'web']);
        DB::table('role_has_permissions')->insert([
            'permission_id' => $permissionId,
            'role_id' => $role->id,
        ]);
        DB::table('routes')->insert([
            'route' => 'specializations.index',
            'permission_name' => 'specializations.index',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $migration = require database_path('migrations/2026_11_19_070000_rename_specialization_module_to_vendors_categories.php');
        $migration->up();
        $migration->up();

        $this->assertDatabaseHas('menu_groups', [
            'id' => $groupId,
            'name' => 'Vendor Category',
            'permission_name' => 'vendors_categories.index',
        ]);
        $this->assertDatabaseHas('menu_items', [
            'menu_group_id' => $groupId,
            'name' => 'All Vendor Categories',
            'route' => 'vendors_categories.index',
            'permission_name' => 'vendors_categories.index',
        ]);
        $this->assertDatabaseHas('menu_items', [
            'menu_group_id' => $groupId,
            'name' => 'Add Vendor Category',
            'route' => 'vendors_categories.create',
            'permission_name' => 'vendors_categories.create',
        ]);
        $this->assertDatabaseHas('permissions', [
            'id' => $permissionId,
            'name' => 'vendors_categories.index',
        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'permission_id' => $permissionId,
            'role_id' => $role->id,
        ]);
        $this->assertDatabaseHas('routes', [
            'route' => 'vendors_categories.index',
            'permission_name' => 'vendors_categories.index',
        ]);
    }
}
