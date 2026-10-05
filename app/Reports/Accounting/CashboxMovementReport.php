<?php

namespace App\Reports\Accounting;

use App\Models\Cashbox;
use App\Models\User;
use App\Reports\Concerns\QueriesLedger;
use App\Reports\Report;
use App\Support\Money;

/**
 * Movement of one cashbox in its own currency: opening, receipts, payments, running balance.
 * A treasurer can only run it for the cashboxes assigned to them.
 */
class CashboxMovementReport extends Report
{
    use QueriesLedger;

    public static function key(): string
    {
        return 'cashbox_movement';
    }

    public function group(): string
    {
        return 'accounting';
    }

    public function permissions(): array
    {
        return ['reports.financial', 'cashboxes.view'];
    }

    public function filters(): array
    {
        return ['cashbox_id', 'from', 'to'];
    }

    public function required(): array
    {
        return ['cashbox_id'];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'date' => ['label' => __('app.fields.date'), 'type' => 'date'],
            'number' => ['label' => __('documents.number'), 'type' => 'text'],
            'description' => ['label' => __('app.fields.description'), 'type' => 'text'],
            'in' => ['label' => __('reports.cash_in'), 'type' => 'money', 'total' => true],
            'out' => ['label' => __('reports.cash_out'), 'type' => 'money', 'total' => true],
            'balance' => ['label' => __('reports.balance'), 'type' => 'money'],
        ];
    }

    public function rows(User $user, array $f): array
    {
        $cashbox = Cashbox::query()->visibleTo($user)->findOrFail($f['cashbox_id']);
        $base = fn () => $this->lines([])->where('l.account_id', $cashbox->account_id);

        // In the cashbox's own currency (original amounts).
        $running = Money::of((string) $base()->where('e.date', '<', $f['from'])->sum(\DB::raw('l.debit - l.credit')));
        $rows = [['description' => __('reports.opening_balance'), 'balance' => $running, '_style' => 'subtotal']];

        $lines = $base()->whereBetween('e.date', [$f['from'], $f['to']])
            ->orderBy('e.date')->orderBy('e.id')
            ->select(['e.date', 'e.number', 'e.description', 'l.debit', 'l.credit'])->get();

        foreach ($lines as $line) {
            $running = $running->plus(Money::of((string) $line->debit))->minus(Money::of((string) $line->credit));
            $rows[] = [
                'date' => $line->date, 'number' => $line->number, 'description' => $line->description,
                'in' => Money::of((string) $line->debit), 'out' => Money::of((string) $line->credit), 'balance' => $running,
            ];
        }

        $rows[] = ['description' => __('reports.closing_balance'), 'balance' => $running, '_style' => 'total'];

        return $rows;
    }
}
