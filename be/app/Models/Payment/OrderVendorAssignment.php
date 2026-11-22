<?php

namespace App\Models\Payment;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderVendorAssignment extends Model
{
    use HasUuids;

    public const STATUS_ASSIGNED = 'assigned';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_LABELS = [
        self::STATUS_ASSIGNED => 'Ditugaskan',
        self::STATUS_ACCEPTED => 'Diterima',
        self::STATUS_IN_PROGRESS => 'Dikerjakan',
        self::STATUS_COMPLETED => 'Selesai',
        self::STATUS_CANCELLED => 'Dibatalkan',
    ];

    protected $table = 'order_vendor_assignments';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'order_uuid',
        'vendor_uuid',
        'scope',
        'status',
        'notes',
        'assigned_by',
        'accepted_at',
        'completed_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_uuid', 'uuid');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_uuid', 'uuid');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by', 'uuid');
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst(str_replace('_', ' ', (string) $this->status));
    }
}
