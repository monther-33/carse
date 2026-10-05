<?php

namespace App\Livewire\Reports;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Cashbox;
use App\Models\Currency;
use App\Models\ExpenseCategory;
use App\Models\User;
use App\Reports\Cell;
use App\Reports\Report;
use App\Reports\ReportRegistry;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Renders any report from the registry with its filters, totals and export links.
 */
#[Layout('layouts.app')]
class Viewer extends Component
{
    public string $key;

    /** @var array<string, mixed> */
    #[Url(as: 'f')]
    public array $filters = [];

    public function mount(string $key): void
    {
        $report = $this->report();
        abort_unless($report->allows(auth()->user()), 403);

        $this->key = $key;
        $this->filters = $report->resolve($this->filters);
    }

    private function report(): Report
    {
        $report = ReportRegistry::find($this->key ?? request()->route('key'));
        abort_if($report === null, 404);

        return $report;
    }

    public function render(): View
    {
        $report = $this->report();
        $user = auth()->user();
        $f = $report->resolve($this->filters);
        $missing = array_filter($report->required(), fn ($k) => empty($f[$k]));

        $columns = $report->columns($user, $f);
        $rows = $missing === [] ? $report->rows($user, $f) : [];

        return view('livewire.reports.viewer', [
            'report' => $report,
            'columns' => $columns,
            'rows' => $rows,
            'totals' => Cell::totals($columns, $rows),
            'notes' => $missing === [] ? $report->notes($user, $f) : [],
            'missing' => $missing,
            'f' => $f,
            'lists' => $this->lists($report),
        ])->title($report->title());
    }

    /**
     * Choices for the filters this report uses.
     *
     * @return array<string, mixed>
     */
    private function lists(Report $report): array
    {
        $uses = array_flip($report->filters());
        $user = auth()->user();

        return [
            'branch_id' => isset($uses['branch_id']) ? Branch::query()->orderBy('name')->pluck('name', 'id') : collect(),
            'currency_id' => isset($uses['currency_id']) ? Currency::query()->orderByDesc('is_base')->pluck('name', 'id') : collect(),
            'account_id' => isset($uses['account_id']) ? Account::query()->orderBy('code')->get()->mapWithKeys(fn ($a) => [$a->id => $a->label()]) : collect(),
            'cashbox_id' => isset($uses['cashbox_id']) ? Cashbox::query()->visibleTo($user)->orderBy('name')->pluck('name', 'id') : collect(),
            'user_id' => isset($uses['user_id']) ? User::query()->orderBy('name')->pluck('name', 'id') : collect(),
            'brand_id' => isset($uses['brand_id']) ? Brand::query()->orderBy('name')->pluck('name', 'id') : collect(),
            'category_id' => isset($uses['category_id']) ? ExpenseCategory::query()->orderBy('name')->pluck('name', 'id') : collect(),
        ];
    }
}
