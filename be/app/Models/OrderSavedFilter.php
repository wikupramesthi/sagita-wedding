<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderSavedFilter extends Model
{
    use HasUuids;

    protected $table = 'order_saved_filters';

    protected $fillable = [
        'user_uuid',
        'name',
        'filters',
        'is_default',
        'pinned_at',
        'sort_order',
    ];

    protected $casts = [
        'filters' => 'array',
        'is_default' => 'boolean',
        'pinned_at' => 'datetime',
        'sort_order' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
