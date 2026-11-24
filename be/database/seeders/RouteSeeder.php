<?php

namespace Database\Seeders;

use App\Models\ManagementAccess\Route;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        $staleRoutes = [
            'banner.storeVideo',
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
            'poll.create',
            'poll.store',
            'poll.update',
            'poll.destroy',
            'polls.index',
            'package.member',
            'account.create',
            'account.store',
            'account.show',
            'account.edit',
            'account.destroy',
            'categories.create',
            'categories.show',
            'categories.edit',
            'testimonial.create',
            'testimonial.show',
            'testimonial.edit',
            'faq.show',
            'faq.edit',
            'banner.show',
            'banner.edit',
            'pages.show',
            'events.edit',
            'vendor.show',
        ];

        Route::whereIn('route', $staleRoutes)->delete();

        $this->upsertRoutes([
            ['route' => 'dashboard.index', 'permission_name' => 'dashboard.index'],
            ['route' => 'dashboard.refresh', 'permission_name' => 'dashboard.index'],
            ['route' => 'dashboard.export', 'permission_name' => 'dashboard.index'],
            ['route' => 'dashboard.export-pdf', 'permission_name' => 'dashboard.index'],
            ['route' => 'dashboard.recommendations', 'permission_name' => 'dashboard.index'],
            ['route' => 'search', 'permission_name' => 'dashboard.index'],
            ['route' => 'dashboard.submitSumber', 'permission_name' => 'dashboard.submitSumber'],

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

            ['route' => 'user.index', 'permission_name' => 'user.index'],
            ['route' => 'user.store', 'permission_name' => 'user.store'],
            ['route' => 'user.update', 'permission_name' => 'user.update'],
            ['route' => 'user.destroy', 'permission_name' => 'user.destroy'],

            ['route' => 'account.index', 'permission_name' => 'account.index'],
            ['route' => 'account.update', 'permission_name' => 'account.update'],

            ['route' => 'menu.index', 'permission_name' => 'menu-group.index'],
            ['route' => 'menu.store', 'permission_name' => 'menu-group.store'],
            ['route' => 'menu.update', 'permission_name' => 'menu-group.update'],
            ['route' => 'menu.destroy', 'permission_name' => 'menu-group.destroy'],
            ['route' => 'menu.item.index', 'permission_name' => 'menu-item.index'],
            ['route' => 'menu.item.store', 'permission_name' => 'menu-item.store'],
            ['route' => 'menu.item.update', 'permission_name' => 'menu-item.update'],
            ['route' => 'menu.item.destroy', 'permission_name' => 'menu-item.destroy'],

            ['route' => 'route.index', 'permission_name' => 'route.index'],
            ['route' => 'route.store', 'permission_name' => 'route.store'],
            ['route' => 'route.update', 'permission_name' => 'route.update'],
            ['route' => 'route.destroy', 'permission_name' => 'route.destroy'],

            ['route' => 'role.index', 'permission_name' => 'role.index'],
            ['route' => 'role.store', 'permission_name' => 'role.store'],
            ['route' => 'role.update', 'permission_name' => 'role.update'],
            ['route' => 'role.destroy', 'permission_name' => 'role.destroy'],

            ['route' => 'permission.index', 'permission_name' => 'permission.index'],
            ['route' => 'permission.store', 'permission_name' => 'permission.store'],
            ['route' => 'permission.update', 'permission_name' => 'permission.update'],
            ['route' => 'permission.destroy', 'permission_name' => 'permission.destroy'],

            ['route' => 'faq.index', 'permission_name' => 'faq.index'],
            ['route' => 'faq.create', 'permission_name' => 'faq.store'],
            ['route' => 'faq.store', 'permission_name' => 'faq.store'],
            ['route' => 'faq.update', 'permission_name' => 'faq.update'],
            ['route' => 'faq.destroy', 'permission_name' => 'faq.destroy'],

            ['route' => 'categories.index', 'permission_name' => 'categories.index'],
            ['route' => 'categories.store', 'permission_name' => 'categories.store'],
            ['route' => 'categories.update', 'permission_name' => 'categories.update'],
            ['route' => 'categories.destroy', 'permission_name' => 'categories.destroy'],

            ['route' => 'banner.index', 'permission_name' => 'banner.index'],
            ['route' => 'banner.create', 'permission_name' => 'banner.store'],
            ['route' => 'banner.store', 'permission_name' => 'banner.store'],
            ['route' => 'banner.update', 'permission_name' => 'banner.update'],
            ['route' => 'banner.destroy', 'permission_name' => 'banner.destroy'],
            ['route' => 'banner.loadMore', 'permission_name' => 'banner.index'],
            ['route' => 'banner.fotoPicker', 'permission_name' => 'banner.index'],
            ['route' => 'banner.albumFotos', 'permission_name' => 'banner.index'],
            ['route' => 'banner.modal', 'permission_name' => 'banner.index'],
            ['route' => 'banner.storeAlbum', 'permission_name' => 'banner.store'],
            ['route' => 'banner.updateAlbum', 'permission_name' => 'banner.update'],
            ['route' => 'banner.destroyAlbum', 'permission_name' => 'banner.destroy'],

            ['route' => 'articles.index', 'permission_name' => 'articles.index'],
            ['route' => 'articles.create', 'permission_name' => 'articles.create'],
            ['route' => 'articles.store', 'permission_name' => 'articles.store'],
            ['route' => 'articles.edit', 'permission_name' => 'articles.update'],
            ['route' => 'articles.update', 'permission_name' => 'articles.update'],
            ['route' => 'articles.destroy', 'permission_name' => 'articles.destroy'],
            ['route' => 'articles.bulkDestroy', 'permission_name' => 'articles.destroy'],

            ['route' => 'filedownload.index', 'permission_name' => 'filedownload.index'],
            ['route' => 'filedownload.store', 'permission_name' => 'filedownload.store'],
            ['route' => 'filedownload.update', 'permission_name' => 'filedownload.update'],
            ['route' => 'filedownload.destroy', 'permission_name' => 'filedownload.destroy'],
            ['route' => 'filedownload.download', 'permission_name' => 'filedownload.index'],

            ['route' => 'vendors_categories.index', 'permission_name' => 'vendors_categories.index'],
            ['route' => 'vendors_categories.store', 'permission_name' => 'vendors_categories.store'],
            ['route' => 'vendors_categories.update', 'permission_name' => 'vendors_categories.update'],
            ['route' => 'vendors_categories.destroy', 'permission_name' => 'vendors_categories.destroy'],

            ['route' => 'packages.index', 'permission_name' => 'package.index'],
            ['route' => 'packages.create', 'permission_name' => 'package.store'],
            ['route' => 'packages.store', 'permission_name' => 'package.store'],
            ['route' => 'packages.edit', 'permission_name' => 'package.update'],
            ['route' => 'packages.update', 'permission_name' => 'package.update'],
            ['route' => 'packages.show', 'permission_name' => 'package.index'],
            ['route' => 'packages.destroy', 'permission_name' => 'package.destroy'],
            ['route' => 'packages.duplicate', 'permission_name' => 'package.store'],
            ['route' => 'packages.bulk', 'permission_name' => 'package.update'],
            ['route' => 'packages.export', 'permission_name' => 'package.index'],
            ['route' => 'packages.preview', 'permission_name' => 'package.index'],
            ['route' => 'packages.reorder', 'permission_name' => 'package.update'],
            ['route' => 'packages.toggle-status', 'permission_name' => 'package.update'],
            ['route' => 'packages.member', 'permission_name' => 'package.member'],
            ['route' => 'packages.quotation', 'permission_name' => 'package.member'],
            ['route' => 'packages.quotation.preview', 'permission_name' => 'package.member'],
            ['route' => 'packages.quotation.download', 'permission_name' => 'package.member'],
            ['route' => 'packages.compare.index', 'permission_name' => 'package.member'],
            ['route' => 'packages.compare.add', 'permission_name' => 'package.member'],
            ['route' => 'packages.compare.remove', 'permission_name' => 'package.member'],
            ['route' => 'packages.compare.clear', 'permission_name' => 'package.member'],
            ['route' => 'packages.wishlist.index', 'permission_name' => 'package.member'],
            ['route' => 'packages.wishlist.toggle', 'permission_name' => 'package.member'],
            ['route' => 'package-promos.index', 'permission_name' => 'package.promo.index'],
            ['route' => 'package-promos.store', 'permission_name' => 'package.promo.store'],
            ['route' => 'package-promos.update', 'permission_name' => 'package.promo.update'],
            ['route' => 'package-promos.destroy', 'permission_name' => 'package.promo.destroy'],

            ['route' => 'pages.index', 'permission_name' => 'pages.index'],
            ['route' => 'pages.create', 'permission_name' => 'pages.create'],
            ['route' => 'pages.store', 'permission_name' => 'pages.store'],
            ['route' => 'pages.edit', 'permission_name' => 'pages.update'],
            ['route' => 'pages.update', 'permission_name' => 'pages.update'],
            ['route' => 'pages.updateSidebar', 'permission_name' => 'pages.update'],
            ['route' => 'pages.destroy', 'permission_name' => 'pages.destroy'],

            ['route' => 'testimonial.index', 'permission_name' => 'testimonial.index'],
            ['route' => 'testimonial.store', 'permission_name' => 'testimonial.store'],
            ['route' => 'testimonial.update', 'permission_name' => 'testimonial.update'],
            ['route' => 'testimonial.destroy', 'permission_name' => 'testimonial.destroy'],

            ['route' => 'events.index', 'permission_name' => 'events.index'],
            ['route' => 'events.create', 'permission_name' => 'events.store'],
            ['route' => 'events.store', 'permission_name' => 'events.store'],
            ['route' => 'events.show', 'permission_name' => 'events.index'],
            ['route' => 'events.update', 'permission_name' => 'events.update'],
            ['route' => 'events.destroy', 'permission_name' => 'events.destroy'],

            ['route' => 'venues.index', 'permission_name' => 'venues.index'],
            ['route' => 'venues.create', 'permission_name' => 'venues.create'],
            ['route' => 'venues.store', 'permission_name' => 'venues.store'],
            ['route' => 'venues.edit', 'permission_name' => 'venues.update'],
            ['route' => 'venues.update', 'permission_name' => 'venues.update'],
            ['route' => 'venues.destroy', 'permission_name' => 'venues.destroy'],

            ['route' => 'vendor.index', 'permission_name' => 'vendor.index'],
            ['route' => 'vendor.create', 'permission_name' => 'vendor.store'],
            ['route' => 'vendor.store', 'permission_name' => 'vendor.store'],
            ['route' => 'vendor.edit', 'permission_name' => 'vendor.update'],
            ['route' => 'vendor.update', 'permission_name' => 'vendor.update'],
            ['route' => 'vendor.destroy', 'permission_name' => 'vendor.destroy'],
            ['route' => 'vendor.archive', 'permission_name' => 'vendor.index'],
            ['route' => 'vendor.restore', 'permission_name' => 'vendor.update'],
            ['route' => 'vendor.mobile', 'permission_name' => 'vendor.mobile'],

            ['route' => 'checkout.package', 'permission_name' => 'checkout.package'],
            ['route' => 'pengguna.index', 'permission_name' => 'pengguna.index'],
            ['route' => 'pengguna.export', 'permission_name' => 'pengguna.export'],
            ['route' => 'pengguna.update-status', 'permission_name' => 'pengguna.index'],

            ['route' => 'memberships.index', 'permission_name' => 'memberships.index'],
            ['route' => 'memberships.show', 'permission_name' => 'memberships.show'],
            ['route' => 'memberships.export', 'permission_name' => 'memberships.index'],

            ['route' => 'orders.index', 'permission_name' => 'orders.index'],
            ['route' => 'orders.store', 'permission_name' => 'orders.store'],
            ['route' => 'orders.create', 'permission_name' => 'orders.create'],
            ['route' => 'orders.timeline', 'permission_name' => 'orders.timeline'],
            ['route' => 'orders.verification', 'permission_name' => 'orders.verification'],
            ['route' => 'orders.venue-booking.update', 'permission_name' => 'orders.venue-booking.update'],
            ['route' => 'orders.show', 'permission_name' => 'orders.show'],
            ['route' => 'orders.analytics', 'permission_name' => 'orders.index'],
            ['route' => 'orders.change-requests.index', 'permission_name' => 'orders.index'],
            ['route' => 'orders.change-requests.review', 'permission_name' => 'orders.index'],
            ['route' => 'orders.change-requests.store', 'permission_name' => 'orders.store'],
            ['route' => 'orders.checklist.update', 'permission_name' => 'orders.show'],
            ['route' => 'orders.checklist.store', 'permission_name' => 'orders.checklist.manage'],
            ['route' => 'orders.checklist.destroy', 'permission_name' => 'orders.checklist.manage'],
            ['route' => 'orders.documents.preview', 'permission_name' => 'orders.show'],
            ['route' => 'orders.documents.download', 'permission_name' => 'orders.show'],
            ['route' => 'orders.workflow-settings.edit', 'permission_name' => 'orders.index'],
            ['route' => 'orders.workflow-settings.update', 'permission_name' => 'orders.index'],
            ['route' => 'orders.approve', 'permission_name' => 'orders.approve'],
            ['route' => 'orders.reject', 'permission_name' => 'orders.reject'],
            ['route' => 'orders.proof', 'permission_name' => 'orders.proof'],
            ['route' => 'orders.proof.show', 'permission_name' => 'orders.proof'],
            ['route' => 'orders.bulk', 'permission_name' => 'orders.bulk'],
            ['route' => 'orders.bulk-undo', 'permission_name' => 'orders.bulkUndo'],
            ['route' => 'orders.claim', 'permission_name' => 'orders.assign'],
            ['route' => 'orders.quick-view', 'permission_name' => 'orders.quickView'],
            ['route' => 'orders.exportCsv', 'permission_name' => 'orders.exportCsv'],
            ['route' => 'orders.exportPdf', 'permission_name' => 'orders.exportCsv'],
            ['route' => 'orders.report', 'permission_name' => 'orders.exportCsv'],
            ['route' => 'orders.audit', 'permission_name' => 'orders.audit'],
            ['route' => 'orders.whatsapp', 'permission_name' => 'orders.whatsapp'],
            ['route' => 'orders.saved-filters', 'permission_name' => 'orders.savedFilters'],
            ['route' => 'orders.saved-filters.store', 'permission_name' => 'orders.savedFilters'],
            ['route' => 'orders.saved-filters.apply', 'permission_name' => 'orders.savedFilters'],
            ['route' => 'orders.saved-filters.default', 'permission_name' => 'orders.savedFilters'],
            ['route' => 'orders.saved-filters.destroy', 'permission_name' => 'orders.savedFilters'],
            ['route' => 'orders.saved-filters.pin', 'permission_name' => 'orders.savedFilters'],
            ['route' => 'orders.saved-filters.move', 'permission_name' => 'orders.savedFilters'],
            ['route' => 'orders.remind-sla', 'permission_name' => 'orders.audit'],
            ['route' => 'orders.activity', 'permission_name' => 'orders.audit'],
            ['route' => 'orders.reorder', 'permission_name' => 'orders.index'],
            ['route' => 'orders.payments.store', 'permission_name' => 'orders.store'],
            ['route' => 'orders.payments.approve', 'permission_name' => 'orders.approve'],
            ['route' => 'orders.payments.reject', 'permission_name' => 'orders.reject'],
            ['route' => 'orders.payments.proof', 'permission_name' => 'orders.proof'],
            ['route' => 'orders.vendors.assign', 'permission_name' => 'orders.assign'],
            ['route' => 'orders.vendors.destroy', 'permission_name' => 'orders.assign'],
            ['route' => 'orders.vendors.status', 'permission_name' => 'orders.assign'],

            ['route' => 'security.login-activity.index', 'permission_name' => 'login-activity.index'],
            ['route' => 'security.login-activity.bulkDestroy', 'permission_name' => 'login-activity.destroy'],
            ['route' => 'security.login-activity.clear', 'permission_name' => 'login-activity.destroy'],
            ['route' => 'security.login-activity.destroy', 'permission_name' => 'login-activity.destroy'],
            ['route' => 'security.login-lockout.index', 'permission_name' => 'login-lockout.index'],
            ['route' => 'security.login-lockout.bulkDestroy', 'permission_name' => 'login-lockout.destroy'],
            ['route' => 'security.login-lockout.destroy', 'permission_name' => 'login-lockout.destroy'],
            ['route' => 'security.audit-log.index', 'permission_name' => 'audit-log.index'],
            ['route' => 'security.audit-log.exportPdf', 'permission_name' => 'audit-log.index'],
            ['route' => 'security.audit-log.bulkDestroy', 'permission_name' => 'audit-log.destroy'],
            ['route' => 'security.audit-log.clear', 'permission_name' => 'audit-log.destroy'],
            ['route' => 'security.audit-log.destroy', 'permission_name' => 'audit-log.destroy'],
            ['route' => 'security.failed-login.index', 'permission_name' => 'failed-login.index'],
            ['route' => 'security.failed-login.bulkDestroy', 'permission_name' => 'failed-login.destroy'],
            ['route' => 'security.failed-login.clear', 'permission_name' => 'failed-login.destroy'],
            ['route' => 'security.failed-login.destroy', 'permission_name' => 'failed-login.destroy'],

            ['route' => 'website-identity.index', 'permission_name' => 'website-identity.index'],
            ['route' => 'website-identity.update', 'permission_name' => 'website-identity.update'],

            ['route' => 'email-setting.index', 'permission_name' => 'email-setting.index'],
            ['route' => 'email-setting.update', 'permission_name' => 'email-setting.update'],
        ]);

        $this->deduplicateRoutes();
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

    private function deduplicateRoutes(): void
    {
        $seen = [];

        foreach (DB::table('routes')->select('id', 'route')->orderBy('id')->get() as $route) {
            if (isset($seen[$route->route])) {
                DB::table('routes')->where('id', $route->id)->delete();

                continue;
            }

            $seen[$route->route] = true;
        }
    }
}
