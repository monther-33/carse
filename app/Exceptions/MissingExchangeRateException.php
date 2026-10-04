<?php

namespace App\Exceptions;

use RuntimeException;

class MissingExchangeRateException extends RuntimeException
{
    public function __construct(string $currency, string $date)
    {
        parent::__construct(__('accounting.errors.missing_rate', ['currency' => $currency, 'date' => $date]));
    }
}
