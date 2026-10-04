<?php

namespace App\Enums;

enum AccountType: string
{
    case Asset = 'asset';
    case Liability = 'liability';
    case Equity = 'equity';
    case Revenue = 'revenue';
    case Expense = 'expense';

    public function nature(): AccountNature
    {
        return match ($this) {
            self::Asset, self::Expense => AccountNature::Debit,
            self::Liability, self::Equity, self::Revenue => AccountNature::Credit,
        };
    }

    public function label(): string
    {
        return __('enums.account_type.'.$this->value);
    }
}
