<?php

namespace App\Actions\Imports;

use App\Actions\Journals\PostManualJournal;
use App\Actions\Journals\SaveManualJournal;
use App\Enums\AccountRole;
use App\Exceptions\MissingExchangeRateException;
use App\Imports\CellParser;
use App\Imports\ImportPreview;
use App\Imports\InvalidCell;
use App\Models\Account;
use App\Models\Currency;
use App\Models\ManualJournal;
use App\Models\Party;
use App\Models\User;
use App\Services\Accounting\AccountResolver;
use App\Services\Currency\ExchangeRateService;
use App\Support\Money;
use App\Support\Settings;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;

/**
 * Opening balances (cashboxes, customers, suppliers, deposits, capital...) → a draft manual
 * journal. Each row is one account (by code), with the party on control accounts, in any
 * currency. If the sheet does not balance, the difference goes to the "opening balances"
 * account, to be closed into capital by the accountant.
 *
 * Vehicle stock is refused here: it comes with the vehicles import, car by car. Customer
 * deposits are refused too: deposit credit is computed from receipt vouchers (DepositService),
 * so open deposits are recorded as reservations with their deposit after go-live.
 */
class ImportOpeningBalances extends Importer
{
    public function __construct(
        private readonly AccountResolver $accounts,
        private readonly ExchangeRateService $rates,
        private readonly SaveManualJournal $save,
        private readonly PostManualJournal $post,
        private readonly Settings $settings,
    ) {}

    public static function kind(): string
    {
        return 'balances';
    }

    public function columns(): array
    {
        return ['account' => true, 'party' => false, 'currency' => false, 'debit' => false, 'credit' => false, 'rate' => false, 'memo' => false];
    }

    public function previewColumns(): array
    {
        return ['account', 'party', 'currency', 'debit', 'credit', 'rate', 'base'];
    }

    public function allows(User $user): bool
    {
        return $user->can('imports.run') && $user->can('create', ManualJournal::class);
    }

    public function isFinancial(): bool
    {
        return true;
    }

