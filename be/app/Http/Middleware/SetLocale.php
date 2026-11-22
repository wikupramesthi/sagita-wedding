<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Area admin selalu memakai Bahasa Indonesia.
 * Tampilan mobile mengikuti session `locale` (ID/EN, default ID).
 * Locale Carbon disesuaikan agar tanggal dan waktu ikut.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = ltrim($request->path(), '/');
        $routeName = $request->route()?->getName();
        $isDashboard = $path === 'backend/dashboard' || str_starts_with($path, 'backend/dashboard/');
        $memberFacing = in_array($routeName, [
            'auth.me',
            'locale',
            'member-view',
            'notifications.index',
            'notifications.read',
            'notifications.readAll',
            'profile.edit',
            'profile.update',
            'profile.destroy',
            'password.confirm',
            'password.update',
            'verification.notice',
            'verification.verify',
            'verification.send',
        ], true);

        if (($path === 'backend' || str_starts_with($path, 'backend/')) && ! $isDashboard) {
            $locale = 'id';
        } elseif ($isDashboard || $memberFacing) {
            $locale = session('locale', 'id');
            if (! in_array($locale, ['id', 'en'], true)) {
                $locale = 'id';
            }
        } else {
            $locale = 'id';
        }

        app()->setLocale($locale);
        \Carbon\Carbon::setLocale($locale === 'id' ? 'id' : 'en');

        return $next($request);
    }
}
