<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'menu.main-menu',
            'menu.role-permission',
            'menu.access-management',
            'dashboard.index',
            'konsultasi.index',
            'konsultasi.bulk',
            'konsultasi.delete',
            'konsultasi.export',
            'konsultasi.import',
            'konsultasi.merge',
            'user.index',
            'user.store',
            'user.update',
            'user.destroy',
            'menu-group.index',
            'menu-group.store',
            'menu-group.update',
            'menu-group.destroy',
            'menu-item.index',
            'menu-item.store',
            'menu-item.update',
            'menu-item.destroy',
            'route.index',
            'route.store',
            'route.update',
            'route.destroy',
            'role.index',
            'role.store',
            'role.update',
            'role.destroy',
            'permission.index',
            'permission.store',
            'permission.update',
            'permission.destroy',
            'package.promo.index',
            'package.promo.store',
            'package.promo.update',
            'package.promo.destroy',
            'filedownload.store',
            'filedownload.update',
            'filedownload.destroy',
            'account.index',
            'account.update',
            'articles.create',
            'articles.destroy',
            'articles.index',
            'articles.store',
            'articles.update',
            'audit-log.destroy',
            'audit-log.index',
            'banner.destroy',
            'banner.index',
            'banner.store',
            'banner.update',
            'categories.destroy',
            'categories.index',
            'categories.store',
            'categories.update',
            'checkout.package',
            'dashboard.submitSumber',
            'email-setting.index',
            'email-setting.test',
            'email-setting.update',
            'events.destroy',
            'events.index',
            'events.store',
            'events.update',
            'failed-login.destroy',
            'failed-login.index',
            'faq.destroy',
            'faq.index',
            'faq.store',
            'faq.update',
            'filedownload.index',
            'login-activity.destroy',
            'login-activity.index',
            'login-lockout.destroy',
            'login-lockout.index',
            'memberships.index',
            'memberships.show',
            'menu.keamanan',
            'orders.approve',
            'orders.assign',
            'orders.audit',
            'orders.bulk',
            'orders.bulkUndo',
            'orders.exportCsv',
            'orders.index',
            'orders.proof',
            'orders.quickView',
            'orders.reject',
            'orders.savedFilters',
            'orders.show',
            'orders.store',
            'orders.whatsapp',
            'package.destroy',
            'package.index',
            'package.member',
            'package.store',
            'package.update',
            'pages.create',
            'pages.destroy',
            'pages.index',
            'pages.store',
            'pages.update',
            'pengguna.export',
            'pengguna.index',
            'testimonial.destroy',
            'testimonial.index',
            'testimonial.store',
            'testimonial.update',
            'vendor.destroy',
            'vendor.index',
            'vendor.mobile',
            'vendor.store',
            'vendor.update',
            'vendors_categories.destroy',
            'vendors_categories.index',
            'vendors_categories.store',
            'vendors_categories.update',
            'venues.create',
            'venues.destroy',
            'venues.index',
            'venues.store',
            'venues.update',
            'website-identity.index',
            'website-identity.update',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission], ['guard_name' => 'web']);
        }

        $stale = [
            'company.',
            'portofolio.',
            'menu.main-portofolio',
            'poll.',
            'polls.',
            'dashboard.form',
        ];

        $staleIds = Permission::query()
            ->where(function ($query) use ($stale) {
                foreach ($stale as $name) {
                    $query->orWhere('name', '=', $name)
                        ->orWhere('name', 'like', $name.'%');
                }
            })
            ->pluck('id');

        if ($staleIds->isNotEmpty()) {
            DB::table('role_has_permissions')->whereIn('permission_id', $staleIds)->delete();
            DB::table('model_has_permissions')->whereIn('permission_id', $staleIds)->delete();
            Permission::query()->whereIn('id', $staleIds)->delete();
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
