<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sita\SitaLead;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SitaLeadController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()?->can('sita.leads.view'), 404);

        $search = trim((string) $request->query('search', ''));
        $status = (string) $request->query('status', '');

        $leads = SitaLead::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%');
                });
            })
            ->when(in_array($status, SitaLead::STATUSES, true), fn ($query) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('pages.sita.leads.index', compact('leads', 'search', 'status'));
    }

    public function show(string $sitaLead)
    {
        abort_unless(auth()->user()?->can('sita.leads.view'), 404);

        $lead = SitaLead::where('uuid', $sitaLead)->firstOrFail();

        return view('pages.sita.leads.show', compact('lead'));
    }

    public function update(Request $request, string $sitaLead)
    {
        abort_unless(auth()->user()?->can('sita.leads.update'), 404);

        $lead = SitaLead::where('uuid', $sitaLead)->firstOrFail();
        $validated = $request->validate([
            'status' => ['required', Rule::in(SitaLead::STATUSES)],
        ]);

        $lead->update(['status' => $validated['status']]);

        return back()->with('success', 'Status prospek berhasil diperbarui.');
    }

    public function destroy(string $sitaLead)
    {
        abort_unless(auth()->user()?->can('sita.leads.update'), 404);

        $lead = SitaLead::where('uuid', $sitaLead)->firstOrFail();
        $lead->delete();

        return back()->with('success', 'Prospek berhasil dihapus.');
    }
}
