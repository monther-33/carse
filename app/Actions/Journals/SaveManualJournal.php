<?php

namespace App\Actions\Journals;

use App\Actions\Concerns\ManagesDocumentLifecycle;
use App\Enums\DocumentStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\Account;
use App\Models\Branch;
use App\Models\ManualJournal;
use App\Services\Accounting\AccountResolver;
use App\Services\Currency\ExchangeRateService;
use App\Support\Money;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Creates or updates a DRAFT manual journal entry (capital, partner drawings, adjustments,
 * opening balances...). Each line is a debit OR a credit in its own currency; the draft must
 * already balance in the base currency, exactly as PostingService will require.
 */
class SaveManualJournal
{
    use ManagesDocumentLifecycle;

    public function __construct(
        private readonly ExchangeRateService $rates,
        private readonly AccountResolver $accounts,
    ) {}

    /**
     * @param  array{date: string, description: string, notes?: string|null,
     *               lines: list<array{account_id: int, party_id?: int|null, debit?: mixed, credit?: mixed, currency_id?: int|null, rate?: mixed, memo?: string|null}>}  $data
     */
    public function handle(array $data, ?ManualJournal $journal = null): ManualJournal
    {
        return DB::transaction(function () use ($data, $journal) {
            if ($journal !== null) {
                $journal = $this->lockInStatus($journal, DocumentStatus::Draft);
            }

            $date = CarbonImmutable::parse($data['date']);
            $lines = $this->normalise($data['lines'], $date);

            $journal ??= new ManualJournal(['status' => DocumentStatus::Draft, 'branch_id' => Auth::user()->branch_id ?? Branch::query()->value('id')]);
            $journal->fill([
                'date' => $date->toDateString(),
                'description' => $data['description'],
                'notes' => $data['notes'] ?? null,
            ])->save();

            $journal->lines()->delete();
            $journal->lines()->createMany($lines);

            return $journal->load('lines');
        });
    }

    /**
     * @param  list<array<string, mixed>>  $lines
     * @return list<array<string, mixed>>
     */
    private function normalise(array $lines, CarbonImmutable $date): array
    {
        $base = $this->rates->baseCurrency();
        $partyAccounts = $this->accounts->partyAccountIds();
        $result = [];
        $debitBase = Money::zero();
        $creditBase = Money::zero();

        foreach ($lines as $line) {
            $debit = Money::of((string) ($line['debit'] ?? '0') ?: '0');
            $credit = Money::of((string) ($line['credit'] ?? '0') ?: '0');

            if ($debit->isZero() && $credit->isZero()) {
                continue;
            }
            if ($debit->isPositive() === $credit->isPositive() || $debit->isNegative() || $credit->isNegative()) {
                throw BusinessRuleException::make('journals.errors.one_side');
            }

            $account = Account::query()->find($line['account_id']);
            if ($account === null || ! $account->isPostable()) {
                throw BusinessRuleException::make('journals.errors.account');
            }
            if (in_array($account->id, $partyAccounts, true) && empty($line['party_id'])) {
                throw BusinessRuleException::make('journals.errors.party_required', ['account' => $account->label()]);
            }

            $currencyId = (int) ($line['currency_id'] ?? 0) ?: $base->id;
            $rate = $this->rates->isBase($currencyId)
                ? Money::rate(1)
                : (empty($line['rate']) ? $this->rates->rateFor($currencyId, $date) : Money::rate((string) $line['rate']));

            $debitBase = $debitBase->plus(Money::toBase($debit, $rate));
            $creditBase = $creditBase->plus(Money::toBase($credit, $rate));

            $result[] = [
                'account_id' => $account->id,
                'party_id' => ($line['party_id'] ?? null) ?: null,
                'debit' => (string) $debit,
                'credit' => (string) $credit,
                'currency_id' => $currencyId,
                'rate' => (string) $rate,
                'memo' => $line['memo'] ?? null,
            ];
        }

        if (count($result) < 2) {
            throw BusinessRuleException::make('journals.errors.min_lines');
        }
        if (! $debitBase->isEqualTo($creditBase)) {
            throw BusinessRuleException::make('journals.errors.unbalanced', [
                'debit' => Money::format($debitBase), 'credit' => Money::format($creditBase),
            ]);
        }

        return $result;
    }
}
