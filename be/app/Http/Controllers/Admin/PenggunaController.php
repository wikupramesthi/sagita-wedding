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
        $q = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');
        $sumber = (string) $request->query('sumber', '');

        if (! array_key_exists($status, User::PROSPECT_STATUS_LABELS)) {
            $status = '';
        }

        if ($sumber !== User::SOURCE_UNKNOWN_KEY && ! array_key_exists($sumber, User::SOURCE_LABELS)) {
            $sumber = '';
        }

        $baseQuery = fn () => User::role('user');

        $users = $baseQuery()
            ->with(['userPackages.package', 'latestOrder.package'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('no_hp', 'like', "%{$q}%");
                });
            })
            ->when($status !== '', fn ($query) => $query->where('prospect_status', $status))
            ->when($sumber !== '', function ($query) use ($sumber) {
                if ($sumber === User::SOURCE_UNKNOWN_KEY) {
                    $query->where(function ($query) {
                        $query->whereNull('sumber_informasi')
                            ->orWhereNotIn('sumber_informasi', array_keys(User::SOURCE_LABELS));
                    });

                    return;
                }

                $query->where('sumber_informasi', $sumber);
            })
            ->when($request->filled('tahun'), fn ($query) => $query->whereYear('created_at', $request->tahun))
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('created_at', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('created_at', '<=', $request->end_date))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => $baseQuery()->count(),
            'sources' => User::sourceStats($baseQuery),
        ];

        foreach (User::PROSPECT_STATUS_LABELS as $key => $label) {
            $stats[$key] = $baseQuery()->where('prospect_status', $key)->count();
        }

        $sourceOptions = User::sourceFilterOptions();

        return view('pages.dashboard.pengguna', compact('users', 'stats', 'q', 'status', 'sumber', 'sourceOptions'));
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
