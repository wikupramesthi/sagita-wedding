<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class PenggunaExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $q = trim((string) $this->request->query('q', ''));
        $status = (string) $this->request->query('status', '');

        if (! array_key_exists($status, User::PROSPECT_STATUS_LABELS)) {
            $status = '';
        }

        $query = User::with('userPackages.package')
            ->role('user')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('no_hp', 'like', "%{$q}%");
                });
            })
            ->when($status !== '', fn ($query) => $query->where('prospect_status', $status))
            ->when($this->request->filled('tahun'), fn ($query) => $query->whereYear('created_at', $this->request->tahun))
            ->when($this->request->filled('start_date'), fn ($query) => $query->whereDate('created_at', '>=', $this->request->start_date))
            ->when($this->request->filled('end_date'), fn ($query) => $query->whereDate('created_at', '<=', $this->request->end_date));

        return view('pages.dashboard.exports', [
            'pengguna' => $query->orderBy('created_at', 'desc')->get(),
            'filters' => [
                'q' => $q,
                'status' => $status,
                'tahun' => $this->request->tahun,
                'start_date' => $this->request->start_date,
                'end_date' => $this->request->end_date,
            ],
        ]);
    }

    public function title(): string
    {
        return 'Data Calon Pelanggan';
    }
}
