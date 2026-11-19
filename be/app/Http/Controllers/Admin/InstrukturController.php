<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstrukturController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $jenisKelamin = (string) $request->get('jenis_kelamin', '');
        $jenisKelamin = in_array($jenisKelamin, ['L', 'P'], true) ? $jenisKelamin : '';
        $vendorsCategoryUuid = (string) $request->get('vendors_category', '');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = User::whereHas('roles', function ($query) {
            $query->where('name', 'instruktur');
        })->with('vendors_categories');

        if ($search !== '') {
            $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search) . '%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)->orWhere('email', 'like', $like);
            });
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($jenisKelamin !== '') {
            $query->where('jenis_kelamin', $jenisKelamin);
        }

        if ($vendorsCategoryUuid !== '') {
            $query->whereHas('vendors_categories', function ($q) use ($vendorsCategoryUuid) {
                $q->where('vendors_categories.uuid', $vendorsCategoryUuid);
            });
        }

        $users = (clone $query)->latest()->get();

        $stats = [
            'total' => (clone $query)->count(),
            'male' => (clone $query)->where('jenis_kelamin', 'L')->count(),
            'female' => (clone $query)->where('jenis_kelamin', 'P')->count(),
            'new_month' => (clone $query)->where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        $vendors_categories = VendorsCategory::where('is_active', 'active')
            ->orderBy('name')
            ->get();

        return view('pages.instruktur.index', [
            'users' => $users,
            'vendors_categories' => $vendors_categories,
            'stats' => $stats,
            'search' => $search,
            'jenisKelamin' => $jenisKelamin,
            'vendorsCategoryUuid' => $vendorsCategoryUuid,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors_categories = VendorsCategory::where('is_active', 'active')->get();
        return view('pages.instruktur.create', compact('vendors_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'avatar'         => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'no_hp'          => 'nullable|unique:users,no_hp',
            'tempat_lahir'   => 'nullable|string|max:100',
            'tanggal_lahir'  => 'nullable|date',
            'jenis_kelamin'  => 'nullable|in:L,P',
            'agama'          => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Lainnya',
            'pengalaman'     => 'required|string|max:255',
            'is_active'      => 'nullable|date',
            'facebook'       => 'nullable|string|max:255',
            'instagram'      => 'nullable|string|max:255',
            'twitter'        => 'nullable|string|max:255',
            'tiktok'         => 'nullable|string|max:255',
            'youtube'        => 'nullable|string|max:255',
            'biografi'       => 'nullable|string',
            'vendors_categories' => 'required|array|min:1',
            'vendors_categories.*' => 'exists:vendors_categories,uuid',
        ]);

        DB::beginTransaction();
        try {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            $user = User::create([
                'uuid'             => Str::uuid(),
                'avatar'           => $avatarPath,
                'name'             => $request->name,
                'email'            => $request->email,
                'password'         => Hash::make('password'),
                'email_verified_at' => now(),
                'no_hp'            => $request->no_hp,
                'tempat_lahir'     => $request->tempat_lahir,
                'tanggal_lahir'    => $request->tanggal_lahir,
                'jenis_kelamin'    => $request->jenis_kelamin,
                'agama'            => $request->agama,
                'pengalaman'          => $request->pengalaman,
                'is_active'        => $request->is_active,
                'facebook'         => $request->facebook,
                'instagram'        => $request->instagram,
                'twitter'          => $request->twitter,
                'tiktok'           => $request->tiktok,
                'youtube'          => $request->youtube,
                'biografi'         => $request->biografi,
            ]);

            $user->assignRole('instruktur');

            // Save the instructor vendor categories //
            foreach ($request->vendors_categories as $vendorsCategoryUuid) {
                DB::table('user_vendors_category')->insert([
                    'uuid'                => (string) Str::uuid(),
                    'user_uuid'           => $user->uuid,
                    'vendors_category_uuid' => $vendorsCategoryUuid,
                ]);
            }

            DB::commit();
            return redirect()->route('instruktur.index')->with('success', 'Instructor has been saved successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $instruktur)
    {
        $authUser = auth()->user();

        if ($authUser->hasRole(['admin', 'super-admin'])) {
            $vendors_categories = VendorsCategory::where('is_active', 'active')->get();

            return view('pages.instruktur.edit', compact(
                'instruktur',
                'vendors_categories'
            ));
        }

        if ($authUser->uuid !== $instruktur->uuid) {
            abort(403, 'You do not have access to this data.');
        }

        $vendors_categories = VendorsCategory::where('is_active', 'active')->get();

        return view('pages.instruktur.edit', compact(
            'instruktur',
            'vendors_categories'
        ));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $instruktur)
    {
        $request->validate([
            'avatar'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $instruktur->uuid . ',uuid',
            'no_hp'          => 'nullable|unique:users,no_hp,' . $instruktur->uuid . ',uuid',
            'tempat_lahir'   => 'nullable|string|max:100',
            'tanggal_lahir'  => 'nullable|date',
            'jenis_kelamin'  => 'nullable|in:L,P',
            'agama'          => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Lainnya',
            'pengalaman'     => 'required|string|max:255',
            'is_active'      => 'nullable|date',
            'facebook'       => 'nullable|string|max:255',
            'instagram'      => 'nullable|string|max:255',
            'twitter'        => 'nullable|string|max:255',
            'tiktok'         => 'nullable|string|max:255',
            'youtube'        => 'nullable|string|max:255',
            'biografi'       => 'nullable|string',

            // Vendor Category
            'vendors_categories'   => 'required|array|min:1',
            'vendors_categories.*' => 'exists:vendors_categories,uuid',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only([
                'name',
                'email',
                'no_hp',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'agama',
                'pengalaman',
                'is_active',
                'facebook',
                'instagram',
                'twitter',
                'tiktok',
                'youtube',
                'biografi'
            ]);

            if ($request->hasFile('avatar')) {
                if ($instruktur->avatar && Storage::disk('public')->exists($instruktur->avatar)) {
                    Storage::disk('public')->delete($instruktur->avatar);
                }
                $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            $instruktur->update($data);

            // Delete the old vendor categories
            DB::table('user_vendors_category')
                ->where('user_uuid', $instruktur->uuid)
                ->delete();


            // Insert the new vendor categories
            $vendors_categories = [];

            foreach ($request->vendors_categories as $vendorsCategoryUuid) {
                $vendors_categories[] = [
                    'uuid'                => (string) Str::uuid(),
                    'user_uuid'           => $instruktur->uuid,
                    'vendors_category_uuid' => $vendorsCategoryUuid,
                ];
            }

            DB::table('user_vendors_category')
                ->insert($vendors_categories);

            DB::commit();
            return redirect()->route('instruktur.index')->with('success', 'Instructor successfully updated.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $instruktur)
    {
        try {
            $instruktur->delete();
            return redirect()
                ->route('instruktur.index')
                ->with('success', 'Instructor has been successfully deleted. (soft delete).');
        } catch (\Throwable $th) {
            return redirect()
                ->route('instruktur.index')
                ->with('error', 'Failed to delete data: ' . $th->getMessage());
        }
    }

    public function restore()
    {
        try {
            User::onlyTrashed()->restore();
            return redirect()->route('instruktur.index')->with('success', 'All instructors have been successfully restored.');
        } catch (\Throwable $th) {
            return redirect()->route('instruktur.index')->with('error', 'Failed to restore data: ' . $th->getMessage());
        }
    }

    public function mobile()
    {
        $user = auth()->user();

        // Berbasis role saja agar konsisten di semua ukuran layar.
        if ($user->hasRole('user')) {

            $instructors = User::role('instruktur')
                ->with('vendors_categories')
                ->select(['uuid', 'name', 'avatar', 'biografi', 'pengalaman'])
                ->get();

            $vendors_categories = VendorsCategory::where('is_active', 'active')
                ->orderBy('name')
                ->get();

            return view(
                'pages.mobile.instruktur',
                compact(
                    'user',
                    'instructors',
                    'vendors_categories'
                )
            );
        }

        abort(403);
    }
}
