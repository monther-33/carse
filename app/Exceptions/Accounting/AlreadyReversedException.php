<?php

namespace App\Exceptions\Accounting;

class AlreadyReversedException extends AccountingException
{
    public function __construct(string $number)
    {
        parent::__construct(__('accounting.errors.already_reversed', ['number' => $number]));
    }
}
