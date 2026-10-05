<?php

namespace App\Livewire\Reports;

use App\Reports\ReportRegistry;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * List of the reports the user may open, by group.
 */
#[Layout('layouts.app')]
class Index extends Component
{
    public function render(): View
    {
        $groups = ReportRegistry::forUser(auth()->user());
        abort_if($groups === [] && ! auth()->user()->can('parties.view'), 403);

        return view('livewire.reports.index', ['groups' => $groups])->title(__('app.nav.reports'));
    }
}
