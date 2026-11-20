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

    public const FOLLOW_UP_LABELS = [
        'overdue' => 'Terlewat',
        'today' => 'Hari ini',
        'upcoming' => 'Terjadwal',
        'done' => 'Selesai',
        'none' => 'Belum dijadwalkan',
    ];

    public const SOURCE_LABELS = [
        'google' => 'Google',
        'sosmed' => 'Media Sosial',
        'friend' => 'Rekomendasi Teman',
        'community' => 'Komunitas',
        'event' => 'Acara / Pameran',
        'website' => 'Website Sagita',
        'other' => 'Lainnya',
        'unknown' => 'Tanpa keterangan',
    ];

    public static function normalizeFilters(array $filters): array
    {
        $status = (string) ($filters['status'] ?? '');
        $method = (string) ($filters['method'] ?? '');
        $lead = (string) ($filters['lead'] ?? '');
        $followUp = (string) ($filters['follow_up'] ?? '');
        $source = (string) ($filters['source'] ?? '');
        $startDate = (string) ($filters['start_date'] ?? '');
        $endDate = (string) ($filters['end_date'] ?? '');

        if (! array_key_exists($status, self::STATUS_LABELS)) {
            $status = '';
        }

        if (! array_key_exists($method, self::METHOD_LABELS)) {
            $method = '';
        }

        if (! array_key_exists($lead, self::LEAD_STATUS_LABELS)) {
            $lead = '';
        }

        if (! array_key_exists($followUp, self::FOLLOW_UP_LABELS)) {
            $followUp = '';
        }

        if (! array_key_exists($source, self::SOURCE_LABELS)) {
            $source = '';
        }

        $datePattern = '/^\d{4}-\d{2}-\d{2}$/';

        return [
            'q' => trim((string) ($filters['q'] ?? '')),
            'status' => $status,
            'method' => $method,
            'lead' => $lead,
            'source' => $source,
            'follow_up' => $followUp,
            'start_date' => preg_match($datePattern, $startDate) ? $startDate : '',
            'end_date' => preg_match($datePattern, $endDate) ? $endDate : '',
        ];
    }

    public static function applyFilters($query, array $filters): void
    {
        $query->when($filters['q'] !== '', function ($query) use ($filters) {
            $query->where(function ($query) use ($filters) {
                $query->where('full_name', 'like', "%{$filters['q']}%")
                    ->orWhere('email', 'like', "%{$filters['q']}%")
                    ->orWhere('whatsapp', 'like', "%{$filters['q']}%")
                    ->orWhere('venue', 'like', "%{$filters['q']}%")
                    ->orWhere('wedding_concept', 'like', "%{$filters['q']}%");
            });
        })
            ->when($filters['status'] !== '', fn ($query) => $query->where('consultation_status', $filters['status']))
            ->when($filters['method'] !== '', fn ($query) => $query->where('consultation_method', $filters['method']))
            ->when($filters['lead'] !== '', fn ($query) => $query->where('lead_status', $filters['lead']))
            ->when($filters['source'] !== '', fn ($query) => $query->where('source', $filters['source']))
            ->when($filters['start_date'] !== '', fn ($query) => $query->whereDate('created_at', '>=', $filters['start_date']))
            ->when($filters['end_date'] !== '', fn ($query) => $query->whereDate('created_at', '<=', $filters['end_date']))
            ->when($filters['follow_up'] === 'overdue', fn ($query) => $query
                ->whereNotNull('follow_up_at')
                ->where('follow_up_at', '<', now())
                ->whereNotIn('lead_status', ['Converted', 'Lost']))
            ->when($filters['follow_up'] === 'today', fn ($query) => $query->whereDate('follow_up_at', today()))
            ->when($filters['follow_up'] === 'upcoming', fn ($query) => $query
                ->whereNotNull('follow_up_at')
                ->where('follow_up_at', '>', now()))
            ->when($filters['follow_up'] === 'none', fn ($query) => $query->whereNull('follow_up_at'));
    }

    public static function stats(): array
    {
        $statusCounts = self::query()
            ->selectRaw('consultation_status, COUNT(*) as total')
            ->groupBy('consultation_status')
            ->pluck('total', 'consultation_status')
            ->mapWithKeys(fn ($total, $status) => [strtolower((string) $status) => (int) $total]);

        $leadCounts = self::query()
            ->selectRaw('lead_status, COUNT(*) as total')
            ->groupBy('lead_status')
            ->pluck('total', 'lead_status')
            ->mapWithKeys(fn ($total, $status) => [strtolower((string) $status) => (int) $total]);

        $total = (int) $statusCounts->sum();
        $converted = (int) ($leadCounts['converted'] ?? 0);
        $overdue = (int) self::query()
            ->whereNotNull('follow_up_at')
            ->where('follow_up_at', '<', now())
            ->whereNotIn('lead_status', ['Converted', 'Lost'])
            ->count();

        return [
            'total' => $total,
            'new' => (int) ($leadCounts['new'] ?? 0),
            'hot' => (int) ($leadCounts['interested'] ?? 0) + (int) ($leadCounts['negotiation'] ?? 0),
            'overdue' => $overdue,
            'converted' => $converted,
            'conversion_rate' => $total > 0 ? (int) round($converted / $total * 100) : 0,
        ];
    }

    protected $fillable = [
        'full_name',
        'email',
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

    public function getSourceLabelAttribute(): string
    {
        return self::SOURCE_LABELS[$this->source] ?? ($this->source ?: 'Tanpa keterangan');
    }

    public function getSourceIconAttribute(): string
    {
        return User::SOURCE_ICONS[$this->source] ?? 'bx-help-circle';
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

    private function leadScore(): int
    {
        if ($this->lead_status === 'Lost') {
            return 0;
        }

        $score = match ($this->lead_status) {
            'Negotiation', 'Converted' => 3,
            'Interested' => 2,
            'Contacted' => 1,
            default => 0,
        };

        $score += $this->wedding_date ? 1 : 0;
        $score += $this->guest_estimate ? 1 : 0;
        $score += $this->venue ? 1 : 0;
        $score += $this->package_id ? 2 : 0;
        $score += $this->consultation_date ? 2 : 0;
        $score += in_array($this->consultation_status, ['Confirmed', 'Completed'], true) ? 1 : 0;

        return $score;
    }

    private function followUpState(): string
    {
        if (! $this->follow_up_at) {
            return 'none';
        }

        if (in_array($this->lead_status, ['Converted', 'Lost'], true)) {
            return 'done';
        }

        if ($this->follow_up_at->isPast()) {
            return 'overdue';
        }

        if ($this->follow_up_at->isToday()) {
            return 'today';
        }

        return 'upcoming';
    }

    public function getLeadScoreAttribute(): int
    {
        return $this->leadScore();
    }

    public function getLeadPriorityAttribute(): string
    {
        $score = $this->leadScore();

        return match (true) {
            $score >= 6 => 'Tinggi',
            $score >= 3 => 'Sedang',
            default => 'Rendah',
        };
    }

    public function getLeadPriorityBadgeClassAttribute(): string
    {
        return match ($this->lead_priority) {
            'Tinggi' => 'bg-danger',
            'Sedang' => 'bg-warning text-dark',
            default => 'bg-secondary',
        };
    }

    public function getLeadPriorityIconAttribute(): string
    {
        return match ($this->lead_priority) {
            'Tinggi' => 'bx bxs-flame',
            'Sedang' => 'bx bx-trending-up',
            default => 'bx bx-minus',
        };
    }

    public function getFollowUpStateAttribute(): string
    {
        return $this->followUpState();
    }

    public function getFollowUpLabelAttribute(): string
    {
        return self::FOLLOW_UP_LABELS[$this->followUpState()];
    }

    public function getFollowUpBadgeClassAttribute(): string
    {
        return match ($this->followUpState()) {
            'overdue' => 'bg-danger',
            'today' => 'bg-warning text-dark',
            'upcoming' => 'bg-info text-dark',
            'done' => 'bg-success',
            default => 'bg-secondary',
        };
    }

    public function getFollowUpDateLabelAttribute(): string
    {
        return $this->follow_up_at?->translatedFormat('d M Y H:i') ?? '—';
    }

    public function getWhatsappLinkAttribute(): string
    {
        $number = (string) preg_replace('/\D+/', '', (string) $this->whatsapp);

        if ($number === '') {
            return '#';
        }

        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        }

        return 'https://wa.me/'.$number;
    }

    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim((string) $this->full_name)) ?: [];

        return strtoupper(mb_substr($words[0] ?? '', 0, 1).mb_substr($words[1] ?? '', 0, 1));
    }
}
