<?php

namespace App\Models\Package;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageBlackoutDate extends Model
{
    use HasUuids;

    protected $table = 'package_blackout_dates';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'package_uuid',
        'date',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(
            Package::class,
            'package_uuid',
            'uuid'
        );
    }
}
