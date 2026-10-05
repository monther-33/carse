<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

/**
 * The downloadable Excel template of an import: one header row (required columns marked
 * with "*"), right to left. Rows may be given (used by tests to build sample files).
 */
class ImportTemplate implements FromArray, ShouldAutoSize, WithEvents, WithHeadings
{
    /**
     * @param  array<string, bool>  $columns  column key => required
     * @param  list<array<string, mixed>>  $rows
     */
    public function __construct(
        private readonly array $columns,
        private readonly array $rows = [],
    ) {}

    /** @return list<string> */
    public function headings(): array
    {
        $headings = [];
        foreach ($this->columns as $key => $required) {
            $headings[] = __('imports.columns.'.$key).($required ? ' *' : '');
        }

        return $headings;
    }

    /** @return list<list<mixed>> */
    public function array(): array
    {
        return array_map(
            fn (array $row) => array_map(fn (string $key) => $row[$key] ?? null, array_keys($this->columns)),
            $this->rows,
        );
    }

    /** @return array<class-string, callable> */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
                $event->sheet->getDelegate()->getStyle('1:1')->getFont()->setBold(true);
            },
        ];
    }
}
