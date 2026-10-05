<?php

namespace App\Actions\Imports;

use App\Enums\PartyType;
use App\Imports\CellParser;
use App\Imports\ImportPreview;
use App\Imports\InvalidCell;
use App\Models\Branch;
use App\Models\Party;
use App\Models\User;
use App\Support\Money;
use Illuminate\Support\Facades\Auth;

/**
 * Customers and suppliers. A party that already exists (same national id, or same name and
 * phone) is skipped, so the same file can be imported twice safely. Balances are imported
 * separately with the opening balances sheet.
 */
class ImportParties extends Importer
{
    public static function kind(): string
    {
        return 'parties';
    }

    public function columns(): array
    {
        return [
            'name' => true, 'type' => true, 'phone' => false, 'phone2' => false,
            'national_id' => false, 'address' => false, 'credit_limit' => false, 'notes' => false,
        ];
    }

    public function previewColumns(): array
    {
        return ['name', 'type', 'phone', 'national_id', 'credit_limit', 'result'];
    }

    public function allows(User $user): bool
    {
        return $user->can('imports.run') && $user->can('parties.manage');
    }

    protected function plan(array $rows, array $options): array
    {
        $preview = new ImportPreview;
        $create = [];
        $seen = [];
        $skipped = 0;

        foreach ($rows as $number => $row) {
            try {
                $data = [
                    'name' => CellParser::text($row['name'] ?? null) ?? throw new InvalidCell(__('imports.errors.required', ['column' => CellParser::label('name')])),
                    'type' => (CellParser::enum(PartyType::class, $row['type'] ?? null, 'type', 'enums.party_type')
                        ?? throw new InvalidCell(__('imports.errors.required', ['column' => CellParser::label('type')])))->value,
                    'phone' => CellParser::text($row['phone'] ?? null),
                    'phone2' => CellParser::text($row['phone2'] ?? null),
                    'national_id' => CellParser::text($row['national_id'] ?? null),
                    'address' => CellParser::text($row['address'] ?? null),
                    'credit_limit' => (string) (CellParser::amount($row['credit_limit'] ?? null, 'credit_limit') ?? Money::zero()),
                    'notes' => CellParser::text($row['notes'] ?? null),
                ];
            } catch (InvalidCell $e) {
                $preview->error($number, $e->getMessage());

                continue;
            }

            if (mb_strlen($data['name']) > 255 || mb_strlen((string) $data['address']) > 255
                || mb_strlen((string) $data['phone']) > 50 || mb_strlen((string) $data['phone2']) > 50 || mb_strlen((string) $data['national_id']) > 50) {
                $preview->error($number, __('imports.errors.too_long'));

                continue;
            }

            $identity = $data['national_id'] !== null ? 'id:'.$data['national_id'] : 'np:'.mb_strtolower($data['name']).'|'.$data['phone'];
            if (isset($seen[$identity])) {
                $preview->error($number, __('imports.errors.duplicate_row', ['row' => $seen[$identity]]));

                continue;
            }
            $seen[$identity] = $number;

            $exists = Party::query()
                ->when($data['national_id'] !== null,
                    fn ($q) => $q->where('national_id', $data['national_id']),
                    fn ($q) => $q->where('name', $data['name'])->where('phone', $data['phone']))
                ->exists();

            $preview->rows[] = [
                'row' => $number,
                'name' => $data['name'],
                'type' => PartyType::from($data['type'])->label(),
                'phone' => $data['phone'],
                'national_id' => $data['national_id'],
                'credit_limit' => Money::format($data['credit_limit']),
                'result' => $exists ? __('imports.result.exists') : __('imports.result.new'),
            ];

            if ($exists) {
                $skipped++;
            } else {
                $create[] = $data;
            }
        }

        $preview->summary[] = __('imports.summary.parties', ['new' => count($create), 'skipped' => $skipped]);

        return [$preview, ['create' => $create]];
    }

    protected function write(array $plan, array $options): string
    {
        $branchId = Auth::user()->branch_id ?? Branch::query()->value('id');

        foreach ($plan['create'] as $data) {
            Party::query()->create($data + ['branch_id' => $branchId]);
        }

        return __('imports.done.parties', ['count' => count($plan['create'])]);
    }
}
