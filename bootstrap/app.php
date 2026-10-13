<?php

use App\Http\Middleware\AuthorizeAny;
use App\Http\Middleware\EnsureFeatureEnabled;
use App\Http\Middleware\EnsureUserIsActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Behind a load balancer / reverse proxy (most cloud hosts) the request reaches PHP over HTTP:
        // trust its X-Forwarded-* headers so links (Livewire included) stay https. TRUSTED_PROXIES narrows it.
        $middleware->trustProxies(at: env('TRUSTED_PROXIES', '*'));
        $middleware->web(append: [EnsureUserIsActive::class]);
        $middleware->alias(['can_any' => AuthorizeAny::class, 'feature' => EnsureFeatureEnabled::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // A page left open past the session lifetime (419): never show "Page expired".
        // Logging out simply ends the session; anything else goes back to sign in again.
        // (Laravel hands the callbacks the 419 HttpException it makes of TokenMismatchException.)
        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if ($e->getStatusCode() !== 419 || $request->expectsJson()) {
                return null;
            }

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('status', $request->routeIs('logout') ? null : __('app.auth.session_expired'));
        });
    })->create();
