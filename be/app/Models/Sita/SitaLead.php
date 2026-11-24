<?php

namespace App\Models\Sita;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SitaLead extends Model
{
    use HasUuids;

    protected $table = 'sita_leads';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'phone',
        'topic',
        'criteria',
        'score',
        'message',
        'status',
        'notes',
        'contacted_at',
        'assigned_to',
    ];

    protected $casts = [
        'criteria' => 'array',
        'score' => 'integer',
        'contacted_at' => 'datetime',
    ];

    public function activities()
    {
        return $this->hasMany(SitaLeadActivity::class, 'lead_uuid', 'uuid')->latest();
    }

    public const STATUSES = ['new', 'contacted', 'qualified', 'converted', 'closed'];

    public const CRITERIA_KEYS = [
        'budgetLimit',
        'guestCount',
        'city',
        'eventDate',
        'vendorCategory',
    ];
}
