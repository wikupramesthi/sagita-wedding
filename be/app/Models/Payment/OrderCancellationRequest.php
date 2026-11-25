<?php

namespace App\Models\Payment;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderCancellationRequest extends Model
{
    use HasUuids;

    protected $table = 'order_cancellation_requests';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'order_uuid',
        'requested_by_uuid',
        'customer_note',
        'status',
        'refund_amount',
        'refund_status',
        'refund_reference',
        'refund_recorded_by_uuid',
        'refund_recorded_at',
        'admin_note',
        'reviewed_by_uuid',
        'reviewed_at',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'refund_recorded_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_uuid', 'uuid');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_uuid', 'uuid');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_uuid', 'uuid');
    }

    public function refundRecordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'refund_recorded_by_uuid', 'uuid');
    }
}
