@extends('print.layout')

@section('content')
    @php($type = $voucher->type)
    <h1>{{ $type->label() }}</h1>

    <table class="info">
        <tr>
            <td class="label">{{ __('documents.number') }}</td><td class="num">{{ $voucher->number }}</td>
            <td class="label">{{ __('app.fields.date') }}</td><td class="num">{{ $voucher->date->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td class="label">
                @if ($type === \App\Enums\VoucherType::Receipt){{ __('print.received_from') }}
                @elseif ($type === \App\Enums\VoucherType::Payment){{ __('print.paid_to') }}
                @else{{ __('vouchers.to_cashbox') }}@endif
            </td>
            <td colspan="3">
                @if ($type === \App\Enums\VoucherType::Transfer)
                    {{ $voucher->toCashbox?->name }}
                @else
                    {{ $voucher->party?->name ?? $voucher->account?->name }}
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">{{ __('documents.amount') }}</td>
            <td class="num" style="font-size: 14pt; font-weight: bold">{{ \App\Support\Money::format($voucher->amount, $voucher->currency->decimals) }} {{ $voucher->currency->code }}</td>
            <td class="label">{{ $type === \App\Enums\VoucherType::Transfer ? __('vouchers.from_cashbox') : __('documents.cashbox') }}</td>
            <td>{{ $voucher->cashbox->name }}</td>
        </tr>
    </table>

    <div class="words">{{ __('print.amount_in_words') }}: {{ \App\Support\Tafqeet::amount($voucher->amount, $voucher->currency->code) }}</div>

    <p>{{ __('app.fields.description') }}: {{ $voucher->description }}</p>

    <table class="signatures">
        <tr>
            <td>{{ __('print.cashier_signature') }}<br>........................</td>
            <td>{{ $type === \App\Enums\VoucherType::Payment ? __('print.receiver_signature') : __('print.payer_signature') }}<br>........................</td>
            <td>{{ __('documents.approved_by') }}: {{ $voucher->approver?->name }}<br>........................</td>
        </tr>
    </table>
@endsection
