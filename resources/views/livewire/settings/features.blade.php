<div class="space-y-4">
    <p class="rounded-md bg-blue-50 p-3 text-sm text-blue-900">{{ __('features.intro') }}</p>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        @foreach ($features as $f)
            <x-ui.card wire:key="feature-{{ $f['key'] }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold text-gray-900">{{ __('features.names.'.$f['key']) }}</h3>
                            <x-ui.badge :color="$f['enabled'] ? 'green' : 'gray'">{{ $f['enabled'] ? __('features.on') : __('features.off') }}</x-ui.badge>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">{{ __('features.descriptions.'.$f['key']) }}</p>
                        @if ($f['blocker'])
                            <p class="mt-2 rounded-md bg-yellow-50 p-2 text-xs text-yellow-800">{{ __('features.cannot_disable', ['reason' => $f['blocker']]) }}</p>
                        @endif
                        @error('feature_'.$f['key'])<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="button" role="switch" aria-checked="{{ $f['enabled'] ? 'true' : 'false' }}"
                            aria-label="{{ __('features.names.'.$f['key']) }}"
                            wire:click="toggle('{{ $f['key'] }}')" wire:loading.attr="disabled"
                            @if ($f['enabled']) wire:confirm="{{ __('features.confirm_off', ['feature' => __('features.names.'.$f['key'])]) }}" @endif
                            @disabled($f['blocker'] !== null)
                            @class([
                                'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full transition disabled:cursor-not-allowed disabled:opacity-50',
                                'bg-brand-700' => $f['enabled'],
                                'bg-gray-300' => ! $f['enabled'],
                            ])>
                        <span @class([
                            'absolute top-0.5 h-5 w-5 rounded-full bg-white shadow transition-all',
                            'start-[1.375rem]' => $f['enabled'],
                            'start-0.5' => ! $f['enabled'],
                        ])></span>
                    </button>
                </div>
            </x-ui.card>
        @endforeach
    </div>
</div>
