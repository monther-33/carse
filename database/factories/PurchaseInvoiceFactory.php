<?php

namespace Database\Factories;

use App\Enums\DocumentStatus;
use App\Enums\PurchaseSource;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\Party;
use App\Models\PurchaseInvoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Empty draft header. Real invoices (with vehicles) go through SavePurchaseInvoice.
 *
 * @extends Factory<PurchaseInvoice>
 */
class PurchaseInvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'branch_id' => fn () => Branch::query()->value('id') ?? Branch::factory(),
            'date' => now()->toDateString(),
            'party_id' => Party::factory()->supplier(),
            'source' => PurchaseSource::Supplier,
            'currency_id' => fn () => Currency::query()->where('is_base', true)->value('id'),
            'rate' => '1.000000',
            'status' => DocumentStatus::Draft,
        ];
    }
}
