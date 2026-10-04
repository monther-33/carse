<?php

use App\Enums\SequenceType;
use App\Services\Numbering\SequenceService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

beforeEach(function () {
    $this->sequences = app(SequenceService::class);
});

test('numbers are sequential per type and restart each year', function () {
    $numbers = DB::transaction(fn () => [
        $this->sequences->next(SequenceType::JournalEntry, CarbonImmutable::create(2030, 1, 5)),
        $this->sequences->next(SequenceType::JournalEntry, CarbonImmutable::create(2030, 12, 31)),
        $this->sequences->next(SequenceType::JournalEntry, CarbonImmutable::create(2031, 1, 1)),
    ]);

    expect($numbers)->toBe(['JE-2030-000001', 'JE-2030-000002', 'JE-2031-000001']);
});

test('a rolled-back transaction does not burn a number', function () {
    $date = CarbonImmutable::create(2032, 3, 1);

    try {
        DB::transaction(function () use ($date) {
            $this->sequences->next(SequenceType::JournalEntry, $date);
            throw new RuntimeException('document failed');
        });
    } catch (RuntimeException) {
    }

    $next = DB::transaction(fn () => $this->sequences->next(SequenceType::JournalEntry, $date));

    expect($next)->toBe('JE-2032-000001');
});

test('next() refuses to run outside a transaction', function () {
    // RefreshDatabase wraps each test in a transaction; step outside it explicitly.
    DB::rollBack();

    try {
        expect(fn () => $this->sequences->next(SequenceType::JournalEntry))->toThrow(LogicException::class);
    } finally {
        DB::beginTransaction();
    }
});

test('parallel processes never get the same number and leave no gaps', function () {
    $workers = 8;
    $perWorker = 25;
    $year = 2099;

    $script = base_path('tests/Support/sequence-worker.php');
    $php = (new PhpExecutableFinder)->find();
    $env = ['APP_ENV' => 'testing', 'DB_CONNECTION' => 'mysql', 'DB_DATABASE' => 'cars_test'];

    // The workers commit in their own connections, outside this test's transaction.
    (new Process([$php, $script, 'reset', (string) $year], base_path(), $env))->mustRun();

    $processes = [];
    for ($i = 0; $i < $workers; $i++) {
        $process = new Process([$php, $script, 'run', (string) $year, (string) $perWorker], base_path(), $env, null, 120);
        $process->start();
        $processes[] = $process;
    }

    $numbers = [];
    foreach ($processes as $process) {
        $process->wait();
        expect($process->getExitCode())->toBe(0, $process->getErrorOutput());
        array_push($numbers, ...array_filter(explode(PHP_EOL, trim($process->getOutput()))));
    }

    (new Process([$php, $script, 'reset', (string) $year], base_path(), $env))->mustRun();

    $expected = array_map(fn (int $n) => sprintf('JE-%d-%06d', $year, $n), range(1, $workers * $perWorker));
    sort($numbers);

    expect($numbers)->toHaveCount($workers * $perWorker)
        ->and(array_unique($numbers))->toHaveCount($workers * $perWorker)
        ->and($numbers)->toBe($expected);
});
