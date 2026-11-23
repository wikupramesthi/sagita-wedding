<?php

namespace App\Models;

use App\Models\Package\Package;
use App\Models\Payment\Order;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    use HasUuids;

    protected $table = 'user_packages';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'user_uuid',
        'package_uuid',
        'order_uuid',
        'quota',
        'started_at',
        'expired_at',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_uuid',
            'uuid'
        );
    }

    public function package()
    {
        return $this->belongsTo(
            Package::class,
            'package_uuid',
            'uuid'
        );
    }

    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'order_uuid',
            'uuid'
        );
    }

    public function getTotalAmountValueAttribute(): float
    {
        return (float) ($this->order?->full_amount ?? 0);
    }

    public function getAmountPaidValueAttribute(): float
    {
        return (float) ($this->order?->amount_paid_value ?? 0);
    }

    public function getRemainingAmountValueAttribute(): float
    {
        return (float) ($this->order?->remaining_amount_value ?? 0);
    }

    public function getPaymentProgressAttribute(): int
    {
        $total = $this->total_amount_value;

        if ($total <= 0) {
            return 0;
        }

        return (int) round(min(100, max(0, $this->amount_paid_value / $total * 100)));
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        if (! $this->order) {
            return 'Tanpa pesanan';
        }

        return match (true) {
            $this->order->isFullyPaid() => 'Lunas',
            $this->order->hasOutstandingBalance() => 'Cicilan',
            default => 'Menunggu',
        };
    }

    public function getEventDateAttribute()
    {
        return $this->order?->event_date;
    }

    public function getDaysToEventAttribute(): ?int
    {
        if (! $this->event_date) {
            return null;
        }

        return (int) now()->startOfDay()->diffInDays(
            $this->event_date->copy()->startOfDay(),
            false
        );
    }
}
