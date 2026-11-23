<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VenueController extends Controller
{
    /**
     * Display a listing of the venues.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = $request->query('status', '');
        $status = in_array($status, ['active', 'inactive'], true) ? $status : '';

        $query = Venue::query();

        if ($q !== '') {
            $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q).'%';
            $query->where(function ($w) use ($like) {
                $w->where('name', 'like', $like)
                    ->orWhere('address', 'like', $like)
                    ->orWhere('excerpt', 'like', $like);
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $perPage = (int) $request->query('per_page', 12);
        $perPage = in_array($perPage, [12, 24, 48, 96], true) ? $perPage : 12;

        $venues = (clone $query)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $statsRow = (clone $query)
            ->selectRaw("COUNT(*) as total, COALESCE(SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END), 0) as active, COALESCE(SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END), 0) as inactive")
            ->first();

        $stats = [
            'total' => (int) ($statsRow->total ?? 0),
            'active' => (int) ($statsRow->active ?? 0),
            'inactive' => (int) ($statsRow->inactive ?? 0),
        ];

        return view(
            'pages.venues.index',
            compact('venues', 'stats', 'q', 'status', 'perPage')
        );
    }

    /**
     * Show the form for creating a new venue.
     */
    public function create()
    {
        return view('pages.venues.create');
    }

    /**
     * Store a newly created venue.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:venues,slug',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'google_maps_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ], $this->validationMessages(), $this->validationAttributes());

        DB::beginTransaction();

        try {

            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')
                    ->store('venues', 'public');
            }

            Venue::create([
                'uuid' => (string) Str::uuid(),
                'name' => $request->name,
                'slug' => $request->slug
                    ? Str::slug($request->slug)
                    : Str::slug($request->name),
                'excerpt' => $request->excerpt,
                'description' => $request->description,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'google_maps_url' => $request->google_maps_url,
                'image' => $imagePath,
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()
                ->route('venues.index')
                ->with(
                    'success',
                    'Data venue berhasil disimpan.'
                );
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->safeErrorMessage($th));
        }
    }

    /**
     * Show the form for editing the specified venue.
     */
    public function edit(Venue $venue)
    {
        return view(
            'pages.venues.edit',
            compact('venue')
        );
    }

    /**
     * Update the specified venue.
     */
    public function update(Request $request, Venue $venue)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:venues,slug,'.$venue->uuid.',uuid',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'google_maps_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ], $this->validationMessages(), $this->validationAttributes());

        DB::beginTransaction();

        try {

            $data = $request->only([
                'name',
                'excerpt',
                'description',
                'address',
                'phone',
                'email',
                'google_maps_url',
                'status',
            ]);

            $data['slug'] = $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->name);

            if ($request->hasFile('image')) {

                if (
                    $venue->image &&
                    Storage::disk('public')->exists($venue->image)
                ) {
                    Storage::disk('public')->delete($venue->image);
                }

                $data['image'] = $request->file('image')
                    ->store('venues', 'public');
            }

            $venue->update($data);

            DB::commit();

            return redirect()
                ->route('venues.index')
                ->with(
                    'success',
                    'Data venue berhasil diperbarui.'
                );
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->safeErrorMessage($th));
        }
    }

    /**
     * Remove the specified venue.
     */
    public function destroy(Venue $venue)
    {
        DB::beginTransaction();

        try {
            $image = $venue->image;

            $venue->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()
                ->route('venues.index')
                ->with(
                    'error',
                    $this->safeErrorMessage($th)
                );
        }

        if ($image && Storage::disk('public')->exists($image)) {
            Storage::disk('public')->delete($image);
        }

        return redirect()
            ->route('venues.index')
            ->with(
                'success',
                'Data venue berhasil dihapus.'
            );
    }

    private function validationMessages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'email' => ':attribute harus berupa alamat email yang valid.',
            'url' => ':attribute harus berupa URL yang valid.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat :values.',
            'in' => 'Pilihan :attribute tidak valid.',
            'unique' => ':attribute sudah digunakan.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'name' => 'Nama venue',
            'slug' => 'Slug',
            'excerpt' => 'Ringkasan',
            'description' => 'Deskripsi',
            'address' => 'Alamat',
            'phone' => 'Telepon',
            'email' => 'Email',
            'google_maps_url' => 'URL Google Maps',
            'image' => 'Gambar',
            'status' => 'Status',
        ];
    }
}
