<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class PenggunaExport implements FromView, ShouldAutoSize, WithCustomValueBinder, WithTitle
{
    private static ?StringValueBinder $binder = null;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $filters = User::normalizeProspectFilters($this->request->all());
        $baseQuery = fn () => User::role('user');

        $query = $baseQuery()->with('latestOrder.package');
        User::applyProspectFilters($query, $filters);

        return view('pages.dashboard.exports', [
            'pengguna' => $query->orderBy('created_at', 'desc')->get(),
            'sourceStats' => User::prospectStats($baseQuery)['sources'],
            'sourceOptions' => User::sourceFilterOptions(),
            'filters' => $filters,
        ]);
    }

    public function title(): string
    {
        return 'Data Calon Pelanggan';
    }

    public function bindValue(Cell $cell, mixed $value): bool
    {
        $value = is_string($value) ? ConsultationExport::escape($value) : $value;

        self::$binder ??= new StringValueBinder;

        return self::$binder->bindValue($cell, $value);
    }
}
