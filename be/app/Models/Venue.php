<?php

namespace App\Models;

use App\Models\Class\ClassModel;
use App\Models\Class\ClassSchedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Venue extends Model
{
    use HasUuids;

    protected $table = 'venues';

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'excerpt',
        'description',
        'address',
        'phone',
        'email',
        'google_maps_url',
        'image',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($venue) {
            if (empty($venue->uuid)) {
                $venue->uuid = (string) Str::uuid();
            }

            if (empty($venue->slug)) {
                $venue->slug = Str::slug($venue->name);
            }
        });

        static::updating(function ($venue) {
            if ($venue->isDirty('name')) {
                $venue->slug = Str::slug($venue->name);
            }
        });
    }

    /**
     * Venue schedules
     */
    public function schedules()
    {
        return $this->hasMany(
            ClassSchedule::class,
            'venue_uuid',
            'uuid'
        );
    }

    /**
     * Classes available at this venue
     */
    public function classes()
    {
        return $this->belongsToMany(
            ClassModel::class,
            'class_schedules',
            'venue_uuid',
            'class_uuid',
            'uuid',
            'uuid'
        )->distinct();
    }
}
