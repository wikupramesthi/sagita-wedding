<?php

namespace App\Services;

use App\Models\EmailSetting;
use Illuminate\Support\Facades\Schema;
use Throwable;

class EmailSettingService
{
    public static function apply(): void
    {
        try {
            if (! Schema::hasTable('email_settings')) {
                return;
            }

            $setting = EmailSetting::query()->where('is_active', true)->first();

            if (! $setting) {
                return;
            }

            config([
                'mail.default' => $setting->mail_driver ?: config('mail.default', 'log'),
                'mail.mailers.smtp.host' => $setting->mail_host ?: config('mail.mailers.smtp.host'),
                'mail.mailers.smtp.port' => (int) ($setting->mail_port ?: config('mail.mailers.smtp.port', 2525)),
                'mail.mailers.smtp.encryption' => $setting->mail_encryption ?: config('mail.mailers.smtp.encryption'),
                'mail.mailers.smtp.username' => $setting->mail_username ?: config('mail.mailers.smtp.username'),
                'mail.mailers.smtp.password' => $setting->mail_password ?: config('mail.mailers.smtp.password'),
                'mail.mailers.smtp.timeout' => (int) ($setting->mail_timeout ?: 60),
            ]);

            if (filled($setting->from_address)) {
                config(['mail.from.address' => $setting->from_address]);
            }

            if (filled($setting->from_name)) {
                config(['mail.from.name' => $setting->from_name]);
            }
        } catch (Throwable) {
            return;
        }
    }
}
