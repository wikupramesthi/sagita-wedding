<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Payment\Order;
use App\Models\Payment\WeddingChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class WeddingChecklistController extends Controller
{
    public function store(Request $request, string $order)
    {
        abort_unless($this->canManageAsAdmin(), 403);

        $orderModel = Order::packageOnly()->where('uuid', $order)->firstOrFail();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_at' => ['nullable', 'date'],
            'status' => ['nullable', 'in:pending,completed'],
        ]);

        $status = $validated['status'] ?? 'pending';
        $completed = $status === 'completed';

        $item = WeddingChecklistItem::create([
            'order_uuid' => $orderModel->uuid,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_at' => $validated['due_at'] ?? null,
            'status' => $status,
            'completed_at' => $completed ? now() : null,
            'completed_by' => $completed ? Auth::user()->uuid : null,
        ]);

        $this->logChecklistAction($orderModel, 'checklist_created', [
            'item_uuid' => $item->uuid,
            'title' => $item->title,
            'status' => $item->status,
        ]);

        return back()->with('success', 'Checklist persiapan berhasil ditambahkan.');
    }

    public function update(Request $request, string $orderUuid, string $itemUuid)
    {
        $order = Order::packageOnly()->where('uuid', $orderUuid)->firstOrFail();
        $isAdmin = $this->canManageAsAdmin();

        abort_unless($isAdmin || $order->user_uuid === Auth::user()->uuid, 403);

        if (! $isAdmin) {
            abort_if(! $order->event_date || $order->event_date->lt(today()), 403);
        }

        $rules = [
            'status' => ['required', 'in:pending,completed'],
        ];

        if ($isAdmin) {
            $rules['title'] = ['sometimes', 'required', 'string', 'max:255'];
            $rules['description'] = ['nullable', 'string'];
            $rules['due_at'] = ['nullable', 'date'];
        }

        $validated = $request->validate($rules);

        $item = WeddingChecklistItem::where('uuid', $itemUuid)
            ->where('order_uuid', $order->uuid)
            ->firstOrFail();

        $completed = $validated['status'] === 'completed';
        $payload = [
            'status' => $validated['status'],
            'completed_at' => $completed ? ($item->completed_at ?: now()) : null,
            'completed_by' => $completed ? Auth::user()->uuid : null,
        ];

        if ($isAdmin) {
            if (array_key_exists('title', $validated)) {
                $payload['title'] = $validated['title'];
            }

            if (array_key_exists('description', $validated)) {
                $payload['description'] = $validated['description'] ?: null;
            }

            if (array_key_exists('due_at', $validated)) {
                $payload['due_at'] = $validated['due_at'] ?: null;
            }
        }

        $item->update($payload);

        $this->logChecklistAction($order, 'checklist_updated', [
            'item_uuid' => $item->uuid,
            'status' => $item->status,
        ]);

        return back()->with('success', 'Checklist persiapan berhasil diperbarui.');
    }

    public function destroy(Request $request, string $order, string $itemUuid)
    {
        abort_unless($this->canManageAsAdmin(), 403);

        $orderModel = Order::packageOnly()->where('uuid', $order)->firstOrFail();
        $item = WeddingChecklistItem::where('uuid', $itemUuid)
            ->where('order_uuid', $orderModel->uuid)
            ->firstOrFail();
        $item->delete();

        $this->logChecklistAction($orderModel, 'checklist_deleted', [
            'item_uuid' => $itemUuid,
            'title' => $item->title,
        ]);

        return back()->with('success', 'Checklist persiapan berhasil dihapus.');
    }

    private function canManageAsAdmin(): bool
    {
        $user = Auth::user();

        return $user->hasAnyRole(['admin', 'super-admin']) || $user->can('orders.checklist.manage');
    }

    private function logChecklistAction(Order $order, string $event, array $newValues = []): void
    {
        $user = Auth::user();

        try {
            AuditLog::create([
                'user_uuid' => $user?->uuid,
                'user_name' => $user?->name ?? 'Sistem',
                'user_email' => $user?->email,
                'event' => $event,
                'auditable_type' => Order::class,
                'auditable_id' => (string) $order->uuid,
                'auditable_label' => $order->order_number,
                'new_values' => $newValues,
                'url' => request()->fullUrl(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Wedding checklist audit log failed: '.$e->getMessage(), ['order' => $order->uuid]);
        }
    }
}
