<?php

namespace App\Http\Middleware;

use App\Models\ManagementAccess\Route;
use Closure;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RouteMiddleware
{
    private const SAFE_WITHOUT_REGISTRATION = [
        'dashboard.*',
        'account.*',
        'notifications.*',
        'profile.*',
        'password.*',
        'verification.*',
        'checkout.*',
        'schedules.*',
        'bookings.*',
        'vendor.mobile',
        'packages.member',
        'packages.wishlist.*',
        'packages.compare.*',
        'packages.quotation',
        'packages.quotation.download',
        'packages.quotation.preview',
        'orders.documents.*',
        'orders.checklist.update',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    // public function handle(Request $request, Closure $next) : Response
    // {
    //     $routes = Route::firstWhere('route', $request->route()?->getName());

    //     return blank($routes) || (bool) $routes->status && $request->user()->can($routes->permission_name)
    //         ? $next($request)
    //         : redirect(RouteServiceProvider::HOME)->withErrors('you do not have access to this route!');
    // }

    public function handle(Request $request, Closure $next): Response
    {
        // Role member hanya boleh membuka route member. Tanpa ini, member
        // yang buka dari laptop (view desktop) bisa mengakses halaman admin
        // (pengguna, articles, events, dsb.) via URL langsung karena
        // controller modul tidak punya cek role sendiri.
        $user = $request->user();

        if ($request->routeIs('keep-alive') || $request->routeIs('logout') || $request->routeIs('logout.confirm') || $request->routeIs('auth.me.logout')) {
            return $next($request);
        }

        if (
            $user && $user->hasRole('user')
            && $request->is('backend/*')
            && self::memberDenied($request->route()?->getName())
        ) {
            abort(404);
        }

        if (
            $user && $user->hasRole('user')
            && $request->is('backend/*')
            && ! self::memberAllowed($request->route()?->getName())
        ) {
            abort(404);
        }

        $routeName = $request->route()?->getName();

        if (
            $user
            && $request->is('backend/*')
            && ! self::memberAllowed($routeName)
            && ! $user->hasAnyRole(['super-admin', 'admin'])
            && ! ($user->hasRole('vendor') && in_array($routeName, ['dashboard.index', 'vendor.mobile'], true))
        ) {
            abort(404);
        }

        $routes = null;

        if ($routeName) {
            $cacheKey = 'route.permission.'.$routeName;
            $routes = Cache::get($cacheKey);

            if (! $routes) {
                $routes = Route::firstWhere('route', $routeName);

                if ($routes) {
                    Cache::put($cacheKey, $routes, now()->addSeconds(60));
                }
            }
        }

        if (blank($routes)) {
            if (
                ($request->is('backend') || $request->is('backend/*'))
                && ! self::isSafeWithoutRegistration($request, $routeName)
            ) {
                abort(404);
            }

            return $next($request);
        }

        return (bool) $routes->status && $user?->can($routes->permission_name)
            ? $next($request)
            : abort(404);
    }

    /**
     * Route privileged yang harus ditolak untuk role member meskipun
     * terdaftar dengan wildcard di memberAllowed().
     */
    private static function memberDenied(?string $routeName): bool
    {
        if (! $routeName) {
            return false;
        }

        static $denied = [
            'search',
            'dashboard.refresh',
            'dashboard.export',
            'dashboard.export-pdf',
            'dashboard.exportPdf',
            'memberships.export',
            'orders.activity',
            'orders.quick-view',
            'orders.whatsapp',
            'orders.audit',
            'orders.export*',
            'orders.bulk*',
            'orders.claim',
            'orders.remind-sla',
            'orders.saved-filters*',
            'orders.payments.approve',
            'orders.payments.reject',
        ];

        foreach ($denied as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Route yang boleh dibuka role member (sisanya khusus admin).
     * Mendukung wildcard ala Str::is().
     *
     * @return array<int, string>
     */
    private static function memberAllowed(?string $routeName): bool
    {
        if (! $routeName) {
            return false;
        }

        static $allowed = [
            'dashboard.*',
            'search', // dinonaktifkan khusus di controller (admin only)
            'profile.*',
            'keep-alive',
            'member-view',
            'account.*',
            'notifications.*',
            'packages.member',
            'packages.wishlist.*',
            'packages.compare.*',
            'packages.quotation',
            'packages.quotation.preview',
            'packages.quotation.download',
            'checkout.*',
            'schedules.*',
            'bookings.*',
            'vendor.mobile',
            'orders.*',
            'memberships.*',
            'events.index',
            'events.show',
            'password.*',
            'verification.*',
            'logout',
        ];

        foreach ($allowed as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return true;
            }
        }

        return false;
    }

    private static function isSafeWithoutRegistration(Request $request, ?string $routeName): bool
    {
        if ($request->is('backend/instruktur') || $request->is('backend/instruktur/*') || $request->is('backend/get-kelurahan/*')) {
            return true;
        }

        if (! $routeName) {
            return false;
        }

        foreach (self::SAFE_WITHOUT_REGISTRATION as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return true;
            }
        }

        return false;
    }
}
