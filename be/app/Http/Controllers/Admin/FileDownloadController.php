<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileDownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $downloads = FileDownload::query()
            ->latest('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('pages.filedownload.index', compact('downloads'));
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
        request()->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'kategori' => ['required', 'in:akademik,informasi,laporan,edaran'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {
            $data = request()->only(['judul', 'deskripsi', 'kategori']);

            if (request()->hasFile('file')) {
                $data['file'] = request()->file('file')->store('downloads/pdf', 'local');
            }

            FileDownload::create($data);
            DB::commit();

            return redirect()->route('filedownload.index')->with('success', 'Dokumen sekolah berhasil ditambahkan.');
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

    public function download(string $fileDownload)
    {
        $item = FileDownload::findOrFail($fileDownload);
        abort_unless($item->file, 404);

        $path = $item->file;
        $disk = Storage::disk('local');
        $public = Storage::disk('public');

        if (! $disk->exists($path) && $public->exists($path)) {
            try {
                $dir = dirname($path);
                if ($dir !== '.' && $dir !== '') {
                    $disk->makeDirectory($dir);
                }
                $stream = $public->readStream($path);
                if ($stream !== false) {
                    $disk->writeStream($path, $stream);
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                    $public->delete($path);
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        abort_unless($disk->exists($path), 404);

        $response = response()->file($disk->path($path), [
            'Content-Disposition' => 'attachment; filename="dokumen.pdf"',
        ]);
        $response->setPrivate();
        $response->headers->set('Cache-Control', 'private, no-store');

        return $response;
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
    public function update($id)
    {
        request()->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'kategori' => ['required', 'in:akademik,informasi,laporan,edaran'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {
            $item = FileDownload::findOrFail($id);
            $data = request()->only(['judul', 'deskripsi', 'kategori']);

            if (request()->hasFile('file')) {
                Storage::disk('local')->delete($item->file);
                Storage::disk('public')->delete($item->file);
                $data['file'] = request()->file('file')->store('downloads/pdf', 'local');
            }

            $item->update($data);
            DB::commit();

            return redirect()->back()->with('success', 'Dokumen sekolah berhasil diperbarui.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $this->safeErrorMessage($th));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = FileDownload::findOrFail($id);
            $file = $item->file;

            $item->delete();
            DB::commit();

            if ($file) {
                Storage::disk('local')->delete($file);
                Storage::disk('public')->delete($file);
            }

            return redirect()->route('filedownload.index')->with('success', 'Dokumen sekolah berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $this->safeErrorMessage($th));
        }
    }
}
