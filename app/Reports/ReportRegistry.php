<?php

namespace App\Reports;

use App\Models\User;

/**
 * All reports, in menu order. Add a new report class here.
 */
final class ReportRegistry
{
    /** @var list<class-string<Report>> */
    private const REPORTS = [
        Accounting\TrialBalanceReport::class,
        Accounting\IncomeStatementReport::class,
        Accounting\BalanceSheetReport::class,
        Accounting\LedgerReport::class,
        Accounting\JournalBookReport::class,
        Accounting\CashboxMovementReport::class,
        Accounting\AgingReport::class,
        Accounting\ExpensesByCategoryReport::class,
        Accounting\CancelledDocumentsReport::class,
        Sales\SalesReport::class,
        Sales\VehicleProfitReport::class,
        Sales\CommissionsReport::class,
        Sales\InstallmentsReport::class,
        Inventory\InventoryReport::class,
        Inventory\StockAgingReport::class,
        Inventory\PurchasesBySupplierReport::class,
        Admin\AuditLogReport::class,
    ];

    public static function find(string $key): ?Report
    {
        foreach (self::REPORTS as $class) {
            if ($class::key() === $key) {
                return app($class);
            }
        }

        return null;
    }

    /**
     * @return array<string, list<Report>> by group, only the reports the user may open
     */
    public static function forUser(User $user): array
    {
        $groups = [];

        foreach (self::REPORTS as $class) {
            $report = app($class);
            if ($report->allows($user)) {
                $groups[$report->group()][] = $report;
            }
        }

        return $groups;
    }
}
