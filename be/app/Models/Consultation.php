<?php

namespace App\Models;

use App\Models\Package\Package;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'consultations';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    public const GUEST_ESTIMATES = [
        'Di bawah 200 tamu',
        '200–500 tamu',
        '500–1.000 tamu',
        'Di atas 1.000 tamu',
    ];

    public const METHOD_LABELS = [
        'Online' => 'Online (Zoom / Google Meet)',
        'Offline' => 'Offline (Datang ke Kantor)',
    ];

    public const STATUS_LABELS = [
        'Pending' => 'Menunggu',
        'Confirmed' => 'Dikonfirmasi',
        'Rescheduled' => 'Dijadwalkan Ulang',
        'Completed' => 'Selesai',
        'Cancelled' => 'Dibatalkan',
        'No Show' => 'Tidak Hadir',
    ];

    public const LEAD_STATUS_LABELS = [
        'New' => 'Baru',
        'Contacted' => 'Sudah Dihubungi',
        'Interested' => 'Berminat',
        'Negotiation' => 'Negosiasi',
        'Converted' => 'Terkonversi',
        'Lost' => 'Tidak Lanjut',
    ];

    protected $fillable = [
        'full_name',
        'whatsapp',
        'wedding_date',
        'guest_estimate',
        'venue',
        'package_id',
        'wedding_concept',
        'consultation_date',
        'consultation_time',
        'consultation_method',
        'consultation_status',
        'admin_notes',
        'handled_by',
        'follow_up_at',
        'lead_status',
        'source',
        'converted_to_booking_at',
    ];

    protected $casts = [
        'wedding_date' => 'date:Y-m-d',
        'consultation_date' => 'date:Y-m-d',
        'follow_up_at' => 'datetime',
        'converted_to_booking_at' => 'datetime',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by', 'id');
    }

    public function getMethodLabelAttribute(): string
    {
        return self::METHOD_LABELS[$this->consultation_method] ?? '—';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->consultation_status] ?? '—';
    }

    public function getLeadStatusLabelAttribute(): string
    {
        return self::LEAD_STATUS_LABELS[$this->lead_status] ?? '—';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->consultation_status) {
            'Confirmed' => 'bg-primary',
            'Rescheduled' => 'bg-info text-dark',
            'Completed' => 'bg-success',
            'Cancelled', 'No Show' => 'bg-danger',
            default => 'bg-warning text-dark',
        };
    }

    public function getLeadStatusBadgeClassAttribute(): string
    {
        return match ($this->lead_status) {
            'Contacted' => 'bg-info text-dark',
            'Interested' => 'bg-primary',
            'Negotiation' => 'bg-warning text-dark',
            'Converted' => 'bg-success',
            'Lost' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
