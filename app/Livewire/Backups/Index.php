<?php

namespace App\Livewire\Backups;

use App\Livewire\Concerns\Notifies;
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
    use Notifies;

    public function mount(): void
    {
        $this->authorize('backups.manage');
    }

    private function disk(): Filesystem
    {
        return Storage::disk((string) config('backup.backup.destination.disks.0', 'backups'));
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

    public function render(): View
    {
        $backups = $this->backups();

        return view('livewire.backups.index', [
            'backups' => $backups,
            'totalSize' => array_sum(array_column($backups, 'size')),
            'stale' => $backups === [] || $backups[0]['date']->lt(now()->subDay()),
            'location' => $this->disk()->path($this->folder()),
        ])->title(__('app.nav.backups'));
    }
}
