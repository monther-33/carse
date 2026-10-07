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
use App\Models\VehicleOwnership;
use App\Models\Voucher;
use App\Services\Currency\ExchangeRateService;
use App\Support\BackupDestinations;
use App\Support\Features;
use App\Support\PermissionLocks;
use App\Support\Settings;
use App\Validation\Validator as AppValidator;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Spatie\Backup\Config\Config as BackupConfig;
use Spatie\Backup\Events\BackupHasFailed;
use Spatie\Backup\Events\CleanupHasFailed;
use Spatie\Backup\Events\UnhealthyBackupWasFound;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(Settings::class);
        $this->app->scoped(ExchangeRateService::class);
        $this->app->scoped(PermissionLocks::class);
        $this->app->scoped(Features::class);
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
            'vehicle_ownership' => VehicleOwnership::class,
        ]);

        // Arabic field names in validation messages, however the field is nested (App\Validation\Validator).
        Validator::resolver(fn ($translator, $data, $rules, $messages, $attributes) => new AppValidator($translator, $data, $rules, $messages, $attributes));

        // Optional second backup folder chosen by the admin (App\Support\BackupDestinations), added
        // just before the backup package builds its configuration: backups, cleanup and monitoring
        // from the scheduler, the command line or the screen all see it.
        $this->app->beforeResolving(BackupConfig::class, fn () => app(BackupDestinations::class)->register());

        // @feature('reservations') ... @endfeature
        Blade::if('feature', fn (string $feature) => app(Features::class)->enabled($feature));

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
