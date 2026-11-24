<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sita\SitaLead;
use App\Models\Sita\SitaLeadActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SitaLeadController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()?->can('sita.leads.view'), 404);

        $filters = $this->filters($request);

        $leads = $this->filteredQuery($filters)
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('pages.sita.leads.index', compact('leads', 'filters'));
    }

    public function show(string $sitaLead)
    {
        abort_unless(auth()->user()?->can('sita.leads.view'), 404);

        $lead = SitaLead::where('uuid', $sitaLead)->with(['activities' => fn ($q) => $q->latest()->limit(50)])->firstOrFail();

        return view('pages.sita.leads.show', compact('lead'));
    }

    public function update(Request $request, string $sitaLead)
    {
        abort_unless(auth()->user()?->can('sita.leads.update'), 404);

        $lead = SitaLead::where('uuid', $sitaLead)->firstOrFail();
        $validated = $request->validate([
            'status' => ['required', Rule::in(SitaLead::STATUSES)],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $from = $lead->status;
        $to = $validated['status'];
        $notes = $this->cleanNote($validated['notes'] ?? null);

        DB::transaction(function () use ($lead, $to, $notes, $from) {
            $changes = [];
            if ($lead->status !== $to) {
                $lead->status = $to;
                $changes['status'] = [$from, $to];
                if (in_array($to, ['contacted', 'qualified', 'converted'], true) && ! $lead->contacted_at) {
                    $lead->contacted_at = now();
                }
            }
            if (($lead->notes ?? null) !== $notes) {
                $changes['notes'] = ['changed', 'changed'];
                $lead->notes = $notes;
            }
            if (! empty($changes)) {
                $lead->save();
                SitaLeadActivity::create([
                    'lead_uuid' => $lead->uuid,
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()?->name,
                    'action' => 'updated',
                    'from_status' => $from !== $to ? $from : null,
                    'to_status' => $from !== $to ? $to : null,
                    'note' => $notes,
                    'meta' => ['changes' => array_keys($changes)],
                ]);
            }
        });

        return back()->with('success', 'Prospek berhasil diperbarui.');
    }

    public function storeNote(Request $request, string $sitaLead)
    {
        abort_unless(auth()->user()?->can('sita.leads.update'), 404);
        $lead = SitaLead::where('uuid', $sitaLead)->firstOrFail();
        $validated = $request->validate(['note' => ['required', 'string', 'max:2000']]);
        $note = $this->cleanNote($validated['note']);

        SitaLeadActivity::create([
            'lead_uuid' => $lead->uuid,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()?->name,
            'action' => 'note',
            'note' => $note,
        ]);

        return back()->with('success', 'Catatan ditambahkan.');
    }

    public function destroy(string $sitaLead)
    {
        abort_unless(auth()->user()?->can('sita.leads.update'), 404);

        $lead = SitaLead::where('uuid', $sitaLead)->firstOrFail();
        $lead->delete();

        return back()->with('success', 'Prospek berhasil dihapus.');
    }

    public function exportCsv(Request $request)
    {
        abort_unless(auth()->user()?->can('sita.leads.view'), 404);
        $filters = $this->filters($request);
        $filename = 'sita-leads-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($filters) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Tanggal', 'Nama', 'Telepon', 'Topik', 'Status', 'Skor', 'Catatan', 'Kriteria']);
            $this->filteredQuery($filters)->orderByDesc('created_at')->chunk(200, function ($leads) use ($out) {
                foreach ($leads as $lead) {
                    fputcsv($out, [
                        $lead->created_at?->format('Y-m-d H:i:s'),
                        $lead->name,
                        $lead->phone,
                        $lead->topic,
                        $lead->status,
                        $lead->score,
                        $lead->notes,
                        json_encode($lead->criteria, JSON_UNESCAPED_UNICODE),
                    ]);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filters(Request $request): array
    {
        $from = $this->parseDate($request->query('from'))?->startOfDay();
        $to = $this->parseDate($request->query('to'))?->endOfDay();
        if ($from && $to && $from->gt($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        return [
            'search' => trim((string) $request->query('search', '')),
            'status' => (string) $request->query('status', ''),
            'topic' => (string) $request->query('topic', ''),
            'score_min' => $request->filled('score_min') ? max(0, (int) $request->query('score_min')) : null,
            'score_max' => $request->filled('score_max') ? max(0, (int) $request->query('score_max')) : null,
            'from' => $from?->toDateString(),
            'to' => $to?->toDateString(),
        ];
    }

    private function filteredQuery(array $filters)
    {
        return SitaLead::query()
            ->when($filters['search'] !== '', function ($query) use ($filters) {
                $query->where(function ($inner) use ($filters) {
                    $inner->where('name', 'like', '%'.$filters['search'].'%')
                        ->orWhere('phone', 'like', '%'.$filters['search'].'%');
                });
            })
            ->when(in_array($filters['status'], SitaLead::STATUSES, true), fn ($q) => $q->where('status', $filters['status']))
            ->when(in_array($filters['topic'], ['packages', 'venues', 'vendors'], true), fn ($q) => $q->where('topic', $filters['topic']))
            ->when($filters['score_min'] !== null, fn ($q) => $q->where('score', '>=', $filters['score_min']))
            ->when($filters['score_max'] !== null, fn ($q) => $q->where('score', '<=', $filters['score_max']))
            ->when($filters['from'], fn ($q) => $q->where('created_at', '>=', Carbon::parse($filters['from'])->startOfDay()))
            ->when($filters['to'], fn ($q) => $q->where('created_at', '<=', Carbon::parse($filters['to'])->endOfDay()));
    }

    private function cleanNote(?string $note): ?string
    {
        $note = trim((string) $note);

        return $note === '' ? null : $note;
    }

    private function parseDate(mixed $value): ?Carbon
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }
        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
