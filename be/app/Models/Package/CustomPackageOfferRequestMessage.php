<?php

namespace App\Models\Package;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomPackageOfferRequestMessage extends Model
{
    use HasUuids;

    protected $table = 'custom_package_offer_request_messages';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'request_uuid',
        'actor_uuid',
        'body',
    ];

    public function packageRequest(): BelongsTo
    {
        return $this->belongsTo(CustomPackageOfferRequest::class, 'request_uuid', 'uuid');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_uuid', 'uuid');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(CustomPackageOfferRequestMessageRead::class, 'message_uuid', 'uuid');
    }
}
