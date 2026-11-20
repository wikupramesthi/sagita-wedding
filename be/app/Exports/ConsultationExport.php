<?php

namespace App\Exports;

use App\Models\Consultation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ConsultationExport implements FromView, ShouldAutoSize, WithTitle
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $filters = Consultation::normalizeFilters($this->request->query());
        $query = Consultation::query()->with(['package:id,name', 'handler:id,name']);
        Consultation::applyFilters($query, $filters);
        $consultations = $query->latest()->get();

        return view('pages.konsultasi.exports', compact('consultations', 'filters'));
    }

    public function title(): string
    {
        return 'Rekap Konsultasi';
    }
}
