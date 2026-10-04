<?php

namespace Database\Factories;

use App\Enums\AccountType;
use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(AccountType::cases());

        return [
            'code' => (string) fake()->unique()->numberBetween(900000, 999999),
            'name' => fake()->words(2, true),
            'parent_id' => null,
            'type' => $type,
            'nature' => $type->nature(),
            'is_group' => false,
            'is_system' => false,
            'is_active' => true,
        ];
    }

    public function group(): static
    {
        return $this->state(fn () => ['is_group' => true]);
    }

    public function ofType(AccountType $type): static
    {
        return $this->state(fn () => ['type' => $type, 'nature' => $type->nature()]);
    }
}
