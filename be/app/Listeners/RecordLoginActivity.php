<?php

namespace App\Listeners;

use App\Models\LoginActivity;
use App\Models\User;
use App\Services\Security\BruteForceProtector;
use App\Services\TelegramAdminNotifier;
use Illuminate\Auth\Events\Login;

class RecordLoginActivity
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        $ip = request()->ip();

        LoginActivity::create([
            'user_uuid' => $user?->getAuthIdentifier(),
            'name' => $user?->name,
            'email' => $user?->email,
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'guard' => $event->guard,
            'logged_in_at' => now(),
        ]);

        try {
            app(BruteForceProtector::class)->clear($ip, $user?->email);
        } catch (\Throwable $e) {
            report($e);
        }

        if ($user instanceof User) {
            try {
                app(TelegramAdminNotifier::class)->successfulLogin($user, (string) $ip);
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }
}
