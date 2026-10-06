<?php

namespace App\Livewire\Settings;

use App\Livewire\Concerns\HandlesBusinessErrors;
use App\Livewire\Concerns\Notifies;
use App\Support\Features as FeatureSwitches;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Switch the optional parts of the system on or off (App\Support\Features).
 */
#[Layout('layouts.app')]
class Features extends Component
{
    use HandlesBusinessErrors, Notifies;

    public function mount(): void
    {
        $this->authorize('settings.manage');
    }

    public function toggle(string $feature, FeatureSwitches $features): void
    {
        $this->authorize('settings.manage');
        abort_unless(in_array($feature, FeatureSwitches::ALL, true), 404);

        $on = ! $features->enabled($feature);
        $done = $this->attempt(function () use ($features, $feature, $on) {
            $features->set($feature, $on);

            return true;
        }, 'feature_'.$feature);

        if ($done !== null) {
            $this->notify(__($on ? 'features.enabled_ok' : 'features.disabled_ok', ['feature' => __('features.names.'.$feature)]));
        }
    }

    public function render(FeatureSwitches $features): View
    {
        $list = [];
        foreach (FeatureSwitches::ALL as $feature) {
            $enabled = $features->enabled($feature);
            $list[] = ['key' => $feature, 'enabled' => $enabled, 'blocker' => $enabled ? $features->blocker($feature) : null];
        }

        return view('livewire.settings.features', ['features' => $list])->title(__('app.nav.features'));
    }
}
