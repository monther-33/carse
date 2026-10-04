<?php

namespace App\Policies;

use App\Models\Party;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PartyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('parties.view');
    }

    public function view(User $user, Party $party): bool
    {
        return $user->can('parties.view');
    }

    public function create(User $user): bool
    {
        return $user->can('parties.manage');
    }

    public function update(User $user, Party $party): bool
    {
        return $user->can('parties.manage');
    }

    /** A party with any journal movement or document is kept (deactivate instead). */
    public function delete(User $user, Party $party): bool
    {
        return $user->can('parties.manage')
            && ! DB::table('journal_lines')->where('party_id', $party->id)->exists()
            && ! DB::table('purchase_invoices')->where('party_id', $party->id)->exists()
            && ! DB::table('vouchers')->where('party_id', $party->id)->exists();
    }

    /** Statements show balances, which are financial information. */
    public function viewStatement(User $user, Party $party): bool
    {
        return $user->canAny(['reports.financial', 'vouchers.view', 'purchases.view']);
    }
}
