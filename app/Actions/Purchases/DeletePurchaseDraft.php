<?php

namespace App\Actions\Purchases;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\DocumentStatus;
use App\Enums\VehicleStatus;
use App\Models\PurchaseInvoice;
use Illuminate\Support\Facades\DB;

/**
 * Deletes a draft purchase invoice and the pending vehicles that only existed on it.
 */
class DeletePurchaseDraft
{
    use ManagesDocumentLifecycle;

    public function handle(PurchaseInvoice $invoice): void
    {
        DB::transaction(function () use ($invoice) {
            $invoice = $this->lockInStatus($invoice, DocumentStatus::Draft);

            foreach ($invoice->items()->with('vehicle')->get() as $item) {
                $item->delete();
                if ($item->vehicle->status === VehicleStatus::Pending) {
                    $item->vehicle->delete();
                }
            }

            $invoice->delete();
        });
    }
}
