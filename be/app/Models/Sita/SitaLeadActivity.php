<?php

namespace App\Models\Sita;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SitaLeadActivity extends Model
{
    protected $table = 'sita_lead_activities';

    protected $fillable = [
        'lead_uuid',
        'user_id',
        'user_name',
        'action',
        'from_status',
        'to_status',
        'note',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(SitaLead::class, 'lead_uuid', 'uuid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
