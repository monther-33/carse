<?php

namespace App\Imports;

use App\Exceptions\BusinessRuleException;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Reads the first sheet of an uploaded .xlsx / .xls / .csv file into rows keyed by column.
 *
 * The first row holds the headers. A header matches a column by its Arabic or English label
 * (as in the downloadable template, with or without the "*" of required columns) or by its
 * key, so columns may come in any order and unknown columns are ignored.
 */
class SheetReader
{
    public const MAX_ROWS = 2000;

    private const TYPES = ['xlsx' => ExcelType::XLSX, 'xls' => ExcelType::XLS, 'csv' => ExcelType::CSV];

    /**
     * @param  array<string, bool>  $columns  column key => required
     * @return array<int, array<string, mixed>> spreadsheet row number => [column => raw value]
     */
    public function read(string $path, string $extension, array $columns): array
    {
        $type = self::TYPES[strtolower($extension)] ?? throw BusinessRuleException::make('imports.errors.file_type');

        $sheets = Excel::toArray(new class implements ToArray
        {
            /** @param array<int, array<int, mixed>> $array */
            public function array(array $array): void {}
        }, $path, null, $type);

        $raw = $sheets[0] ?? [];
        $headers = array_shift($raw) ?? [];
        $map = $this->mapHeaders($headers, $columns);

        $rows = [];
        foreach ($raw as $index => $cells) {
            $row = [];
            foreach ($map as $position => $key) {
                $row[$key] = $cells[$position] ?? null;
            }
            if (array_filter($row, fn ($v) => $v !== null && trim((string) $v) !== '') === []) {
                continue; // blank line
            }
            $rows[$index + 2] = $row; // +1 for the header, +1 because sheets count from 1
        }

        if ($rows === []) {
            throw BusinessRuleException::make('imports.errors.empty');
        }
        if (count($rows) > self::MAX_ROWS) {
            throw BusinessRuleException::make('imports.errors.too_many', ['max' => self::MAX_ROWS]);
        }

        return $rows;
    }

    /**
     * @param  array<int, mixed>  $headers
     * @param  array<string, bool>  $columns
     * @return array<int, string> position => column key
     */
    private function mapHeaders(array $headers, array $columns): array
    {
        $lookup = [];
        foreach (array_keys($columns) as $key) {
            foreach ([$key, __('imports.columns.'.$key, [], 'ar'), __('imports.columns.'.$key, [], 'en')] as $name) {
                $lookup[self::normalise($name)] = $key;
            }
        }

        $map = [];
        foreach ($headers as $position => $header) {
            $key = $lookup[self::normalise((string) $header)] ?? null;
            if ($key !== null && ! in_array($key, $map, true)) {
                $map[$position] = $key;
            }
        }

        $missing = array_keys(array_filter($columns, fn (bool $required, string $key) => $required && ! in_array($key, $map, true), ARRAY_FILTER_USE_BOTH));
        if ($missing !== []) {
            throw BusinessRuleException::make('imports.errors.missing_columns', [
                'columns' => implode('، ', array_map(fn ($k) => __('imports.columns.'.$k), $missing)),
            ]);
        }

        return $map;
    }

    private static function normalise(string $header): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/u', ' ', str_replace('*', '', $header)) ?? ''));
    }
}
