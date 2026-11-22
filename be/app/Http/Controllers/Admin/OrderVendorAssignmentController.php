<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Payment\Order;
use App\Models\Payment\OrderVendorAssignment;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Throwable;

class OrderVendorAssignmentController extends Controller
{
    public function assign(Request $request, string $order)
    {
        abort_unless(Gate::allows('manageVendors', Order::class), 403);

        $record = Order::packageOnly()->where('uuid', $order)->firstOrFail();

        $validated = $request->validate([
            'vendor_uuid' => ['required', 'uuid', Rule::exists('users', 'uuid')],
            'scope' => ['required', 'string', 'max:80'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $vendor = User::role('vendor')->where('uuid', $validated['vendor_uuid'])->first();
        if (! $vendor) {
            return back()->withErrors(['vendor_uuid' => 'Vendor tidak valid.'])->withInput();
        }

        try {
            $assignment = OrderVendorAssignment::firstOrCreate(
                [
                    'order_uuid' => $record->uuid,
                    'vendor_uuid' => $validated['vendor_uuid'],
                    'scope' => $validated['scope'],
                ],
                [
                    'status' => OrderVendorAssignment::STATUS_ASSIGNED,
                    'notes' => $validated['notes'] ?? null,
                    'assigned_by' => Auth::user()?->uuid,
                ]
            );
        } catch (QueryException $exception) {
            if (! str_contains($exception->getMessage(), 'order_vendor_scope_unique')) {
                throw $exception;
            }

            return back()->withErrors(['scope' => 'Vendor sudah ditugaskan untuk scope ini.'])->withInput();
        }

        if (! $assignment->wasRecentlyCreated) {
            return back()->withErrors(['scope' => 'Vendor sudah ditugaskan untuk scope ini.'])->withInput();
        }

        $this->log($record, 'vendor_assigned', [
            'assignment_uuid' => $assignment->uuid,
            'vendor_uuid' => $assignment->vendor_uuid,
            'vendor_name' => $vendor->name,
            'scope' => $assignment->scope,
            'status' => $assignment->status,
        ]);

        return back()->with('success', 'Vendor berhasil ditugaskan.');
    }

    public function updateStatus(Request $request, string $order, string $assignment)
    {
        abort_unless(Gate::allows('manageVendors', Order::class), 403);

        $record = Order::packageOnly()->where('uuid', $order)->firstOrFail();
        $vendorAssignment = OrderVendorAssignment::where('uuid', $assignment)
            ->where('order_uuid', $record->uuid)
            ->firstOrFail();

        $validated = $request->validate([
            'status' => ['required', Rule::in([
                OrderVendorAssignment::STATUS_ASSIGNED,
                OrderVendorAssignment::STATUS_ACCEPTED,
                OrderVendorAssignment::STATUS_IN_PROGRESS,
                OrderVendorAssignment::STATUS_COMPLETED,
                OrderVendorAssignment::STATUS_CANCELLED,
            ])],
        ]);

        $oldStatus = $vendorAssignment->status;
        $status = $validated['status'];
        $updates = ['status' => $status];

        if (in_array($status, [OrderVendorAssignment::STATUS_ACCEPTED, OrderVendorAssignment::STATUS_IN_PROGRESS, OrderVendorAssignment::STATUS_COMPLETED], true)
            && ! $vendorAssignment->accepted_at) {
            $updates['accepted_at'] = now();
        }
        if ($status === OrderVendorAssignment::STATUS_COMPLETED) {
            $updates['completed_at'] = now();
        } elseif ($vendorAssignment->completed_at) {
            $updates['completed_at'] = null;
        }

        $vendorAssignment->update($updates);

        $this->log($record, 'vendor_status_changed', [
            'assignment_uuid' => $vendorAssignment->uuid,
            'vendor_uuid' => $vendorAssignment->vendor_uuid,
            'scope' => $vendorAssignment->scope,
            'old_status' => $oldStatus,
            'new_status' => $status,
        ]);

        return back()->with('success', 'Status vendor berhasil diperbarui.');
    }

    public function destroy(string $order, string $assignment)
    {
        abort_unless(Gate::allows('manageVendors', Order::class), 403);

        $record = Order::packageOnly()->where('uuid', $order)->firstOrFail();
        $vendorAssignment = OrderVendorAssignment::where('uuid', $assignment)
            ->where('order_uuid', $record->uuid)
            ->firstOrFail();

        $payload = [
            'assignment_uuid' => $vendorAssignment->uuid,
            'vendor_uuid' => $vendorAssignment->vendor_uuid,
            'scope' => $vendorAssignment->scope,
            'status' => $vendorAssignment->status,
        ];
        $vendorAssignment->delete();

        $this->log($record, 'vendor_unassigned', $payload);

        return back()->with('success', 'Penugasan vendor dihapus.');
    }

    private function log(Order $order, string $event, array $newValues): void
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
        } catch (Throwable) {
        }
    }
}
