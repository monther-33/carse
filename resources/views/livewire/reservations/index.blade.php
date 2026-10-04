<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            @can('create', \App\Models\Reservation::class)
                <x-ui.button icon="plus" wire:click="create">{{ __('reservations.new') }}</x-ui.button>
            @endcan
        </x-slot:actions>

        <div class="border-b border-gray-200 p-4">
            <select wire:model.live="status" class="form-input sm:w-44">
                <option value="">{{ __('app.all') }}</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s->value }}">{{ $s->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                <tr>
                    <th>{{ __('documents.number') }}</th>
                    <th>{{ __('vehicles.vehicle') }}</th>
                    <th>{{ __('sales.customer') }}</th>
                    <th>{{ __('sales.deposit') }}</th>
                    <th>{{ __('reservations.expires_at') }}</th>
                    <th>{{ __('sales.salesperson') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($reservations as $r)
                    @php($expiringSoon = $r->status === \App\Enums\ReservationStatus::Active && $r->expires_at->lte(today()->addDays(2)))
                    <tr wire:key="res-{{ $r->id }}">
                        <td class="num font-mono text-xs">{{ $r->number }}</td>
                        <td>{{ $r->vehicle->title() }} <span class="block num font-mono text-xs text-gray-500">{{ $r->vehicle->vin }}</span></td>
                        <td>{{ $r->party->name }}</td>
                        <td class="num">
                            {{ \App\Support\Money::format($r->deposit) }} {{ $r->currency->code }}
                            @if ($r->voucher?->isDraft())<x-ui.badge color="yellow">{{ __('reservations.deposit_pending') }}</x-ui.badge>@endif
                        </td>
                        <td @class(['num', 'font-semibold text-red-700' => $expiringSoon])>{{ $r->expires_at->format('Y-m-d') }}</td>
                        <td class="text-xs">{{ $r->salesperson->name }}</td>
                        <td><x-ui.badge :color="$r->status === \App\Enums\ReservationStatus::Active ? 'blue' : 'gray'">{{ $r->status->label() }}</x-ui.badge></td>
                        <td class="text-end whitespace-nowrap">
                            @if ($r->status === \App\Enums\ReservationStatus::Active)
                                @can('create', \App\Models\SalesInvoice::class)
                                    <x-ui.button size="sm" icon="tag" :href="route('sales.create', ['reservation' => $r->id])" wire:navigate>{{ __('reservations.sell') }}</x-ui.button>
                                @endcan
                                @can('cancel', $r)
                                    <x-ui.button variant="ghost" size="sm" icon="x" wire:click="openCancel({{ $r->id }})">{{ __('documents.cancel_document') }}</x-ui.button>
                                @endcan
                            @endif
                            @if ($r->voucher?->isPosted())
                                <x-ui.button variant="ghost" size="sm" icon="printer" :href="route('print.voucher', $r->voucher)" target="_blank" />
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $reservations->links() }}</div>
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="__('reservations.new')">
        <form wire:submit="save" id="res-form" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-ui.field :label="__('vehicles.vehicle')" error="form.vehicle_id" class="sm:col-span-2" required>
                <livewire:pickers.vehicle-picker wire:model="form.vehicle_id" :statuses="['available']" />
            </x-ui.field>
            <x-ui.field :label="__('sales.customer')" error="form.party_id" class="sm:col-span-2" required>
                <livewire:pickers.party-picker wire:model="form.party_id" kind="customer" :allow-create="true" />
            </x-ui.field>
            <x-ui.field :label="__('documents.cashbox')" for="r-cb" error="form.cashbox_id" required>
                <select id="r-cb" wire:model="form.cashbox_id" class="form-input">
                    <option value="">—</option>
                    @foreach ($cashboxes as $cb)
                        <option value="{{ $cb->id }}">{{ $cb->name }} ({{ $cb->currency->code }})</option>
                    @endforeach
                </select>
            </x-ui.field>
            <x-ui.field :label="__('sales.deposit')" for="r-dep" error="form.deposit" required>
                <input id="r-dep" type="text" dir="ltr" inputmode="decimal" wire:model="form.deposit" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('reservations.expires_at')" for="r-exp" error="form.expires_at" required>
                <input id="r-exp" type="date" wire:model="form.expires_at" class="form-input">
            </x-ui.field>
            <x-ui.field :label="__('app.fields.notes')" for="r-notes" error="form.notes">
                <input id="r-notes" type="text" wire:model="form.notes" class="form-input">
            </x-ui.field>
            <p class="text-xs text-gray-500 sm:col-span-2">{{ __('reservations.hint') }}</p>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="res-form" wire:loading.attr="disabled">{{ __('reservations.reserve') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    <x-ui.modal wire:model="showCancel" :title="__('documents.cancel_document')" max-width="md">
        <p class="mb-3 text-sm text-gray-600">{{ __('reservations.cancel_hint') }}</p>
        <x-ui.field :label="__('documents.cancel_reason')" for="reason" error="reason" required>
            <input id="reason" type="text" wire:model="reason" class="form-input">
        </x-ui.field>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.close') }}</x-ui.button>
            <x-ui.button variant="danger" wire:click="cancel">{{ __('documents.confirm_cancel') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
