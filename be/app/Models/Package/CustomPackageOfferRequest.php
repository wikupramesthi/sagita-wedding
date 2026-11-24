<?php

namespace App\Models\Package;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomPackageOfferRequest extends Model
{
    use HasUuids;

    protected $table = 'custom_package_offer_requests';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_uuid',
        'event_date',
        'guest_count',
        'venue_note',
        'budget_min',
        'budget_max',
        'request_details',
        'status',
        'admin_note',
        'assigned_admin_uuid',
        'handled_at',
        'fingerprint_hash',
        'review_due_at',
        'sla_notified_at',
        'quoted_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'guest_count' => 'integer',
        'budget_min' => 'integer',
        'budget_max' => 'integer',
        'handled_at' => 'datetime',
        'review_due_at' => 'datetime',
        'sla_notified_at' => 'datetime',
        'quoted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_admin_uuid', 'uuid');
    }

    public function offer(): HasOne
    {
        return $this->hasOne(CustomPackageOffer::class, 'source_request_uuid', 'uuid')
            ->latestOfMany('version');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(CustomPackageOfferRequestAttachment::class, 'request_uuid', 'uuid')
            ->latest();
    }

    public function workflowEvents(): HasMany
    {
        return $this->hasMany(CustomPackageWorkflowEvent::class, 'request_uuid', 'uuid')
            ->latest();
    }
}
