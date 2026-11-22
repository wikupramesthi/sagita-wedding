<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CheckoutController;
use App\Http\Controllers\Admin\ConsultationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailSettingController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FileDownloadController;
use App\Http\Controllers\Admin\GlobalSearchController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderChangeRequestController;
use App\Http\Controllers\Admin\OrderDocumentController;
use App\Http\Controllers\Admin\OrderVendorAssignmentController;
use App\Http\Controllers\Admin\OrderWorkflowAnalyticsController;
use App\Http\Controllers\Admin\OrderWorkflowSettingsController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PackageInteractionController;
use App\Http\Controllers\Admin\PackagePromoController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\Admin\Security\AuditLogController;
use App\Http\Controllers\Admin\Security\FailedLoginController;
use App\Http\Controllers\Admin\Security\LoginActivityController;
use App\Http\Controllers\Admin\Security\LoginLockoutController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\VendorController;
// payment
use App\Http\Controllers\Admin\VendorsCategoryController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\WebsiteIdentityController;
use App\Http\Controllers\Admin\WeddingChecklistController;
use App\Http\Controllers\ConsultationFormController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManagementAccess\MenuGroupController;
use App\Http\Controllers\ManagementAccess\MenuItemController;
use App\Http\Controllers\ManagementAccess\PermissionController;
use App\Http\Controllers\ManagementAccess\RoleController;
use App\Http\Controllers\ManagementAccess\RouteController;
use App\Http\Controllers\ManagementAccess\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\InvalidateDashboardCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/formulir-konsultasi', [ConsultationFormController::class, 'create'])->name('consultation.form');
Route::post('/formulir-konsultasi', [ConsultationFormController::class, 'store'])
    ->middleware('throttle:api-contact')
    ->name('consultation.form.submit');

// Socialite Routes GOOGLE
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('googleAuth');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| Integrasi frontend publik (Express)
|--------------------------------------------------------------------------
| Frontend di port/domain lain meneruskan cookie session browser ke
| endpoint ini lewat server-to-server, jadi tidak ada CORS di sini.
| Tanpa middleware 'auth' supaya selalu balik JSON 401 — bukan redirect
| ke halaman login Blade yang tidak berguna bagi Express.
|
*/
Route::get('/auth/me', function (Request $request) {
    $user = $request->user();

    if (! $user) {
        return response()->json([
            'authenticated' => false,
            'user' => null,
        ], 401);
    }

    $avatar = $user->avatar;
    $avatarIsRemote = $avatar && Str::startsWith($avatar, ['http://', 'https://']);
    $avatarExists = $avatar && ! $avatarIsRemote && Storage::disk('public')->exists($avatar);

    return response()->json([
        'authenticated' => true,
        'user' => [
            'uuid' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $avatarIsRemote
                ? $avatar
                : ($avatarExists ? asset('storage/'.$avatar) : null),
            'roles' => $user->getRoleNames()->values()->all(),
        ],
    ]);
})->name('auth.me');

// Di-exempt dari CSRF di bootstrap/app.php (frontend tidak punya token).
Route::post('/auth/me/logout', function (Request $request) {
    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        'authenticated' => false,
        'user' => null,
    ]);
})->name('auth.me.logout');

Route::any('/backend/instruktur', fn () => redirect('/backend/vendor', 301)->withQueryString());
Route::any('/backend/instruktur/{path}', fn (string $path) => redirect('/backend/vendor/'.$path, 301)->withQueryString())
    ->where('path', '.*');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Keep-alive to reset the idle timer (called via JS when the user clicks "Stay signed in")
    Route::post('/keep-alive', function (Request $request) {
        $request->session()->put('last_activity', time());

        return response()->json(['ok' => true]);
    })->name('keep-alive');

    // Override tampilan member: /view/mobile atau /view/desktop.
    Route::get('/view/{mode}', function (string $mode) {
        if (in_array($mode, ['mobile', 'desktop'], true)) {
            session(['member_view' => $mode]);
        }

        return back();
    })->name('member-view');

    // Bahasa tampilan: /lang/id atau /lang/en.
    Route::get('/lang/{locale}', function (string $locale) {
        if (in_array($locale, ['id', 'en'], true)) {
            session(['locale' => $locale]);
        }

        return back();
    })->name('locale');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
});

