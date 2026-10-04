<?php

namespace App\Livewire\FiscalPeriods;

use App\Actions\Accounting\CloseFiscalPeriod;
use App\Actions\Accounting\GenerateFiscalYear;
use App\Livewire\Concerns\Notifies;
use App\Models\FiscalPeriod;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    use Notifies;

    #[Url]
    public int $year;

    public function mount(): void
    {
        $this->authorize('viewAny', FiscalPeriod::class);
        $this->year ??= (int) now()->year;
    }

    public function generate(GenerateFiscalYear $action): void
    {
        $this->authorize('create', FiscalPeriod::class);

        $this->validate(['year' => ['required', 'integer', 'between:2000,2100']]);

        $created = $action->handle($this->year);
        $this->notify(__('app.periods.generated', ['count' => $created]));
    }

    public function close(int $id, CloseFiscalPeriod $action): void
    {
        $period = FiscalPeriod::query()->findOrFail($id);
        $this->authorize('close', $period);

        $action->handle($period);
        $this->notify(__('app.periods.closed_ok', ['name' => $period->name]));
    }

    public function render(): View
    {
        return view('livewire.fiscal-periods.index', [
            'periods' => FiscalPeriod::query()
                ->with('closer')
                ->whereYear('start_date', $this->year)
                ->orderBy('start_date')
                ->get(),
        ])->title(__('app.nav.periods'));
    }
}
