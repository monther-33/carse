<?php

namespace App\Exceptions\Accounting;

class MissingAccountMappingException extends AccountingException
{
    public function __construct(string $role)
    {
        parent::__construct(__('accounting.errors.missing_mapping', ['role' => $role]));
    }
}
