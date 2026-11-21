<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $vendorsCategoryUuid = (string) $request->get('vendors_category', '');
        $periode = (string) $request->get('periode', 'semua');
        $periode = in_array($periode, ['semua', 'bulan_ini', '30_hari', 'tahun_ini'], true) ? $periode : 'semua';
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $sort = (string) $request->get('sort', 'terbaru');
        $sort = in_array($sort, ['terbaru', 'terlama', 'nama_asc', 'nama_desc', 'bergabung_terbaru'], true) ? $sort : 'terbaru';

        $query = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })->with('vendors_categories');

        if ($search !== '') {
            $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search).'%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('no_hp', 'like', $like);
            });
        }

        if ($periode === 'bulan_ini') {
            $query->whereBetween('is_active', [now()->startOfMonth(), now()->endOfMonth()]);
        } elseif ($periode === '30_hari') {
            $query->where('is_active', '>=', now()->subDays(30));
        } elseif ($periode === 'tahun_ini') {
            $query->whereYear('is_active', now()->year);
        }

        if ($startDate) {
            $query->whereDate('is_active', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('is_active', '<=', $endDate);
        }

        if ($vendorsCategoryUuid !== '') {
            $query->whereHas('vendors_categories', function ($q) use ($vendorsCategoryUuid) {
                $q->where('vendors_categories.uuid', $vendorsCategoryUuid);
            });
        }

        switch ($sort) {
            case 'terlama':
                $query->orderBy('created_at');
                break;
            case 'nama_asc':
                $query->orderBy('name');
                break;
            case 'nama_desc':
                $query->orderByDesc('name');
                break;
            case 'bergabung_terbaru':
                $query->orderByDesc('is_active');
                break;
            default:
                $query->latest();
                break;
        }

        $users = (clone $query)->paginate(10)->withQueryString();

        $stats = [
            'total' => (clone $query)->count(),
            'with_phone' => (clone $query)->whereNotNull('no_hp')->where('no_hp', '!=', '')->count(),
            'new_month' => (clone $query)->whereBetween('is_active', [now()->startOfMonth(), now()->endOfMonth()])->count(),
        ];

        $vendors_categories = VendorsCategory::where('is_active', 'active')
            ->orderBy('name')
            ->get();

        $stats['categories_active'] = $vendors_categories->count();

        $archivedCount = User::onlyTrashed()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'vendor');
            })
            ->count();

        return view('pages.vendor.index', [
            'users' => $users,
            'vendors_categories' => $vendors_categories,
            'stats' => $stats,
            'search' => $search,
            'vendorsCategoryUuid' => $vendorsCategoryUuid,
            'periode' => $periode,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'sort' => $sort,
            'archivedCount' => $archivedCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors_categories = VendorsCategory::where('is_active', 'active')->get();

        return view('pages.vendor.create', compact('vendors_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'nullable|unique:users,no_hp',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'agama' => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Lainnya',
            'pengalaman' => 'required|string|max:255',
            'is_active' => 'nullable|date',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'biografi' => 'nullable|string',
            'vendors_categories' => 'required|array|min:1',
            'vendors_categories.*' => 'exists:vendors_categories,uuid',
        ]);

        DB::beginTransaction();
        try {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            $user = User::create([
                'uuid' => Str::uuid(),
                'avatar' => $avatarPath,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'no_hp' => $request->no_hp,
                'tempat_lahir' => $request->filled('tempat_lahir') ? $request->tempat_lahir : null,
                'tanggal_lahir' => $request->filled('tanggal_lahir') ? $request->tanggal_lahir : null,
                'jenis_kelamin' => $request->filled('jenis_kelamin') ? $request->jenis_kelamin : null,
                'agama' => $request->filled('agama') ? $request->agama : null,
                'pengalaman' => $request->pengalaman,
                'is_active' => $request->is_active,
                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'twitter' => $request->twitter,
                'tiktok' => $request->tiktok,
                'youtube' => $request->youtube,
                'biografi' => $request->biografi,
            ]);

            $user->assignRole('vendor');

            // Simpan kategori vendor.
            foreach ($request->vendors_categories as $vendorsCategoryUuid) {
                DB::table('user_vendors_category')->insert([
                    'uuid' => (string) Str::uuid(),
                    'user_uuid' => $user->uuid,
                    'vendors_category_uuid' => $vendorsCategoryUuid,
                ]);
            }

            DB::commit();

            return redirect()->route('vendor.index')->with('success', 'Vendor berhasil disimpan.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $this->safeErrorMessage($th));
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
    public function edit(User $vendor)
    {
        $authUser = auth()->user();

        if ($authUser->hasRole(['admin', 'super-admin'])) {
            $vendors_categories = VendorsCategory::where('is_active', 'active')->get();

            return view('pages.vendor.edit', compact(
                'vendor',
                'vendors_categories'
            ));
        }

        if ($authUser->uuid !== $vendor->uuid) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $vendors_categories = VendorsCategory::where('is_active', 'active')->get();

        return view('pages.vendor.edit', compact(
            'vendor',
            'vendors_categories'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $vendor)
    {
        $request->validate([
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$vendor->uuid.',uuid',
            'no_hp' => 'nullable|unique:users,no_hp,'.$vendor->uuid.',uuid',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'agama' => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Lainnya',
            'pengalaman' => 'required|string|max:255',
            'is_active' => 'nullable|date',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'biografi' => 'nullable|string',

            // Vendor Category
            'vendors_categories' => 'required|array|min:1',
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
                'biografi',
            ]);

            foreach (['tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama'] as $opsional) {
                if (! $request->filled($opsional)) {
                    unset($data[$opsional]);
                }
            }

            if ($request->hasFile('avatar')) {
                if ($vendor->avatar && Storage::disk('public')->exists($vendor->avatar)) {
                    Storage::disk('public')->delete($vendor->avatar);
                }
                $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            $vendor->update($data);

            // Delete the old vendor categories
            DB::table('user_vendors_category')
                ->where('user_uuid', $vendor->uuid)
                ->delete();

            // Insert the new vendor categories
            $vendors_categories = [];

            foreach ($request->vendors_categories as $vendorsCategoryUuid) {
                $vendors_categories[] = [
                    'uuid' => (string) Str::uuid(),
                    'user_uuid' => $vendor->uuid,
                    'vendors_category_uuid' => $vendorsCategoryUuid,
                ];
            }

            DB::table('user_vendors_category')
                ->insert($vendors_categories);

            DB::commit();

            return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diperbarui.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $this->safeErrorMessage($th));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $vendor)
    {
        try {
            $vendor->delete();

            return redirect()
                ->route('vendor.index')
                ->with('success', 'Vendor berhasil dihapus sementara.');
        } catch (\Throwable $th) {
            return redirect()
                ->route('vendor.index')
                ->with('error', $this->safeErrorMessage($th));
        }
    }

    public function archive(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $query = User::onlyTrashed()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'vendor');
            })
            ->with('vendors_categories');

        if ($search !== '') {
            $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search).'%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('no_hp', 'like', $like);
            });
        }

        $vendors = (clone $query)->orderByDesc('deleted_at')->paginate(10)->withQueryString();

        return view('pages.vendor.archive', [
            'vendors' => $vendors,
            'search' => $search,
        ]);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'uuid' => 'required|array|min:1',
            'uuid.*' => 'string|exists:users,uuid',
        ]);

        try {
            $vendors = User::onlyTrashed()
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'vendor');
                })
                ->whereIn('uuid', $request->uuid)
                ->get();

            if ($vendors->isEmpty()) {
                return redirect()
                    ->route('vendor.archive')
                    ->with('error', 'Tidak ada vendor arsip yang cocok dipulihkan.');
            }

            foreach ($vendors as $vendor) {
                $vendor->restore();
            }

            return redirect()
                ->route('vendor.archive')
                ->with('success', $vendors->count().' vendor berhasil dipulihkan.');
        } catch (\Throwable $th) {
            return redirect()
                ->route('vendor.archive')
                ->with('error', $this->safeErrorMessage($th));
        }
    }

    public function mobile()
    {
        $user = auth()->user();

        // Berbasis role saja agar konsisten di semua ukuran layar.
        if ($user->hasRole('user')) {

            $vendors = User::role('vendor')
                ->with('vendors_categories')
                ->select(['uuid', 'name', 'avatar', 'biografi', 'pengalaman'])
                ->get();

            $vendors_categories = VendorsCategory::where('is_active', 'active')
                ->orderBy('name')
                ->get();

            return view(
                'pages.mobile.vendor',
                compact(
                    'user',
                    'vendors',
                    'vendors_categories'
                )
            );
        }

        abort(403);
    }
}
