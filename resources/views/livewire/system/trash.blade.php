<div class="space-y-4">
    <p class="rounded-md bg-blue-50 p-3 text-sm text-blue-800">{{ __('trash.intro') }}</p>

    <x-ui.card :padding="false">
        <div class="grid grid-cols-1 gap-3 border-b border-gray-200 p-4 sm:grid-cols-3">
            <input type="search" wire:model.live.debounce.300ms="q" placeholder="{{ __('trash.search') }}" class="form-input sm:col-span-2">
            <select wire:model.live="show" class="form-input">
                <option value="pending">{{ __('trash.show') }}: {{ __('trash.pending') }}</option>
                <option value="all">{{ __('trash.show') }}: {{ __('trash.all') }}</option>
            </select>
        </div>
        @error('document')<p class="m-4 rounded-md bg-red-50 p-3 text-sm text-red-700">{{ $message }}</p>@enderror

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead><tr>
                    <th>{{ __('trash.what') }}</th>
                    <th class="hidden sm:table-cell">{{ __('trash.rows') }}</th>
                    <th>{{ __('trash.deleted_by') }}</th>
                    <th class="hidden sm:table-cell">{{ __('trash.deleted_at') }}</th>
                    <th></th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($batches as $b)
                    <tr wire:key="tb-{{ $b->batch }}">
                        <td>{{ $b->label }}</td>
                        <td class="num hidden sm:table-cell">{{ $b->rows_count }}</td>
                        <td class="text-sm">{{ $users[$b->deleted_by] ?? '—' }}</td>
                        <td class="num hidden text-xs sm:table-cell">{{ \Illuminate\Support\Carbon::parse($b->deleted_at)->format('Y-m-d H:i') }}</td>
                        <td class="text-end">
                            @if ($b->restored_at)
                                <x-ui.badge color="green">{{ __('trash.restored') }}</x-ui.badge>
                                <span class="block text-xs text-gray-500">{{ __('trash.restored_by', ['name' => $users[$b->restored_by] ?? '—', 'at' => \Illuminate\Support\Carbon::parse($b->restored_at)->format('Y-m-d H:i')]) }}</span>
                            @else
                                <x-ui.button size="sm" variant="secondary" icon="return"
                                             wire:click="restore('{{ $b->batch }}')"
                                             wire:confirm="{{ __('trash.confirm_restore', ['label' => $b->label]) }}">{{ __('trash.restore') }}</x-ui.button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $batches->links() }}</div>
    </x-ui.card>
</div>
