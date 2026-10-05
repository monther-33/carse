<?php

namespace App\Providers;

use App\Listeners\LogAuthenticationEvents;
use App\Listeners\NotifyBackupProblems;
use App\Models\Expense;
use App\Models\InstallmentPlan;
use App\Models\ManualJournal;
use App\Models\OpeningStock;
use App\Models\PurchaseInvoice;
use App\Models\Reservation;
use App\Models\ReturnDocument;
use App\Models\SalesInvoice;
use App\Models\Vehicle;
use App\Models\Voucher;
use App\Services\Currency\ExchangeRateService;
use App\Support\Settings;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Spatie\Backup\Events\BackupHasFailed;
use Spatie\Backup\Events\CleanupHasFailed;
use Spatie\Backup\Events\UnhealthyBackupWasFound;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(Settings::class);
        $this->app->scoped(ExchangeRateService::class);
    }

    public function boot(): void
    {
        // Stable aliases for polymorphic references (journal sources, voucher references, returns).
        Relation::morphMap([
            'vehicle' => Vehicle::class,
            'purchase_invoice' => PurchaseInvoice::class,
            'expense' => Expense::class,
            'voucher' => Voucher::class,
            'return' => ReturnDocument::class,
            'sales_invoice' => SalesInvoice::class,
            'reservation' => Reservation::class,
            'installment_plan' => InstallmentPlan::class,
            'manual_journal' => ManualJournal::class,
            'opening_stock' => OpeningStock::class,
        ]);

        Model::preventLazyLoading(! $this->app->isProduction());
        Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());

        Event::listen(Login::class, [LogAuthenticationEvents::class, 'login']);
        Event::listen(Failed::class, [LogAuthenticationEvents::class, 'failed']);
        Event::listen(Logout::class, [LogAuthenticationEvents::class, 'logout']);

        Event::listen(BackupHasFailed::class, [NotifyBackupProblems::class, 'failed']);
        Event::listen(CleanupHasFailed::class, [NotifyBackupProblems::class, 'cleanupFailed']);
        Event::listen(UnhealthyBackupWasFound::class, [NotifyBackupProblems::class, 'unhealthy']);
    }
}
