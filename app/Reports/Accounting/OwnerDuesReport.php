<?php

namespace App\Reports\Accounting;

use App\Enums\AccountRole;
use App\Models\Party;
use App\Models\User;
use App\Reports\Report;
use App\Services\Accounting\AccountResolver;
use App\Services\Ownership\OwnerPayouts;
use App\Support\Features;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * What the showroom owes each vehicle owner and partner now (LYD): balance on the owners
 * account, the part held back until customers pay ("as collected" sales), and what may be
 * paid now. A negative balance means the owner owes the showroom (expenses, contributions).
 */
class OwnerDuesReport extends Report
{
    public static function key(): string
    {
        return 'owner_dues';
    }

    public function feature(): ?string
    {
        return Features::CONSIGNMENT;
    }

    public function group(): string
    {
        return 'accounting';
    }

    public function permissions(): array
    {
        return ['reports.financial', 'consignments.view'];
    }

    public function filters(): array
    {
        return [];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'owner' => ['label' => __('ownership.owner'), 'type' => 'text'],
            'phone' => ['label' => __('app.fields.phone'), 'type' => 'text'],
            'balance' => ['label' => __('ownership.balance'), 'type' => 'money', 'total' => true],
            'pending' => ['label' => __('ownership.pending'), 'type' => 'money', 'total' => true],
            'available' => ['label' => __('ownership.available'), 'type' => 'money', 'total' => true],
        ];
    }

    public function rows(User $user, array $f): array
    {
        $payouts = app(OwnerPayouts::class);
        $partyIds = DB::table('journal_lines')
            ->where('account_id', app(AccountResolver::class)->idFor(AccountRole::OwnersPayable))
            ->whereNotNull('party_id')->distinct()->pluck('party_id');

        return Party::withTrashed()->whereIn('id', $partyIds)->orderBy('name')->get()
            ->map(function (Party $party) use ($payouts) {
                $balance = $payouts->balance($party->id);

                return [
                    'owner' => $party->name,
                    'phone' => $party->phone,
                    'balance' => $balance,
                    'pending' => $payouts->pending($party->id),
                    'available' => $payouts->available($party->id),
                ];
            })
            ->filter(fn (array $row) => ! $row['balance']->isZero() || ! Money::of($row['pending'])->isZero())
            ->values()->all();
    }

    public function notes(User $user, array $f): array
    {
        return [__('ownership.dues_note')];
    }
}
