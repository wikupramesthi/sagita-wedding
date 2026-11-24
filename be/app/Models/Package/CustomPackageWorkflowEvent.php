<?php

namespace App\Models\Package;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomPackageWorkflowEvent extends Model
{
    use HasUuids;

    protected $table = 'custom_package_workflow_events';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'actor_uuid',
        'request_uuid',
        'offer_uuid',
        'event_key',
        'from_status',
        'to_status',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_uuid', 'uuid');
    }

    public function packageRequest(): BelongsTo
    {
        return $this->belongsTo(CustomPackageOfferRequest::class, 'request_uuid', 'uuid');
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(CustomPackageOffer::class, 'offer_uuid', 'uuid');
    }
}
