<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Support\ImageShrinker;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status', '');
        $status = in_array($status, ['draft', 'published', 'cancelled', 'completed'], true) ? $status : '';
        $tanggal_mulai = $request->get('tanggal_mulai');
        $tanggal_selesai = $request->get('tanggal_selesai');

        $query = Event::query();

        if ($search !== '') {
            $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search).'%';
            $query->where('judul', 'like', $like);
        }

        // Filter by status
        if ($status !== '') {
            $query->where('status', $status);
        }

        // Filter by start date
        if ($tanggal_mulai) {
            $query->whereDate('tanggal', '>=', $tanggal_mulai);
        }

        // Filter by end date
        if ($tanggal_selesai) {
            $query->whereDate('tanggal', '<=', $tanggal_selesai);
        }

        $items = (clone $query)
            ->orderBy('tanggal', 'DESC')
            ->paginate(15)
            ->withQueryString();

        // Member: versi mobile phone-frame — hanya event published mendatang.
        if (! auth()->user()->hasAnyRole(['super-admin', 'admin'])) {
            $searchMobile = trim((string) $request->get('search', ''));

            $mobileQuery = Event::where('status', 'published')
                ->whereDate('tanggal', '>=', now()->toDateString());

            if ($searchMobile !== '') {
                $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $searchMobile).'%';
                $mobileQuery->where('judul', 'like', $like);
            }

            $events = $mobileQuery->orderBy('tanggal')->orderBy('waktu_mulai')
                ->paginate(10)->withQueryString();

            return view('pages.mobile.events', [
                'events' => $events,
                'search' => $searchMobile,
                'unreadCount' => auth()->user()->unreadNotifications()->count(),
            ]);
        }

        // Stats follow the active filter so numbers stay in sync with the data.
        $stats = [
            'total' => (clone $query)->count(),
            'published' => (clone $query)->where('status', 'published')->count(),
            'draft' => (clone $query)->where('status', 'draft')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
        ];

        return view('pages.event.index', [
            'title' => 'Acara',
            'items' => $items,
            'stats' => $stats,
            'search' => $search,
            'status' => $status,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => $this->judulRule(),
            'deskripsi' => 'required|string',
            'gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after_or_equal:waktu_mulai',
            'lokasi' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published,cancelled,completed',
        ], $this->validationMessages(), $this->validationAttributes());

        DB::beginTransaction();

        try {
            $path = $request->file('gambar')->store('events', 'public');

            // Maks 1024px: hero & kartu event di HP hanya tampil <= 200px.
            ImageShrinker::shrink(storage_path('app/public/'.$path), 1024);

            Event::create([
                'uuid' => Str::uuid(),
                'judul' => $request->judul,
                'slug' => Str::slug($request->judul),
                'deskripsi' => $request->deskripsi,
                'gambar' => $path,
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'lokasi' => $request->lokasi,
                'kapasitas' => $request->kapasitas,
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Acara berhasil dibuat.');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', $this->safeErrorMessage($th));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Member: detail event mobile (hanya yang published).
        if (! auth()->user()->hasAnyRole(['super-admin', 'admin'])) {
            $event = Event::where('uuid', $id)->where('status', 'published')->firstOrFail();

            return view('pages.mobile.event-show', compact('event'));
        }

        return redirect()->route('events.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $item = Event::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'judul' => $this->judulRule($uuid),
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after_or_equal:waktu_mulai',
            'lokasi' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,published,cancelled,completed',
        ], $this->validationMessages(), $this->validationAttributes());

        DB::beginTransaction();

        try {
            $data = $request->only([
                'judul',
                'deskripsi',
                'tanggal',
                'waktu_mulai',
                'waktu_selesai',
                'lokasi',
                'kapasitas',
                'status',
            ]);
            $data['slug'] = Str::slug($data['judul']);

            // When uploading a new image
            if ($request->hasFile('gambar')) {

                // Delete the old image
                if ($item->gambar && Storage::disk('public')->exists($item->gambar)) {
                    Storage::disk('public')->delete($item->gambar);
                }

                // Save the new image
                $data['gambar'] = $request->file('gambar')->store('events', 'public');
                ImageShrinker::shrink(storage_path('app/public/'.$data['gambar']), 1024);
            }

            $item->update($data);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Acara berhasil diperbarui.');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', $this->safeErrorMessage($th));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        $item = Event::where('uuid', $uuid)->firstOrFail();

        DB::beginTransaction();

        try {
            // Delete the image
            if ($item->gambar && Storage::disk('public')->exists($item->gambar)) {
                Storage::disk('public')->delete($item->gambar);
            }

            // Delete the event record
            $item->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Acara berhasil dihapus.');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', $this->safeErrorMessage($th));
        }
    }

    private function judulRule(?string $uuid = null): array
    {
        return [
            'bail',
            'required',
            'string',
            'max:255',
            function (string $attribute, mixed $value, Closure $fail) use ($uuid): void {
                $query = Event::where('slug', Str::slug($value));

                if ($uuid) {
                    $query->where('uuid', '!=', $uuid);
                }

                if ($query->exists()) {
                    $fail('Nama acara ini sudah digunakan.');
                }
            },
        ];
    }

    private function validationMessages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'gambar.max' => 'Ukuran Gambar tidak boleh lebih dari :max KB.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat :values.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'date_format' => ':attribute harus sesuai format :format.',
            'after_or_equal' => ':attribute tidak boleh lebih awal dari :date.',
            'integer' => ':attribute harus berupa angka.',
            'min' => ':attribute minimal :min.',
            'in' => 'Pilihan :attribute tidak valid.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'judul' => 'Nama Acara',
            'deskripsi' => 'Deskripsi',
            'gambar' => 'Gambar',
            'tanggal' => 'Tanggal',
            'waktu_mulai' => 'Waktu Mulai',
            'waktu_selesai' => 'Waktu Selesai',
            'lokasi' => 'Lokasi',
            'kapasitas' => 'Kapasitas',
            'status' => 'Status',
        ];
    }
}
