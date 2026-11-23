<?php

namespace App\Models\Payment;

use App\Models\Consultation;
use App\Models\Package\Package;
use App\Models\Package\PackageOption;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasUuids;

    protected $table = 'orders';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_uuid',
        'order_number',
        'type',
        'package_uuid',
        'package_option_uuid',
        'event_date',
        'amount',
        'payment_scheme',
        'total_amount',
        'amount_paid',
        'remaining_amount',
        'balance_due_at',
        'installment_schedule',
        'installment_reminders_sent',
        'balance_reminder_sent_at',
        'status',
        'expired_at',
        'paid_at',
        'admin_note',
        'assigned_admin_uuid',
        'proof_image_path',
        'proof_uploaded_at',
        'sla_reminded_at',
        'approval_snapshot',
        'pending_key',
        'addons',
        'promo_code',
        'promo_discount',
        'promo_snapshot',
        'consultation_uuid',
        'venue_uuid',
        'venue_booking_status',
        'venue_booking_at',
        'venue_booking_amount',
        'venue_booking_note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'balance_due_at' => 'datetime',
        'installment_schedule' => 'array',
        'installment_reminders_sent' => 'array',
        'balance_reminder_sent_at' => 'datetime',
        'event_date' => 'date',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'sla_reminded_at' => 'datetime',
        'proof_uploaded_at' => 'datetime',
        'approval_snapshot' => 'array',
        'addons' => 'array',
        'promo_snapshot' => 'array',
        'promo_discount' => 'integer',
        'venue_booking_at' => 'datetime',
        'venue_booking_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $order): void {
            $order->syncPendingKey();
        });

        static::updating(function (self $order): void {
            $order->syncPendingKey();
        });
    }

    public function syncPendingKey(): void
    {
        if ($this->type !== 'package') {
            $this->pending_key = null;

            return;
        }

        if (in_array($this->status, ['pending', 'partially_paid'], true) && $this->user_uuid && $this->package_option_uuid) {
            $this->pending_key = $this->user_uuid.'|'.$this->package_option_uuid;

            return;
        }

        if (! in_array($this->status, ['pending', 'partially_paid'], true)) {
            $this->pending_key = null;
        }
    }

    public function scopePackageOnly($query)
    {
        return $query->where('type', 'package');
    }

    public function getAgingBucketAttribute(): string
    {
        if (! in_array($this->status, ['pending', 'partially_paid'], true) || ! $this->created_at) {
            return '-';
        }

        $hours = $this->created_at->diffInHours();

        return match (true) {
            $hours >= 48 => 'Kritis >48 jam',
            $hours >= 24 => 'Terlambat >24 jam',
            $hours >= 2 => 'Menunggu 2-24 jam',
            default => 'Baru <2 jam',
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_admin_uuid', 'uuid');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_uuid', 'uuid');
    }

    public function packageOption(): BelongsTo
    {
        return $this->belongsTo(PackageOption::class, 'package_option_uuid', 'uuid');
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class, 'consultation_uuid', 'uuid');
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'venue_uuid', 'uuid');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'order_uuid', 'uuid')->latestOfMany('created_at');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_uuid', 'uuid');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(OrderDocument::class, 'order_uuid', 'uuid')->orderBy('issued_at');
    }

    public function checklistItems(): HasMany
    {
        return $this->hasMany(WeddingChecklistItem::class, 'order_uuid', 'uuid')
            ->orderBy('due_at')
            ->orderBy('created_at');
    }

    public function changeRequests(): HasMany
    {
        return $this->hasMany(OrderChangeRequest::class, 'order_uuid', 'uuid')->latest();
    }

    public function vendorAssignments(): HasMany
    {
        return $this->hasMany(OrderVendorAssignment::class, 'order_uuid', 'uuid')->latest();
    }

    public function proofUrl(): ?string
    {
        return $this->proof_image_path
            ? route('orders.proof.show', $this->uuid, false)
            : null;
    }

    public const BOOKING_FEE_AMOUNT = 2000000;

    public function getPaymentSchemeAttribute($value): string
    {
        $scheme = $value ?: (string) data_get($this->promo_snapshot, 'payment_scheme', 'full');

        return in_array($scheme, ['no_dp', 'booking_fee', 'dp30', 'dp50', 'full'], true) ? $scheme : 'full';
    }

    public function getSchemeLabelAttribute(): string
    {
        $labels = [
            'no_dp' => 'Tanpa DP',
            'dp30' => 'DP 30%',
            'dp50' => 'DP 50%',
            'full' => 'Bayar Lunas',
        ];
        $scheme = $this->payment_scheme;

        return $labels[$scheme] ?? (string) data_get($this->promo_snapshot, 'scheme_label', 'Bayar Lunas');
    }

    public function getFullAmountAttribute($value): float
    {
        $full = $value ?? $this->attributes['total_amount'] ?? data_get($this->promo_snapshot, 'full_amount');
        $full = (float) ($full ?: 0);
        if ($full > 0) {
            return $full;
        }
        $amount = (float) ($this->amount ?: 0);
        $paid = (float) ($this->attributes['amount_paid'] ?? 0);
        $remaining = (float) ($this->attributes['remaining_amount'] ?? 0);

        return max($amount, $paid + $remaining);
    }

    public function getAmountPaidValueAttribute(): float
    {
        $paid = (float) ($this->attributes['amount_paid'] ?? 0);
        if ($paid > 0) {
            return $paid;
        }
        if ($this->status === 'paid') {
            return (float) $this->full_amount;
        }

        return 0.0;
    }

    public function getRemainingAmountValueAttribute(): float
    {
        $remaining = $this->attributes['remaining_amount'] ?? null;
        if ($remaining !== null) {
            return max(0, (float) $remaining);
        }

        return max(0, (float) $this->full_amount - (float) $this->amount_paid_value);
    }

    public function getDueAmountAttribute(): float
    {
        if ($this->status === 'partially_paid') {
            return (float) $this->remaining_amount_value;
        }
        if ($this->status === 'paid') {
            return 0.0;
        }

        return (float) ($this->amount ?: 0);
    }

    public function isFullyPaid(): bool
    {
        return $this->status === 'paid' && $this->remaining_amount_value <= 0.01;
    }

    public function isPartiallyPaid(): bool
    {
        return $this->status === 'partially_paid';
    }

    public function hasOutstandingBalance(): bool
    {
        return $this->remaining_amount_value > 0.01 && in_array($this->status, ['pending', 'partially_paid'], true);
    }

    public function pendingVerificationPayment()
    {
        return $this->hasOne(Payment::class, 'order_uuid', 'uuid')
            ->where(function ($q) {
                $q->where('verification_status', 'pending')->orWhere('status', 'pending');
            })->latestOfMany('created_at');
    }

    public function verifiedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_uuid', 'uuid')
            ->where(function ($q) {
                $q->where('verification_status', 'verified')->orWhere('status', 'paid');
            })->orderBy('paid_at')->orderBy('created_at');
    }
}
