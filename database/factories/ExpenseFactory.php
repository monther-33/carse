<?php

namespace Database\Factories;

use App\Enums\DocumentStatus;
use App\Models\Cashbox;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Draft expense in the cashbox currency. Post it with App\Actions\Expenses\PostExpense.
 *
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        $cashbox = Cashbox::query()->first() ?? Cashbox::factory()->create();

        return [
            'branch_id' => $cashbox->branch_id,
            'date' => now()->toDateString(),
            'category_id' => ExpenseCategory::factory(),
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
