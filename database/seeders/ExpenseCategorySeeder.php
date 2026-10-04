<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

/**
 * Starter expense categories mapped to the operating expense accounts (section 5.2).
 * Vehicle-related categories post to the car's stock account when an expense is linked
 * to a vehicle; their account is only used when no vehicle is selected.
 */
class ExpenseCategorySeeder extends Seeder
{
    private const CATEGORIES = [
        'رواتب وأجور' => '61',
        'إيجار' => '62',
        'كهرباء' => '63',
        'دعاية وإعلان' => '64',
        'مصروفات متنوعة' => '66',
        'شحن وجمارك وتخليص' => '66',
        'صيانة وتجهيز سيارات' => '66',
    ];

    public function run(): void
    {
        $accounts = Account::query()->pluck('id', 'code');

        foreach (self::CATEGORIES as $name => $code) {
            if (isset($accounts[$code])) {
                ExpenseCategory::query()->firstOrCreate(['name' => $name], ['account_id' => $accounts[$code]]);
            }
        }
    }
}
