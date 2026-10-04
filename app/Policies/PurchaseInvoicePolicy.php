<?php

namespace App\Policies;

use App\Enums\DocumentStatus;
use App\Models\PurchaseInvoice;
use App\Models\User;
use App\Policies\Concerns\DocumentPolicy;

class PurchaseInvoicePolicy extends DocumentPolicy
{
    protected function module(): string
    {
        return 'purchases';
    }

    /** A return is a new posted document affecting stock: it needs approval rights. */
    public function returnItem(User $user, PurchaseInvoice $invoice): bool
    {
        return $invoice->status === DocumentStatus::Posted && $user->can('purchases.approve');
    }
}
