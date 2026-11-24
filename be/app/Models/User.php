<?php

namespace App\Models;

use App\Models\Package\CustomPackageOffer;
use App\Models\Package\CustomPackageOfferRequest;
use App\Models\Package\Package;
use App\Models\Payment\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// payment
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    public const PROSPECT_STATUS_LABELS = [
        'new_registered' => 'Baru Terdaftar',
        'interested' => 'Tertarik',
        'consultation' => 'Konsultasi',
        'negotiation' => 'Negosiasi',
        'customer' => 'Menjadi Pelanggan',
        'inactive' => 'Tidak Aktif',
    ];

    public const PROSPECT_STATUS_DESCRIPTIONS = [
        'new_registered' => 'Baru mendaftar menggunakan akun Google.',
        'interested' => 'Sudah menunjukkan ketertarikan terhadap layanan Sagita.',
        'consultation' => 'Sudah mengajukan konsultasi.',
        'negotiation' => 'Sedang membahas paket, harga, dan kebutuhan pernikahan.',
        'customer' => 'Sudah menyetujui pemesanan atau melakukan booking.',
        'inactive' => 'Tidak ada tindak lanjut atau tidak menunjukkan aktivitas.',
    ];

    public const SOURCE_LABELS = [
        'google' => 'Google',
        'sosmed' => 'Media Sosial',
        'friend' => 'Rekomendasi Teman',
        'community' => 'Komunitas',
        'event' => 'Acara / Pameran',
        'website' => 'Website Sagita',
        'other' => 'Lainnya',
    ];

    public const SOURCE_UNKNOWN_KEY = 'unknown';

    public const SOURCE_UNKNOWN_LABEL = 'Tanpa keterangan';

    public const SOURCE_ICONS = [
        'google' => 'bxl-google',
        'sosmed' => 'bx-share-alt',
        'friend' => 'bx-user-check',
        'community' => 'bx-group',
        'event' => 'bx-calendar-event',
        'website' => 'bx-globe',
        'other' => 'bx-dots-horizontal-rounded',
        'unknown' => 'bx-help-circle',
    ];

    public static function sourceFilterOptions(): array
    {
        return self::SOURCE_LABELS + [self::SOURCE_UNKNOWN_KEY => self::SOURCE_UNKNOWN_LABEL];
    }

    public static function normalizeProspectFilters(array $filters): array
    {
        $status = (string) ($filters['status'] ?? '');
        $sumber = (string) ($filters['sumber'] ?? '');
        $startDate = (string) ($filters['start_date'] ?? '');
        $endDate = (string) ($filters['end_date'] ?? '');

        if (! array_key_exists($status, self::PROSPECT_STATUS_LABELS)) {
            $status = '';
        }

        if ($sumber !== self::SOURCE_UNKNOWN_KEY && ! array_key_exists($sumber, self::SOURCE_LABELS)) {
            $sumber = '';
        }

        $datePattern = '/^\d{4}-\d{2}-\d{2}$/';

        return [
            'q' => trim((string) ($filters['q'] ?? '')),
            'status' => $status,
            'sumber' => $sumber,
            'start_date' => preg_match($datePattern, $startDate) ? $startDate : '',
            'end_date' => preg_match($datePattern, $endDate) ? $endDate : '',
        ];
    }

    public static function applyProspectFilters($query, array $filters): void
    {
        $query->when($filters['q'] !== '', function ($query) use ($filters) {
            $query->where(function ($query) use ($filters) {
                $query->where('name', 'like', "%{$filters['q']}%")
                    ->orWhere('email', 'like', "%{$filters['q']}%")
                    ->orWhere('no_hp', 'like', "%{$filters['q']}%");
            });
        })
            ->when($filters['status'] !== '', fn ($query) => $query->where('prospect_status', $filters['status']))
            ->when($filters['sumber'] !== '', function ($query) use ($filters) {
                if ($filters['sumber'] === self::SOURCE_UNKNOWN_KEY) {
                    $query->where(function ($query) {
                        $query->whereNull('sumber_informasi')
                            ->orWhereNotIn('sumber_informasi', array_keys(self::SOURCE_LABELS));
                    });

                    return;
                }

                $query->where('sumber_informasi', $filters['sumber']);
            })
            ->when($filters['start_date'] !== '', fn ($query) => $query->whereDate('created_at', '>=', $filters['start_date']))
            ->when($filters['end_date'] !== '', fn ($query) => $query->whereDate('created_at', '<=', $filters['end_date']));
    }

    public static function prospectStats(callable $baseQuery): array
    {
        $knownSources = array_keys(self::SOURCE_LABELS);

        $sourceRows = $baseQuery()
            ->selectRaw('users.sumber_informasi as source, COUNT(*) as total')
            ->groupBy('users.sumber_informasi')
            ->pluck('total', 'source')
            ->map(fn ($total) => (int) $total);

        $statusRows = $baseQuery()
            ->selectRaw('users.prospect_status as prospect_status, COUNT(*) as total')
            ->groupBy('users.prospect_status')
            ->pluck('total', 'prospect_status')
            ->map(fn ($total) => (int) $total);

        $sources = [];

        foreach (array_keys(self::sourceFilterOptions()) as $key) {
            if ($key === self::SOURCE_UNKNOWN_KEY) {
                $sources[$key] = (int) $sourceRows
                    ->reject(fn ($total, $source) => in_array($source, $knownSources, true))
                    ->sum();

                continue;
            }

            $sources[$key] = (int) ($sourceRows[$key] ?? 0);
        }

        $statuses = [];

        foreach (array_keys(self::PROSPECT_STATUS_LABELS) as $key) {
            $statuses[$key] = (int) ($statusRows[$key] ?? 0);
        }

        return [
            'total' => array_sum($sources),
            'sources' => $sources,
            'statuses' => $statuses,
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'avatar',
        'name',
        'password',
        'email_verified_at',
        'email',
        'google_id',
        'no_hp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'pengalaman',
        'is_active',
        'alamat',
        'facebook',
        'instagram',
        'twitter',
        'tiktok',
        'youtube',
        'biografi',
        'sumber_informasi',
        'prospect_status',
        'prospect_status_updated_at',
        'consultation_uuid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthIdentifierName()
    {
        return 'uuid';
    }

    public function routeNotificationForWhatsApp(): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->no_hp) ?? '';

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (str_starts_with($digits, '8')) {
            return '62'.$digits;
        }

        return str_starts_with($digits, '62') ? $digits : null;
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'prospect_status_updated_at' => 'datetime',

            'membership_start_date' => 'date',
            'membership_end_date' => 'date',
            'total_quota' => 'integer',
            'remaining_quota' => 'integer',
        ];
    }

    /**
     * Auto-generate UUID when creating user.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = Str::uuid()->toString();
            }
        });
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (! $this->avatar) {
            return null;
        }

        if (Str::startsWith($this->avatar, ['http://', 'https://'])) {
            return $this->avatar;
        }

        $path = ltrim($this->avatar, '/');

        if ($path === '' || Str::contains($path, '..')) {
            return null;
        }

        return Storage::disk('public')->exists($path) ? asset('storage/'.$path) : null;
    }

    public function getProspectStatusLabelAttribute(): string
    {
        return self::PROSPECT_STATUS_LABELS[$this->prospect_status] ?? self::PROSPECT_STATUS_LABELS['new_registered'];
    }

    public function getProspectStatusDescriptionAttribute(): string
    {
        return self::PROSPECT_STATUS_DESCRIPTIONS[$this->prospect_status] ?? self::PROSPECT_STATUS_DESCRIPTIONS['new_registered'];
    }

    public function getProspectStatusBadgeClassAttribute(): string
    {
        return match ($this->prospect_status) {
            'interested' => 'bg-info text-dark',
            'consultation' => 'bg-primary',
            'negotiation' => 'bg-warning text-dark',
            'customer' => 'bg-success',
            'inactive' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getSumberInformasiLabelAttribute(): string
    {
        return self::SOURCE_LABELS[$this->sumber_informasi] ?? '-';
    }

    public function latestOrder()
    {
        return $this->hasOne(Order::class, 'user_uuid', 'uuid')->latestOfMany('created_at');
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class, 'consultation_uuid', 'uuid');
    }

    public function userPackages()
    {
        return $this->hasMany(
            UserPackage::class,
            'user_uuid',
            'uuid'
        );
    }

    /**
     * Relationship to Kecamatan.
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    /**
     * Relationship to Kelurahan.
     */
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function vendors_categories()
    {
        return $this->belongsToMany(
            VendorsCategory::class,
            'user_vendors_category',
            'user_uuid',
            'vendors_category_uuid',
            'uuid',
            'uuid'
        )->using(UserVendorsCategory::class);
    }

    public function package()
    {
        return $this->belongsTo(
            Package::class,
            'package_uuid',
            'uuid'
        );
    }

    public function orders()
    {
        return $this->hasMany(
            Order::class,
            'user_uuid',
            'uuid'
        );
    }

    public function customPackageOffers()
    {
        return $this->hasMany(
            CustomPackageOffer::class,
            'user_uuid',
            'uuid'
        );
    }

    public function customPackageOfferRequests()
    {
        return $this->hasMany(
            CustomPackageOfferRequest::class,
            'user_uuid',
            'uuid'
        );
    }
}
