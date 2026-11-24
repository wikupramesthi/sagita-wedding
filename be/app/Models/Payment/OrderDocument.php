<?php

namespace App\Models\Payment;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDocument extends Model
{
    use HasUuids;

    protected $table = 'order_documents';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'order_uuid',
        'payment_uuid',
        'document_key',
        'document_number',
        'type',
        'snapshot',
        'issued_at',
        'accepted_at',
        'accepted_by_uuid',
        'accepted_snapshot',
    ];

    protected $casts = [
        'snapshot' => 'array',
        'issued_at' => 'datetime',
        'accepted_at' => 'datetime',
        'accepted_snapshot' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_uuid', 'uuid');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_uuid', 'uuid');
    }

    public function acceptedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accepted_by_uuid', 'uuid');
    }
}
