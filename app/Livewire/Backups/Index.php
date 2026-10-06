<?php

namespace App\Livewire\Backups;

use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Support\BackupDestinations;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Backups made by the scheduler (database dump + uploaded files, zipped): list, download,
 * and "back up now". Restoring is a manual, documented procedure (docs/DEPLOYMENT.md).
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use HandlesBusinessErrors, Notifies;

    public string $extraPath = '';

    public function mount(BackupDestinations $destinations): void
    {
        $this->authorize('backups.manage');
        $this->extraPath = (string) $destinations->extraPath();
    }

    public function saveExtraPath(BackupDestinations $destinations): void
    {
        $this->authorize('backups.manage');
        $this->validate(['extraPath' => ['nullable', 'string', 'max:500']]);

        $saved = $this->attempt(function () use ($destinations) {
            $destinations->setExtraPath($this->extraPath);

            return true;
        }, 'extraPath');

        if ($saved !== null) {
            $this->notify(__($this->extraPath === '' ? 'backups.extra_removed' : 'backups.extra_saved'));
        }
    }

    public function removeExtraPath(BackupDestinations $destinations): void
    {
        $this->authorize('backups.manage');
        $destinations->setExtraPath(null);
        $this->extraPath = '';
        $this->notify(__('backups.extra_removed'));
    }

    /** @return array{count: int, latest: CarbonImmutable|null, reachable: bool}|null */
    private function extraStatus(BackupDestinations $destinations): ?array
    {
        $path = $destinations->extraPath();
        if ($path === null) {
            return null;
        }

        $folder = $path.DIRECTORY_SEPARATOR.$this->folder();
        $files = is_dir($folder) ? (glob($folder.DIRECTORY_SEPARATOR.'*.zip') ?: []) : [];
        $latest = $files === [] ? null : max(array_map('filemtime', $files));

        return [
            'count' => count($files),
            'latest' => $latest ? CarbonImmutable::createFromTimestamp($latest, config('app.timezone')) : null,
            'reachable' => is_dir($path) && is_writable($path),
        ];
    }

    private function disk(): Filesystem
    {
        return Storage::disk('backups');
    }

    private function folder(): string
    {
        return (string) config('backup.backup.name');
    }

    /** @return list<array{name: string, size: int, date: CarbonImmutable}> */
    private function backups(): array
    {
        $disk = $this->disk();

        return collect($disk->files($this->folder()))
            ->filter(fn (string $path) => str_ends_with($path, '.zip'))
            ->map(fn (string $path) => [
                'name' => basename($path),
                'size' => (int) $disk->size($path),
                'date' => CarbonImmutable::createFromTimestamp($disk->lastModified($path), config('app.timezone')),
            ])
            ->sortByDesc('date')->values()->all();
    }

    public function runNow(): void
    {
        $this->authorize('backups.manage');
        set_time_limit(900);

        app(BackupDestinations::class)->register(); // the extra folder, if any
        $code = Artisan::call('backup:run');

        if ($code === 0) {
            $this->notify(__('backups.created_ok'));
        } else {
            $this->notifyError(__('backups.failed', ['message' => trim(Artisan::output())]));
        }
    }

    public function download(string $name): StreamedResponse
    {
        $this->authorize('backups.manage');

        $backup = collect($this->backups())->firstWhere('name', $name);
        abort_if($backup === null, 404);

        return $this->disk()->download($this->folder().'/'.$backup['name']);
    }

    public function render(BackupDestinations $destinations): View
    {
        $backups = $this->backups();

        return view('livewire.backups.index', [
            'backups' => $backups,
            'totalSize' => array_sum(array_column($backups, 'size')),
            'stale' => $backups === [] || $backups[0]['date']->lt(now()->subDay()),
            'location' => $this->disk()->path($this->folder()),
            'extra' => $this->extraStatus($destinations),
        ])->title(__('app.nav.backups'));
    }
}
