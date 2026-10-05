<div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
    @foreach ($groups as $group => $reports)
        <x-ui.card :title="__('reports.groups.'.$group)" :padding="false">
            <ul class="divide-y divide-gray-100">
                @foreach ($reports as $report)
                    <li>
                        <a href="{{ route('reports.show', $report::key()) }}" wire:navigate class="flex items-center justify-between px-4 py-3 text-sm hover:bg-brand-50">
                            <span>{{ $report->title() }}</span>
                            <x-ui.icon name="chevron" class="h-4 w-4 rotate-180 text-gray-400" />
                        </a>
                    </li>
                @endforeach
                @if ($group === 'accounting' && auth()->user()->can('parties.view'))
                    <li>
                        <a href="{{ route('parties.index') }}" wire:navigate class="flex items-center justify-between px-4 py-3 text-sm hover:bg-brand-50">
                            <span>{{ __('reports.titles.party_statement') }}</span>
                            <x-ui.icon name="chevron" class="h-4 w-4 rotate-180 text-gray-400" />
                        </a>
                    </li>
                @endif
            </ul>
        </x-ui.card>
    @endforeach
</div>
