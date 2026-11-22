<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment\OrderWorkflowSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderWorkflowSettingsController extends Controller
{
    public function edit()
    {
        abort_unless(Auth::user()?->hasAnyRole(['super-admin', 'admin']), 403);

        return view('pages.orders.workflow-settings', [
            'settings' => OrderWorkflowSetting::current(),
        ]);
    }

    public function update(Request $request)
    {
        abort_unless(Auth::user()?->hasAnyRole(['super-admin', 'admin']), 403);

        $validated = $request->validate([
            'contract_template' => ['required', 'string', 'max:12000'],
            'checklist' => ['required', 'array', 'min:1', 'max:20'],
            'checklist.*.title' => ['required', 'string', 'max:120'],
            'checklist.*.description' => ['nullable', 'string', 'max:500'],
            'checklist.*.weeks_before_event' => ['required', 'integer', 'min:0', 'max:104'],
        ]);

        $settings = OrderWorkflowSetting::current();
        $settings->update([
            'contract_template' => trim($validated['contract_template']),
            'checklist_template' => array_values(array_map(
                fn (array $item): array => [
                    'title' => trim($item['title']),
                    'description' => trim((string) ($item['description'] ?? '')),
                    'weeks_before_event' => (int) $item['weeks_before_event'],
                ],
                $validated['checklist']
            )),
            'updated_by' => Auth::user()->uuid,
        ]);

        return back()->with('success', 'Template kontrak dan checklist berhasil diperbarui. Perubahan berlaku untuk pesanan baru.');
    }
}
