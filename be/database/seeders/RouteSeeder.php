<?php

namespace Database\Seeders;

use App\Models\ManagementAccess\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        // Dashboard
        $this->upsertRoutes([
            ['route' => 'dashboard.index', 'permission_name' => 'dashboard.index'],
            ['route' => 'dashboard.refresh', 'permission_name' => 'dashboard.index'],
            ['route' => 'dashboard.export', 'permission_name' => 'dashboard.index'],
            ['route' => 'dashboard.export-pdf', 'permission_name' => 'dashboard.index'],
        ]);

        // General Setting
        // $this->upsertRoutes([
        //     [
        //         'route' => 'setting.index',
        //         'permission_name' => 'setting_index',
        //     ],
        //     [
        //         'route' => 'setting.update',
        //         'permission_name' => 'setting_update',
        //     ],
        // ]);

        // Konsultasi
        $this->upsertRoutes([
            ['route' => 'konsultasi.index', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.create', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.store', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.show', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.edit', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.update', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.destroy', 'permission_name' => 'konsultasi.delete'],
            ['route' => 'konsultasi.update-lead', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.pdf-preview', 'permission_name' => 'konsultasi.export'],
            ['route' => 'konsultasi.pdf-stream', 'permission_name' => 'konsultasi.export'],
            ['route' => 'konsultasi.pdf-download', 'permission_name' => 'konsultasi.export'],
            ['route' => 'konsultasi.export', 'permission_name' => 'konsultasi.export'],
            ['route' => 'konsultasi.kanban', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.calendar', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.duplicates', 'permission_name' => 'konsultasi.merge'],
            ['route' => 'konsultasi.bulk', 'permission_name' => 'konsultasi.bulk'],
            ['route' => 'konsultasi.bulk-undo', 'permission_name' => 'konsultasi.bulk'],
            ['route' => 'konsultasi.merge', 'permission_name' => 'konsultasi.merge'],
            ['route' => 'konsultasi.remind', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.reorder', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.import', 'permission_name' => 'konsultasi.import'],
            ['route' => 'konsultasi.import.template', 'permission_name' => 'konsultasi.import'],
            ['route' => 'konsultasi.import.preview', 'permission_name' => 'konsultasi.import'],
            ['route' => 'konsultasi.import.commit', 'permission_name' => 'konsultasi.import'],
            ['route' => 'konsultasi.saved-filters', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.saved-filters.store', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.saved-filters.apply', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.saved-filters.default', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.saved-filters.destroy', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.whatsapp', 'permission_name' => 'konsultasi.index'],
            ['route' => 'konsultasi.calendar-ics', 'permission_name' => 'konsultasi.index'],
        ]);

        // User Management
        $this->upsertRoutes([
            [
                'route' => 'user.index',
                'permission_name' => 'user.index',
            ],
            [
                'route' => 'user.store',
                'permission_name' => 'user.store',
            ],
            [
                'route' => 'user.update',
                'permission_name' => 'user.update',
            ],
            [
                'route' => 'user.destroy',
                'permission_name' => 'user.destroy',
            ],
        ]);

        // User Profile
        // $this->upsertRoutes([
        //     [
        //         'route' => 'profile.edit',
        //         'permission_name' => 'profile.edit',
        //     ],
        // ]);

        // Menu Group Management
        $this->upsertRoutes([
            [
                'route' => 'menu.index',
                'permission_name' => 'menu-group.index',
            ],
            [
                'route' => 'menu.store',
                'permission_name' => 'menu-group.store',
            ],
            [
                'route' => 'menu.update',
                'permission_name' => 'menu-group.update',
            ],
            [
                'route' => 'menu.destroy',
                'permission_name' => 'menu-group.destroy',
            ],
        ]);

        // Menu Item Management
        $this->upsertRoutes([
            [
                'route' => 'menu.item.index',
                'permission_name' => 'menu-item.index',
            ],
            [
                'route' => 'menu.item.store',
                'permission_name' => 'menu-item.store',
            ],
            [
                'route' => 'menu.item.update',
                'permission_name' => 'menu-item.update',
            ],
            [
                'route' => 'menu.item.destroy',
                'permission_name' => 'menu-item.destroy',
            ],
        ]);

        // Route Management
        $this->upsertRoutes([
            [
                'route' => 'route.index',
                'permission_name' => 'route.index',
            ],
            [
                'route' => 'route.store',
                'permission_name' => 'route.store',
            ],
            [
                'route' => 'route.update',
                'permission_name' => 'route.update',
            ],
            [
                'route' => 'route.destroy',
                'permission_name' => 'route.destroy',
            ],
        ]);

        // Role Management
        $this->upsertRoutes([
            [
                'route' => 'role.index',
                'permission_name' => 'role.index',
            ],
            [
                'route' => 'role.store',
                'permission_name' => 'role.store',
            ],
            [
                'route' => 'role.update',
                'permission_name' => 'role.update',
            ],
            [
                'route' => 'role.destroy',
                'permission_name' => 'role.destroy',
            ],
        ]);

        // Permission Management
        $this->upsertRoutes([
            [
                'route' => 'permission.index',
                'permission_name' => 'permission.index',
            ],
            [
                'route' => 'permission.store',
                'permission_name' => 'permission.store',
            ],
            [
                'route' => 'permission.update',
                'permission_name' => 'permission.update',
            ],
            [
                'route' => 'permission.destroy',
                'permission_name' => 'permission.destroy',
            ],
        ]);

        // Email Setting
        $this->upsertRoutes([
            [
                'route' => 'email-setting.index',
                'permission_name' => 'email-setting.index',
            ],
            [
                'route' => 'email-setting.update',
                'permission_name' => 'email-setting.update',
            ],
            [
                'route' => 'email-setting.test',
                'permission_name' => 'email-setting.test',
            ],
        ]);
    }

    private function upsertRoutes(array $routes): void
    {
        foreach ($routes as $route) {
            Route::updateOrInsert(
                ['route' => $route['route']],
                [
                    'permission_name' => $route['permission_name'],
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
