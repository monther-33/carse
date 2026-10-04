<?php

namespace App\Actions\Accounting;

use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Creates or updates a chart-of-accounts node.
 *
 * Rules: a child inherits its parent's type and nature, its code must start with the
 * parent's code, and only group accounts can have children. An account that already
 * has postings cannot become a group; one with children cannot become a leaf.
 */
class SaveAccount
{
    /**
     * @param  array{code: string, name: string, parent_id: int|null, is_group: bool, is_active: bool, type?: string|null}  $data
     */
    public function handle(array $data, ?Account $account = null): Account
    {
        return DB::transaction(function () use ($data, $account) {
            $parent = $data['parent_id'] ? Account::query()->lockForUpdate()->findOrFail($data['parent_id']) : null;

            if ($parent !== null) {
                $this->assertValidParent($parent, $data['code'], $account);
            } elseif ($account === null || $account->parent_id !== null) {
                // Root accounts come from the seeded chart only.
                throw ValidationException::withMessages(['parent_id' => __('accounting.validation.parent_required')]);
            }

            if ($account !== null) {
                $this->assertGroupChangeAllowed($account, $data['is_group']);

                $codeChanged = $data['code'] !== $account->code;
                if ($codeChanged && ($account->is_system || $account->children()->exists())) {
                    throw ValidationException::withMessages(['code' => __('accounting.validation.code_locked')]);
                }
            }

            $type = $parent !== null ? $parent->type : $account->type;

            $account ??= new Account;
            $account->fill([
                'code' => $data['code'],
                'name' => $data['name'],
                'parent_id' => $parent?->getKey(),
                'type' => $type,
                'nature' => $type->nature(),
                'is_group' => $data['is_group'],
                'is_active' => $data['is_active'],
            ]);
            $account->save();

            return $account;
        });
    }

    public function nextChildCode(Account $parent): string
    {
        $codes = Account::query()->where('parent_id', $parent->getKey())->pluck('code');

        if ($codes->isEmpty()) {
            // 1 -> 11, 11 -> 1101 (two-digit suffix below level 1).
            return $parent->code.(strlen($parent->code) === 1 ? '1' : '01');
        }

        $suffixLength = strlen((string) $codes->first()) - strlen($parent->code);
        $max = $codes->map(fn (string $code) => (int) substr($code, strlen($parent->code)))->max();

        return $parent->code.str_pad((string) ($max + 1), $suffixLength, '0', STR_PAD_LEFT);
    }

    private function assertValidParent(Account $parent, string $code, ?Account $account): void
    {
        if (! $parent->is_group) {
            throw ValidationException::withMessages(['parent_id' => __('accounting.validation.parent_must_be_group')]);
        }

        if (! str_starts_with($code, $parent->code) || $code === $parent->code) {
            throw ValidationException::withMessages(['code' => __('accounting.validation.code_prefix', ['prefix' => $parent->code])]);
        }

        if ($account !== null && $account->is_system && $account->parent_id !== $parent->getKey()) {
            throw ValidationException::withMessages(['parent_id' => __('accounting.validation.system_account_locked')]);
        }

        if ($account !== null && $account->getKey() === $parent->getKey()) {
            throw ValidationException::withMessages(['parent_id' => __('accounting.validation.parent_must_be_group')]);
        }
    }

    private function assertGroupChangeAllowed(Account $account, bool $isGroup): void
    {
        if ($isGroup === $account->is_group) {
            return;
        }

        if ($isGroup && $account->lines()->exists()) {
            throw ValidationException::withMessages(['is_group' => __('accounting.validation.has_postings')]);
        }

        if (! $isGroup && $account->children()->exists()) {
            throw ValidationException::withMessages(['is_group' => __('accounting.validation.has_children')]);
        }
    }
}
