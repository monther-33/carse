<?php

use App\Actions\Expenses\PostExpense;
use App\Actions\Expenses\SaveExpense;
use App\Actions\Purchases\PostPurchaseInvoice;
use App\Actions\Purchases\SavePurchaseInvoice;
use App\Actions\Vouchers\PostVoucher;
use App\Actions\Vouchers\SaveVoucher;
use App\Models\Account;
use App\Models\CarModel;
use App\Models\Cashbox;
use App\Models\Currency;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Party;
use App\Models\PurchaseInvoice;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Voucher;
use App\Services\Accounting\PostingService;
use App\Services\Accounting\ReversalService;
use App\Support\Money;
use Brick\Math\BigDecimal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
| Helpers for feature tests. The base seed (chart of accounts, cashboxes, roles,
| currencies, current-year periods) is loaded once by TestCase::$seed.
*/

function account(string $code): Account
{
    return Account::query()->where('code', $code)->firstOrFail();
}

function lyd(): Currency
{
    return Currency::query()->where('code', 'LYD')->firstOrFail();
}

function usd(): Currency
{
    return Currency::query()->where('code', 'USD')->firstOrFail();
}

function cashbox(string $name): Cashbox
{
    return Cashbox::query()->where('name', $name)->firstOrFail();
}

function userWithRole(string $role): User
{
    return User::factory()->role($role)->create();
}

function posting(): PostingService
{
    return app(PostingService::class);
}

function reversal(): ReversalService
{
    return app(ReversalService::class);
}

function supplier(): Party
{
    return Party::factory()->supplier()->create();
}

function customer(): Party
{
    return Party::factory()->create();
}

/**
 * Line of a purchase invoice for SavePurchaseInvoice.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function purchaseLine(array $overrides = []): array
{
    $model = CarModel::query()->firstOrFail();

    return $overrides + [
        'vin' => strtoupper(fake()->unique()->bothify('JT###??#?#??#####')),
        'brand_id' => $model->brand_id,
        'model_id' => $model->id,
        'year' => 2022,
        'condition' => 'used',
        'entry_status' => 'available',
        'price' => '50000',
    ];
}

/**
 * Save and post a purchase invoice. Returns the posted invoice.
 *
 * @param  list<array<string, mixed>>  $lines
 * @param  array<string, mixed>  $header
 */
function purchase(array $lines, array $header = []): PurchaseInvoice
{
    $invoice = app(SavePurchaseInvoice::class)->handle($header + [
        'date' => today()->toDateString(),
        'party_id' => $header['party_id'] ?? supplier()->id,
        'source' => 'supplier',
        'currency_id' => lyd()->id,
        'rate' => '1',
        'discount' => '0',
        'paid' => '0',
        'cashbox_id' => null,
        'notes' => null,
        'items' => $lines,
    ]);

    return app(PostPurchaseInvoice::class)->handle($invoice);
}

/** Buy one car in LYD on credit and return it (fresh). */
function purchaseVehicle(string $price = '50000', string $status = 'available'): Vehicle
{
    $invoice = purchase([purchaseLine(['price' => $price, 'entry_status' => $status])]);

    return $invoice->items()->firstOrFail()->vehicle()->firstOrFail();
}

function postExpense(?Vehicle $vehicle, string $amount, ?Cashbox $cashbox = null): Expense
{
    $expense = app(SaveExpense::class)->handle([
        'date' => today()->toDateString(),
        'category_id' => ExpenseCategory::query()->firstOrFail()->id,
        'cashbox_id' => ($cashbox ?? cashbox('خزينة دينار'))->id,
        'amount' => $amount,
        'vehicle_id' => $vehicle?->id,
        'description' => 'مصروف',
        'recurs_every_months' => null,
    ]);

    return app(PostExpense::class)->handle($expense);
}

/**
 * @param  array<string, mixed>  $data
 */
function postVoucher(array $data): Voucher
{
    $voucher = app(SaveVoucher::class)->handle($data + ['date' => today()->toDateString(), 'description' => 'سند']);

    return app(PostVoucher::class)->handle($voucher);
}

/**
 * Balance of one account in base currency (debit - credit) from journal lines.
 */
function baseBalance(Account|string $account): BigDecimal
{
    $id = $account instanceof Account ? $account->id : account($account)->id;

    $row = DB::table('journal_lines')->where('account_id', $id)
        ->selectRaw('COALESCE(SUM(debit_base), 0) AS d, COALESCE(SUM(credit_base), 0) AS c')
        ->first();

    return Money::of((string) $row->d)->minus(Money::of((string) $row->c));
}

/**
 * Trial balance check over the whole ledger: total debits == total credits (base).
 */
function ledgerIsBalanced(): bool
{
    $row = DB::table('journal_lines')
        ->selectRaw('COALESCE(SUM(debit_base), 0) AS d, COALESCE(SUM(credit_base), 0) AS c')
        ->first();

    return Money::of((string) $row->d)->isEqualTo(Money::of((string) $row->c));
}
