<?php

namespace App\Exceptions\Accounting;

class InvalidJournalLineException extends AccountingException
{
    public function __construct(string $reason)
    {
        parent::__construct(__('accounting.errors.invalid_line', ['reason' => $reason]));
    }
}
