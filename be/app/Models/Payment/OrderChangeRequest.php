<?php

namespace App\Models\Payment;

use App\Models\Package\PackageAddon;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderChangeRequest extends Model
{
    use HasUuids;

    protected $table = 'order_change_requests';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'order_uuid',
        'requested_by_uuid',
        'change_type',
        'requested_event_date',
        'addon_uuid',
        'addon_quantity',
        'requested_price',
        'customer_note',
        'status',
        'admin_note',
        'reviewed_by_uuid',
        'reviewed_at',
    ];

    protected $casts = [
        'requested_event_date' => 'date',
        'addon_quantity' => 'integer',
        'requested_price' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_uuid', 'uuid');
    }

    public function addon(): BelongsTo
    {
        return $this->belongsTo(PackageAddon::class, 'addon_uuid', 'uuid');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_uuid', 'uuid');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_uuid', 'uuid');
    }
}
