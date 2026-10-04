@php($code = $invoice->currency->code)
<table class="grid" style="width: 55%">
    <tr><td>{{ __('documents.subtotal') }}</td><td class="num">{{ \App\Support\Money::format($invoice->subtotal) }}</td></tr>
    @if (\App\Support\Money::of($invoice->discount)->isPositive())
        <tr><td>{{ __('documents.discount') }}</td><td class="num">{{ \App\Support\Money::format($invoice->discount) }}</td></tr>
    @endif
    <tr class="total-row"><td>{{ __('documents.total') }}</td><td class="num">{{ \App\Support\Money::format($invoice->total) }} {{ $code }}</td></tr>
    @if (\App\Support\Money::of($invoice->trade_in_value)->isPositive())
        <tr><td>{{ __('sales.trade_in') }}</td><td class="num">{{ \App\Support\Money::format($invoice->trade_in_value) }}</td></tr>
    @endif
    @if (\App\Support\Money::of($invoice->deposit_applied)->isPositive())
        <tr><td>{{ __('sales.deposit') }}</td><td class="num">{{ \App\Support\Money::format($invoice->deposit_applied) }}</td></tr>
    @endif
    @if (\App\Support\Money::of($invoice->paid)->isPositive())
        <tr><td>{{ $invoice->installmentPlan ? __('sales.down_payment') : __('sales.paid_at_sale') }}</td><td class="num">{{ \App\Support\Money::format($invoice->paid) }}</td></tr>
    @endif
    <tr class="total-row">
        <td>{{ $invoice->installmentPlan ? __('sales.financed') : __('sales.remaining') }}</td>
        <td class="num">{{ \App\Support\Money::format($invoice->balanceAfterSale()) }} {{ $code }}</td>
    </tr>
</table>
<div class="words">{{ __('print.amount_in_words') }}: {{ \App\Support\Tafqeet::amount($invoice->total, $code) }}</div>
