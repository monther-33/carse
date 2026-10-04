<?php

namespace App\Providers;

use App\Listeners\LogAuthenticationEvents;
use App\Services\Currency\ExchangeRateService;
use App\Support\Settings;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(Settings::class);
        $this->app->scoped(ExchangeRateService::class);
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());
        Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());

        Event::listen(Login::class, [LogAuthenticationEvents::class, 'login']);
        Event::listen(Failed::class, [LogAuthenticationEvents::class, 'failed']);
        Event::listen(Logout::class, [LogAuthenticationEvents::class, 'logout']);
    }
}
