<?php

use App\Enums\DocumentStatus;
use App\Exceptions\Accounting\AlreadyReversedException;
use App\Exceptions\Accounting\ClosedPeriodException;
use App\Exceptions\Accounting\InvalidJournalLineException;
use App\Models\FiscalPeriod;
use App\Models\JournalEntry;
use App\Services\Accounting\JournalBuilder;
use App\Services\Currency\ExchangeRateService;
use Carbon\CarbonImmutable;

beforeEach(function () {
    $this->actingAs(userWithRole('admin'));
    $this->today = CarbonImmutable::today();
    app(ExchangeRateService::class)->setRate(usd(), $this->today, '4.850000');

    // Multi-line, multi-currency entry with dimensions.
    $this->original = posting()->post(
        JournalBuilder::make($this->today, 'فاتورة شراء')
            ->debit(account('14'), '9700', vehicleId: 11, memo: 'سيارة')
            ->credit(cashbox('خزينة دولار')->account_id, '1000', usd())
            ->credit(account('21'), '4850', partyId: 5)
    );
});

test('the reversal mirrors every line exactly and links both entries', function () {
    $reversal = reversal()->reverse($this->original);
    $original = $this->original->fresh('lines');

    expect($original->status)->toBe(DocumentStatus::Cancelled)
        ->and($original->reversed_by_id)->toBe($reversal->id)
        ->and($reversal->reverses_id)->toBe($original->id)
        ->and($reversal->status)->toBe(DocumentStatus::Posted)
        ->and($reversal->description)->toBe(__('accounting.reversal_of', ['number' => $original->number]))
        ->and((string) $reversal->total_base)->toBe((string) $original->total_base)
        ->and($reversal->lines)->toHaveCount($original->lines->count());

    foreach ($original->lines as $i => $line) {
        $mirror = $reversal->lines[$i];
        expect($mirror->account_id)->toBe($line->account_id)
            ->and((string) $mirror->debit)->toBe((string) $line->credit)
            ->and((string) $mirror->credit)->toBe((string) $line->debit)
            ->and((string) $mirror->debit_base)->toBe((string) $line->credit_base)
            ->and((string) $mirror->credit_base)->toBe((string) $line->debit_base)
            ->and($mirror->currency_id)->toBe($line->currency_id)
            ->and((string) $mirror->rate)->toBe((string) $line->rate)
            ->and($mirror->party_id)->toBe($line->party_id)
            ->and($mirror->vehicle_id)->toBe($line->vehicle_id);
    }

    // Every affected account nets to exactly zero.
    foreach (['14', '21'] as $code) {
        expect(baseBalance($code)->isZero())->toBeTrue();
    }
    expect(baseBalance(cashbox('خزينة دولار')->account)->isZero())->toBeTrue()
        ->and(ledgerIsBalanced())->toBeTrue();
});

test('an entry cannot be reversed twice', function () {
    reversal()->reverse($this->original);
    reversal()->reverse($this->original->fresh());
})->throws(AlreadyReversedException::class);

test('a reversal entry cannot itself be reversed', function () {
    $reversal = reversal()->reverse($this->original);
    reversal()->reverse($reversal);
})->throws(InvalidJournalLineException::class);

test('an entry from a closed period is reversed into the current open period', function () {
    $lastMonth = $this->today->subMonthNoOverflow();
    $period = FiscalPeriod::query()->containing($lastMonth)->first()
        ?? FiscalPeriod::factory()->forMonth($lastMonth->year, $lastMonth->month)->create();

    $old = posting()->post(
        JournalBuilder::make($lastMonth, 'قيد قديم')->debit(account('14'), '100')->credit(account('21'), '100')
    );
    $period->update(['is_closed' => true]);

    $reversal = reversal()->reverse($old);

    expect($reversal->date->toDateString())->toBe($this->today->toDateString())
        ->and($reversal->period_id)->not->toBe($period->id)
        ->and($old->fresh()->status)->toBe(DocumentStatus::Cancelled);
});

test('a reversal dated in a closed period is rejected and leaves the original untouched', function () {
    FiscalPeriod::query()->containing($this->today)->update(['is_closed' => true]);

    expect(fn () => reversal()->reverse($this->original, $this->today))->toThrow(ClosedPeriodException::class);

    $original = $this->original->fresh();
    expect($original->status)->toBe(DocumentStatus::Posted)
        ->and($original->reversed_by_id)->toBeNull()
        ->and(JournalEntry::query()->count())->toBe(1);
});
