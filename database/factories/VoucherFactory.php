<?php

namespace Database\Factories;

use App\Enums\DocumentStatus;
use App\Enums\VoucherType;
use App\Models\Cashbox;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Draft voucher. Prefer App\Actions\Vouchers\SaveVoucher in tests, which validates.
 *
 * @extends Factory<Voucher>
 */
class VoucherFactory extends Factory
{
    public function definition(): array
    {
        $cashbox = Cashbox::query()->first() ?? Cashbox::factory()->create();

        return [
            'branch_id' => $cashbox->branch_id,
            'type' => VoucherType::Receipt,
            'date' => now()->toDateString(),
            'cashbox_id' => $cashbox->id,
            'amount' => '100.000',
            'currency_id' => $cashbox->currency_id,
            'rate' => '1.000000',
            'amount_base' => '100.000',
            'description' => fake()->sentence(3),
            'status' => DocumentStatus::Draft,
        ];
    }
}
