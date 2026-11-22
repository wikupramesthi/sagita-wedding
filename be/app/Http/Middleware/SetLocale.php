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

        if ($path === 'backend' || str_starts_with($path, 'backend/')) {
            $locale = 'id';
        } else {
            $locale = session('locale', 'id');
            if (! in_array($locale, ['id', 'en'], true)) {
                $locale = 'id';
            }
        }

        app()->setLocale($locale);
        \Carbon\Carbon::setLocale($locale === 'id' ? 'id' : 'en');

        return $next($request);
    }
}
