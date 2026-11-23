<?php

namespace App\Models\Package;

use App\Models\Payment\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasUuids;

    protected $table = 'packages';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'guest_capacity',
        'event_duration_hours',
        'venue_note',
        'cover_url',
        'dp_percentage',
        'payment_terms_note',
        'is_popular',
        'is_active',
        'is_published',
        'published_at',
        'lead_time_days',
    ];

    protected $casts = [
        'is_popular' => 'boolean',
        'is_active' => 'string',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'lead_time_days' => 'integer',
        'guest_capacity' => 'integer',
        'event_duration_hours' => 'integer',
        'dp_percentage' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query
            ->where('is_published', true)
            ->where('is_active', 'active')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeWedding($query)
    {
        return $query->where(function ($query) {
            $query->where(function ($query) {
                $query->whereNotNull('guest_capacity')
                    ->orWhereNotNull('event_duration_hours')
                    ->orWhereNotNull('venue_note')
                    ->orWhereNotNull('cover_url');
            })->orWhere(function ($query) {
                $query->whereRaw('LOWER(COALESCE(name, "")) NOT LIKE ?', ['%membership%'])
                    ->whereRaw('LOWER(COALESCE(name, "")) NOT LIKE ?', ['%keanggotaan%'])
                    ->whereRaw('LOWER(COALESCE(name, "")) NOT LIKE ?', ['%studio class%'])
                    ->whereRaw('LOWER(COALESCE(slug, "")) NOT LIKE ?', ['%membership%'])
                    ->whereRaw('LOWER(COALESCE(slug, "")) NOT LIKE ?', ['%keanggotaan%'])
                    ->whereRaw('LOWER(COALESCE(slug, "")) NOT LIKE ?', ['%studio-class%']);
            });
        });
    }

    public function options(): HasMany
    {
        return $this->hasMany(
            PackageOption::class,
            'package_uuid',
            'uuid'
        )->orderBy('sort_order');
    }

    public function features(): HasMany
    {
        return $this->hasMany(
            PackageFeature::class,
            'package_uuid',
            'uuid'
        )->orderBy('sort_order');
    }

    public function addons(): HasMany
    {
        return $this->hasMany(
            PackageAddon::class,
            'package_uuid',
            'uuid'
        )->orderBy('sort_order');
    }

    public function users(): HasMany
    {
        return $this->hasMany(
            User::class,
            'package_uuid',
            'uuid'
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(
            Order::class,
            'package_uuid',
            'uuid'
        );
    }

    public function blackoutDates(): HasMany
    {
        return $this->hasMany(
            PackageBlackoutDate::class,
            'package_uuid',
            'uuid'
        )->orderBy('date');
    }

    public function promos(): HasMany
    {
        return $this->hasMany(
            PackagePromo::class,
            'package_uuid',
            'uuid'
        )->orderBy('sort_order');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(
            PackageWishlist::class,
            'package_uuid',
            'uuid'
        );
    }
}
