<?php

namespace App\Models\Sita;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SitaSynonym extends Model
{
    use HasUuids;

    protected $table = 'sita_synonyms';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CACHE_KEY = 'sita.synonyms.active.v1';

    protected $fillable = [
        'locale',
        'term',
        'canonical',
        'topic',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
