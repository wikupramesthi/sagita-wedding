<?php

namespace Database\Seeders;

use App\Models\ManagementAccess\MenuGroup;
use App\Models\ManagementAccess\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['name' => 'Role dan Akses', 'icon' => 'bx-shield-quarter', 'permission_name' => 'menu.role-permission', 'status' => 1, 'position' => 11],
            ['name' => 'Pengaturan Sistem', 'icon' => 'bx-cog', 'permission_name' => 'menu-item.index', 'status' => 1, 'position' => 12],
            ['name' => 'Riwayat Pemesanan', 'icon' => 'bx-receipt', 'permission_name' => 'orders.index', 'status' => 1, 'position' => 3],
            ['name' => 'Publikasi', 'icon' => 'bxs-file', 'permission_name' => 'articles.index', 'status' => 1, 'position' => 9],
            ['name' => 'Portfolio & Galeri', 'icon' => 'bx-camera', 'permission_name' => 'banner.index', 'status' => 1, 'position' => 5],
            ['name' => 'Master Data', 'icon' => 'bx-folder-open', 'permission_name' => 'testimonial.index', 'status' => 1, 'position' => 10],
            ['name' => 'Paket & Layanan', 'icon' => 'bx-package', 'permission_name' => 'package.member', 'status' => 1, 'position' => 1],
            ['name' => 'Vendor & Mitra', 'icon' => 'bx-camera-home', 'permission_name' => 'venues.index', 'status' => 1, 'position' => 6],
            ['name' => 'Keamanan', 'icon' => 'bx-shield-alt-2', 'permission_name' => 'menu.keamanan', 'status' => 1, 'position' => 13],
            ['name' => 'Pembayaran & Invoice', 'icon' => 'bx-id-card', 'permission_name' => 'memberships.index', 'status' => 1, 'position' => 4],
            ['name' => 'Data Prospek', 'icon' => 'bx-user', 'permission_name' => 'konsultasi.index', 'status' => 1, 'position' => 2],
        ];

        foreach ($groups as $group) {
            MenuGroup::updateOrCreate(['permission_name' => $group['permission_name']], $group);
        }

        $settingsGroupId = MenuGroup::where('permission_name', 'menu-item.index')->value('id');

        MenuItem::where('route', 'company.index')->update([
            'name' => 'Website Identity',
            'route' => 'website-identity.index',
            'permission_name' => 'website-identity.index',
            'menu_group_id' => $settingsGroupId,
        ]);

        MenuItem::where('name', 'Paket Wedding Organizer')->delete();

        $items = [
            'menu.role-permission' => [
                ['name' => 'Module Role', 'route' => 'permission.index', 'permission_name' => 'permission.index', 'position' => 1],
                ['name' => 'Master Role', 'route' => 'role.index', 'permission_name' => 'role.index', 'position' => 2],
            ],
            'menu-item.index' => [
                ['name' => 'Daftar Pengguna', 'route' => 'user.index', 'permission_name' => 'user.index', 'position' => 1],
                ['name' => 'Module Aplikasi', 'route' => 'route.index', 'permission_name' => 'route.index', 'position' => 2],
                ['name' => 'Menu Manager', 'route' => 'menu.index', 'permission_name' => 'menu-group.index', 'position' => 3],
                ['name' => 'Website Identity', 'route' => 'website-identity.index', 'permission_name' => 'website-identity.index', 'position' => 99],
                ['name' => 'Pengaturan Email', 'route' => 'email-setting.index', 'permission_name' => 'email-setting.index', 'position' => 1],
            ],
            'orders.index' => [
                ['name' => 'Pesanan Saya', 'route' => 'orders.index', 'permission_name' => 'orders.index', 'position' => 2],
            ],
            'articles.index' => [
                ['name' => 'Artikel', 'route' => 'articles.index', 'permission_name' => 'articles.index', 'position' => 1],
                ['name' => 'Acara', 'route' => 'events.index', 'permission_name' => 'events.index', 'position' => 2],
                ['name' => 'Kategori Artikel', 'route' => 'categories.index', 'permission_name' => 'categories.index', 'position' => 3],
            ],
            'banner.index' => [
                ['name' => 'Galeri Foto', 'route' => 'banner.index', 'permission_name' => 'banner.index', 'position' => 1],
            ],
            'testimonial.index' => [
                ['name' => 'Halaman Statis', 'route' => 'pages.index', 'permission_name' => 'pages.index', 'position' => 3],
                ['name' => 'Testimoni', 'route' => 'testimonial.index', 'permission_name' => 'testimonial.index', 'position' => 4],
                ['name' => 'Faq & Answer', 'route' => 'faq.index', 'permission_name' => 'faq.index', 'position' => 5],
            ],
            'package.member' => [
                ['name' => 'Data Paket', 'route' => 'packages.index', 'permission_name' => 'package.index', 'position' => 1],
                ['name' => 'Paket Organizer', 'route' => 'packages.member', 'permission_name' => 'package.member', 'position' => 2],
                ['name' => 'Promo', 'route' => 'package-promos.index', 'permission_name' => 'package.promo.index', 'position' => 3],
                ['name' => 'Permintaan Paket Saya', 'route' => 'package-offer-requests.mine', 'permission_name' => 'package.member', 'position' => 4],
                ['name' => 'Penawaran Saya', 'route' => 'package-offers.mine', 'permission_name' => 'package.member', 'position' => 5],
            ],
            'venues.index' => [
                ['name' => 'Data Venue', 'route' => 'venues.index', 'permission_name' => 'venues.index', 'position' => 1],
                ['name' => 'Data Vendor', 'route' => 'vendor.index', 'permission_name' => 'vendor.index', 'position' => 2],
                ['name' => 'Kategori Vendor', 'route' => 'vendors_categories.index', 'permission_name' => 'vendors_categories.index', 'position' => 3],
            ],
            'menu.keamanan' => [
                ['name' => 'Aktivitas Login', 'route' => 'security.login-activity.index', 'permission_name' => 'login-activity.index', 'position' => 1],
                ['name' => 'Log Audit', 'route' => 'security.audit-log.index', 'permission_name' => 'audit-log.index', 'position' => 2],
                ['name' => 'Login Gagal', 'route' => 'security.failed-login.index', 'permission_name' => 'failed-login.index', 'position' => 3],
                ['name' => 'Blokir Login', 'route' => 'security.login-lockout.index', 'permission_name' => 'login-lockout.index', 'position' => 4],
            ],
            'memberships.index' => [
                ['name' => 'Riwayat Pembayaran', 'route' => 'memberships.index', 'permission_name' => 'memberships.index', 'position' => 1],
            ],
            'konsultasi.index' => [
                ['name' => 'Konsultasi Masuk', 'route' => 'konsultasi.index', 'permission_name' => 'konsultasi.index', 'position' => 1],
                ['name' => 'Calon Pelanggan', 'route' => 'pengguna.index', 'permission_name' => 'pengguna.index', 'position' => 2],
            ],
        ];

        foreach ($items as $permissionName => $menuItems) {
            $groupId = MenuGroup::where('permission_name', $permissionName)->value('id');

            foreach ($menuItems as $item) {
                MenuItem::updateOrCreate(
                    ['menu_group_id' => $groupId, 'route' => $item['route']],
                    [
                        'name' => $item['name'],
                        'status' => 1,
                        'permission_name' => $item['permission_name'],
                        'position' => $item['position'],
                    ]
                );
            }
        }

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

        $groupIds = MenuGroup::query()->pluck('id');
        MenuItem::whereNotIn('menu_group_id', $groupIds)->delete();

        $seen = [];
        foreach (DB::table('menu_items')->select('id', 'route', 'menu_group_id')->orderBy('id')->get() as $item) {
            $key = $item->route.'|'.$item->menu_group_id;
            if (isset($seen[$key])) {
                DB::table('menu_items')->where('id', $item->id)->delete();

                continue;
            }

            $seen[$key] = true;
        }
    }
}
