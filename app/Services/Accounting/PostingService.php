<?php

namespace App\Services\Accounting;

use App\Enums\DocumentStatus;
use App\Enums\SequenceType;
use App\Exceptions\Accounting\AccountNotPostableException;
use App\Exceptions\Accounting\ClosedPeriodException;
use App\Exceptions\Accounting\InvalidJournalLineException;
use App\Exceptions\Accounting\NoFiscalPeriodException;
use App\Exceptions\Accounting\UnbalancedEntryException;
use App\Models\Account;
use App\Models\FiscalPeriod;
use App\Models\JournalEntry;
use App\Services\Currency\ExchangeRateService;
use App\Services\Numbering\SequenceService;
use App\Support\Money;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LogicException;

/**
 * The ONLY code allowed to write journal_entries / journal_lines.
 *
 * Guarantees for every posted entry:
 *  - at least two lines, each strictly positive on one side;
 *  - sum(debit_base) == sum(credit_base) exactly (3 decimals, brick/math);
 *  - every account exists, is active and is not a group account;
 *  - the entry date falls in an existing, open fiscal period.
 */
class PostingService
{
    public function __construct(
        private readonly SequenceService $sequences,
        private readonly ExchangeRateService $rates,
    ) {}

    public function post(JournalBuilder $builder): JournalEntry
    {
        return DB::transaction(function () use ($builder) {
            $lines = $this->resolveLines($builder);
            $this->assertBalanced($lines);
            $this->assertAccountsPostable($lines);
            $period = $this->lockOpenPeriod($builder);

            $number = $this->sequences->next(SequenceType::JournalEntry, $builder->date);

            return JournalWriteGuard::allow(function () use ($builder, $lines, $period, $number) {
                $entry = JournalEntry::query()->create([
                    'branch_id' => $this->branchId($builder),
                    'number' => $number,
                    'date' => $builder->date->toDateString(),
                    'description' => $builder->description,
                    'source_type' => $builder->source?->getMorphClass(),
                    'source_id' => $builder->source?->getKey(),
                    'status' => DocumentStatus::Posted,
                    'period_id' => $period->getKey(),
                    'reverses_id' => $builder->reverses?->getKey(),
                    'total_base' => (string) Money::sum(array_column($lines, 'debit_base')),
                ]);

                foreach ($lines as $index => $line) {
                    $entry->lines()->create(['line_no' => $index + 1] + $line);
                }

                return $entry->load('lines');
            });
        });
    }

    /**
     * Links an original entry to its reversal. Called by ReversalService only.
     */
    public function markReversed(JournalEntry $original, JournalEntry $reversal): void
    {
        JournalWriteGuard::allow(function () use ($original, $reversal) {
            $original->forceFill([
                'status' => DocumentStatus::Cancelled,
                'reversed_by_id' => $reversal->getKey(),
            ])->save();
        });
    }

    /**
     * Validates each requested line and converts it to the row stored in journal_lines.
     *
     * @return list<array<string, mixed>>
     */
    private function resolveLines(JournalBuilder $builder): array
    {
        if (count($builder->lines) < 2) {
            throw new InvalidJournalLineException(__('accounting.errors.min_lines'));
        }

        $baseCurrency = $this->rates->baseCurrency();
        $rows = [];

        foreach ($builder->lines as $line) {
            if (! $line->amount->isPositive()) {
                throw new InvalidJournalLineException(__('accounting.errors.non_positive_amount'));
            }

            $currencyId = $line->currencyId ?? (int) $baseCurrency->getKey();

            if ($currencyId === $baseCurrency->getKey()) {
                if ($line->rate !== null && ! $line->rate->isEqualTo(1)) {
                    throw new InvalidJournalLineException(__('accounting.errors.base_rate_must_be_one'));
                }
                $rate = Money::rate(1);
            } else {
                $rate = $line->rate ?? $this->rates->rateFor($currencyId, $builder->date);
            }

            if (! $rate->isPositive()) {
                throw new InvalidJournalLineException(__('accounting.errors.non_positive_rate'));
            }

            $amount = (string) $line->amount;
            $base = (string) Money::toBase($line->amount, $rate);
            $zero = (string) Money::zero();

            $rows[] = [
                'account_id' => $line->accountId,
                'party_id' => $line->partyId,
                'vehicle_id' => $line->vehicleId,
                'debit' => $line->isDebit ? $amount : $zero,
                'credit' => $line->isDebit ? $zero : $amount,
                'currency_id' => $currencyId,
                'rate' => (string) $rate,
                'debit_base' => $line->isDebit ? $base : $zero,
                'credit_base' => $line->isDebit ? $zero : $base,
                'memo' => $line->memo,
            ];
        }

        return $rows;
    }

    /**
     * @param  list<array<string, mixed>>  $lines
     */
    private function assertBalanced(array $lines): void
    {
        $debit = Money::sum(array_column($lines, 'debit_base'));
        $credit = Money::sum(array_column($lines, 'credit_base'));

        if (! $debit->isEqualTo($credit)) {
            throw new UnbalancedEntryException(Money::format($debit), Money::format($credit));
        }
    }

    /**
     * @param  list<array<string, mixed>>  $lines
     */
    private function assertAccountsPostable(array $lines): void
    {
        $ids = array_values(array_unique(array_column($lines, 'account_id')));
        $accounts = Account::query()->whereKey($ids)->get()->keyBy('id');

        foreach ($ids as $id) {
            $account = $accounts->get($id);

            if ($account === null || ! $account->isPostable()) {
                throw new AccountNotPostableException($account?->label() ?? '#'.$id);
            }
        }
    }

    /**
     * A shared lock on the period row blocks a concurrent close until this entry commits.
     */
    private function lockOpenPeriod(JournalBuilder $builder): FiscalPeriod
    {
        $period = FiscalPeriod::query()->containing($builder->date)->sharedLock()->first();

        if ($period === null) {
            throw new NoFiscalPeriodException($builder->date->toDateString());
        }

        if ($period->is_closed) {
            throw new ClosedPeriodException($period->name);
        }

        return $period;
    }

    private function branchId(JournalBuilder $builder): int
    {
        $branchId = $builder->branchId ?? $builder->reverses?->branch_id;
        $branchId ??= Auth::user()?->branch_id;

        if ($branchId === null) {
            throw new LogicException('A branch is required to post a journal entry.');
        }

        return (int) $branchId;
    }
}
