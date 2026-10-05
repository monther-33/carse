<?php

namespace App\Livewire\Imports;

use App\Actions\Imports\Importer;
use App\Actions\Imports\ImportOpeningBalances;
use App\Actions\Imports\ImportOpeningStock;
use App\Actions\Imports\ImportParties;
use App\Actions\OpeningStock\DeleteOpeningStockDraft;
use App\Actions\OpeningStock\PostOpeningStock;
use App\Exceptions\BusinessRuleException;
use App\Exports\ImportTemplate;
use App\Imports\SheetReader;
use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Models\OpeningStock;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Go-live data from Excel: parties, vehicles in stock (opening stock document) and opening
 * balances (draft manual journal). Upload → preview with row errors → import, all or nothing.
 */
#[Layout('layouts.app')]
class Index extends Component
{
    use HandlesBusinessErrors, Notifies, WithFileUploads, WithPagination;

    /** @var array<string, class-string<Importer>> */
    public const KINDS = [
        'parties' => ImportParties::class,
        'vehicles' => ImportOpeningStock::class,
        'balances' => ImportOpeningBalances::class,
    ];

    public string $kind = 'parties';

    public string $date = '';

    public string $description = '';

    /** @var TemporaryUploadedFile|null */
    public $file = null;

    /** @var array{errors: list<array{row: int, message: string}>, rows: list<array<string, string|int|null>>, summary: list<string>}|null */
    public ?array $preview = null;

    public bool $showCancel = false;

    public ?int $cancelId = null;

    public string $reason = '';

    public function mount(): void
    {
        $kinds = $this->allowedKinds();
        abort_if($kinds === [], 403);

        $this->kind = $kinds[0];
        $this->date = now()->toDateString();
    }

    /** @return list<string> */
    private function allowedKinds(): array
    {
        return array_values(array_filter(array_keys(self::KINDS), fn (string $kind) => app(self::KINDS[$kind])->allows(auth()->user())));
    }

    private function importer(): Importer
    {
        abort_unless(isset(self::KINDS[$this->kind]), 404);
        $importer = app(self::KINDS[$this->kind]);
        abort_unless($importer->allows(auth()->user()), 403);

        return $importer;
    }

    public function updated(string $property): void
    {
        if (in_array($property, ['kind', 'file', 'date'], true)) {
            $this->preview = null;
            $this->resetValidation();
        }
    }

    public function template(): BinaryFileResponse
    {
        $importer = $this->importer();

        return Excel::download(new ImportTemplate($importer->columns()), __('imports.template_file', ['kind' => $this->kind]).'.xlsx');
    }

    public function check(SheetReader $reader): void
    {
        $importer = $this->importer();
        $rows = $this->readFile($importer, $reader);

        if ($rows !== null) {
            $this->preview = $importer->analyse($rows, $this->options())->toArray();
        }
    }

    public function import(SheetReader $reader): void
    {
        $importer = $this->importer();
        $rows = $this->readFile($importer, $reader);
        if ($rows === null) {
            return;
        }

        $message = $this->attempt(fn () => $importer->import($rows, $this->options()), 'file');
        if ($message !== null) {
            $this->reset('file', 'preview', 'description');
            $this->notify($message);
        }
    }

    /**
     * @return array<int, array<string, mixed>>|null
     */
    private function readFile(Importer $importer, SheetReader $reader): ?array
    {
        $this->validate([
            'file' => ['required', 'file', 'max:10240', 'extensions:xlsx,xls,csv'],
            'date' => $importer->isFinancial() ? ['required', 'date'] : ['nullable'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            return $reader->read($this->file->getRealPath(), $this->file->getClientOriginalExtension(), $importer->columns());
        } catch (BusinessRuleException $e) {
            $this->preview = null;
            $this->addError('file', $e->getMessage());

            return null;
        }
    }

    /** @return array{date: string, description: string} */
    private function options(): array
    {
        return ['date' => $this->date, 'description' => $this->description];
    }

    public function approve(int $id, PostOpeningStock $action): void
    {
        $stock = OpeningStock::query()->findOrFail($id);
        $this->authorize('approve', $stock);

        if ($this->attempt(fn () => $action->handle($stock), 'stock') !== null) {
            $this->notify(__('documents.posted_ok'));
        }
    }

    public function delete(int $id, DeleteOpeningStockDraft $action): void
    {
        $stock = OpeningStock::query()->findOrFail($id);
        $this->authorize('delete', $stock);

        $action->handle($stock);
        $this->notify(__('app.deleted'));
    }

    public function openCancel(int $id): void
    {
        $this->authorize('cancel', OpeningStock::query()->findOrFail($id));
        $this->cancelId = $id;
        $this->reset('reason');
        $this->resetValidation();
        $this->showCancel = true;
    }

    public function cancel(PostOpeningStock $action): void
    {
        $stock = OpeningStock::query()->findOrFail($this->cancelId);
        $this->authorize('cancel', $stock);
        $this->validate(['reason' => ['required', 'string', 'max:255']]);

        if ($this->attempt(fn () => $action->cancel($stock, $this->reason), 'reason') !== null) {
            $this->showCancel = false;
            $this->notify(__('documents.cancelled_ok'));
        }
    }

    public function render(): View
    {
        $importer = $this->importer();

        return view('livewire.imports.index', [
            'kinds' => $this->allowedKinds(),
            'importer' => $importer,
            'stocks' => auth()->user()->can('viewAny', OpeningStock::class)
                ? OpeningStock::query()->with(['items.vehicle', 'creator'])->latest('id')->paginate(10)
                : null,
        ])->title(__('app.nav.imports'));
    }
}
