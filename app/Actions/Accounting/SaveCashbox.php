<?php

namespace App\Actions\Accounting;

use App\Enums\CashboxType;
use App\Models\Account;
use App\Models\Cashbox;
use App\Support\Settings;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * A new cashbox gets its own ledger account, created under the parent group configured
 * in settings (cashbox.parent.cash / cashbox.parent.bank). Its currency is fixed once created.
 */
class SaveCashbox
{
    public function __construct(
        private readonly SaveAccount $saveAccount,
        private readonly Settings $settings,
    ) {}

    /**
     * @param  array{branch_id: int, name: string, type: string, currency_id: int, bank_name?: string|null, account_number?: string|null, is_active: bool, user_ids?: list<int>}  $data
     */
    public function handle(array $data, ?Cashbox $cashbox = null): Cashbox
    {
        return DB::transaction(function () use ($data, $cashbox) {
            $type = CashboxType::from($data['type']);

            if ($cashbox === null) {
                $cashbox = new Cashbox(['currency_id' => $data['currency_id'], 'type' => $type]);
                $cashbox->account_id = $this->createAccount($type, $data['name'])->getKey();
            } else {
                $cashbox->account->update(['name' => $data['name']]);
            }

            $cashbox->fill([
                'branch_id' => $data['branch_id'],
                'name' => $data['name'],
                'bank_name' => $type === CashboxType::Bank ? ($data['bank_name'] ?? null) : null,
                'account_number' => $type === CashboxType::Bank ? ($data['account_number'] ?? null) : null,
                'is_active' => $data['is_active'],
            ]);
            $cashbox->save();

            if (array_key_exists('user_ids', $data)) {
                $cashbox->users()->sync($data['user_ids']);
            }

            return $cashbox;
        });
    }

    private function createAccount(CashboxType $type, string $name): Account
    {
        $parentId = $this->settings->get('cashbox.parent.'.$type->value);
        $parent = $parentId === null ? null : Account::query()->find((int) $parentId);

        if ($parent === null || ! $parent->is_group) {
            throw ValidationException::withMessages(['type' => __('accounting.validation.cashbox_parent_missing')]);
        }

        return $this->saveAccount->handle([
            'code' => $this->saveAccount->nextChildCode($parent),
            'name' => $name,
            'parent_id' => $parent->getKey(),
            'is_group' => false,
            'is_active' => true,
        ]);
    }
}
