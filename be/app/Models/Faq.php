<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Faq extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'Perencanaan Pernikahan',
        'Paket & Layanan',
        'Pemesanan & Pembayaran',
        'Vendor & Mitra',
        'Pelaksanaan Acara',
        'Perubahan & Pembatalan',
    ];

    public const KATEGORI_DEFAULT = self::CATEGORIES[0];

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['uuid', 'pertanyaan', 'kategori', 'jawaban', 'urutan', 'status'];

    public function scopeKategori($query, ?string $kategori)
    {
        if (filled($kategori)) {
            $query->where('kategori', $kategori);
        }

        return $query;
    }

    public function scopeOrderByKategori($query)
    {
        $cases = collect(self::CATEGORIES)
            ->map(fn (string $category, int $index) => "when ? then {$index}")
            ->implode(' ');

        return $query->orderByRaw(
            "case kategori {$cases} else ".count(self::CATEGORIES).' end',
            self::CATEGORIES,
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
