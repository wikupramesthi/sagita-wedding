<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $kategori = (string) $request->query('kategori', '');

        if (! in_array($kategori, Faq::CATEGORIES, true)) {
            $kategori = '';
        }

        $items = Faq::query()
            ->when($kategori !== '', fn ($query) => $query->where('kategori', $kategori))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('pertanyaan', 'like', "%{$search}%")
                        ->orWhere('jawaban', 'like', "%{$search}%");
                });
            })
            ->orderByKategori()
            ->get();

        $counts = Faq::query()
            ->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        $statusCounts = Faq::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('pages.faq.index', [
            'title' => 'Master Data',
            'items' => $items,
            'categories' => Faq::CATEGORIES,
            'defaultKategori' => Faq::KATEGORI_DEFAULT,
            'kategori' => $kategori,
            'search' => $search,
            'counts' => $counts,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function create()
    {
        return view('pages.faq.index', [
            'title' => 'Master Data',
            'items' => Faq::orderByKategori()->get(),
            'categories' => Faq::CATEGORIES,
            'defaultKategori' => Faq::KATEGORI_DEFAULT,
            'kategori' => '',
            'search' => '',
            'counts' => collect(),
            'statusCounts' => collect(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateFaq($request);

        try {
            DB::transaction(fn () => Faq::create($validated));

            return redirect()->route('faq.index')->with('success', 'FAQ berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()->with('error', 'Gagal menambahkan FAQ. Silakan coba lagi.')->withInput();
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update($uuid, Request $request)
    {
        $item = Faq::where('uuid', $uuid)->firstOrFail();
        $validated = $this->validateFaq($request, $item);

        try {
            DB::transaction(fn () => $item->update($validated));

            return redirect()->route('faq.index')->with('success', 'FAQ berhasil diperbarui.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()->with('error', 'Gagal memperbarui FAQ. Silakan coba lagi.')->withInput();
        }
    }

    public function destroy($uuid)
    {
        try {
            DB::transaction(fn () => Faq::where('uuid', $uuid)->firstOrFail()->delete());

            return redirect()->route('faq.index')->with('success', 'FAQ berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()->with('error', 'Gagal menghapus FAQ. Silakan coba lagi.');
        }
    }

    private function validateFaq(Request $request, ?Faq $faq = null): array
    {
        $urutanRule = Rule::unique('faqs', 'urutan')
            ->where(fn ($query) => $query->where('kategori', $request->input('kategori')));

        if ($faq) {
            $urutanRule->ignore($faq->uuid, 'uuid');
        }

        return $request->validate([
            'pertanyaan' => ['required', 'string', 'max:255'],
            'kategori' => ['required', Rule::in(Faq::CATEGORIES)],
            'jawaban' => ['required', 'string'],
            'urutan' => ['required', 'integer', 'min:1', $urutanRule],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ], $this->validationMessages(), $this->validationAttributes());
    }

    private function validationMessages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'integer' => ':attribute harus berupa angka.',
            'min' => ':attribute minimal :min.',
            'in' => 'Pilihan :attribute tidak valid.',
            'unique' => ':attribute sudah digunakan.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'pertanyaan' => 'Pertanyaan',
            'kategori' => 'Kategori',
            'jawaban' => 'Jawaban',
            'urutan' => 'Urutan',
            'status' => 'Status',
        ];
    }
}
