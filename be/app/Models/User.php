<?php

namespace App\Models;

use App\Models\Class\ClassBooking;
use App\Models\Class\ClassModel;
use App\Models\Package\Package;
use App\Models\Payment\Order;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// payment
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
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

    public static function sourceStats(callable $baseQuery): array
    {
        $keys = array_keys(self::SOURCE_LABELS);
        $stats = [];

        foreach ($keys as $key) {
            $stats[$key] = $baseQuery()->where('sumber_informasi', $key)->count();
        }

        $stats[self::SOURCE_UNKNOWN_KEY] = $baseQuery()
            ->where(function ($query) use ($keys) {
                $query->whereNull('sumber_informasi')
                    ->orWhereNotIn('sumber_informasi', $keys);
            })
            ->count();

        return $stats;
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

    public function classes()
    {
        return $this->hasMany(
            ClassModel::class,
            'vendor_uuid',
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

    public function bookings()
    {
        return $this->hasMany(
            ClassBooking::class,
            'user_uuid',
            'uuid'
        );
    }
}
