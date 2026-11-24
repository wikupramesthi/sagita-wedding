<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment\Order;
use App\Models\Payment\OrderFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderFeedbackController extends Controller
{
    public function store(Request $request, string $orderUuid)
    {
        $order = Order::packageOnly()->where('uuid', $orderUuid)->firstOrFail();
        abort_unless($order->user_uuid === Auth::user()->uuid, 403);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($order, $validated): void {
            $lockedOrder = Order::packageOnly()
                ->where('uuid', $order->uuid)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                in_array($lockedOrder->status, ['partially_paid', 'paid'], true)
                    && $lockedOrder->event_date
                    && $lockedOrder->event_date->lt(today()),
                403
            );

            OrderFeedback::updateOrCreate(
                ['order_uuid' => $lockedOrder->uuid],
                [
                    'user_uuid' => Auth::user()->uuid,
                    'rating' => $validated['rating'],
                    'comment' => $validated['comment'] ?? null,
                    'submitted_at' => now(),
                ]
            );
        });

        return back()->with('success', 'Terima kasih. Masukan Anda berhasil disimpan.');
    }
}
