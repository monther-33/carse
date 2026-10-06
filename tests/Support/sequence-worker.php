<?php

/**
 * Standalone worker for the sequence concurrency test.
 *
 *   php sequence-worker.php reset <year>
 *   php sequence-worker.php prepare <year>   (reset, then create the year's counters)
 *   php sequence-worker.php run <year> <count>
 *
 * Each "run" takes <count> numbers, each in its own transaction with a small random
 * delay while holding the lock, and prints one number per line.
 * Only ever runs against the cars_test database.
 */

use App\Enums\SequenceType;
use App\Services\Numbering\SequenceService;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../../vendor/autoload.php';

$app = require __DIR__.'/../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

if (config('database.connections.mysql.database') !== 'cars_test') {
    fwrite(STDERR, "Refusing to run outside cars_test.\n");
    exit(1);
}

[$_, $mode, $year] = $argv + [null, null, null];
$date = CarbonImmutable::create((int) $year, 6, 1);

if ($mode === 'reset') {
    DB::table('sequences')->where('year', (int) $year)->delete();
    exit(0);
}

// Like GenerateFiscalYear: the year's counters exist before anything is numbered.
if ($mode === 'prepare') {
    DB::table('sequences')->where('year', (int) $year)->delete();
    $app->make(SequenceService::class)->ensureYear((int) $year);
    exit(0);
}

$count = (int) ($argv[3] ?? 1);
$service = $app->make(SequenceService::class);

for ($i = 0; $i < $count; $i++) {
    $number = DB::transaction(function () use ($service, $date) {
        $number = $service->next(SequenceType::JournalEntry, $date);
        usleep(random_int(0, 3000)); // keep the lock a little to force contention

        return $number;
    });

    echo $number, PHP_EOL;
}
