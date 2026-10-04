<?php

namespace App\Actions\Sales;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\DocumentStatus;
use App\Enums\VehicleStatus;
use App\Models\Installment;
use App\Models\SalesInvoice;
use Illuminate\Support\Facades\DB;

/**
 * Deletes a draft sale (quotation) with its lines, planned payments, schedule and
 * the pending trade-in vehicle that only existed on it.
 */
class DeleteSalesDraft
{
    use ManagesDocumentLifecycle;

    public function handle(SalesInvoice $invoice): void
    {
        DB::transaction(function () use ($invoice) {
            $invoice = $this->lockInStatus($invoice, DocumentStatus::Draft);
            $invoice->load(['tradeIn.vehicle', 'installmentPlan']);

            $invoice->items()->delete();
            $invoice->payments()->delete();

            if ($invoice->installmentPlan !== null) {
                Installment::query()->where('plan_id', $invoice->installmentPlan->id)->delete();
                $invoice->installmentPlan->delete();
            }

            if ($invoice->tradeIn !== null) {
                $vehicle = $invoice->tradeIn->vehicle;
                $invoice->tradeIn->delete();
                if ($vehicle->status === VehicleStatus::Pending) {
                    $vehicle->delete();
                }
            }

            $invoice->delete();
        });
    }
}
