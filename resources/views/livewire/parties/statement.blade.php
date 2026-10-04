<div class="space-y-4">
    <x-ui.card>
        <div class="flex flex-wrap items-end gap-4">
            <x-ui.field :label="__('reports.from')" for="from">
                <input id="from" type="date" wire:model.live="from" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('reports.to')" for="to">
                <input id="to" type="date" wire:model.live="to" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.account')" for="account">
                <select id="account" wire:model.live="account" class="form-input">
                    <option value="all">{{ __('app.all') }}</option>
                    <option value="receivables">{{ __('enums.account_role.receivables') }}</option>
                    <option value="payables">{{ __('enums.account_role.payables') }}</option>
                    <option value="customer_deposits">{{ __('enums.account_role.customer_deposits') }}</option>
                </select>
            </x-ui.field>
            <div class="ms-auto text-sm text-gray-600">
                <p>{{ $party->type->label() }} · <span class="num">{{ $party->phone }}</span></p>
            </div>
        </div>
    </x-ui.card>

    <x-ui.card :padding="false">
        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('app.fields.date') }}</th>
                    <th>{{ __('documents.number') }}</th>
                    <th>{{ __('app.fields.description') }}</th>
                    <th>{{ __('app.fields.account') }}</th>
                    <th>{{ __('reports.original_amount') }}</th>
                    <th>{{ __('reports.debit') }}</th>
                    <th>{{ __('reports.credit') }}</th>
                    <th>{{ __('reports.balance') }}</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                <tr class="bg-gray-50 font-medium">
                    <td colspan="7">{{ __('reports.opening_balance') }}</td>
                    <td class="num">{{ \App\Support\Money::format($opening) }}</td>
                </tr>
                @forelse ($lines as $line)
                    <tr>
                        <td class="num whitespace-nowrap">{{ $line->date }}</td>
                        <td class="num font-mono text-xs">{{ $line->number }}</td>
                        <td>{{ $line->description }}</td>
                        <td class="text-xs text-gray-600">{{ $line->account }}</td>
                        <td class="num text-xs text-gray-600">
                            @if ($line->currency !== 'LYD')
                                {{ \App\Support\Money::format(\App\Support\Money::of($line->debit)->isPositive() ? $line->debit : $line->credit, 2) }} {{ $line->currency }}
                                × {{ rtrim(rtrim($line->rate, '0'), '.') }}
                            @endif
                        </td>
                        <td class="num">{{ \App\Support\Money::of($line->debit_base)->isPositive() ? \App\Support\Money::format($line->debit_base) : '' }}</td>
                        <td class="num">{{ \App\Support\Money::of($line->credit_base)->isPositive() ? \App\Support\Money::format($line->credit_base) : '' }}</td>
                        <td class="num font-medium">{{ \App\Support\Money::format($line->balance) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-6 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                @endforelse
                <tr class="bg-gray-50 font-semibold">
                    <td colspan="7">{{ __('reports.closing_balance') }}</td>
                    <td class="num">{{ \App\Support\Money::format($closing) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-200 p-4 text-sm text-gray-600">
            <p>{{ __('reports.balance_sign_hint') }}</p>
            @if ($byCurrency)
                <p class="mt-1">{{ __('reports.open_by_currency') }}:
                    @foreach ($byCurrency as $code => $amount)
                        <span class="num font-medium">{{ \App\Support\Money::format($amount) }} {{ $code }}</span>@if (! $loop->last) · @endif
                    @endforeach
                </p>
            @endif
        </div>
    </x-ui.card>
</div>
