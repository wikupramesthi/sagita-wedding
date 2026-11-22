<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment\Order;
use App\Models\Payment\WeddingChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeddingChecklistController extends Controller
{
    public function update(Request $request, string $orderUuid, string $itemUuid)
    {
        $order = Order::packageOnly()->where('uuid', $orderUuid)->firstOrFail();
        abort_unless($order->user_uuid === Auth::user()->uuid, 403);
        abort_if(! $order->event_date || $order->event_date->lt(today()), 403);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,completed'],
        ]);

        $item = WeddingChecklistItem::where('uuid', $itemUuid)
            ->where('order_uuid', $order->uuid)
            ->firstOrFail();

        $completed = $validated['status'] === 'completed';
        $item->update([
            'status' => $validated['status'],
            'completed_at' => $completed ? now() : null,
            'completed_by' => $completed ? Auth::user()->uuid : null,
        ]);

        return back()->with('success', 'Checklist persiapan berhasil diperbarui.');
    }
}
