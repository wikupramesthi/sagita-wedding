<?php

namespace App\Models\Package;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomPackageOfferOptionItem extends Model
{
    use HasUuids;

    protected $table = 'custom_package_offer_option_items';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'custom_package_offer_option_uuid',
        'name',
        'description',
        'quantity',
        'unit_price',
        'line_total',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'integer',
        'line_total' => 'integer',
        'sort_order' => 'integer',
    ];

    public function option(): BelongsTo
    {
        return $this->belongsTo(CustomPackageOfferOption::class, 'custom_package_offer_option_uuid', 'uuid');
    }
}
