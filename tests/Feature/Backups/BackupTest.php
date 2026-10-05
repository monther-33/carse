<?php

use App\Livewire\Backups\Index;
use App\Notifications\BackupProblem;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Backup\Events\BackupHasFailed;

beforeEach(function () {
    Storage::fake('backups');
});

test('only backup managers reach the backups screen', function () {
    $this->actingAs(userWithRole('accountant'))->get(route('backups.index'))->assertForbidden();
    $this->actingAs(userWithRole('admin'))->get(route('backups.index'))->assertOk()->assertSee(__('backups.none'));
});

test('backups are listed newest first and can be downloaded, but only by name from the list', function () {
    $this->actingAs(userWithRole('admin'));
    $folder = config('backup.backup.name');
    Storage::disk('backups')->put("{$folder}/2026-01-01-02-00-00.zip", 'old');
    Storage::disk('backups')->put("{$folder}/2026-01-02-02-00-00.zip", 'new');
    touch(Storage::disk('backups')->path("{$folder}/2026-01-01-02-00-00.zip"), now()->subDays(2)->timestamp);

    Livewire::test(Index::class)
        ->assertSeeInOrder(['2026-01-02-02-00-00.zip', '2026-01-01-02-00-00.zip'])
        ->call('download', '2026-01-02-02-00-00.zip')->assertFileDownloaded('2026-01-02-02-00-00.zip');

    Livewire::test(Index::class)->call('download', '../../.env')->assertNotFound();
});

test('a real backup dumps the database and the uploaded files into a zip', function () {
    $binary = rtrim((string) config('database.connections.mysql.dump.dump_binary_path'), '/\\');
    if (! is_file($binary.'/mysqldump.exe') && ! is_file($binary.'/mysqldump') && trim((string) shell_exec('which mysqldump 2>/dev/null')) === '') {
        $this->markTestSkipped('mysqldump is not available on this machine.');
    }

    $this->actingAs(userWithRole('admin'));
    Livewire::test(Index::class)->call('runNow')->assertDispatched('notify', type: 'success');

    $files = Storage::disk('backups')->files(config('backup.backup.name'));
    expect($files)->toHaveCount(1);

    $zip = new ZipArchive;
    $zip->open(Storage::disk('backups')->path($files[0]));
    $names = collect(range(0, $zip->numFiles - 1))->map(fn ($i) => str_replace('\\', '/', (string) $zip->getNameIndex($i)));
    $zip->close();

    expect($names->contains(fn ($n) => str_starts_with($n, 'db-dumps/') && str_ends_with($n, '.sql')))->toBeTrue()
        ->and($names->every(fn ($n) => str_starts_with($n, 'db-dumps/') || str_starts_with($n, 'public/') || str_starts_with($n, 'private/')))->toBeTrue();
});

test('a failed backup reaches the backup managers under the bell', function () {
    $admin = userWithRole('admin');
    $accountant = userWithRole('accountant');

    event(new BackupHasFailed(new Exception('mysqldump: not found')));

    expect($admin->unreadNotifications()->where('type', BackupProblem::class)->count())->toBe(1)
        ->and($accountant->unreadNotifications()->count())->toBe(0)
        ->and(BackupProblem::lines($admin->unreadNotifications()->first()->data)[0]['text'])->toContain('mysqldump: not found');
});

test('backups, cleanup and the health check are scheduled daily', function () {
    $commands = collect(app(Schedule::class)->events())->map(fn ($event) => $event->command)->implode("\n");

    expect($commands)->toContain('backup:run')->toContain('backup:clean')->toContain('backup:monitor');
});
