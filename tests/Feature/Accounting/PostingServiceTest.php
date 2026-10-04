<?php

use App\Enums\DocumentStatus;
use App\Exceptions\Accounting\AccountNotPostableException;
use App\Exceptions\Accounting\ClosedPeriodException;
use App\Exceptions\Accounting\InvalidJournalLineException;
use App\Exceptions\Accounting\JournalWriteNotAllowedException;
use App\Exceptions\Accounting\NoFiscalPeriodException;
use App\Exceptions\Accounting\UnbalancedEntryException;
use App\Exceptions\MissingExchangeRateException;
use App\Models\FiscalPeriod;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\Sequence;
use App\Services\Accounting\JournalBuilder;
use App\Services\Currency\ExchangeRateService;
use Carbon\CarbonImmutable;

beforeEach(function () {
    $this->actingAs(userWithRole('accountant'));
    $this->today = CarbonImmutable::today();
});

test('a balanced entry is posted with base amounts and a sequential number', function () {
    $entry = posting()->post(
        JournalBuilder::make($this->today, 'رأس مال افتتاحي')
            ->debit(cashbox('خزينة دينار')->account_id, '50000')
            ->credit(account('31'), '50000')
    );

    expect($entry->status)->toBe(DocumentStatus::Posted)
        ->and($entry->number)->toBe(sprintf('JE-%d-000001', $this->today->year))
        ->and((string) $entry->total_base)->toBe('50000.000')
        ->and($entry->period_id)->toBe(FiscalPeriod::query()->containing($this->today)->value('id'))
        ->and($entry->lines)->toHaveCount(2)
        ->and($entry->lines[0]->line_no)->toBe(1)
        ->and((string) $entry->lines[0]->debit_base)->toBe('50000.000')
        ->and((string) $entry->lines[1]->credit_base)->toBe('50000.000')
        ->and((string) baseBalance('31'))->toBe('-50000.000');

    $second = posting()->post(
        JournalBuilder::make($this->today, 'ثاني')->debit(account('14'), '1')->credit(account('31'), '1')
    );
    expect($second->number)->toBe(sprintf('JE-%d-000002', $this->today->year));
});

test('an unbalanced entry is rejected and nothing is written, not even a sequence number', function () {
    $post = fn () => posting()->post(
        JournalBuilder::make($this->today, 'غير متوازن')
            ->debit(account('14'), '1000.000')
            ->credit(account('21'), '999.999')
    );

    expect($post)->toThrow(UnbalancedEntryException::class);
    expect(JournalEntry::query()->count())->toBe(0)
        ->and(JournalLine::query()->count())->toBe(0)
        ->and(Sequence::query()->count())->toBe(0);
});

test('balance is checked on base amounts across currencies', function () {
    app(ExchangeRateService::class)->setRate(usd(), $this->today, '4.850000');

    // 1,000 USD @ 4.85 = 4,850 LYD on the debit side.
    $entry = posting()->post(
        JournalBuilder::make($this->today, 'شراء بالدولار')
            ->debit(account('14'), '4850')
            ->credit(cashbox('خزينة دولار')->account_id, '1000', usd())
    );

    $usdLine = $entry->lines->firstWhere('currency_id', usd()->id);
    expect((string) $usdLine->credit)->toBe('1000.000')
        ->and((string) $usdLine->rate)->toBe('4.850000')
        ->and((string) $usdLine->credit_base)->toBe('4850.000');

    // Same amounts at a different explicit rate no longer balance.
    expect(fn () => posting()->post(
        JournalBuilder::make($this->today, 'سعر مختلف')
            ->debit(account('14'), '4850')
            ->credit(cashbox('خزينة دولار')->account_id, '1000', usd(), '4.900000')
    ))->toThrow(UnbalancedEntryException::class);
});

test('a foreign line without a rate on or before the date is rejected', function () {
    posting()->post(
        JournalBuilder::make($this->today, 'بدون سعر')
            ->debit(account('14'), '100', usd())
            ->credit(account('21'), '100', usd())
    );
})->throws(MissingExchangeRateException::class);

test('the base currency must use rate 1', function () {
    posting()->post(
        JournalBuilder::make($this->today, 'سعر خاطئ')
            ->debit(account('14'), '100', lyd(), '2')
            ->credit(account('21'), '200')
    );
})->throws(InvalidJournalLineException::class);

test('posting to a closed period is rejected', function () {
    FiscalPeriod::query()->containing($this->today)->update(['is_closed' => true]);

    posting()->post(
        JournalBuilder::make($this->today, 'فترة مقفلة')->debit(account('14'), '10')->credit(account('21'), '10')
    );
})->throws(ClosedPeriodException::class);

test('posting on a date with no fiscal period is rejected', function () {
    posting()->post(
        JournalBuilder::make('1999-06-15', 'بلا فترة')->debit(account('14'), '10')->credit(account('21'), '10')
    );
})->throws(NoFiscalPeriodException::class);

test('posting to a group account is rejected', function () {
    posting()->post(
        JournalBuilder::make($this->today, 'حساب تجميعي')->debit(account('1'), '10')->credit(account('21'), '10')
    );
})->throws(AccountNotPostableException::class);

test('posting to an inactive account is rejected', function () {
    account('42')->update(['is_active' => false]);

    posting()->post(
        JournalBuilder::make($this->today, 'حساب معطل')->debit(account('14'), '10')->credit(account('42'), '10')
    );
})->throws(AccountNotPostableException::class);

test('lines must be at least two and strictly positive', function (Closure $build) {
    expect(fn () => posting()->post($build()))->toThrow(InvalidJournalLineException::class);
})->with([
    'single line' => fn () => JournalBuilder::make(today(), 'x')->debit(account('14'), '10'),
    'zero amount' => fn () => JournalBuilder::make(today(), 'x')->debit(account('14'), '0')->credit(account('21'), '0'),
    'negative amount' => fn () => JournalBuilder::make(today(), 'x')->debit(account('14'), '-10')->credit(account('21'), '-10'),
]);

test('journal rows cannot be written, changed or deleted outside PostingService', function () {
    expect(fn () => JournalEntry::query()->create([
        'branch_id' => 1, 'number' => 'X', 'date' => today(), 'description' => 'x',
        'status' => DocumentStatus::Posted, 'period_id' => 1, 'total_base' => '0',
    ]))->toThrow(JournalWriteNotAllowedException::class);

    $entry = posting()->post(
        JournalBuilder::make($this->today, 'محمي')->debit(account('14'), '10')->credit(account('21'), '10')
    );

    expect(fn () => $entry->update(['description' => 'تلاعب']))->toThrow(JournalWriteNotAllowedException::class)
        ->and(fn () => $entry->delete())->toThrow(JournalWriteNotAllowedException::class)
        ->and(fn () => $entry->lines->first()->update(['debit' => '99']))->toThrow(JournalWriteNotAllowedException::class)
        ->and(fn () => $entry->lines->first()->delete())->toThrow(JournalWriteNotAllowedException::class);

    expect((string) $entry->fresh()->lines->first()->debit)->toBe('10.000');
});

test('party and vehicle dimensions and memo are stored on lines', function () {
    $entry = posting()->post(
        JournalBuilder::make($this->today, 'أبعاد')
            ->debit(account('13'), '500', partyId: 7, memo: 'عميل')
            ->credit(account('41'), '500', vehicleId: 3)
    );

    expect($entry->lines[0]->party_id)->toBe(7)
        ->and($entry->lines[0]->memo)->toBe('عميل')
        ->and($entry->lines[1]->vehicle_id)->toBe(3);
});
