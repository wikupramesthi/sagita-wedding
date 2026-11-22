<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class DashboardExport implements FromCollection, ShouldAutoSize, WithHeadings, WithTitle, WithCustomValueBinder
{
    public function __construct(
        private array $rows,
        private string $period
    ) {}

    public function collection(): Collection
    {
        return collect($this->rows);
    }

    public function headings(): array
    {
        return ['Bagian', 'Label', 'Nilai', 'Keterangan'];
    }

    public function title(): string
    {
        return 'Dashboard '.$this->period;
    }

    public function bindValue(Cell $cell, mixed $value): bool
    {
        $value = is_string($value) ? ConsultationExport::escape($value) : $value;

        return (new StringValueBinder())->bindValue($cell, $value);
    }
}
