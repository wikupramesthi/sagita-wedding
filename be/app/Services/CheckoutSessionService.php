<?php

namespace App\Services;

use App\Models\Payment\CheckoutSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutSessionService
{
    public function start(Request $request, string $packageUuid): void
    {
        $attempts = $request->session()->get('checkout_attempts', []);
        $visitorKey = $attempts[$packageUuid] ?? null;
        $userUuid = $request->user()?->hasRole('user')
            ? $request->user()->uuid
            : null;
        $checkout = $visitorKey
            ? CheckoutSession::where('visitor_key', $visitorKey)->first()
            : null;

        if (! $checkout || $checkout->status === 'completed') {
            $visitorKey = (string) Str::uuid();
            $checkout = CheckoutSession::create([
                'visitor_key' => $visitorKey,
                'package_uuid' => $packageUuid,
                'user_uuid' => $userUuid,
                'status' => 'started',
                'started_at' => now(),
            ]);
        } elseif ($userUuid && ! $checkout->user_uuid) {
            $checkout->update(['user_uuid' => $userUuid]);
        }

        $attempts[$packageUuid] = $checkout->visitor_key;
        $request->session()->put('checkout_attempts', $attempts);
    }

    public function complete(Request $request, string $packageUuid, string $orderUuid): void
    {
        $attempts = $request->session()->get('checkout_attempts', []);
        $visitorKey = $attempts[$packageUuid] ?? null;

        if ($visitorKey) {
            CheckoutSession::where('visitor_key', $visitorKey)
                ->where('package_uuid', $packageUuid)
                ->where('status', 'started')
                ->update([
                    'order_uuid' => $orderUuid,
                    'status' => 'completed',
                    'completed_at' => now(),
                    'updated_at' => now(),
                ]);
        }

        unset($attempts[$packageUuid]);
        $request->session()->put('checkout_attempts', $attempts);
    }
}
