<?php

namespace App\Policies;

use App\Policies\Concerns\DocumentPolicy;

class VoucherPolicy extends DocumentPolicy
{
    protected function module(): string
    {
        return 'vouchers';
    }
}
