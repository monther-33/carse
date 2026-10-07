<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Resets the system to a fresh install (owner's request): every table is rebuilt empty and the
 * base seeders run again (roles, chart of accounts, currencies, cashboxes, settings, fiscal year,
 * the admin and developer users). Uploaded files (vehicle photos, ID cards, receipts, logo,
 * recycle bin copies) are deleted. Backups are kept.
 *
 *   php artisan db:seed --class=ResetSystemSeeder --force
 *
 * Interactive only: the operator must type the database name, and is offered a full backup first.
 * With --no-interaction it does nothing.
 */
class ResetSystemSeeder extends Seeder
{
    public function run(): void
    {
        $command = $this->command ?? throw new RuntimeException('Run it from the command line: php artisan db:seed --class=ResetSystemSeeder');
        $database = (string) config('database.connections.'.config('database.default').'.database');

        $command->warn(__('system_reset.warning', ['database' => $database]));
        if ($command->ask(__('system_reset.type_name', ['database' => $database])) !== $database) {
            $command->error(__('system_reset.cancelled'));

            return;
        }

        if ($command->confirm(__('system_reset.backup_first'), true)) {
            $code = Artisan::call('backup:run', ['--disable-notifications' => true]);
            if ($code !== 0 && ! $command->confirm(__('system_reset.backup_failed'), false)) {
                $command->error(__('system_reset.cancelled'));

                return;
            }
        }

        Artisan::call('migrate:fresh', ['--force' => true]);
        $this->call(DatabaseSeeder::class);
        $this->deleteUploads();
        Artisan::call('cache:clear');

        $command->info(__('system_reset.done'));
    }

    /** Uploaded files on the public and private disks (not backups, not PDF temp files). */
    private function deleteUploads(): void
    {
        foreach (['public', 'local'] as $disk) {
            foreach (Storage::disk($disk)->directories() as $directory) {
                Storage::disk($disk)->deleteDirectory($directory);
            }
            foreach (Storage::disk($disk)->files() as $file) {
                if ($file !== '.gitignore') {
                    Storage::disk($disk)->delete($file);
                }
            }
        }
    }
}
