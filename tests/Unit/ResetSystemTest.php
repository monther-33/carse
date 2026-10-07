<?php

use App\Models\Account;
use App\Models\Party;
use App\Models\User;
use Database\Seeders\DemoSeeder;
use Database\Seeders\ResetSystemSeeder;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/*
| The reset rebuilds the schema, so it cannot run inside RefreshDatabase's transaction: these
| tests use the test database directly and leave it migrated and base-seeded, as the feature
| tests expect. Uploads go to fake disks so no real file is touched.
*/
uses(TestCase::class);

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
    $this->artisan('migrate:fresh', ['--seed' => true, '--force' => true]);
});

it('does nothing unless the database name is typed', function () {
    $party = Party::factory()->create();

    $this->artisan('db:seed', ['--class' => ResetSystemSeeder::class, '--force' => true])
        ->expectsQuestion(__('system_reset.type_name', ['database' => 'cars_test']), 'cars')
        ->expectsOutput(__('system_reset.cancelled'))
        ->assertSuccessful();

    expect(Party::query()->find($party->id))->not->toBeNull();
});

it('empties the database and the uploads and seeds a fresh install', function () {
    Party::factory()->count(3)->create();
    User::factory()->create(['username' => 'old_user']);
    Storage::disk('public')->put('7/photo.jpg', 'x');
    Storage::disk('local')->put('trash/abc/file.pdf', 'x');

    $this->artisan('db:seed', ['--class' => ResetSystemSeeder::class, '--force' => true])
        ->expectsQuestion(__('system_reset.type_name', ['database' => 'cars_test']), 'cars_test')
        ->expectsConfirmation(__('system_reset.backup_first'), 'no')
        ->expectsOutput(__('system_reset.done'))
        ->assertSuccessful();

    expect(Party::query()->withTrashed()->count())->toBe(0)
        ->and(User::query()->pluck('username')->sort()->values()->all())->toBe(['admin', 'developer'])
        ->and(Account::query()->where('code', '24')->exists())->toBeTrue()
        ->and(Storage::disk('public')->allFiles())->toBe([])
        ->and(Storage::disk('local')->allFiles())->toBe([]);
});

it('never seeds the demo data in production', function () {
    app()->detectEnvironment(fn () => 'production');

    app(DemoSeeder::class)->run();

    expect(User::query()->where('username', 'sales1')->exists())->toBeFalse();
});
