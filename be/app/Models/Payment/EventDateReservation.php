<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventDateReservation extends Model
{
    protected $table = 'event_date_reservations';

    protected $primaryKey = 'event_date';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'event_date',
        'order_uuid',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'expires_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_uuid', 'uuid');
    }
}
