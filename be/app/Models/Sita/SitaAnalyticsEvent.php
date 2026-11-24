<?php

namespace App\Models\Sita;

use Illuminate\Database\Eloquent\Model;

class SitaAnalyticsEvent extends Model
{
    protected $table = 'sita_analytics_events';

    protected $fillable = [
        'name',
        'topic',
        'query_hash',
        'query_sample',
        'turn_id',
        'confidence',
        'fallback',
        'clarify',
        'helpful',
        'response_ms',
        'path',
        'occurred_at',
    ];

    protected $casts = [
        'confidence' => 'float',
        'fallback' => 'boolean',
        'clarify' => 'boolean',
        'helpful' => 'boolean',
        'response_ms' => 'integer',
        'occurred_at' => 'datetime',
    ];
}
