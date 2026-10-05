<?php

namespace App\Exports;

use App\Reports\Cell;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

/**
 * Any report as a right-to-left Excel sheet with numeric cells (totals included as a row).
 */
class ReportExport implements FromArray, ShouldAutoSize, WithEvents, WithHeadings, WithTitle
{
    /**
     * @param  array<string, array{label: string, type: string, total?: bool}>  $columns
     * @param  list<array<string, mixed>>  $rows
     */
    public function __construct(
        private readonly string $title,
        private readonly array $columns,
        private readonly array $rows,
    ) {}

    /** @return list<string> */
    public function headings(): array
    {
        return array_column($this->columns, 'label');
    }

    /** @return list<list<mixed>> */
    public function array(): array
    {
        $data = [];
        foreach ($this->rows as $row) {
            $line = [];
            foreach ($this->columns as $key => $column) {
                $value = Cell::excel($row[$key] ?? null, $column['type']);
                if (! empty($row['_indent']) && $key === array_key_first($this->columns) && is_string($value)) {
                    $value = str_repeat('   ', (int) $row['_indent']).$value;
                }
                $line[] = $value;
            }
            $data[] = $line;
        }

        $totals = Cell::totals($this->columns, $this->rows);
        if ($totals !== null && $this->rows !== []) {
            $line = [];
            foreach ($this->columns as $key => $column) {
                $line[] = array_key_exists($key, $totals) ? Cell::excel($totals[$key], $column['type']) : null;
            }
            $line[0] ??= __('documents.total');
            $data[] = $line;
        }

        return $data;
    }

    public function title(): string
    {
        return mb_substr($this->title, 0, 31);
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
