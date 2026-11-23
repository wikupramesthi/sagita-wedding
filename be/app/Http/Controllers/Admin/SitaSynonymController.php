<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sita\SitaSynonym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class SitaSynonymController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('sita.synonyms.manage'), 404);

        $synonyms = SitaSynonym::query()
            ->orderBy('locale')
            ->orderBy('term')
            ->orderBy('sort_order')
            ->paginate(20)
            ->withQueryString();

        return view('pages.sita.synonyms.index', compact('synonyms'));
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
