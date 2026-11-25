<?php

namespace App\Models\Package;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomPackageOfferRequestMessageRead extends Model
{
    protected $table = 'custom_package_offer_request_message_reads';

    protected $fillable = [
        'message_uuid',
        'user_uuid',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(CustomPackageOfferRequestMessage::class, 'message_uuid', 'uuid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