    protected function plan(array $rows, array $options): array
    {
        $preview = new ImportPreview;
        $date = CarbonImmutable::parse($options['date'] ?? 'today')->startOfDay();
        $accounts = Account::query()->get()->keyBy('code');
        $currencies = Currency::query()->active()->get()->keyBy(fn (Currency $c) => strtoupper($c->code));
        $base = $this->rates->baseCurrency();
        $partyAccounts = $this->accounts->partyAccountIds();
        $stockAccounts = [$this->accounts->idFor(AccountRole::Inventory), $this->accounts->idFor(AccountRole::InTransit)];
        $depositsAccount = $this->accounts->idFor(AccountRole::CustomerDeposits);
        $lines = [];
        $debitBase = Money::zero();
        $creditBase = Money::zero();

        foreach ($rows as $number => $row) {
            try {
                $code = CellParser::text($row['account'] ?? null) ?? throw new InvalidCell(__('imports.errors.required', ['column' => CellParser::label('account')]));
                $account = $accounts[$code] ?? throw new InvalidCell(__('imports.errors.account', ['code' => $code]));
                if (! $account->isPostable()) {
                    throw new InvalidCell(__('imports.errors.account_group', ['code' => $code]));
                }
                if (in_array($account->id, $stockAccounts, true)) {
                    throw new InvalidCell(__('imports.errors.stock_account', ['code' => $code]));
                }
                if ($account->id === $depositsAccount) {
                    throw new InvalidCell(__('imports.errors.deposit_account', ['code' => $code]));
                }

                $party = $this->party(CellParser::text($row['party'] ?? null));
                if (in_array($account->id, $partyAccounts, true) && $party === null) {
                    throw new InvalidCell(__('journals.errors.party_required', ['account' => $account->label()]));
                }
                if (! in_array($account->id, $partyAccounts, true) && $party !== null) {
                    throw new InvalidCell(__('imports.errors.party_not_allowed', ['code' => $code]));
                }

                $currencyCode = strtoupper(CellParser::text($row['currency'] ?? null) ?? $base->code);
                $currency = $currencies[$currencyCode] ?? throw new InvalidCell(__('imports.errors.currency', ['code' => $currencyCode]));

                $debit = CellParser::amount($row['debit'] ?? null, 'debit') ?? Money::zero();
                $credit = CellParser::amount($row['credit'] ?? null, 'credit') ?? Money::zero();
                if ($debit->isPositive() === $credit->isPositive()) {
                    throw new InvalidCell(__('journals.errors.one_side'));
                }

                $rate = $this->rates->isBase($currency)
                    ? Money::rate(1)
                    : (CellParser::rate($row['rate'] ?? null, 'rate') ?? $this->rates->rateFor($currency, $date));
            } catch (InvalidCell $e) {
                $preview->error($number, $e->getMessage());

                continue;
            } catch (MissingExchangeRateException) {
                $preview->error($number, __('imports.errors.no_rate', ['currency' => $currencyCode ?? '', 'date' => $date->toDateString()]));

                continue;
            }

            $amountBase = Money::toBase($debit->isPositive() ? $debit : $credit, $rate);
            $debit->isPositive() ? $debitBase = $debitBase->plus($amountBase) : $creditBase = $creditBase->plus($amountBase);

            $lines[] = [
                'account_id' => $account->id,
                'party_id' => $party?->id,
                'debit' => (string) $debit,
                'credit' => (string) $credit,
                'currency_id' => $currency->id,
                'rate' => (string) $rate,
                'memo' => CellParser::text($row['memo'] ?? null),
            ];
            $preview->rows[] = [
                'row' => $number,
                'account' => $account->label(),
                'party' => $party?->name,
                'currency' => $currency->code,
                'debit' => $debit->isPositive() ? Money::format($debit) : null,
                'credit' => $credit->isPositive() ? Money::format($credit) : null,
                'rate' => $this->rates->isBase($currency) ? null : (string) $rate,
                'base' => Money::format($amountBase),
            ];
        }

        $difference = $debitBase->minus($creditBase);
        $preview->summary[] = __('imports.summary.balances', [
            'count' => count($lines), 'debit' => Money::format($debitBase), 'credit' => Money::format($creditBase),
        ]);

        if (! $difference->isZero()) {
            $opening = $this->accounts->for(AccountRole::OpeningBalances);
            $preview->summary[] = __('imports.summary.difference', ['amount' => Money::format($difference->abs()), 'account' => $opening->label()]);
            $lines[] = [
                'account_id' => $opening->id,
                'party_id' => null,
                'debit' => $difference->isNegative() ? (string) $difference->abs() : '0',
                'credit' => $difference->isPositive() ? (string) $difference : '0',
                'currency_id' => $base->id,
                'rate' => '1',
                'memo' => __('imports.difference_memo'),
            ];
        }

        return [$preview, ['lines' => $lines]];
    }

    /** A party by national id, or by its exact name when that name is unique. */
    private function party(?string $reference): ?Party
    {
        if ($reference === null) {
            return null;
        }

        $byId = Party::query()->where('national_id', $reference)->get(['id', 'name']);
        $matches = $byId->isNotEmpty() ? $byId : Party::query()->where('name', $reference)->get(['id', 'name']);

        return match ($matches->count()) {
            1 => $matches->first(),
            0 => throw new InvalidCell(__('imports.errors.party_not_found', ['party' => $reference])),
            default => throw new InvalidCell(__('imports.errors.party_ambiguous', ['party' => $reference])),
        };
    }

    protected function write(array $plan, array $options): string
    {
        $journal = $this->save->handle([
            'date' => (string) $options['date'],
            'description' => ($options['description'] ?? null) ?: __('imports.opening_balances_description'),
            'lines' => $plan['lines'],
        ]);

        if (! $this->settings->bool('documents.require_approval', true) && Auth::user()->can('approve', $journal)) {
            $posted = $this->post->handle($journal);

            return __('imports.done.balances_posted', ['count' => count($plan['lines']), 'number' => $posted->number]);
        }

        return __('imports.done.balances', ['count' => count($plan['lines'])]);
    }
}
