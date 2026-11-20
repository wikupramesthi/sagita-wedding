<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PenggunaExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $filters = User::normalizeProspectFilters($request->all());
        $baseQuery = fn () => User::role('user');

        $query = $baseQuery()->with(['userPackages.package', 'latestOrder.package']);
        User::applyProspectFilters($query, $filters);

        $users = $query->latest()->paginate(10)->withQueryString();
        $stats = User::prospectStats($baseQuery);

        return view('pages.dashboard.pengguna', compact('users', 'stats') + [
            'sourceOptions' => User::sourceFilterOptions(),
        ] + $filters);
    }

    public function updateStatus(Request $request, User $user)
    {
        if (! $user->hasRole('user')) {
            abort(404);
        }

        $validated = $request->validate([
            'prospect_status' => ['required', Rule::in(array_keys(User::PROSPECT_STATUS_LABELS))],
        ], [
            'prospect_status.required' => 'Status calon pelanggan wajib dipilih.',
            'prospect_status.in' => 'Status calon pelanggan tidak valid.',
        ]);

        $user->update([
            'prospect_status' => $validated['prospect_status'],
            'prospect_status_updated_at' => now(),
        ]);

        return back()->with('success', 'Status calon pelanggan berhasil diperbarui.');
    }

    public function export(Request $request)
    {
        return Excel::download(
            new PenggunaExport($request),
            'calon-pelanggan-'.now()->format('Ymd-His').'.xlsx'
        );
    }
}
