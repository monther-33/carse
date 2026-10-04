<?php

namespace Database\Seeders;

use App\Actions\Accounting\GenerateFiscalYear;
use App\Actions\Accounting\SaveCashbox;
use App\Models\Branch;
use App\Models\Cashbox;
use App\Models\Currency;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Branch, currencies, cashboxes, fiscal year and the first admin user.
 */
class FoundationSeeder extends Seeder
{
    public function run(SaveCashbox $saveCashbox, GenerateFiscalYear $generateYear): void
    {
        $branch = Branch::query()->firstOrCreate(['code' => 'MAIN'], ['name' => 'الفرع الرئيسي']);

        Location::query()->firstOrCreate(['branch_id' => $branch->id, 'name' => 'صالة العرض']);

        $lyd = Currency::query()->firstOrCreate(['code' => 'LYD'], [
            'name' => 'دينار ليبي', 'symbol' => 'د.ل', 'decimals' => 3, 'is_base' => true,
        ]);
        $usd = Currency::query()->firstOrCreate(['code' => 'USD'], [
            'name' => 'دولار أمريكي', 'symbol' => '$', 'decimals' => 2, 'is_base' => false,
        ]);

        $this->call([ChartOfAccountsSeeder::class, SettingsSeeder::class]);

        if (Cashbox::query()->doesntExist()) {
            foreach ([
                ['name' => 'خزينة دينار', 'type' => 'cash', 'currency_id' => $lyd->id],
                ['name' => 'خزينة دولار', 'type' => 'cash', 'currency_id' => $usd->id],
                ['name' => 'حساب مصرفي رئيسي', 'type' => 'bank', 'currency_id' => $lyd->id],
            ] as $cashbox) {
                $saveCashbox->handle($cashbox + ['branch_id' => $branch->id, 'is_active' => true]);
            }
        }

        $generateYear->handle((int) now()->year);

        $admin = User::query()->firstOrCreate(['email' => 'admin@cars.local'], [
            'branch_id' => $branch->id,
            'name' => 'مدير النظام',
            'password' => Hash::make((string) config('app.seed_admin_password')),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');
    }
}
