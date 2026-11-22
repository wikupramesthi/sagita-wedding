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

class DashboardExport implements FromCollection, ShouldAutoSize, WithCustomValueBinder, WithHeadings, WithTitle
{
    private static ?StringValueBinder $binder = null;

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

        self::$binder ??= new StringValueBinder;

        return self::$binder->bindValue($cell, $value);
    }
}
