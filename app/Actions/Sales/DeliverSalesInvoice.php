<?php

namespace App\Actions\Sales;

use App\Enums\DocumentStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\SalesInvoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Records handing the car(s) over to the customer (printed as the delivery note).
 */
class DeliverSalesInvoice
{
    public function handle(SalesInvoice $invoice): SalesInvoice
    {
        return DB::transaction(function () use ($invoice) {
            $invoice = SalesInvoice::query()->lockForUpdate()->findOrFail($invoice->id);

            if ($invoice->status !== DocumentStatus::Posted) {
                throw BusinessRuleException::make('documents.errors.not_posted');
            }
            if ($invoice->delivered_at !== null) {
                throw BusinessRuleException::make('sales.errors.already_delivered');
            }

            $invoice->update(['delivered_at' => now(), 'delivered_by' => Auth::id()]);

            return $invoice;
        });
    }
}
