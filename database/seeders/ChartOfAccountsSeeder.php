<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Models\Account;
use Illuminate\Database\Seeder;

/**
 * Default chart of accounts (spec section 5.2). Cash and bank leaves (1101, 1102, 1201)
 * are created by CashboxSeeder together with their cashboxes.
 */
class ChartOfAccountsSeeder extends Seeder
{
    /**
     * [code, name, type, is_group, children]
     */
    private const TREE = [
        ['1', 'الأصول', AccountType::Asset, true, [
            ['11', 'النقدية والخزائن', null, true, []],
            ['12', 'البنوك', null, true, []],
            ['13', 'ذمم العملاء', null, false, []],
            ['14', 'مخزون السيارات', null, false, []],
            ['15', 'سيارات في الطريق / تحت التخليص', null, false, []],
        ]],
        ['2', 'الخصوم', AccountType::Liability, true, [
            ['21', 'ذمم الموردين', null, false, []],
            ['22', 'عرابين العملاء', null, false, []],
            ['23', 'عمولات مستحقة', null, false, []],
        ]],
        ['3', 'حقوق الملكية', AccountType::Equity, true, [
            ['31', 'رأس المال', null, false, []],
            ['32', 'جاري الشركاء', null, false, []],
            ['33', 'الأرباح المحتجزة', null, false, []],
            ['34', 'أرصدة افتتاحية', null, false, []],
        ]],
        ['4', 'الإيرادات', AccountType::Revenue, true, [
            ['41', 'مبيعات السيارات', null, false, []],
            ['42', 'إيرادات أخرى', null, false, []],
        ]],
        ['5', 'تكلفة السيارات المباعة', AccountType::Expense, true, [
            ['51', 'تكلفة السيارات المباعة', null, false, []],
        ]],
        ['6', 'المصروفات التشغيلية', AccountType::Expense, true, [
            ['61', 'الرواتب والأجور', null, false, []],
            ['62', 'الإيجار', null, false, []],
            ['63', 'الكهرباء', null, false, []],
            ['64', 'الدعاية والإعلان', null, false, []],
            ['65', 'عمولات المبيعات', null, false, []],
            ['66', 'مصروفات متنوعة', null, false, []],
        ]],
        ['7', 'فروقات العملة والخصم المسموح به', AccountType::Expense, true, [
            ['71', 'فروقات العملة', null, false, []],
            ['72', 'الخصم المسموح به', null, false, []],
        ]],
    ];

    public function run(): void
    {
        foreach (self::TREE as $node) {
            $this->seedNode($node, null, $node[2]);
        }
    }

    /**
     * @param  array{0: string, 1: string, 2: AccountType|null, 3: bool, 4: array<int, mixed>}  $node
     */
    private function seedNode(array $node, ?Account $parent, AccountType $type): void
    {
        [$code, $name, , $isGroup, $children] = $node;

        $account = Account::query()->firstOrCreate(['code' => $code], [
            'name' => $name,
            'parent_id' => $parent?->getKey(),
            'type' => $type,
            'nature' => $type->nature(),
            'is_group' => $isGroup,
            'is_system' => true,
            'is_active' => true,
        ]);

        foreach ($children as $child) {
            $this->seedNode($child, $account, $type);
        }
    }
}
