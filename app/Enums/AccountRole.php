<?php

namespace App\Enums;

/**
 * Logical accounts used by automatic postings. Each one maps to an account id
 * stored in settings under "account.{value}", so no account code lives in code.
 */
enum AccountRole: string
{
    case Receivables = 'receivables';
    case Payables = 'payables';
    case Inventory = 'inventory';
    case InTransit = 'in_transit';
    case CustomerDeposits = 'customer_deposits';
    case AccruedCommissions = 'accrued_commissions';
    case Capital = 'capital';
    case PartnersCurrent = 'partners_current';
    case RetainedEarnings = 'retained_earnings';
    case VehicleSales = 'vehicle_sales';
    case OtherRevenue = 'other_revenue';
    case CostOfSales = 'cost_of_sales';
    case CommissionExpense = 'commission_expense';
    case FxDifferences = 'fx_differences';
    case DiscountAllowed = 'discount_allowed';
    case ForfeitedDeposits = 'forfeited_deposits';
    case OpeningBalances = 'opening_balances';
    case OwnersPayable = 'owners_payable';
    case ConsignmentRevenue = 'consignment_revenue';

    public function settingKey(): string
    {
        return 'account.'.$this->value;
    }

    public function label(): string
    {
        return __('enums.account_role.'.$this->value);
    }
}
