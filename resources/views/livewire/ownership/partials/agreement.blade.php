{{-- One-line summary of a vehicle ownership agreement. Expects $ownership. --}}
@php($o = $ownership)
@if ($o->isConsignment())
    @switch($o->earning_mode)
        @case(\App\Enums\EarningMode::Percent)
            {{ __('ownership.summary.percent', ['percent' => rtrim(rtrim((string) $o->earning_percent, '0'), '.')]) }}
            @break
        @case(\App\Enums\EarningMode::None)
            {{ __('ownership.summary.none') }}
            @break
        @case(\App\Enums\EarningMode::Fixed)
            {{ __('ownership.summary.fixed', ['amount' => \App\Support\Money::format($o->earning_amount)]) }}
            @break
        @default
            {{ __('ownership.summary.net_price', ['amount' => \App\Support\Money::format($o->earning_amount)]) }}
    @endswitch
@else
    {{ __('ownership.summary.partnership', ['share' => rtrim(rtrim((string) $o->showroom_share, '0'), '.')]) }}
@endif
<span class="block text-gray-500">{{ __('ownership.payout') }}: {{ $o->payout->label() }}</span>
