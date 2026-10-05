<?php

namespace Database\Seeders;

use App\Enums\AccountRole;
use App\Models\Account;
use App\Models\Branch;
use App\Support\Settings;
use Illuminate\Database\Seeder;

/**
 * Default settings. Existing values are kept so re-seeding never overwrites admin choices.
 * This is the only place that knows account codes; everything else reads account ids from settings.
 */
class SettingsSeeder extends Seeder
{
    private const ACCOUNT_ROLES = [
        AccountRole::Receivables->value => '13',
        AccountRole::Inventory->value => '14',
        AccountRole::InTransit->value => '15',
        AccountRole::Payables->value => '21',
        AccountRole::CustomerDeposits->value => '22',
        AccountRole::AccruedCommissions->value => '23',
        AccountRole::Capital->value => '31',
        AccountRole::PartnersCurrent->value => '32',
        AccountRole::RetainedEarnings->value => '33',
        AccountRole::VehicleSales->value => '41',
        AccountRole::OtherRevenue->value => '42',
        AccountRole::CostOfSales->value => '51',
        AccountRole::CommissionExpense->value => '65',
        AccountRole::FxDifferences->value => '71',
        AccountRole::DiscountAllowed->value => '72',
        AccountRole::ForfeitedDeposits->value => '42',
        AccountRole::OpeningBalances->value => '34',
    ];

    public function run(Settings $settings): void
    {
        $accountIds = Account::query()->pluck('id', 'code');

        $defaults = [
            'company.name' => 'معرض السيارات',
            'company.phone' => null,
            'company.address' => null,
            'company.logo' => null,
            'branch.default_id' => Branch::query()->value('id'),
            'documents.require_approval' => '1',
            'sales.commission_type' => 'percent',
            'sales.commission_value' => '0',
            'inventory.stale_days_warning' => '60',
            'inventory.stale_days_critical' => '90',
            'reservations.expiry_action' => 'credit', // credit | forfeit
            'cashbox.parent.cash' => $accountIds['11'] ?? null,
            'cashbox.parent.bank' => $accountIds['12'] ?? null,
        ];

        foreach (self::ACCOUNT_ROLES as $role => $code) {
            $defaults['account.'.$role] = $accountIds[$code] ?? null;
        }

        $existing = $settings->all();
        $missing = array_diff_key($defaults, $existing);

        $settings->set($missing);
    }
}
