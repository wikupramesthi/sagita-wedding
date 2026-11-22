<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Event;
use App\Models\Faq;
use App\Models\Package\Package as WeddingPackage;
use App\Models\Package\PackageAddon;
use App\Models\Package\PackageBlackoutDate;
use App\Models\Package\PackageFeature;
use App\Models\Package\PackageOption;
use App\Models\Package\PackagePromo;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\VendorsCategory;
use App\Models\Venue;
use App\Models\WebsiteIdentity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BatchVersionService
{
    private const KEY = 'batch:content-version';

    private const WATCHED_MODELS = [
        Article::class,
        Banner::class,
        Category::class,
        Event::class,
        Faq::class,
        Page::class,
        Testimonial::class,
        User::class,
        Venue::class,
        VendorsCategory::class,
        WebsiteIdentity::class,
        WeddingPackage::class,
        PackageAddon::class,
        PackageBlackoutDate::class,
        PackageFeature::class,
        PackageOption::class,
        PackagePromo::class,
    ];

    public static function current(): int
    {
        return (int) Cache::rememberForever(self::KEY, fn () => 1);
    }

    public static function touchFor(Model $model): void
    {
        foreach (self::WATCHED_MODELS as $watched) {
            if ($model instanceof $watched) {
                self::bump();

                return;
            }
        }
    }

    public static function bump(): void
    {
        if (! Cache::has(self::KEY)) {
            Cache::forever(self::KEY, 1);

            return;
        }

        Cache::increment(self::KEY);
    }
}
