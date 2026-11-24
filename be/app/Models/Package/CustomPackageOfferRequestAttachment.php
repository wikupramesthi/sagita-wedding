<?php

namespace App\Models\Package;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomPackageOfferRequestAttachment extends Model
{
    use HasUuids;

    protected $table = 'custom_package_offer_request_attachments';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'request_uuid',
        'uploaded_by_uuid',
        'storage_path',
        'original_name',
        'mime_type',
        'size_bytes',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];

    public function packageRequest(): BelongsTo
    {
        return $this->belongsTo(CustomPackageOfferRequest::class, 'request_uuid', 'uuid');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_uuid', 'uuid');
    }
}
