<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VendorsCategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori vendor.
     */
    public function index()
    {
        $vendors_categories = VendorsCategory::withCount([
            'users as vendors_categories_count',
        ])->get();

        return view(
            'pages.vendors_categories.index',
            compact('vendors_categories')
        );
    }

    /**
     * Simpan kategori vendor baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:vendors_categories,name',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            VendorsCategory::create([
                'uuid' => (string) Str::uuid(),
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Kategori vendor berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $this->safeErrorMessage($th));
        }
    }

    /**
     * Perbarui kategori vendor.
     */
    public function update($uuid, Request $request)
    {
        $request->validate([
            'name' => 'required|unique:vendors_categories,name,'.$uuid.',uuid',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $item = VendorsCategory::where('uuid', $uuid)->firstOrFail();

            $item->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Kategori vendor berhasil diperbarui.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $this->safeErrorMessage($th));
        }
    }

    /**
     * Hapus kategori vendor.
     */
    public function destroy($uuid)
    {
        DB::beginTransaction();
        try {
            $item = VendorsCategory::where('uuid', $uuid)->firstOrFail();
            $item->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Kategori vendor berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $this->safeErrorMessage($th));
        }
    }
}
