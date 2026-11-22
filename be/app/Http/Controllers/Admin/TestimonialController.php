<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Testimonial::orderBy('urutan', 'ASC')->get();

        return view('pages.testimonial.index', [
            'title' => 'Testimoni',
            'items' => $items,
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
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'isi_testimoni' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'urutan' => 'required|integer|min:1|unique:testimonials,urutan',
            'is_active' => 'required|in:active,inactive',

        ], $this->validationMessages(), $this->validationAttributes());

        DB::beginTransaction();
        try {
            $data = $request->only(['nama', 'jabatan', 'isi_testimoni', 'urutan', 'is_active']);
            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('testimoni', 'public');
            }

            Testimonial::create([
                'uuid' => (string) Str::uuid(),
                'nama' => $data['nama'],
                'jabatan' => $data['jabatan'],
                'isi_testimoni' => $data['isi_testimoni'],
                'foto' => $data['foto'],
                'urutan' => $data['urutan'],
                'is_active' => $data['is_active'],
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Testimoni berhasil ditambahkan.');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $item = Testimonial::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'isi_testimoni' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'urutan' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('testimonials', 'urutan')->ignore($item->id),
            ],
            'is_active' => 'required|in:active,inactive',
        ], $this->validationMessages(), $this->validationAttributes());

        DB::beginTransaction();

        try {
            $data = $request->only([
                'nama',
                'jabatan',
                'isi_testimoni',
                'urutan',
                'is_active',
            ]);

            if ($request->hasFile('foto')) {
                if ($item->foto && Storage::disk('public')->exists($item->foto)) {
                    Storage::disk('public')->delete($item->foto);
                }

                $data['foto'] = $request->file('foto')->store('testimoni', 'public');
            }

            $item->update($data);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Testimoni berhasil diperbarui.');
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
        DB::beginTransaction();

        try {
            $item = Testimonial::where('uuid', $uuid)->firstOrFail();
            $foto = $item->foto;

            $item->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $this->safeErrorMessage($th));
        }

        if ($foto && Storage::disk('public')->exists($foto)) {
            Storage::disk('public')->delete($foto);
        }

        return redirect()->back()->with('success', 'Testimoni berhasil dihapus.');
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
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat :values.',
        ];
    }

    private function validationAttributes(): array
    {
        return [
            'nama' => 'Nama',
            'jabatan' => 'Jabatan',
            'isi_testimoni' => 'Isi testimoni',
            'foto' => 'Foto',
            'urutan' => 'Urutan',
            'is_active' => 'Status',
        ];
    }
}
