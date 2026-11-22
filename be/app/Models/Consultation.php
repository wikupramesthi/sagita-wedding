<?php

namespace App\Models;

use App\Models\Package\Package;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Consultation extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected static function booted(): void
    {
        static::addGlobalScope('not_merged', function ($query) {
            $query->whereNull('consultations.merged_into_uuid');
        });

        static::saving(function (self $consultation) {
            $email = self::normalizeEmail($consultation->email);
            $whatsapp = self::normalizeWhatsapp($consultation->whatsapp);

            $consultation->email_normalized = $email;
            $consultation->whatsapp_e164 = $whatsapp;

            if (! $consultation->merged_into_uuid
                && ! $consultation->isDirty('email_dedupe_key')
                && (! $consultation->exists || $consultation->isDirty('email') || (! $consultation->email_dedupe_key && $email !== null))) {
                $consultation->email_dedupe_key = $email;
            }

            if (! $consultation->merged_into_uuid
                && ! $consultation->isDirty('whatsapp_dedupe_key')
                && (! $consultation->exists || $consultation->isDirty('whatsapp') || (! $consultation->whatsapp_dedupe_key && $whatsapp !== null))) {
                $consultation->whatsapp_dedupe_key = $whatsapp;
            }

            if ($consultation->isDirty('lead_status')) {
                $consultation->sort_order = (int) (static::query()
                    ->where('lead_status', $consultation->lead_status)
                    ->when($consultation->exists, fn ($query) => $query->where('uuid', '!=', $consultation->uuid))
                    ->max('sort_order') ?? -1) + 1;
            }
        });

        static::deleting(function (self $consultation) {
            if ($consultation->isForceDeleting()) {
                return;
            }

            DB::table('consultations')
                ->where('uuid', $consultation->uuid)
                ->update([
                    'email_dedupe_key' => null,
                    'whatsapp_dedupe_key' => null,
                ]);
        });

        static::restoring(function (self $consultation) {
            if ($consultation->merged_into_uuid) {
                return;
            }

            $consultation->email_dedupe_key = self::normalizeEmail($consultation->email);
            $consultation->whatsapp_dedupe_key = self::normalizeWhatsapp($consultation->whatsapp);
        });
    }

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

    public const AGING_LABELS = [
        'fresh' => '0–7 hari',
        'month' => '8–30 hari',
        'stale' => '31–60 hari',
        'old' => '> 60 hari',
    ];

    public const SLA_WARNING_HOURS = 8;

    public const SLA_BREACH_HOURS = 24;

    public const SLA_LABELS = [
        'done' => 'Selesai',
        'met' => 'Terespon',
        'safe' => 'Aman',
        'warning' => 'Mendekati SLA',
        'breach' => 'SLA Terlewat',
    ];

    public const WIP_LIMITS = [
        'New' => 20,
        'Contacted' => 20,
        'Interested' => 15,
        'Negotiation' => 10,
    ];

    public static function normalizeEmail(?string $email): ?string
    {
        $email = strtolower(trim((string) $email));

        return $email === '' ? null : $email;
    }

    public static function normalizeWhatsapp(?string $whatsapp): ?string
    {
        $number = preg_replace('/\D+/', '', (string) $whatsapp);

        if (! $number) {
            return null;
        }

        if (str_starts_with($number, '0')) {
            return '62'.substr($number, 1);
        }

        if (str_starts_with($number, '8')) {
            return '62'.$number;
        }

        return $number;
    }

    public static function normalizeFilters(array $filters): array
    {
        $status = (string) ($filters['status'] ?? '');
        $method = (string) ($filters['method'] ?? '');
        $lead = (string) ($filters['lead'] ?? '');
        $followUp = (string) ($filters['follow_up'] ?? '');
        $sla = (string) ($filters['sla'] ?? '');
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

        if (! array_key_exists($sla, self::SLA_LABELS)) {
            $sla = '';
        }

        $datePattern = '/^\d{4}-\d{2}-\d{2}$/';

        return [
            'q' => trim((string) ($filters['q'] ?? '')),
            'status' => $status,
            'method' => $method,
            'lead' => $lead,
            'source' => $source,
            'follow_up' => $followUp,
            'sla' => $sla,
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
            ->when($filters['follow_up'] === 'today', fn ($query) => $query
                ->whereDate('follow_up_at', today())
                ->whereNotIn('lead_status', ['Converted', 'Lost']))
            ->when($filters['follow_up'] === 'upcoming', fn ($query) => $query
                ->whereNotNull('follow_up_at')
                ->where('follow_up_at', '>', now())
                ->whereNotIn('lead_status', ['Converted', 'Lost']))
            ->when($filters['follow_up'] === 'done', fn ($query) => $query
                ->where(function ($query) {
                    $query->whereIn('lead_status', ['Converted', 'Lost'])
                        ->orWhereNotNull('converted_to_booking_at');
                }))
            ->when($filters['follow_up'] === 'none', fn ($query) => $query->whereNull('follow_up_at'))
            ->when($filters['sla'] === 'breach', fn ($query) => $query
                ->where('lead_status', 'New')
                ->where('created_at', '<', now()->subHours(self::SLA_BREACH_HOURS)))
            ->when($filters['sla'] === 'warning', fn ($query) => $query
                ->where('lead_status', 'New')
                ->where('created_at', '<', now()->subHours(self::SLA_WARNING_HOURS))
                ->where('created_at', '>=', now()->subHours(self::SLA_BREACH_HOURS)))
            ->when($filters['sla'] === 'safe', fn ($query) => $query
                ->where('lead_status', 'New')
                ->where('created_at', '>=', now()->subHours(self::SLA_WARNING_HOURS)))
            ->when($filters['sla'] === 'met', fn ($query) => $query
                ->whereNotIn('lead_status', ['New', 'Converted', 'Lost']))
            ->when($filters['sla'] === 'done', fn ($query) => $query
                ->whereIn('lead_status', ['Converted', 'Lost']));
    }

    public static function stats(array $filters = []): array
    {
        $filters = self::normalizeFilters($filters);
        $base = self::query();
        self::applyFilters($base, $filters);

        $statusCounts = (clone $base)
            ->selectRaw('consultation_status, COUNT(*) as total')
            ->groupBy('consultation_status')
            ->pluck('total', 'consultation_status')
            ->mapWithKeys(fn ($total, $status) => [strtolower((string) $status) => (int) $total]);

        $leadCounts = (clone $base)
            ->selectRaw('lead_status, COUNT(*) as total')
            ->groupBy('lead_status')
            ->pluck('total', 'lead_status')
            ->mapWithKeys(fn ($total, $status) => [strtolower((string) $status) => (int) $total]);

        $total = (int) $statusCounts->sum();
        $converted = (int) ($leadCounts['converted'] ?? 0);
        $overdue = (clone $base)
            ->whereNotNull('follow_up_at')
            ->where('follow_up_at', '<', now())
            ->whereNotIn('lead_status', ['Converted', 'Lost'])
            ->count();

        $slaBase = (clone $base)->where('lead_status', 'New');
        $slaBreach = (clone $slaBase)->where('created_at', '<', now()->subHours(self::SLA_BREACH_HOURS))->count();
        $slaWarning = (clone $slaBase)
            ->where('created_at', '<', now()->subHours(self::SLA_WARNING_HOURS))
            ->where('created_at', '>=', now()->subHours(self::SLA_BREACH_HOURS))
            ->count();

        $sourceRows = (clone $base)
            ->selectRaw("source, COUNT(*) as total, SUM(CASE WHEN lead_status = 'Converted' THEN 1 ELSE 0 END) as converted")
            ->groupBy('source')
            ->get()
            ->keyBy('source');

        $sourceConversion = collect(self::SOURCE_LABELS)
            ->map(function ($label, $source) use ($sourceRows) {
                $row = $sourceRows->get($source);
                $sourceTotal = (int) ($row->total ?? 0);
                $sourceConverted = (int) ($row->converted ?? 0);

                return [
                    'label' => $label,
                    'total' => $sourceTotal,
                    'converted' => $sourceConverted,
                    'rate' => $sourceTotal > 0 ? (int) round($sourceConverted / $sourceTotal * 100) : 0,
                ];
            })
            ->all();

        $agingBase = (clone $base)->whereNotIn('lead_status', ['Converted', 'Lost']);
        $now = now();
        $aging = [
            'fresh' => (clone $agingBase)->where('created_at', '>=', $now->copy()->subDays(7))->count(),
            'month' => (clone $agingBase)->where('created_at', '<', $now->copy()->subDays(7))->where('created_at', '>=', $now->copy()->subDays(30))->count(),
            'stale' => (clone $agingBase)->where('created_at', '<', $now->copy()->subDays(30))->where('created_at', '>=', $now->copy()->subDays(60))->count(),
            'old' => (clone $agingBase)->where('created_at', '<', $now->copy()->subDays(60))->count(),
        ];

        return [
            'total' => $total,
            'new' => (int) ($leadCounts['new'] ?? 0),
            'hot' => (int) ($leadCounts['interested'] ?? 0) + (int) ($leadCounts['negotiation'] ?? 0),
            'overdue' => $overdue,
            'sla_breach' => $slaBreach,
            'sla_warning' => $slaWarning,
            'converted' => $converted,
            'conversion_rate' => $total > 0 ? (int) round($converted / $total * 100) : 0,
            'source_conversion' => $sourceConversion,
            'aging' => $aging,
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
        'reminder_sent_at',
        'lead_status',
        'source',
        'converted_to_booking_at',
        'merged_into_uuid',
        'merged_at',
        'merged_by',
        'sort_order',
        'email_dedupe_key',
        'whatsapp_dedupe_key',
    ];

    protected $casts = [
        'wedding_date' => 'date:Y-m-d',
        'consultation_date' => 'date:Y-m-d',
        'follow_up_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'converted_to_booking_at' => 'datetime',
        'merged_at' => 'datetime',
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

        return min($score, 10);
    }

    private function slaState(): string
    {
        if (in_array($this->lead_status, ['Converted', 'Lost'], true)) {
            return 'done';
        }

        if ($this->lead_status !== 'New') {
            return 'met';
        }

        $hours = (int) ($this->created_at?->diffInHours(now()) ?? 0);

        return match (true) {
            $hours >= self::SLA_BREACH_HOURS => 'breach',
            $hours >= self::SLA_WARNING_HOURS => 'warning',
            default => 'safe',
        };
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

    public function getLeadJourneyAttribute(): array
    {
        $isConverted = $this->lead_status === 'Converted' || $this->converted_to_booking_at !== null;
        $isLost = $this->lead_status === 'Lost';
        $hasContact = $this->lead_status !== 'New' || $this->follow_up_at !== null;
        $hasSchedule = $this->consultation_date !== null;

        return [
            [
                'icon' => 'bx-user-plus',
                'title' => 'Lead masuk',
                'description' => 'Formulir diterima dari '.$this->source_label,
                'time' => $this->created_at?->translatedFormat('d M Y H:i') ?? '—',
                'state' => 'done',
            ],
            [
                'icon' => 'bx-phone-call',
                'title' => 'Kontak awal',
                'description' => $hasContact
                    ? 'Status '.$this->lead_status_label.'. '.$this->follow_up_label
                    : 'Belum ada kontak atau tindak lanjut tercatat',
                'time' => $hasContact
                    ? ($this->follow_up_at?->translatedFormat('d M Y H:i') ?? $this->updated_at?->translatedFormat('d M Y H:i') ?? '—')
                    : 'Menunggu',
                'state' => $hasContact ? 'done' : 'current',
            ],
            [
                'icon' => 'bx-calendar-check',
                'title' => 'Konsultasi dijadwalkan',
                'description' => $hasSchedule
                    ? $this->method_label.' · '.$this->status_label
                    : 'Jadwal konsultasi belum ditentukan',
                'time' => $hasSchedule
                    ? $this->consultation_date->translatedFormat('d M Y').($this->consultation_time ? ' · '.$this->consultation_time : '')
                    : 'Menunggu',
                'state' => $hasSchedule ? 'done' : ($hasContact ? 'current' : 'pending'),
            ],
            [
                'icon' => $isLost ? 'bx-x-circle' : 'bx-check-double',
                'title' => $isLost ? 'Lead ditutup' : 'Konversi booking',
                'description' => $isConverted
                    ? 'Pesanan berhasil dibuat'
                    : ($isLost ? 'Lead tidak dilanjutkan' : 'Menunggu keputusan pelanggan'),
                'time' => $isConverted
                    ? ($this->converted_to_booking_at?->translatedFormat('d M Y H:i') ?? $this->updated_at?->translatedFormat('d M Y H:i') ?? '—')
                    : ($isLost ? ($this->updated_at?->translatedFormat('d M Y H:i') ?? '—') : 'Menunggu'),
                'state' => $isConverted ? 'done' : ($isLost ? 'failed' : 'pending'),
            ],
        ];
    }

    public function getLeadJourneyProgressAttribute(): int
    {
        $steps = $this->lead_journey;
        $done = count(array_filter($steps, static fn (array $step): bool => $step['state'] === 'done'));

        return (int) round($done / count($steps) * 100);
    }

    public function getLeadJourneyDoneAttribute(): int
    {
        return count(array_filter(
            $this->lead_journey,
            static fn (array $step): bool => $step['state'] === 'done'
        ));
    }

    public function getLeadJourneyNextActionAttribute(): array
    {
        if ($this->lead_status === 'Converted' || $this->converted_to_booking_at !== null) {
            return [
                'tone' => 'success',
                'message' => 'Lead sudah dikonversi menjadi pesanan. Lanjutkan penyiapan layanan dari halaman pesanan.',
            ];
        }

        if ($this->lead_status === 'Lost') {
            return [
                'tone' => 'danger',
                'message' => 'Lead ditutup. Tidak ada tindak lanjut aktif untuk calon pelanggan ini.',
            ];
        }

        if ($this->follow_up_state === 'overdue') {
            return [
                'tone' => 'danger',
                'message' => 'Tindak lanjut terlewat. Hubungi pelanggan sekarang untuk menjaga respons.',
            ];
        }

        if ($this->follow_up_state === 'today') {
            return [
                'tone' => 'warning',
                'message' => 'Tindak lanjut dijadwalkan hari ini. Pastikan pelanggan dihubungi sesuai jadwal.',
            ];
        }

        if ($this->lead_status === 'New' && $this->follow_up_at === null) {
            return [
                'tone' => 'primary',
                'message' => 'Hubungi pelanggan untuk mengonfirmasi rencana dan kebutuhan acara.',
            ];
        }

        if ($this->consultation_date === null) {
            return [
                'tone' => 'primary',
                'message' => 'Tentukan jadwal konsultasi agar pelanggan masuk ke tahap berikutnya.',
            ];
        }

        return [
            'tone' => 'primary',
            'message' => 'Pantau tindak lanjut dan pastikan jadwal konsultasi tetap sesuai rencana.',
        ];
    }

    public function getWhatsappLinkAttribute(): string
    {
        $number = self::normalizeWhatsapp($this->whatsapp);

        return $number ? 'https://wa.me/'.$number : '#';
    }

    public function getWhatsappMessageAttribute(): string
    {
        $date = $this->wedding_date?->translatedFormat('d F Y') ?: 'tanggal yang akan ditentukan';

        return 'Halo '.$this->full_name.', kami dari Sagita Wedding ingin menindaklanjuti konsultasi Anda'.($this->wedding_date ? ' untuk rencana pernikahan '.$date : '').'. Apakah Bapak/Ibu masih tersedia untuk berdiskusi?';
    }

    public function getGoogleCalendarUrlAttribute(): string
    {
        if (! $this->consultation_date) {
            return '#';
        }

        $start = Carbon::parse(
            $this->consultation_date->toDateString().' '.($this->consultation_time ?: '10:00'),
            config('app.timezone')
        );
        $end = $start->copy()->addHour();

        return 'https://calendar.google.com/calendar/render?action=TEMPLATE'
            .'&text='.rawurlencode('Konsultasi Sagita Wedding - '.$this->full_name)
            .'&dates='.$start->utc()->format('Ymd\THis\Z').'/'.$end->utc()->format('Ymd\THis\Z')
            .'&details='.rawurlencode($this->whatsapp_message)
            .'&location='.rawurlencode((string) $this->venue);
    }

    public function getSlaStateAttribute(): string
    {
        return $this->slaState();
    }

    public function getSlaLabelAttribute(): string
    {
        return self::SLA_LABELS[$this->slaState()];
    }

    public function getSlaBadgeClassAttribute(): string
    {
        return match ($this->slaState()) {
            'done' => 'bg-success',
            'met' => 'bg-primary',
            'warning' => 'bg-warning text-dark',
            'breach' => 'bg-danger',
            default => 'bg-info text-dark',
        };
    }

    public function getSlaDeadlineAttribute(): ?CarbonInterface
    {
        return $this->created_at?->copy()->addHours(self::SLA_BREACH_HOURS);
    }

    public function getSlaDeadlineLabelAttribute(): string
    {
        if (in_array($this->lead_status, ['Converted', 'Lost'], true)) {
            return '—';
        }

        if ($this->lead_status !== 'New') {
            return 'Sudah ditindaklanjuti';
        }

        return $this->sla_deadline?->translatedFormat('d M Y H:i') ?? '—';
    }

    public function getAgingDaysAttribute(): int
    {
        return (int) ($this->created_at?->diffInDays(now()) ?? 0);
    }

    public function getAgingLabelAttribute(): string
    {
        $days = $this->aging_days;

        return match (true) {
            $days <= 7 => self::AGING_LABELS['fresh'],
            $days <= 30 => self::AGING_LABELS['month'],
            $days <= 60 => self::AGING_LABELS['stale'],
            default => self::AGING_LABELS['old'],
        };
    }

    public static function findDuplicates(?self $consultation = null, ?array $data = null, bool $lock = false)
    {
        $email = self::normalizeEmail($data['email'] ?? $consultation?->email);
        $whatsapp = self::normalizeWhatsapp($data['whatsapp'] ?? $consultation?->whatsapp);

        if (! $email && ! $whatsapp) {
            return collect();
        }

        return static::withoutGlobalScope('not_merged')
            ->whereNull('merged_into_uuid')
            ->where(function ($query) use ($email, $whatsapp) {
                if ($email) {
                    $query->orWhere('email_normalized', $email);
                }

                if ($whatsapp) {
                    $query->orWhere('whatsapp_e164', $whatsapp);
                }
            })
            ->when($consultation, fn ($query) => $query->where('uuid', '!=', $consultation->uuid))
            ->when($lock, fn ($query) => $query->lockForUpdate())
            ->with('handler:id,name')
            ->limit(100)
            ->get();
    }

    public static function duplicateGroups()
    {
        $rows = static::withoutGlobalScope('not_merged')
            ->whereNull('merged_into_uuid')
            ->get(['uuid', 'full_name', 'whatsapp', 'email', 'email_normalized', 'whatsapp_e164', 'source', 'lead_status', 'admin_notes', 'created_at']);

        $groups = [];

        foreach (['email_normalized' => 'Email', 'whatsapp_e164' => 'WhatsApp'] as $field => $label) {
            $rows->whereNotNull($field)
                ->filter(fn ($value) => trim((string) $value) !== '')
                ->groupBy($field)
                ->each(function ($records, $value) use (&$groups, $label) {
                    if ($records->count() > 1) {
                        $groups[] = [
                            'field' => $label,
                            'value' => $value,
                            'records' => $records->values(),
                        ];
                    }
                });
        }

        return collect($groups);
    }

    public function mergeInto(self $primary): void
    {
        $primaryScore = (int) $primary->lead_score;
        $duplicateScore = (int) $this->lead_score;

        $fields = [
            'email', 'whatsapp', 'wedding_date', 'guest_estimate', 'venue', 'package_id',
            'wedding_concept', 'consultation_date', 'consultation_time', 'consultation_method',
            'consultation_status', 'handled_by', 'follow_up_at', 'source',
            'converted_to_booking_at',
        ];

        foreach ($fields as $field) {
            if (blank($primary->{$field}) && filled($this->{$field})) {
                $primary->{$field} = $this->{$field};
            }
        }

        if ($duplicateScore > $primaryScore) {
            $primary->lead_status = $this->lead_status;
        }

        $note = 'Digabungkan dari '.$this->full_name.' ('.$this->uuid.') pada '.now()->translatedFormat('d F Y H:i');
        $primary->admin_notes = trim(($primary->admin_notes ?: '')."\n\n".$note."\n".($this->admin_notes ?: ''));

        DB::transaction(function () use ($primary) {
            $primary->save();

            $this->forceFill([
                'merged_into_uuid' => $primary->uuid,
                'merged_at' => now(),
                'merged_by' => auth()->id(),
            ])->save();
        });
    }

    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim((string) $this->full_name)) ?: [];

        return strtoupper(mb_substr($words[0] ?? '', 0, 1).mb_substr($words[1] ?? '', 0, 1));
    }
}
