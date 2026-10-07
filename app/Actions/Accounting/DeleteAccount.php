<?php

namespace App\Actions\Accounting;

use App\Models\Account;
use App\Models\Cashbox;
use App\Services\Trash\RecycleBin;
use App\Support\Settings;
use Illuminate\Validation\ValidationException;

/**
 * An account can be deleted only if nothing references it; otherwise deactivate it.
 */
class DeleteAccount
{
    public function __construct(
        private readonly Settings $settings,
        private readonly RecycleBin $bin,
    ) {}

    public function handle(Account $account): void
    {
        $this->bin->keep($account, function () use ($account) {
            $account = Account::query()->lockForUpdate()->findOrFail($account->getKey());

            $inUse = $account->is_system
                || $account->children()->exists()
                || $account->lines()->exists()
                || Cashbox::query()->where('account_id', $account->getKey())->exists()
                || $this->isMappedInSettings($account);

            if ($inUse) {
                throw ValidationException::withMessages(['account' => __('accounting.validation.account_in_use')]);
            }

            $account->delete();
        });
    }

    private function isMappedInSettings(Account $account): bool
    {
        foreach ($this->settings->all() as $key => $value) {
            if ((str_starts_with($key, 'account.') || str_starts_with($key, 'cashbox.parent.'))
                && (string) $value === (string) $account->getKey()) {
                return true;
            }
        }

        return false;
    }
}
