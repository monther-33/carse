<?php

namespace App\Services\Accounting;

use App\Enums\AccountRole;
use App\Exceptions\Accounting\MissingAccountMappingException;
use App\Models\Account;
use App\Support\Settings;

/**
 * Maps logical account roles (receivables, inventory...) to real accounts via settings.
 */
class AccountResolver
{
    public function __construct(private readonly Settings $settings) {}

    public function for(AccountRole $role): Account
    {
        $id = $this->settings->get($role->settingKey());

        $account = $id === null ? null : Account::query()->find((int) $id);

        if ($account === null) {
            throw new MissingAccountMappingException($role->label());
        }

        return $account;
    }

    public function idFor(AccountRole $role): int
    {
        return (int) $this->for($role)->getKey();
    }
}
