<?php

namespace App\Exceptions\Accounting;

class ClosedPeriodException extends AccountingException
{
    public function __construct(string $period)
    {
        parent::__construct(__('accounting.errors.closed_period', ['period' => $period]));
    }
}
