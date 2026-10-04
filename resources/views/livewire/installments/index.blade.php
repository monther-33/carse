<div class="space-y-4">
    <x-ui.card :padding="false">
        <div class="flex flex-wrap gap-3 border-b border-gray-200 p-4">
            <select wire:model.live="filter" class="form-input sm:w-48">
                <option value="overdue">{{ __('installments.filters.overdue') }}</option>
                <option value="week">{{ __('installments.filters.week') }}</option>
                <option value="open">{{ __('installments.filters.open') }}</option>
                <option value="paid">{{ __('installments.filters.paid') }}</option>
            </select>
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('installments.search_placeholder') }}" class="form-input sm:max-w-xs">
        </div>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('sales.customer') }}</th>
                    <th>{{ __('sales.invoice_number') }}</th>
                    <th>#</th>
                    <th>{{ __('sales.due_date') }}</th>
                    <th>{{ __('documents.amount') }}</th>
                    <th>{{ __('documents.paid') }}</th>
                    <th>{{ __('installments.remaining') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($installments as $inst)
                    @php($invoice = $inst->plan->invoice)
                    <tr wire:key="inst-{{ $inst->id }}" @class(['bg-red-50' => $inst->isOverdue()])>
                        <td>{{ $invoice->party->name }} <span class="block num text-xs text-gray-500">{{ $invoice->party->phone }}</span></td>
                        <td class="num font-mono text-xs">
                            @can('view', $invoice)
                                <a href="{{ route('sales.show', $invoice) }}" wire:navigate class="text-brand-700 hover:underline">{{ $invoice->number }}</a>
                            @else
                                {{ $invoice->number }}
                            @endcan
                        </td>
                        <td class="num">{{ $inst->sequence }}/{{ $inst->plan->months }}</td>
                        <td class="num">{{ $inst->due_date->format('Y-m-d') }}</td>
                        <td class="num">{{ \App\Support\Money::format($inst->amount) }} {{ $invoice->currency->code }}</td>
                        <td class="num">{{ \App\Support\Money::format($inst->paid_amount) }}</td>
                        <td class="num font-semibold">{{ \App\Support\Money::format($inst->remaining()) }}</td>
                        <td><x-ui.badge :color="$inst->isOverdue() ? 'red' : 'gray'">{{ $inst->isOverdue() ? __('installments.overdue') : $inst->status->label() }}</x-ui.badge></td>
                        <td class="text-end">
                            @if ($inst->remaining()->isPositive() && in_array($inst->status, [\App\Enums\InstallmentStatus::Pending, \App\Enums\InstallmentStatus::Partial]))
                                @can('create', \App\Models\Voucher::class)
                                    <x-ui.button size="sm" icon="cash" wire:click="openCollect({{ $inst->id }})">{{ __('installments.collect') }}</x-ui.button>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $installments->links() }}</div>
    </x-ui.card>

    <x-ui.modal wire:model="showCollect" :title="__('installments.collect')" max-width="md">
        <form wire:submit="collect" id="collect-form" class="space-y-4">
            <p class="text-xs text-gray-500">{{ __('installments.collect_hint') }}</p>
            <x-ui.field :label="__('app.fields.date')" for="c-date" error="form.date" required>
                <input id="c-date" type="date" wire:model="form.date" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('documents.cashbox')" for="c-cb" error="form.cashbox_id" required>
                <select id="c-cb" wire:model="form.cashbox_id" class="form-input">
                    <option value="">—</option>
                    @foreach ($cashboxes as $cb)
                        <option value="{{ $cb->id }}">{{ $cb->name }} ({{ $cb->currency->code }})</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('documents.amount')" for="c-amount" error="form.amount" required>
                <input id="c-amount" type="text" dir="ltr" inputmode="decimal" wire:model="form.amount" class="form-input">
            </x-ui.field>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="collect-form" wire:loading.attr="disabled">{{ __('installments.collect') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
