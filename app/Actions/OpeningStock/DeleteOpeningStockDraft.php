<?php

namespace App\Actions\OpeningStock;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\DocumentStatus;
use App\Enums\VehicleStatus;
use App\Models\OpeningStock;
use Illuminate\Support\Facades\DB;

/**
 * Deletes a draft opening stock and the pending vehicles that only existed on it.
 */
class DeleteOpeningStockDraft
{
    use ManagesDocumentLifecycle;

    public function handle(OpeningStock $stock): void
    {
        DB::transaction(function () use ($stock) {
            $stock = $this->lockInStatus($stock, DocumentStatus::Draft);

            foreach ($stock->items()->with('vehicle')->get() as $item) {
                $item->delete();
                if ($item->vehicle->status === VehicleStatus::Pending) {
                    $item->vehicle->delete();
                }
            }

            $stock->delete();
        });
    }
}
