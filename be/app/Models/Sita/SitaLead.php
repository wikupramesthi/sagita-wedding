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
    ];

    protected $casts = [
        'criteria' => 'array',
        'score' => 'integer',
    ];

    public const STATUSES = ['new', 'contacted', 'qualified', 'converted', 'closed'];

    public const CRITERIA_KEYS = [
        'budgetLimit',
        'guestCount',
        'city',
        'eventDate',
        'vendorCategory',
    ];
}
