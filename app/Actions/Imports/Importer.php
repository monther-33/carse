<?php

namespace App\Actions\Imports;

use App\Exceptions\BusinessRuleException;
use App\Imports\ImportPreview;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * One kind of Excel import. analyse() reads and checks every row without writing anything;
 * import() runs the same checks and then writes everything in one transaction, or nothing.
 */
abstract class Importer
{
    abstract public static function kind(): string;

    /** @return array<string, bool> column key => required */
    abstract public function columns(): array;

    /**
     * Columns shown in the preview table (keys of the preview rows).
     *
     * @return list<string>
     */
    abstract public function previewColumns(): array;

    abstract public function allows(User $user): bool;

    /** Whether the import posts accounting entries (needs a date). */
    public function isFinancial(): bool
    {
        return false;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array{date?: string|null, description?: string|null}  $options
     * @return array{0: ImportPreview, 1: array<string, mixed>}
     */
    abstract protected function plan(array $rows, array $options): array;

    /**
     * @param  array<string, mixed>  $plan
     * @param  array{date?: string|null, description?: string|null}  $options
     */
    abstract protected function write(array $plan, array $options): string;

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array{date?: string|null, description?: string|null}  $options
     */
    public function analyse(array $rows, array $options = []): ImportPreview
    {
        return $this->plan($rows, $options)[0];
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array{date?: string|null, description?: string|null}  $options
     * @return string a translated result message
     */
    public function import(array $rows, array $options = []): string
    {
        return DB::transaction(function () use ($rows, $options) {
            [$preview, $plan] = $this->plan($rows, $options);

            if ($preview->hasErrors()) {
                throw BusinessRuleException::make('imports.errors.has_errors', ['count' => count($preview->errors)]);
            }

            return $this->write($plan, $options);
        });
    }
}
