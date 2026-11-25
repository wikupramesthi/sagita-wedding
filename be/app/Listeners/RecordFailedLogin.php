<?php

namespace App\Listeners;

use App\Models\FailedLogin;
use App\Services\Security\BruteForceProtector;
use App\Services\TelegramAdminNotifier;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\RateLimiter;

class RecordFailedLogin
{
    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'] ?? $event->user?->email;
        $ip = request()->ip();

        FailedLogin::create([
            'email' => $email,
            'user_uuid' => $event->user?->getAuthIdentifier(),
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'reason' => 'credentials',
            'attempted_at' => now(),
        ]);

        if (! $event->user && is_string($email) && trim($email) !== '') {
            $key = 'telegram-unregistered-login:'.hash('sha256', strtolower(trim($email)).'|'.$ip);
            RateLimiter::hit($key, 15 * 60);
            $attempts = RateLimiter::attempts($key);

            if ($attempts === 3) {
                app(TelegramAdminNotifier::class)->unregisteredLoginAttempt($email, (string) $ip, $attempts);
            }
        }

        try {
            app(BruteForceProtector::class)->recordFailure($ip, $email);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
