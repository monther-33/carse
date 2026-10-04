<?php

use App\Models\Account;
use App\Models\Cashbox;
use App\Models\Currency;
use App\Models\User;
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
