<?php

namespace App\Exceptions\Accounting;

class NoFiscalPeriodException extends AccountingException
{
    public function __construct(string $date)
    {
        parent::__construct(__('accounting.errors.no_period', ['date' => $date]));
    }
}
