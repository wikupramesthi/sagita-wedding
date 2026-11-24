<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sita\SitaSynonym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SitaSynonymController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()?->can('sita.synonyms.manage'), 404);

        $search = trim((string) $request->query('q', ''));
        $locale = (string) $request->query('locale', '');
        $topic = (string) $request->query('topic', '');
        $active = (string) $request->query('is_active', '');

        $synonyms = SitaSynonym::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('term', 'like', '%'.$search.'%')
                        ->orWhere('canonical', 'like', '%'.$search.'%');
                });
            })
            ->when(in_array($locale, ['id', 'en'], true), fn ($query) => $query->where('locale', $locale))
            ->when(in_array($topic, ['packages', 'venues', 'vendors'], true), fn ($query) => $query->where('topic', $topic))
            ->when($active === '1' || $active === '0', fn ($query) => $query->where('is_active', (int) $active))
            ->orderBy('locale')
            ->orderBy('term')
            ->orderBy('sort_order')
            ->paginate(20)
            ->withQueryString();

        $filters = compact('search', 'locale', 'topic', 'active');

        return view('pages.sita.synonyms.index', compact('synonyms', 'filters'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()?->can('sita.synonyms.manage'), 404);

        $validated = $this->validated($request);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        SitaSynonym::create($validated);
        Cache::forget(SitaSynonym::CACHE_KEY);

        return back()->with('success', 'Sinonim berhasil ditambahkan.');
    }

    public function update(Request $request, string $sitaSynonym)
    {
        abort_unless(auth()->user()?->can('sita.synonyms.manage'), 404);

        $synonym = SitaSynonym::where('uuid', $sitaSynonym)->firstOrFail();
        $validated = $this->validated($request, $synonym->uuid);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $synonym->update($validated);
        Cache::forget(SitaSynonym::CACHE_KEY);

        return back()->with('success', 'Sinonim berhasil diperbarui.');
    }

    public function destroy(string $sitaSynonym)
    {
        abort_unless(auth()->user()?->can('sita.synonyms.manage'), 404);

        $synonym = SitaSynonym::where('uuid', $sitaSynonym)->firstOrFail();
        $synonym->delete();
        Cache::forget(SitaSynonym::CACHE_KEY);

        return back()->with('success', 'Sinonim berhasil dihapus.');
    }

    public function bulkAction(Request $request)
    {
        abort_unless(auth()->user()?->can('sita.synonyms.manage'), 404);
        $validated = $request->validate([
            'action' => ['required', Rule::in(['activate', 'deactivate', 'delete'])],
            'uuids' => ['required', 'array', 'min:1', 'max:200'],
            'uuids.*' => ['string'],
        ]);

        $uuids = array_values(array_unique($validated['uuids']));
        $query = SitaSynonym::query()->whereIn('uuid', $uuids);
        $count = (clone $query)->count();

        if ($count === 0) {
            return back()->with('error', 'Tidak ada sinonim yang dipilih.');
        }

        if ($validated['action'] === 'delete') {
            $query->delete();
            $message = $count.' sinonim dihapus.';
        } else {
            $query->update(['is_active' => $validated['action'] === 'activate']);
            $message = $count.' sinonim diperbarui.';
        }

        Cache::forget(SitaSynonym::CACHE_KEY);

        return back()->with('success', $message);
    }

    public function exportCsv(Request $request)
    {
        abort_unless(auth()->user()?->can('sita.synonyms.manage'), 404);
        $filename = 'sita-synonyms-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($request) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['locale', 'term', 'canonical', 'topic', 'sort_order', 'is_active']);
            $this->filteredQuery($request)->orderBy('locale')->orderBy('term')->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $row) {
                    fputcsv($out, [$row->locale, $row->term, $row->canonical, $row->topic, $row->sort_order, $row->is_active ? 1 : 0]);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function importCsv(Request $request)
    {
        abort_unless(auth()->user()?->can('sita.synonyms.manage'), 404);
        $validated = $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $handle = fopen($validated['csv_file']->getRealPath(), 'r');
        if (! $handle) {
            return back()->with('error', 'File CSV tidak dapat dibaca.');
        }

        $header = fgetcsv($handle);
        $map = $this->csvMap($header ?: []);
        if (! isset($map['term'], $map['canonical'])) {
            fclose($handle);

            return back()->with('error', 'Kolom wajib: term, canonical. Opsional: locale, topic, sort_order, is_active.');
        }

        $count = 0;
        DB::transaction(function () use ($handle, $map, &$count) {
            while (($row = fgetcsv($handle)) !== false) {
                if (count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                    continue;
                }
                $locale = in_array($row[$map['locale']] ?? 'id', ['id', 'en'], true) ? $row[$map['locale']] : 'id';
                $term = trim((string) ($row[$map['term']] ?? ''));
                $canonical = trim((string) ($row[$map['canonical']] ?? ''));
                if ($term === '' || $canonical === '') {
                    continue;
                }
                $topic = trim((string) ($row[$map['topic']] ?? ''));
                $topic = in_array($topic, ['packages', 'venues', 'vendors'], true) ? $topic : null;
                $sortOrder = (int) ($row[$map['sort_order']] ?? 0);
                $isActive = isset($map['is_active']) ? filter_var($row[$map['is_active']] ?? '1', FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) : true;
                SitaSynonym::updateOrCreate(
                    ['locale' => $locale, 'term' => mb_strtolower($term)],
                    ['canonical' => mb_strtolower($canonical), 'topic' => $topic, 'sort_order' => $sortOrder, 'is_active' => $isActive ?? true]
                );
                $count++;
            }
        });
        fclose($handle);
        Cache::forget(SitaSynonym::CACHE_KEY);

        return back()->with('success', $count.' baris sinonim diproses.');
    }

    private function filteredQuery(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $locale = (string) $request->query('locale', '');
        $topic = (string) $request->query('topic', '');
        $active = (string) $request->query('is_active', '');

        return SitaSynonym::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('term', 'like', '%'.$search.'%')
                        ->orWhere('canonical', 'like', '%'.$search.'%');
                });
            })
            ->when(in_array($locale, ['id', 'en'], true), fn ($query) => $query->where('locale', $locale))
            ->when(in_array($topic, ['packages', 'venues', 'vendors'], true), fn ($query) => $query->where('topic', $topic))
            ->when($active === '1' || $active === '0', fn ($query) => $query->where('is_active', (int) $active));
    }

    private function csvMap(array $header): array
    {
        $map = [];
        foreach ($header as $i => $col) {
            $key = strtolower(trim((string) preg_replace('/^\xEF\xBB\xBF/', '', $col)));
            $map[$key] = $i;
        }

        return $map;
    }

    private function validated(Request $request, ?string $ignoreUuid = null): array
    {
        return $request->validate([
            'locale' => ['required', 'string', 'size:2', Rule::in(['id', 'en'])],
            'term' => [
                'required', 'string', 'max:80',
                Rule::unique('sita_synonyms', 'term')->where(fn ($query) => $query->where('locale', $request->input('locale')))->ignore($ignoreUuid, 'uuid'),
            ],
            'canonical' => ['required', 'string', 'max:80'],
            'topic' => ['nullable', 'string', Rule::in(['packages', 'venues', 'vendors'])],
            'sort_order' => ['nullable', 'integer', 'min:-1000', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ], [], [
            'locale' => 'bahasa',
            'term' => 'istilah',
            'canonical' => 'bentuk baku',
            'topic' => 'topik',
        ]);
    }
}
