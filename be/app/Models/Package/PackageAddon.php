<?php

namespace App\Models\Package;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageAddon extends Model
{
    use HasUuids;

    protected $table = 'package_addons';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'package_uuid',
        'name',
        'description',
        'price',
        'discount_price',
        'min_quantity',
        'max_quantity',
        'requires_addon_uuid',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'discount_price' => 'integer',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(
            Package::class,
            'package_uuid',
            'uuid'
        );
    }

    public function requiresAddon(): BelongsTo
    {
        return $this->belongsTo(
            PackageAddon::class,
            'requires_addon_uuid',
            'uuid'
        );
    }

    public function getFinalPriceAttribute()
    {
        return $this->discount_price !== null &&
            $this->discount_price < $this->price
            ? $this->discount_price
            : $this->price;
    }
}
