<?php

namespace App\Imports;

/**
 * What an import would do: the rows as understood, the errors (by spreadsheet row) and a
 * short summary. Nothing is written while a preview is built.
 */
class ImportPreview
{
    /** @var list<array{row: int, message: string}> */
    public array $errors = [];

    /** @var list<array<string, string|int|null>> */
    public array $rows = [];

    /** @var list<string> */
    public array $summary = [];

    public function error(int $row, string $message): void
    {
        $this->errors[] = ['row' => $row, 'message' => $message];
    }

    public function hasErrors(): bool
    {
        return $this->errors !== [];
    }

    /**
     * @return array{errors: list<array{row: int, message: string}>, rows: list<array<string, string|int|null>>, summary: list<string>}
     */
    public function toArray(): array
    {
        return ['errors' => $this->errors, 'rows' => $this->rows, 'summary' => $this->summary];
    }
}
