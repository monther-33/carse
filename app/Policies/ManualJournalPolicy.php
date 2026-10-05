<?php

namespace App\Policies;

use App\Policies\Concerns\DocumentPolicy;

class ManualJournalPolicy extends DocumentPolicy
{
    protected function module(): string
    {
        return 'journal';
    }
}
