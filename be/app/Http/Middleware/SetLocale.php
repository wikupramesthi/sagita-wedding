<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Area admin selalu memakai Bahasa Indonesia.
 * Tampilan mobile mengikuti session `locale` (ID/EN, default EN).
 * Locale Carbon disesuaikan agar tanggal dan waktu ikut.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = ltrim($request->path(), '/');

        if ($path === 'backend' || str_starts_with($path, 'backend/')) {
            $locale = 'id';
        } else {
            $locale = session('locale', 'en');
            if (! in_array($locale, ['id', 'en'], true)) {
                $locale = 'en';
            }
        }

        app()->setLocale($locale);
        \Carbon\Carbon::setLocale($locale === 'id' ? 'id' : 'en');

        return $next($request);
    }
}
