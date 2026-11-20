<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $status = $request->get('status', '');
        $status = in_array($status, ['draft', 'published', 'scheduled'], true) ? $status : '';

        $baseQuery = Article::query()
            ->when($start_date, fn ($query) => $query->whereDate('created_at', '>=', $start_date))
            ->when($end_date, fn ($query) => $query->whereDate('created_at', '<=', $end_date))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search).'%';
                $query->where(function ($w) use ($like) {
                    $w->where('title', 'like', $like)->orWhere('slug', 'like', $like);
                });
            });

        // Stats are computed from the filtered query (no pagination),
        // so the cards always match the filtered data.
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'published' => (clone $baseQuery)->where('status', 'published')->count(),
            'draft' => (clone $baseQuery)->where('status', 'draft')->count(),
            'scheduled' => (clone $baseQuery)->where('status', 'scheduled')->count(),
        ];

        $articles = $baseQuery
            ->with(['user', 'category'])
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('pages.articles.index', compact(
            'articles',
            'stats',
            'search',
            'start_date',
            'end_date',
            'status'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('slug', 'asc')->get();

        return view('pages.articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->validationRules(),
            $this->validationMessages(),
            $this->validationAttributes()
        );

        $article = new Article;
        $article->uuid = Str::uuid();
        $article->user_uuid = auth()->user()->uuid;
        $article->category_uuid = $validated['category_uuid'];
        $article->title = $validated['title'];
        $article->slug = $this->generateUniqueSlug($validated['title']);
        $article->excerpt = $validated['excerpt'] ?? null;
        $article->content = $validated['content'];
        $article->scheduled_at = $validated['scheduled_at'] ?? null;
        $article->tagging = $validated['tagging'] ?? null;
        $article->video = $validated['video'] ?? null;
        $article->status = $validated['status'];
        $article->search_engine = $validated['search_engine'];

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('images', 'public');
            $article->featured_image = $path;
        }

        $article->save();

        $article->seo()->updateOrCreate([], [
            'title' => $article->title,
            'description' => $article->excerpt,
            'image' => $article->featured_image,
            'author' => auth()->user()->name,
            'robots' => $validated['search_engine'] ?? 'index, follow',
            'canonical_url' => config('frontend.url').'/blog/'.$article->slug,
        ]);

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $categories = Category::orderBy('slug', 'asc')->get();

        return view('pages.articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($uuid, Request $request)
    {
        $validated = $request->validate(
            $this->validationRules($uuid),
            $this->validationMessages(),
            $this->validationAttributes()
        );

        DB::beginTransaction();

        $newImage = null;
        $oldImage = null;

        try {
            $article = Article::where('uuid', $uuid)->firstOrFail();
            $oldImage = $article->featured_image;
            $titleChanged = $article->title !== $validated['title'];

            if ($request->hasFile('featured_image')) {
                $newImage = $request->file('featured_image')->store('images', 'public');
                $article->featured_image = $newImage;
            }

            $article->update([
                'category_uuid' => $validated['category_uuid'],
                'title' => $validated['title'],
                'slug' => $titleChanged ? $this->generateUniqueSlug($validated['title'], $article->uuid) : $article->slug,
                'excerpt' => $validated['excerpt'] ?? null,
                'content' => $validated['content'],
                'scheduled_at' => $validated['scheduled_at'] ?? null,
                'tagging' => $validated['tagging'] ?? null,
                'video' => $validated['video'] ?? null,
                'status' => $validated['status'],
                'search_engine' => $validated['search_engine'],
                'featured_image' => $article->featured_image,
            ]);

            $article->seo()->updateOrCreate([], [
                'title' => $article->title,
                'description' => $article->excerpt,
                'image' => $article->featured_image,
                'author' => auth()->user()->name,
                'robots' => $validated['search_engine'] ?? 'index, follow',
                'canonical_url' => config('frontend.url').'/blog/'.$article->slug,
            ]);

            DB::commit();

            if ($newImage && $oldImage && $oldImage !== $newImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            return redirect()->route('articles.index')->with('success', 'Artikel berhasil diperbarui.');
        } catch (\Throwable $th) {
            DB::rollBack();

            if ($newImage && Storage::disk('public')->exists($newImage)) {
                Storage::disk('public')->delete($newImage);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui artikel. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Remove the selected articles along with their files.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (! is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada artikel yang dipilih.');
        }

        $ids = array_slice(array_values(array_unique(array_filter($ids))), 0, 100);

        $articles = Article::whereIn('uuid', $ids)->get();

        if ($articles->isEmpty()) {
            return back()->with('error', 'Data yang dipilih tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            foreach ($articles as $article) {
                $article->delete();
            }

            DB::commit();

            foreach ($articles as $article) {
                if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
                    Storage::disk('public')->delete($article->featured_image);
                }
            }

            return back()->with('success', $articles->count().' artikel berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus artikel. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        DB::beginTransaction();
        try {
            $article = Article::where('uuid', $uuid)->firstOrFail();
            $image = $article->featured_image;

            $article->delete();

            DB::commit();

            if ($image && Storage::disk('public')->exists($image)) {
                Storage::disk('public')->delete($image);
            }

            return redirect()->back()->with('success', 'Artikel berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menghapus artikel. Silakan coba lagi.');
        }
    }

    private function validationRules(?string $uuid = null): array
    {
        $titleRule = Rule::unique('articles', 'title');

        if ($uuid !== null) {
            $titleRule->ignore($uuid, 'uuid');
        }

        return [
            'title' => ['required', 'max:255', $titleRule],
            'excerpt' => ['nullable', 'max:255'],
            'content' => ['required'],
            'category_uuid' => ['required', 'exists:categories,uuid'],
            'scheduled_at' => ['nullable', 'date'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'tagging' => ['nullable', 'string', 'max:255'],
            'video' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,scheduled'],
            'search_engine' => ['required', 'in:index,noindex'],
        ];
    }

    private function validationMessages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'featured_image.max' => 'Ukuran :attribute tidak boleh lebih dari 2 MB.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat :values.',
            'exists' => ':attribute tidak valid.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'in' => ':attribute tidak valid.',
            'unique' => ':attribute sudah digunakan.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'title' => 'Judul',
            'excerpt' => 'Ringkasan',
            'content' => 'Konten',
            'category_uuid' => 'Kategori',
            'scheduled_at' => 'Tanggal Terbit',
            'featured_image' => 'Gambar Utama',
            'tagging' => 'Tag',
            'video' => 'Tautan YouTube',
            'status' => 'Status',
            'search_engine' => 'Mesin Pencari',
        ];
    }

    private function generateUniqueSlug(string $title, ?string $uuid = null): string
    {
        $base = Str::slug($title) ?: 'artikel';
        $slug = $base;
        $suffix = 1;

        while (Article::withTrashed()
            ->where('slug', $slug)
            ->when($uuid, fn ($query) => $query->where('uuid', '!=', $uuid))
            ->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
