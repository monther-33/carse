<?php

namespace App\Actions\Accounting;

use App\Models\FiscalPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Closing takes an exclusive lock on the period row, so it waits for any posting
 * in flight (PostingService holds a shared lock) and blocks new ones afterwards.
 */
class CloseFiscalPeriod
{
    public function handle(FiscalPeriod $period): FiscalPeriod
    {
        return DB::transaction(function () use ($period) {
            $period = FiscalPeriod::query()->lockForUpdate()->findOrFail($period->getKey());

            if ($period->is_closed) {
                throw ValidationException::withMessages(['period' => __('accounting.validation.period_already_closed')]);
            }

            $period->update([
                'is_closed' => true,
                'closed_at' => now(),
                'closed_by' => Auth::id(),
            ]);

            return $period;
        });
    }
}
