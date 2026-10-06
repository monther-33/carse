{{-- Credit limit: a warning only, the sale is never blocked (App\Services\Sales\CreditLimitCheck). --}}
@if ($warning)
    <div class="flex items-start gap-2 rounded-md border border-yellow-300 bg-yellow-50 p-3 text-sm text-yellow-900">
        <x-ui.icon name="shield" class="mt-0.5 h-5 w-5 shrink-0" />
        <div>
            <p class="font-semibold">{{ __('sales.credit_limit_warning.title') }}</p>
            <p>{{ __('sales.credit_limit_warning.body', [
                'limit' => \App\Support\Money::format($warning['limit']),
                'balance' => \App\Support\Money::format($warning['balance']),
                'after' => \App\Support\Money::format($warning['after']),
                'over' => \App\Support\Money::format($warning['over']),
            ]) }}</p>
        </div>
    </div>
@endif
