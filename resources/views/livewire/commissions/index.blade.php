<div class="space-y-4">
    <x-ui.card :padding="false">
        <div class="flex flex-wrap items-center gap-3 border-b border-gray-200 p-4">
            <select wire:model.live="status" class="form-input sm:w-40">
                <option value="">{{ __('app.all') }}</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s->value }}">{{ $s->label() }}</option>
                @endforeach
            </select>
            @if ($users->isNotEmpty())
                <select wire:model.live="userId" class="form-input sm:w-48">
                    <option value="">{{ __('commissions.all_salespeople') }}</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            @endif
            <span class="ms-auto text-sm">{{ __('documents.total') }}: <strong class="num">{{ \App\Support\Money::format($total) }}</strong></span>
        </div>

        @error('selected')<p class="px-4 pt-3 text-sm text-red-600">{{ $message }}</p>@enderror

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    @if ($canPay && $userId && $status === 'accrued')<th></th>@endif
                    <th>{{ __('sales.salesperson') }}</th>
                    <th>{{ __('sales.invoice_number') }}</th>
                    <th>{{ __('vehicles.vehicle') }}</th>
                    <th>{{ __('sales.customer') }}</th>
                    <th>{{ __('documents.amount') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($commissions as $c)
                    <tr wire:key="com-{{ $c->id }}">
                        @if ($canPay && $userId && $status === 'accrued')
                            <td><input type="checkbox" value="{{ $c->id }}" wire:model="selected" class="rounded border-gray-300 text-brand-600"></td>
                        @endif
                        <td>{{ $c->user->name }}</td>
                        <td class="num font-mono text-xs">{{ $c->invoice->number }}</td>
                        <td>{{ $c->item->vehicle->title() }}</td>
                        <td>{{ $c->invoice->party->name }}</td>
                        <td class="num">{{ \App\Support\Money::format($c->amount) }}</td>
                        <td>
                            <x-ui.badge :color="$c->status === \App\Enums\CommissionStatus::Paid ? 'green' : ($c->status === \App\Enums\CommissionStatus::Cancelled ? 'red' : 'yellow')">{{ $c->status->label() }}</x-ui.badge>
                            @if ($c->voucher)<span class="num font-mono text-xs text-gray-500">{{ $c->voucher->number }}</span>@endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $commissions->links() }}</div>

        @if ($canPay && $userId && $status === 'accrued')
            <div class="flex flex-wrap items-end gap-3 border-t border-gray-200 bg-gray-50 p-4">
                <x-ui.field :label="__('documents.cashbox')" for="cb" error="cashbox_id">
                    <select id="cb" wire:model="cashbox_id" class="form-input">
                        <option value="">—</option>
                        @foreach ($cashboxes as $cb)
                            <option value="{{ $cb->id }}">{{ $cb->name }}</option>
                        @endforeach
                    </select>
                </x-ui.field>
                <x-ui.button icon="cash" wire:click="pay" wire:confirm="{{ __('commissions.confirm_pay') }}">{{ __('commissions.pay_selected') }}</x-ui.button>
            </div>
        @elseif ($canPay)
            <p class="border-t border-gray-200 p-4 text-xs text-gray-500">{{ __('commissions.pay_hint') }}</p>
        @endif
    </x-ui.card>
</div>
