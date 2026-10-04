<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExpenseCategory>
 */
class ExpenseCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'account_id' => fn () => Account::query()->where('code', '66')->value('id') ?? Account::factory(),
            'is_active' => true,
        ];
    }
}
