<?php

namespace App\Policies;

use App\Enums\DocumentStatus;
use App\Models\SalesInvoice;
use App\Models\User;
use App\Policies\Concerns\DocumentPolicy;
use Illuminate\Database\Eloquent\Model;

/**
 * Sales staff see their own invoices only (sales.view); sales.view_all sees everything.
 */
class SalesInvoicePolicy extends DocumentPolicy
{
    protected function module(): string
    {
        return 'sales';
    }

    public function viewAny(User $user): bool
    {
        return $user->canAny(['sales.view', 'sales.view_all']);
    }

    public function view(User $user, Model $document): bool
    {
        /** @var SalesInvoice $document */
        return $user->can('sales.view_all')
            || ($user->can('sales.view') && in_array($user->id, [$document->salesperson_id, $document->created_by], true));
    }

    public function update(User $user, Model $document): bool
    {
        return parent::update($user, $document) && $this->view($user, $document);
    }

    public function returnItem(User $user, SalesInvoice $invoice): bool
    {
        return $invoice->status === DocumentStatus::Posted && $user->can('sales.approve');
    }

    public function deliver(User $user, SalesInvoice $invoice): bool
    {
        return $invoice->status === DocumentStatus::Posted && $invoice->delivered_at === null
            && $user->can('sales.create') && $this->view($user, $invoice);
    }

    public function print(User $user, SalesInvoice $invoice): bool
    {
        return $this->view($user, $invoice);
    }
}
