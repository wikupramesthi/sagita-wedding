<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeddingPlan extends Model
{
    public const BUDGET_CATEGORIES = [
        'venue' => 'Venue',
        'catering' => 'Katering',
        'decoration' => 'Dekorasi',
        'documentation' => 'Dokumentasi',
        'attire' => 'Busana & rias',
        'other' => 'Lainnya',
    ];

    public const TASKS = [
        'date_venue' => 'Tentukan tanggal dan venue',
        'budget' => 'Susun anggaran pernikahan',
        'vendors' => 'Pilih vendor utama',
        'guest_list' => 'Susun daftar tamu',
        'attire' => 'Tentukan busana dan rias',
        'final_details' => 'Finalisasi detail acara',
    ];

    protected $table = 'wedding_plans';

    protected $primaryKey = 'user_uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_uuid',
        'event_date',
        'total_budget',
        'budget_breakdown',
        'completed_tasks',
    ];

    protected $casts = [
        'event_date' => 'date',
        'total_budget' => 'decimal:2',
        'budget_breakdown' => 'array',
        'completed_tasks' => 'array',
    ];
}
