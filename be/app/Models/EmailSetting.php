<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailSetting extends Model
{
    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_encryption',
        'mail_username',
        'mail_password',
        'mail_timeout',
        'from_address',
        'from_name',
        'is_active',
    ];

    protected $casts = [
        'mail_port' => 'integer',
        'mail_timeout' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'mail_password',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model): void {
            if (blank($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public static function singleton(): self
    {
        $identity = WebsiteIdentity::singleton();

        return static::firstOrCreate([], [
            'mail_driver' => config('mail.default', 'log'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_password' => config('mail.mailers.smtp.password'),
            'mail_timeout' => 60,
            'from_address' => $identity->email ?: config('mail.from.address'),
            'from_name' => $identity->site_name ?: config('app.name'),
            'is_active' => true,
        ]);
    }

    public static function syncFromWebsiteIdentity(?WebsiteIdentity $identity = null, ?string $previousEmail = null, ?string $previousName = null): void
    {
        $setting = static::singleton();
        $identity ??= WebsiteIdentity::singleton();
        $updates = [];

        if (filled($identity->email) && (blank($setting->from_address) || ($previousEmail !== null && $setting->from_address === $previousEmail))) {
            $updates['from_address'] = $identity->email;
        }

        if (filled($identity->site_name) && (blank($setting->from_name) || ($previousName !== null && $setting->from_name === $previousName))) {
            $updates['from_name'] = $identity->site_name;
        }

        if ($updates !== []) {
            $setting->update($updates);
        }
    }

    public function hasPassword(): bool
    {
        return filled($this->mail_password);
    }
}
