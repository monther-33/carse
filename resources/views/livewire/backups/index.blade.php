@php($mb = fn (int $bytes) => number_format($bytes / 1048576, 2).' MB')
<div class="space-y-4">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-ui.card>
            <p class="text-xs text-gray-500">{{ __('backups.latest') }}</p>
            <p class="num mt-1 text-xl font-bold">{{ $backups[0]['date'] ?? null ? $backups[0]['date']->format('Y-m-d H:i') : '—' }}</p>
            @if ($stale)
                <x-ui.badge color="red" class="mt-2">{{ __('backups.stale') }}</x-ui.badge>
            @else
                <x-ui.badge color="green" class="mt-2">{{ __('backups.healthy') }}</x-ui.badge>
            @endif
        </x-ui.card>
        <x-ui.card>
            <p class="text-xs text-gray-500">{{ __('backups.count') }}</p>
            <p class="num mt-1 text-xl font-bold">{{ count($backups) }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ __('backups.total_size') }} <span class="num">{{ $mb($totalSize) }}</span></p>
        </x-ui.card>
        <x-ui.card>
            <p class="text-xs text-gray-500">{{ __('backups.location') }}</p>
            <p class="mt-1 break-all font-mono text-xs" dir="ltr">{{ $location }}</p>
            <p class="mt-1 text-xs text-gray-500">{{ __('backups.schedule_hint') }}</p>
        </x-ui.card>
    </div>

    <x-ui.card :title="__('backups.list')" :padding="false">
        <x-slot:actions>
            <x-ui.button icon="database" wire:click="runNow" wire:confirm="{{ __('backups.confirm_run') }}" wire:loading.attr="disabled" wire:target="runNow">
                <span wire:loading.remove wire:target="runNow">{{ __('backups.run_now') }}</span>
                <span wire:loading wire:target="runNow">{{ __('backups.running') }}</span>
            </x-ui.button>
        </x-slot:actions>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead><tr>
                    <th>{{ __('backups.file') }}</th>
                    <th>{{ __('app.fields.date') }}</th>
                    <th>{{ __('backups.size') }}</th>
                    <th></th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($backups as $backup)
                    <tr wire:key="b-{{ $backup['name'] }}">
                        <td class="font-mono text-xs" dir="ltr">{{ $backup['name'] }}</td>
                        <td class="num">{{ $backup['date']->format('Y-m-d H:i') }}</td>
                        <td class="num">{{ $mb($backup['size']) }}</td>
                        <td class="text-end">
                            <x-ui.button variant="ghost" size="sm" icon="download" wire:click="download('{{ $backup['name'] }}')">{{ __('backups.download') }}</x-ui.button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-8 text-center text-gray-500">{{ __('backups.none') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <p class="p-4 text-xs text-gray-500">{{ __('backups.restore_hint') }}</p>
    </x-ui.card>
</div>
