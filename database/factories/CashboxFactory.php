<?php

namespace Database\Factories;

use App\Enums\AccountType;
use App\Enums\CashboxType;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Cashbox;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cashbox>
 */
class CashboxFactory extends Factory
{
    public function definition(): array
    {
        return [
            'branch_id' => fn () => Branch::query()->value('id') ?? Branch::factory(),
            'name' => 'خزينة '.fake()->unique()->word(),
            'type' => CashboxType::Cash,
            'currency_id' => fn () => Currency::query()->where('is_base', true)->value('id') ?? Currency::factory(),
            'account_id' => Account::factory()->ofType(AccountType::Asset),
            'is_active' => true,
        ];
    }
}
