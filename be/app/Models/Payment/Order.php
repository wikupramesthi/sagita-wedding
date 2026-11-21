<?php

namespace App\Models\Payment;

use App\Models\User;
use App\Models\Package\Package;
use App\Models\Class\ClassSchedule;
use App\Models\Class\ClassBooking;
use App\Models\Package\PackageOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'class_schedule_uuid',
        'amount',
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
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'sla_reminded_at' => 'datetime',
        'proof_uploaded_at' => 'datetime',
        'approval_snapshot' => 'array',
        'addons' => 'array',
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

        if ($this->status === 'pending' && $this->user_uuid && $this->package_option_uuid) {
            $this->pending_key = $this->user_uuid . '|' . $this->package_option_uuid;

            return;
        }

        if ($this->status !== 'pending') {
            $this->pending_key = null;
        }
    }

    public function scopePackageOnly($query)
    {
        return $query->where('type', 'package');
    }

    public function getAgingBucketAttribute(): string
    {
        if ($this->status !== 'pending' || !$this->created_at) {
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
        return $this->belongsTo(
            User::class,
            'user_uuid',
            'uuid'
        );
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_admin_uuid',
            'uuid'
        );
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(
            Package::class,
            'package_uuid',
            'uuid'
        );
    }

    public function packageOption(): BelongsTo
    {
        return $this->belongsTo(
            PackageOption::class,
            'package_option_uuid',
            'uuid'
        );
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(
            ClassSchedule::class,
            'class_schedule_uuid',
            'uuid'
        );
    }

    public function payment(): HasOne
    {
        return $this->hasOne(
            Payment::class,
            'order_uuid',
            'uuid'
        );
    }

    public function booking(): HasOne
    {
        return $this->hasOne(
            ClassBooking::class,
            'order_uuid',
            'uuid'
        );
    }

    /**
     * Route URL of the uploaded transfer proof, if any.
     * Served through the app (owner/admin only), never a
     * guessable public URL. Relative so it works on any host.
     */
    public function proofUrl(): ?string
    {
        return $this->proof_image_path
            ? route('orders.proof.show', $this->uuid, false)
            : null;
    }
}
