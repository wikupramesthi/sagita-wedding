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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
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

            return redirect()->back()->with('success', 'Vendor category successfully added.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
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

            return redirect()->back()->with('success', 'Vendor category successfully changed.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        DB::beginTransaction();
        try {
            $item = VendorsCategory::where('uuid', $uuid)->firstOrFail();
            $item->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Vendor category successfully deleted.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
