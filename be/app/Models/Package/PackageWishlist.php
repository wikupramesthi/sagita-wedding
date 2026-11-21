<?php

namespace App\Models\Package;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageWishlist extends Model
{
    use HasUuids;

    protected $table = 'package_wishlists';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_uuid',
        'package_uuid',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_uuid',
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
}
