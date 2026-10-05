<div class="space-y-4">
    <x-ui.card :padding="false">
        <x-slot:actions>
            @can('create', \App\Models\ManualJournal::class)
                <x-ui.button icon="plus" wire:click="create">{{ __('journals.new') }}</x-ui.button>
            @endcan
        </x-slot:actions>

        <div class="border-b border-gray-200 p-4">
            <select wire:model.live="status" class="form-input sm:w-40">
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
                    <th>{{ __('app.fields.date') }}</th>
                    <th>{{ __('app.fields.description') }}</th>
                    <th>{{ __('journals.lines_count') }}</th>
                    <th>{{ __('documents.created_by') }}</th>
                    <th>{{ __('app.fields.status') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse ($journals as $journal)
                    <tr wire:key="mj-{{ $journal->id }}">
                        <td class="num font-mono text-xs">{{ $journal->displayNumber() }}</td>
                        <td class="num">{{ $journal->date->format('Y-m-d') }}</td>
                        <td>{{ $journal->description }}</td>
                        <td class="num">{{ $journal->lines->count() }}</td>
                        <td class="text-xs">{{ $journal->creator?->name }}</td>
                        <td><x-ui.badge :color="$journal->status->color()">{{ $journal->status->label() }}</x-ui.badge></td>
                        <td class="text-end whitespace-nowrap">
                            @can('update', $journal)
                                <x-ui.button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $journal->id }})" />
                            @endcan
                            @can('delete', $journal)
                                <x-ui.button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $journal->id }})" wire:confirm="{{ __('documents.confirm_delete_draft') }}" />
                            @endcan
                            @can('approve', $journal)
                                <x-ui.button size="sm" icon="check" wire:click="approve({{ $journal->id }})" wire:confirm="{{ __('documents.confirm_approve') }}">{{ __('documents.approve') }}</x-ui.button>
                            @endcan
                            @can('cancel', $journal)
                                <x-ui.button variant="ghost" size="sm" icon="x" wire:click="openCancel({{ $journal->id }})">{{ __('documents.cancel_document') }}</x-ui.button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-gray-500">{{ __('app.no_records') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $journals->links() }}</div>
    </x-ui.card>

    <x-ui.modal wire:model="showForm" :title="$editingId ? __('journals.edit') : __('journals.new')" max-width="4xl">
        <form wire:submit="save" id="journal-form" class="space-y-4">
            @error('document')<p class="rounded-md bg-red-50 p-2 text-sm text-red-700">{{ $message }}</p>@enderror
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <x-ui.field :label="__('app.fields.date')" error="date" required>
                    <input type="date" wire:model="date" class="form-input">
                </x-ui.field>
                <x-ui.field :label="__('app.fields.description')" error="description" class="sm:col-span-2" required>
                    <input type="text" wire:model="description" class="form-input">
                </x-ui.field>
            </div>

            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead><tr>
                        <th>{{ __('app.fields.account') }}</th>
                        <th>{{ __('vouchers.party') }}</th>
                        <th>{{ __('reports.debit') }}</th>
                        <th>{{ __('reports.credit') }}</th>
                        <th>{{ __('app.fields.currency') }}</th>
                        <th>{{ __('documents.rate') }}</th>
                        <th>{{ __('journals.memo') }}</th>
                        <th></th>
                    </tr></thead>
                    <tbody>
                    @foreach ($lines as $i => $line)
                        <tr wire:key="ml-{{ $i }}">
                            <td>
                                <select wire:model.live="lines.{{ $i }}.account_id" class="form-input min-w-48">
                                    <option value="">—</option>
                                    @foreach ($accounts as $a)
                                        <option value="{{ $a->id }}">{{ $a->label() }}</option>
                                    @endforeach
                                </select>
                                @error('lines.'.$i.'.account_id')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            </td>
                            <td>
                                <select wire:model="lines.{{ $i }}.party_id" class="form-input min-w-36" @disabled(! in_array((int) $line['account_id'], $partyAccounts, true) && ! $line['party_id'])>
                                    <option value="">—</option>
                                    @foreach ($parties as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" dir="ltr" inputmode="decimal" wire:model.live.debounce.500ms="lines.{{ $i }}.debit" class="form-input w-28"></td>
                            <td><input type="text" dir="ltr" inputmode="decimal" wire:model.live.debounce.500ms="lines.{{ $i }}.credit" class="form-input w-28"></td>
                            <td>
                                <select wire:model="lines.{{ $i }}.currency_id" class="form-input w-24">
                                    <option value="">{{ $currencies->firstWhere('is_base', true)?->code }}</option>
                                    @foreach ($currencies->where('is_base', false) as $c)
                                        <option value="{{ $c->id }}">{{ $c->code }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" dir="ltr" wire:model="lines.{{ $i }}.rate" class="form-input w-24" placeholder="{{ __('journals.auto') }}"></td>
                            <td><input type="text" wire:model="lines.{{ $i }}.memo" class="form-input w-36"></td>
                            <td>@if (count($lines) > 2)<x-ui.button variant="ghost" size="sm" icon="trash" wire:click="removeLine({{ $i }})" />@endif</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold">
                        <td colspan="2">{{ __('documents.total') }} <span class="text-xs font-normal text-gray-500">({{ __('journals.totals_hint') }})</span></td>
                        <td class="num">{{ \App\Support\Money::format($debitTotal) }}</td>
                        <td class="num">{{ \App\Support\Money::format($creditTotal) }}</td>
                        <td colspan="4">
                            @if ($debitTotal->isEqualTo($creditTotal) && $debitTotal->isPositive())
                                <x-ui.badge color="green">{{ __('reports.balanced') }}</x-ui.badge>
                            @else
                                <x-ui.badge color="red">{{ __('journals.difference', ['amount' => \App\Support\Money::format($debitTotal->minus($creditTotal)->abs())]) }}</x-ui.badge>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <x-ui.button variant="secondary" size="sm" icon="plus" wire:click="addLine">{{ __('journals.add_line') }}</x-ui.button>
        </form>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.cancel') }}</x-ui.button>
            <x-ui.button type="submit" form="journal-form" wire:loading.attr="disabled">{{ __('documents.save_draft') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    <x-ui.modal wire:model="showCancel" :title="__('documents.cancel_document')" max-width="md">
        <x-ui.field :label="__('documents.cancel_reason')" error="reason" required>
            <input type="text" wire:model="reason" class="form-input">
        </x-ui.field>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">{{ __('app.close') }}</x-ui.button>
            <x-ui.button variant="danger" wire:click="cancel">{{ __('documents.confirm_cancel') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
