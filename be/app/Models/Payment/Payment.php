<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasUuids;

    protected $table = 'payments';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'order_uuid',
        'kind',
        'payment_gateway',
        'transaction_id',
        'payment_type',
        'amount',
        'gross_amount',
        'status',
        'verification_status',
        'verified_by',
        'verified_at',
        'paid_at',
        'raw_response',
        'proof_image_path',
        'proof_uploaded_at',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gross_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'proof_uploaded_at' => 'datetime',
        'raw_response' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(
            Order::class,
            'order_uuid',
            'uuid'
        );
    }

    public function getProofVerificationStatusAttribute(): string
    {
        $verificationStatus = $this->verification_status ?: $this->status;

        if (in_array($verificationStatus, ['approved', 'verified'], true) || $this->status === 'paid') {
            return 'accepted';
        }

        if (in_array($verificationStatus, ['rejected', 'failed'], true) || $this->status === 'failed') {
            return 'rejected';
        }

        return 'pending';
    }
}