Route::group(['middleware' => ['web', 'auth', 'verified', InvalidateDashboardCache::class], 'prefix' => 'backend'], function () {
    $superAdmin = 'role:super-admin';
    // $user = 'role:user';
    Route::post('/dashboard/sumber-informasi', [DashboardController::class, 'submitSumber'])->name('dashboard.submitSumber');
    Route::get('/dashboard/package-recommendations', [DashboardController::class, 'recommendations'])->name('dashboard.recommendations');
    Route::get('/dashboard/refresh', [DashboardController::class, 'refresh'])->name('dashboard.refresh');
    Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');
    Route::get('/dashboard/export-pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export-pdf');
    // Cross-module global search for the command palette (Ctrl+K)
    Route::get('/search', [GlobalSearchController::class, 'index'])->name('search');
    Route::resource('dashboard', DashboardController::class)->only('index');
    Route::resource('user', UserController::class)->middleware($superAdmin)->only('index', 'store', 'update', 'destroy');
    Route::resource('route', RouteController::class)->middleware($superAdmin)->only('index', 'store', 'update', 'destroy');
    Route::resource('permission', PermissionController::class)->middleware($superAdmin)->only('index', 'store', 'update', 'destroy');
    Route::resource('role', RoleController::class)->middleware([$superAdmin])->only('index', 'store', 'update', 'destroy');
    Route::resource('menu', MenuGroupController::class)->middleware($superAdmin)->only('index', 'store', 'update', 'destroy');
    Route::resource('menu.item', MenuItemController::class)->middleware($superAdmin)->only('index', 'store', 'update', 'destroy');
    Route::resource('faq', FaqController::class);
    Route::resource('testimonial', TestimonialController::class);
    Route::get('banner/media', [BannerController::class, 'loadMore'])->name('banner.loadMore');
    Route::get('banner/foto-picker', [BannerController::class, 'fotoPicker'])->name('banner.fotoPicker');
    Route::get('banner/album-fotos/{album}', [BannerController::class, 'albumFotos'])->name('banner.albumFotos');
    Route::get('banner/modal/{tipe}/{uuid}', [BannerController::class, 'modal'])->whereIn('tipe', ['foto', 'video', 'album', 'view-album'])->name('banner.modal');
    Route::resource('banner', BannerController::class);
    Route::post('banner/album', [BannerController::class, 'storeAlbum'])->name('banner.storeAlbum');
    Route::put('banner/album/{album}', [BannerController::class, 'updateAlbum'])->name('banner.updateAlbum');
    Route::delete('banner/album/{album}', [BannerController::class, 'destroyAlbum'])->name('banner.destroyAlbum');
    Route::resource('categories', CategoryController::class);
    Route::resource('vendors_categories', VendorsCategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::delete('articles/bulk', [ArticleController::class, 'bulkDestroy'])->name('articles.bulkDestroy');
    Route::resource('articles', ArticleController::class)->except(['show']);
    Route::resource('account', AccountController::class);
    Route::get('/get-kelurahan/{kecamatan_id}', [AccountController::class, 'getKelurahan']);
    Route::resource('pages', PagesController::class);
    Route::resource('events', EventsController::class);
    Route::get('filedownload/{fileDownload}/download', [FileDownloadController::class, 'download'])->name('filedownload.download');
    Route::resource('filedownload', FileDownloadController::class)->only(['index', 'store', 'update', 'destroy']);
    // Website Identity (singleton: single form, no table CRUD)
    Route::get('website-identity', [WebsiteIdentityController::class, 'index'])->name('website-identity.index');
    Route::put('website-identity', [WebsiteIdentityController::class, 'update'])->name('website-identity.update');
    Route::get('email-setting', [EmailSettingController::class, 'index'])->name('email-setting.index');
    Route::put('email-setting', [EmailSettingController::class, 'update'])->name('email-setting.update');
    Route::post('email-setting/test', [EmailSettingController::class, 'test'])->name('email-setting.test');
    Route::get('pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/export', [PenggunaController::class, 'export'])->name('pengguna.export');
    Route::put('/pengguna/{user}/status', [PenggunaController::class, 'updateStatus'])->name('pengguna.update-status');

    // payment
    Route::get(
        '/packages/members',
        [PackageController::class, 'members']
    )->name('packages.member');
    Route::get(
        '/packages/wishlist',
        [PackageInteractionController::class, 'wishlistIndex']
    )->name('packages.wishlist.index');
    Route::get(
        '/packages/compare',
        [PackageInteractionController::class, 'compareIndex']
    )->name('packages.compare.index');
    Route::post(
        '/packages/compare/clear',
        [PackageInteractionController::class, 'compareClear']
    )->name('packages.compare.clear');
    Route::get(
        '/packages/{package}/quotation/preview',
        [QuotationController::class, 'preview']
    )->name('packages.quotation.preview');
    Route::get(
        '/packages/{package}/quotation',
        [QuotationController::class, 'package']
    )->name('packages.quotation');
    Route::get(
        '/packages/{package}/quotation/download',
        [QuotationController::class, 'download']
    )->name('packages.quotation.download');
    Route::post(
        '/packages/{package}/wishlist',
        [PackageInteractionController::class, 'wishlistToggle']
    )->name('packages.wishlist.toggle');
    Route::post(
        '/packages/{package}/compare',
        [PackageInteractionController::class, 'compareAdd']
    )->name('packages.compare.add');
    Route::delete(
        '/packages/{package}/compare',
        [PackageInteractionController::class, 'compareRemove']
    )->name('packages.compare.remove');
    Route::post(
        '/packages/bulk',
        [PackageController::class, 'bulkAction']
    )->name('packages.bulk');
    Route::get('/packages/export', [PackageController::class, 'export'])->name('packages.export');
    Route::post('/packages/{package}/duplicate', [PackageController::class, 'duplicate'])->name('packages.duplicate');
    Route::patch('/packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
    Route::post('/packages/{package}/reorder', [PackageController::class, 'reorder'])->name('packages.reorder');
    Route::get('/packages/{package}/preview', [PackageController::class, 'preview'])->name('packages.preview');
    Route::resource('package-promos', PackagePromoController::class)->except(['show','create','edit']);
    Route::resource('packages', PackageController::class);
    Route::resource('venues', VenueController::class)->except(['show']);
Route::get('/checkout/package/{packageUuid}', [
        CheckoutController::class,
        'package',
    ])->name('checkout.package');
    Route::get('orders/report', [OrderController::class, 'report'])->name('orders.report');
    Route::get('orders/export-pdf', [OrderController::class, 'exportPdf'])->name('orders.exportPdf');
    Route::get('orders/export-csv', [OrderController::class, 'exportCsv'])->name('orders.exportCsv');
    Route::get('orders/audit', [OrderController::class, 'exportAudit'])->name('orders.audit');
    Route::get('orders/saved-filters', [OrderController::class, 'savedFilters'])->name('orders.saved-filters');
    Route::post('orders/saved-filters', [OrderController::class, 'storeSavedFilter'])->name('orders.saved-filters.store');
    Route::get('orders/saved-filters/{savedFilter}/apply', [OrderController::class, 'applySavedFilter'])->name('orders.saved-filters.apply');
    Route::post('orders/saved-filters/{savedFilter}/default', [OrderController::class, 'setDefaultSavedFilter'])->name('orders.saved-filters.default');
    Route::post('orders/saved-filters/{savedFilter}/pin', [OrderController::class, 'togglePinSavedFilter'])->name('orders.saved-filters.pin');
    Route::post('orders/saved-filters/{savedFilter}/move', [OrderController::class, 'moveSavedFilter'])->name('orders.saved-filters.move');
    Route::delete('orders/saved-filters/{savedFilter}', [OrderController::class, 'deleteSavedFilter'])->name('orders.saved-filters.destroy');
    Route::get('orders/workflow-settings', [OrderWorkflowSettingsController::class, 'edit'])->name('orders.workflow-settings.edit');
    Route::put('orders/workflow-settings', [OrderWorkflowSettingsController::class, 'update'])->name('orders.workflow-settings.update');
    Route::get('orders/analytics', [OrderWorkflowAnalyticsController::class, 'index'])->name('orders.analytics');
    Route::get('orders/change-requests', [OrderChangeRequestController::class, 'index'])->name('orders.change-requests.index');
    Route::post('orders/change-requests/{changeRequest}/review', [OrderChangeRequestController::class, 'review'])->name('orders.change-requests.review');
    Route::get('orders/{order}/documents/{document}/download', [OrderDocumentController::class, 'download'])->name('orders.documents.download');
    Route::post('orders/{order}/checklist/{item}', [WeddingChecklistController::class, 'update'])->name('orders.checklist.update');
    Route::post('orders/{order}/change-requests', [OrderChangeRequestController::class, 'store'])->name('orders.change-requests.store');
    Route::post('orders/bulk', [OrderController::class, 'bulkAction'])->name('orders.bulk');
    Route::post('orders/remind-sla', [OrderController::class, 'remindSla'])->name('orders.remind-sla');
    Route::post('orders/bulk/undo', [OrderController::class, 'bulkUndo'])->name('orders.bulk-undo');
    Route::post('orders/{order}/claim', [OrderController::class, 'claim'])->name('orders.claim');
    Route::get('orders/{order}/quick-view', [OrderController::class, 'quickView'])->name('orders.quick-view');
    Route::get('orders/{order}/activity', [OrderController::class, 'activity'])->name('orders.activity');
    Route::get('orders/{order}/whatsapp', [OrderController::class, 'whatsapp'])->name('orders.whatsapp');
    Route::post('orders/{order}/vendors', [OrderVendorAssignmentController::class, 'assign'])->name('orders.vendors.assign');
    Route::patch('orders/{order}/vendors/{assignment}/status', [OrderVendorAssignmentController::class, 'updateStatus'])->name('orders.vendors.status');
    Route::delete('orders/{order}/vendors/{assignment}', [OrderVendorAssignmentController::class, 'destroy'])->name('orders.vendors.destroy');
    Route::post('orders/{order}/approve', [OrderController::class, 'approve'])->name('orders.approve');
    Route::post('orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');
    Route::post('orders/{order}/payments', [OrderController::class, 'submitPayment'])->name('orders.payments.store')->middleware('throttle:10,1');
    Route::post('orders/{order}/payments/{payment}/approve', [OrderController::class, 'approvePayment'])->name('orders.payments.approve');
    Route::post('orders/{order}/payments/{payment}/reject', [OrderController::class, 'rejectPayment'])->name('orders.payments.reject');
    Route::get('orders/{order}/payments/{payment}/proof', [OrderController::class, 'showPaymentProof'])->name('orders.payments.proof');
    Route::get('orders/{order}/proof', [OrderController::class, 'showProof'])->name('orders.proof.show');
    Route::post('orders/{order}/proof', [OrderController::class, 'uploadProof'])->name('orders.proof')->middleware('throttle:10,1');
    Route::resource('orders', OrderController::class)
        ->only(['index', 'show']);
    Route::post('orders', [OrderController::class, 'store'])
        ->name('orders.store')->middleware('throttle:30,1');
    Route::post('orders/{order}/reorder', [OrderController::class, 'reorder'])
        ->name('orders.reorder')->middleware('throttle:10,1');
    Route::get('memberships/export', [MembershipController::class, 'export'])->name('memberships.export');
    Route::resource('memberships', MembershipController::class)
        ->only(['index', 'show']);
    // end payment

    Route::get('konsultasi/pdf/preview', [ConsultationController::class, 'pdfPreview'])->name('konsultasi.pdf-preview');
    Route::get('konsultasi/pdf/stream', [ConsultationController::class, 'pdfStream'])->name('konsultasi.pdf-stream');
    Route::get('konsultasi/pdf/download', [ConsultationController::class, 'pdfDownload'])->name('konsultasi.pdf-download');
    Route::get('konsultasi/export', [ConsultationController::class, 'export'])->name('konsultasi.export');
    Route::get('konsultasi/kanban', [ConsultationController::class, 'kanban'])->name('konsultasi.kanban');
    Route::get('konsultasi/calendar', [ConsultationController::class, 'calendar'])->name('konsultasi.calendar');
    Route::get('konsultasi/duplicates', [ConsultationController::class, 'duplicates'])->name('konsultasi.duplicates');
    Route::post('konsultasi/bulk', [ConsultationController::class, 'bulk'])->name('konsultasi.bulk');
    Route::post('konsultasi/bulk/undo', [ConsultationController::class, 'bulkUndo'])->name('konsultasi.bulk-undo');
    Route::post('konsultasi/merge', [ConsultationController::class, 'merge'])->name('konsultasi.merge');
    Route::post('konsultasi/{consultation}/remind', [ConsultationController::class, 'remind'])->name('konsultasi.remind');
    Route::get('konsultasi/{consultation}/whatsapp', [ConsultationController::class, 'whatsapp'])->name('konsultasi.whatsapp');
    Route::get('konsultasi/{consultation}/calendar-ics', [ConsultationController::class, 'calendarIcs'])->name('konsultasi.calendar-ics');
    Route::get('konsultasi/import', [ConsultationController::class, 'import'])->name('konsultasi.import');
    Route::get('konsultasi/import/template', [ConsultationController::class, 'importTemplate'])->name('konsultasi.import.template');
    Route::post('konsultasi/import/preview', [ConsultationController::class, 'importPreview'])->name('konsultasi.import.preview');
    Route::post('konsultasi/import/commit', [ConsultationController::class, 'importCommit'])->name('konsultasi.import.commit');
    Route::get('konsultasi/saved-filters', [ConsultationController::class, 'savedFilters'])->name('konsultasi.saved-filters');
    Route::post('konsultasi/saved-filters', [ConsultationController::class, 'storeSavedFilter'])->name('konsultasi.saved-filters.store');
    Route::get('konsultasi/saved-filters/{savedFilter}/apply', [ConsultationController::class, 'applySavedFilter'])->name('konsultasi.saved-filters.apply');
    Route::post('konsultasi/saved-filters/{savedFilter}/default', [ConsultationController::class, 'setDefaultSavedFilter'])->name('konsultasi.saved-filters.default');
    Route::delete('konsultasi/saved-filters/{savedFilter}', [ConsultationController::class, 'deleteSavedFilter'])->name('konsultasi.saved-filters.destroy');
    Route::post('konsultasi/{consultation}/reorder', [ConsultationController::class, 'reorder'])->name('konsultasi.reorder');
    Route::put('konsultasi/{consultation}/lead', [ConsultationController::class, 'updateLead'])->name('konsultasi.update-lead');
    Route::resource('konsultasi', ConsultationController::class)->parameters([
        'konsultasi' => 'consultation',
    ]);
    Route::get('vendor/arsip', [VendorController::class, 'archive'])->name('vendor.archive');
    Route::post('vendor/restore', [VendorController::class, 'restore'])->name('vendor.restore');
    Route::resource('vendor', VendorController::class);
    Route::get('/mobile/vendor', [VendorController::class, 'mobile'])
        ->name('vendor.mobile');
    Route::patch('/pages/{uuid}/sidebar', [PagesController::class, 'updateSidebar'])->name('pages.updateSidebar');

    // Security (audit log, login activity, lockout)
    Route::prefix('security')->name('security.')->group(function () {
        Route::get('login-activity', [LoginActivityController::class, 'index'])->name('login-activity.index');
        Route::delete('login-activity/bulk', [LoginActivityController::class, 'bulkDestroy'])->name('login-activity.bulkDestroy');
        Route::delete('login-activity/clear', [LoginActivityController::class, 'clear'])->name('login-activity.clear');
        Route::delete('login-activity/{loginActivity}', [LoginActivityController::class, 'destroy'])->name('login-activity.destroy');

        Route::get('login-lockout', [LoginLockoutController::class, 'index'])->name('login-lockout.index');
        Route::delete('login-lockout/bulk', [LoginLockoutController::class, 'bulkDestroy'])->name('login-lockout.bulkDestroy');
        Route::delete('login-lockout/{loginLockout}', [LoginLockoutController::class, 'destroy'])->name('login-lockout.destroy');

        Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
        Route::get('audit-log/export-pdf', [AuditLogController::class, 'exportPdf'])->name('audit-log.exportPdf');
        Route::delete('audit-log/bulk', [AuditLogController::class, 'bulkDestroy'])->name('audit-log.bulkDestroy');
        Route::delete('audit-log/clear', [AuditLogController::class, 'clear'])->name('audit-log.clear');
        Route::delete('audit-log/{auditLog}', [AuditLogController::class, 'destroy'])->name('audit-log.destroy');

        Route::get('failed-login', [FailedLoginController::class, 'index'])->name('failed-login.index');
        Route::delete('failed-login/bulk', [FailedLoginController::class, 'bulkDestroy'])->name('failed-login.bulkDestroy');
        Route::delete('failed-login/clear', [FailedLoginController::class, 'clear'])->name('failed-login.clear');
        Route::delete('failed-login/{failedLogin}', [FailedLoginController::class, 'destroy'])->name('failed-login.destroy');
    });

    // Route::get('list-menu', [MenuGroupController::class, 'listMenu'])->name('list-menu');
});

require __DIR__.'/auth.php';
