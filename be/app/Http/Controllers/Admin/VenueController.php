<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\Class\ClassSchedule;
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
            $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q) . '%';
            $query->where(function ($w) use ($like) {
                $w->where('name', 'like', $like)
                    ->orWhere('address', 'like', $like)
                    ->orWhere('excerpt', 'like', $like);
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $venues = (clone $query)->withCount('schedules')->latest()->get();

        // Stats follow the active filter so numbers stay in sync with the data.
        $stats = [
            'total' => (clone $query)->count(),
            'active' => (clone $query)->where('status', 'active')->count(),
            'inactive' => (clone $query)->where('status', 'inactive')->count(),
            'schedules' => ClassSchedule::whereIn('venue_uuid', (clone $query)->select('venues.uuid'))->count(),
        ];

        return view(
            'pages.venues.index',
            compact('venues', 'stats', 'q', 'status')
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
            'name'            => 'required|string|max:255',
            'slug'            => 'nullable|string|max:255|unique:venues,slug',
            'excerpt'         => 'nullable|string',
            'description'     => 'nullable|string',
            'address'         => 'required|string',
            'phone'           => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'google_maps_url' => 'nullable|url|max:500',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'          => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();

        try {

            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')
                    ->store('venues', 'public');
            }

            Venue::create([
                'uuid'            => (string) Str::uuid(),
                'name'            => $request->name,
                'slug'            => $request->slug
                    ? Str::slug($request->slug)
                    : Str::slug($request->name),
                'excerpt'         => $request->excerpt,
                'description'     => $request->description,
                'address'         => $request->address,
                'phone'           => $request->phone,
                'email'           => $request->email,
                'google_maps_url' => $request->google_maps_url,
                'image'           => $imagePath,
                'status'          => $request->status,
            ]);

            DB::commit();

            return redirect()
                ->route('venues.index')
                ->with(
                    'success',
                    'Venue data has been saved successfully.'
                );
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $th->getMessage());
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
            'name'            => 'required|string|max:255',
            'slug'            => 'nullable|string|max:255|unique:venues,slug,' . $venue->uuid . ',uuid',
            'excerpt'         => 'nullable|string',
            'description'     => 'nullable|string',
            'address'         => 'required|string',
            'phone'           => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'google_maps_url' => 'nullable|url|max:500',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'          => 'required|in:active,inactive',
        ]);

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
                    'Venue data has been updated successfully.'
                );
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified venue.
     */
    public function destroy(Venue $venue)
    {
        try {

            // Venue tidak boleh dihapus jika masih
            // digunakan oleh class schedule.
            if ($venue->schedules()->exists()) {
                return redirect()
                    ->route('venues.index')
                    ->with(
                        'error',
                        'This venue cannot be deleted because it is still used by a class schedule.'
                    );
            }

            if (
                $venue->image &&
                Storage::disk('public')->exists($venue->image)
            ) {
                Storage::disk('public')->delete($venue->image);
            }

            $venue->delete();

            return redirect()
                ->route('venues.index')
                ->with(
                    'success',
                    'Venue data has been deleted successfully.'
                );
        } catch (\Throwable $th) {

            return redirect()
                ->route('venues.index')
                ->with(
                    'error',
                    'Failed to delete venue: ' . $th->getMessage()
                );
        }
    }
}
