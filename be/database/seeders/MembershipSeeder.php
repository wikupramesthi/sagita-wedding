<?php

namespace Database\Seeders;

use App\Models\ManagementAccess\MenuGroup;
use App\Models\ManagementAccess\MenuItem;
use App\Models\ManagementAccess\Route;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MembershipSeeder extends Seeder
{
    /**
     * Seed permissions, menu, and route guard for the
     * Memberships (members + class bookings) module.
     * Also registers manual order verification routes.
     * Idempotent: safe to re-run.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminPermissions = [
            'memberships.index',
            'memberships.show',
            'orders.approve',
            'orders.reject',
            'orders.proof',
            'orders.bulk',
            'orders.exportCsv',
            'orders.audit',
            'orders.whatsapp',
            'orders.assign',
            'orders.savedFilters',
            'orders.bulkUndo',
            'orders.quickView',
        ];

        $memberPermissions = [
            'memberships.index',
            'memberships.show',
            'orders.proof',
        ];

        foreach (array_unique(array_merge($adminPermissions, $memberPermissions)) as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $menuGroup = MenuGroup::firstOrCreate(
            ['permission_name' => 'menu.memberships'],
            [
                'name' => 'Memberships',
                'icon' => 'bx-id-card',
                'status' => true,
                'position' => 55,
            ]
        );

        $items = [
            ['name' => 'All Members', 'route' => 'memberships.index', 'permission_name' => 'memberships.index', 'position' => 1],
        ];

        foreach ($items as $item) {
            MenuItem::firstOrCreate(
                [
                    'route' => $item['route'],
                    'menu_group_id' => $menuGroup->id,
                ],
                [
                    'name' => $item['name'],
                    'status' => true,
                    'permission_name' => $item['permission_name'],
                    'position' => $item['position'],
                ]
            );
        }

        $routes = [
            'memberships.index' => 'memberships.index',
            'memberships.show' => 'memberships.show',
            'orders.approve' => 'orders.approve',
            'orders.reject' => 'orders.reject',
            'orders.proof' => 'orders.proof',
            'orders.proof.show' => 'orders.proof',
            'orders.bulk' => 'orders.bulk',
            'orders.exportCsv' => 'orders.exportCsv',
            'orders.report' => 'orders.exportCsv',
            'orders.exportPdf' => 'orders.exportCsv',
            'orders.audit' => 'orders.audit',
            'orders.whatsapp' => 'orders.whatsapp',
            'orders.saved-filters' => 'orders.savedFilters',
            'orders.saved-filters.store' => 'orders.savedFilters',
            'orders.saved-filters.apply' => 'orders.savedFilters',
            'orders.saved-filters.default' => 'orders.savedFilters',
            'orders.saved-filters.destroy' => 'orders.savedFilters',
            'orders.bulk-undo' => 'orders.bulkUndo',
            'orders.claim' => 'orders.assign',
            'orders.quick-view' => 'orders.quickView',
        ];

        foreach ($routes as $route => $permission) {
            Route::firstOrCreate(
                ['route' => $route],
                ['permission_name' => $permission, 'status' => true]
            );
        }

        $adminRoles = Role::whereIn('name', ['super-admin', 'admin'])->get();
        foreach ($adminRoles as $role) {
            $role->givePermissionTo($adminPermissions);
        }

        $memberRoles = Role::whereIn('name', ['user'])->get();
        foreach ($memberRoles as $role) {
            $role->givePermissionTo($memberPermissions);
        }
    }
}
