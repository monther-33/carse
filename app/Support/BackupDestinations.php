<?php

namespace App\Support;

use App\Exceptions\BusinessRuleException;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Throwable;

/**
 * Where backups go. Always the main "backups" disk (BACKUP_PATH); optionally a second
 * folder chosen by the admin (another drive, an external disk, a synced cloud folder),
 * stored in settings as "backup.extra_path". Every backup is written to both, cleaned with
 * the same retention, and monitored, so a missing drive raises the backup-failed alert.
 */
class BackupDestinations
{
    public const EXTRA_DISK = 'backups_extra';

    public const SETTING = 'backup.extra_path';

    public function __construct(private readonly Settings $settings) {}

    public function extraPath(): ?string
    {
        try {
            // Straight from the table, not the shared settings cache: this runs while the backup
            // package boots, possibly before seeding or right after a migrate:fresh.
            $path = Setting::query()->where('key', self::SETTING)->value('value');
        } catch (Throwable) {
            return null; // no database yet (install, package discovery)
        }

        return is_string($path) && $path !== '' ? $path : null;
    }

    /** Adds the extra folder to the backup and monitor destinations for this request. */
    public function register(): void
    {
        $path = $this->extraPath();
        if ($path === null) {
            return;
        }

        config([
            'filesystems.disks.'.self::EXTRA_DISK => ['driver' => 'local', 'root' => $path, 'throw' => false, 'report' => false],
            'backup.backup.destination.disks' => array_values(array_unique([...config('backup.backup.destination.disks', []), self::EXTRA_DISK])),
            'backup.monitor_backups.0.disks' => array_values(array_unique([...config('backup.monitor_backups.0.disks', []), self::EXTRA_DISK])),
        ]);
    }

    /**
     * Validates and saves the extra folder (null removes it). The folder is created if needed,
     * must be writable, and may not be inside the public web folder.
     */
    public function setExtraPath(?string $path): void
    {
        $path = $path === null ? '' : rtrim(trim($path), '/\\');

        if ($path !== '') {
            $isAbsolute = preg_match('~^([A-Za-z]:[\\\\/]|/|\\\\\\\\)~', $path) === 1;
            if (! $isAbsolute) {
                throw BusinessRuleException::make('backups.errors.absolute');
            }

            if (! is_dir($path) && ! @File::makeDirectory($path, 0775, true)) {
                throw BusinessRuleException::make('backups.errors.create', ['path' => $path]);
            }

            $real = (string) realpath($path);
            $public = (string) realpath(public_path());
            if ($real === '' || str_starts_with(strtolower($real), strtolower($public))) {
                throw BusinessRuleException::make('backups.errors.public');
            }

            $probe = $real.DIRECTORY_SEPARATOR.'.write-test';
            if (@file_put_contents($probe, 'ok') === false) {
                throw BusinessRuleException::make('backups.errors.writable', ['path' => $path]);
            }
            @unlink($probe);
        }

        $before = $this->extraPath();
        $this->settings->set([self::SETTING => $path === '' ? null : $path]);

        activity('System')->causedBy(Auth::user())->event('backup_extra_path')
            ->withProperties(['old' => $before, 'attributes' => $path === '' ? null : $path])
            ->log('backup_extra_path');
    }
}
