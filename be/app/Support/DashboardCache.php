<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DashboardCache
{
    public const VERSION_KEY = 'dashboard.admin.summary.version';

    public static function key(string $startDate, string $endDate, bool $canViewConsultations = true): string
    {
        return 'dashboard.admin.summary.v3.'.self::version().'.'.sha1($startDate.'|'.$endDate.'|'.(int) $canViewConsultations);
    }

    public static function invalidate(): void
    {
        Cache::forget(self::VERSION_KEY);
    }

    public static function version(): string
    {
        return (string) Cache::rememberForever(self::VERSION_KEY, fn () => Str::uuid()->toString());
    }
}
