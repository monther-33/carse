<?php

namespace App\Exceptions\Accounting;

class AccountNotPostableException extends AccountingException
{
    public function __construct(string $account)
    {
        parent::__construct(__('accounting.errors.not_postable', ['account' => $account]));
    }
}
