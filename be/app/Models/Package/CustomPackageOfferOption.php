<?php

namespace App\Models\Package;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomPackageOfferOption extends Model
{
    use HasUuids;

    protected $table = 'custom_package_offer_options';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'custom_package_offer_uuid',
        'label',
        'description',
        'subtotal_amount',
        'discount_amount',
        'total_amount',
        'deposit_percentage',
        'payment_terms_note',
        'sort_order',
    ];

    protected $casts = [
        'subtotal_amount' => 'integer',
        'discount_amount' => 'integer',
        'total_amount' => 'integer',
        'deposit_percentage' => 'integer',
        'sort_order' => 'integer',
    ];

    public function offer(): BelongsTo
    {
        return $this->belongsTo(CustomPackageOffer::class, 'custom_package_offer_uuid', 'uuid');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CustomPackageOfferOptionItem::class, 'custom_package_offer_option_uuid', 'uuid')
            ->orderBy('sort_order');
    }
}
