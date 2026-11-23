<?php

namespace Database\Seeders;

use App\Models\ManagementAccess\MenuGroup;
use App\Models\ManagementAccess\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminWeddingOpsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $newPermissions = [
            'orders.create',
            'orders.verification',
            'orders.checklist.manage',
            'orders.venue-booking.update',
            'orders.timeline',
        ];

        foreach ($newPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission], ['guard_name' => 'web']);
        }

        $venuePermissions = [
            'venues.index',
            'venues.create',
            'venues.store',
            'venues.update',
            'venues.destroy',
        ];

        foreach (Role::where('name', 'admin')->get() as $role) {
            $role->givePermissionTo($venuePermissions);
        }

        foreach (Role::whereIn('name', ['super-admin', 'admin'])->get() as $role) {
            $role->givePermissionTo($newPermissions);
        }

        MenuGroup::where('permission_name', 'orders.index')->update(['name' => 'Pesanan & Pembayaran']);
        MenuGroup::where('permission_name', 'memberships.index')->update(['name' => 'Paket Member']);
        MenuGroup::where('permission_name', 'venues.index')->update(['name' => 'Vendor & Mitra']);

        $ordersGroupId = MenuGroup::where('permission_name', 'orders.index')->value('id');
        MenuItem::updateOrCreate(
            ['menu_group_id' => $ordersGroupId, 'route' => 'orders.verification'],
            [
                'name' => 'Verifikasi Pembayaran',
                'status' => 1,
                'permission_name' => 'orders.verification',
                'position' => 1,
            ]
        );

        $consultationGroupId = MenuGroup::where('permission_name', 'konsultasi.index')->value('id');
        MenuItem::updateOrCreate(
            ['menu_group_id' => $consultationGroupId, 'route' => 'orders.create'],
            [
                'name' => 'Buat Pesanan dari Lead',
                'status' => 1,
                'permission_name' => 'orders.create',
                'position' => 3,
            ]
        );

        $staleRoutes = [
            'company.index',
            'company.create',
            'company.store',
            'company.update',
            'company.destroy',
            'portofolio.index',
            'portofolio.create',
            'portofolio.store',
            'portofolio.update',
            'portofolio.destroy',
            'poll.index',
            'poll.store',
            'poll.update',
            'poll.destroy',
            'polls.index',
            'dashboard.form',
        ];

        MenuItem::whereIn('route', $staleRoutes)->delete();
        MenuItem::whereIn('permission_name', $staleRoutes)->delete();

        $stalePermissions = [
            'company.',
            'portofolio.',
            'dashboard.form',
        ];

        $staleIds = Permission::query()
            ->where(function ($query) use ($stalePermissions) {
                foreach ($stalePermissions as $name) {
                    $query->orWhere('name', '=', $name)
                        ->orWhere('name', 'like', $name.'%');
                }
            })
            ->pluck('id');

        if ($staleIds->isNotEmpty()) {
            DB::table('role_has_permissions')->whereIn('permission_id', $staleIds)->delete();
            DB::table('model_has_permissions')->whereIn('permission_id', $staleIds)->delete();
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
