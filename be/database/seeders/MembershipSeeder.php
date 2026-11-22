<?php

namespace Database\Seeders;

use App\Models\ManagementAccess\MenuGroup;
use App\Models\ManagementAccess\MenuItem;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

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

        $menuGroup = MenuGroup::firstOrCreate(
            ['permission_name' => 'menu.memberships'],
            [
                'name' => 'Memberships',
                'icon' => 'bx-id-card',
                'status' => true,
                'position' => 55,
            ]
        );

        MenuItem::firstOrCreate(
            ['route' => 'memberships.index', 'menu_group_id' => $menuGroup->id],
            [
                'name' => 'All Members',
                'status' => true,
                'permission_name' => 'memberships.index',
                'position' => 1,
            ]
        );

        foreach (Role::whereIn('name', ['super-admin', 'admin'])->get() as $role) {
            $role->givePermissionTo($adminPermissions);
        }

        foreach (Role::whereIn('name', ['user'])->get() as $role) {
            $role->givePermissionTo($memberPermissions);
        }
    }
}
