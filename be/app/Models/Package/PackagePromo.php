<?php

namespace App\Models\Package;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackagePromo extends Model
{
    use HasUuids;

    protected $table = 'package_promos';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'package_uuid',
        'code',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'max_uses',
        'used_count',
        'starts_at',
        'ends_at',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'value' => 'integer',
        'min_purchase' => 'integer',
        'max_discount' => 'integer',
        'max_uses' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(
            Package::class,
            'package_uuid',
            'uuid'
        );
    }

    public function scopeUsable($query)
    {
        return $query
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->where(function ($query) {
                $query->whereNull('max_uses')
                    ->orWhereColumn('used_count', '<', 'max_uses');
            });
    }

    public function isUsable(): bool
    {
        return $this->is_active &&
            ($this->starts_at === null || $this->starts_at->lessThanOrEqualTo(now())) &&
            ($this->ends_at === null || $this->ends_at->greaterThanOrEqualTo(now())) &&
            ($this->max_uses === null || $this->used_count < $this->max_uses);
    }

    public function discountFor(int $subtotal): int
    {
        if ($subtotal < $this->min_purchase) {
            return 0;
        }

        $discount = $this->type === 'percent'
            ? (int) round($subtotal * ($this->value / 100))
            : $this->value;

        if ($this->max_discount !== null) {
            $discount = min($discount, $this->max_discount);
        }

        return max(0, min($discount, $subtotal));
    }
}
