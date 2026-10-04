<?php

namespace App\Exceptions\Accounting;

class UnbalancedEntryException extends AccountingException
{
    public function __construct(string $debit, string $credit)
    {
        parent::__construct(__('accounting.errors.unbalanced', ['debit' => $debit, 'credit' => $credit]));
    }
}
