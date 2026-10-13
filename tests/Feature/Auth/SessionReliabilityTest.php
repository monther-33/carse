<?php

use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\URL;
use Livewire\Volt\Volt;

it('logs out cleanly even when the session already expired', function () {
    $this->post(route('logout'))->assertRedirect(route('login'));
    $this->assertGuest();
});

it('logs out and lands on the sign-in page', function () {
    $this->actingAs(userWithRole('admin'));

    $this->post(route('logout'))->assertRedirect(route('login'));
    $this->assertGuest();
});

it('turns an expired page (419) into the sign-in page instead of an error', function (string $uri, ?string $message) {
    $request = Request::create($uri, 'POST');
    $request->setLaravelSession(app('session.store'));
    app()->instance('request', $request);
    $request->setRouteResolver(fn () => rescue(fn () => app('router')->getRoutes()->match($request), null, false));

    $response = app(ExceptionHandler::class)->render($request, new TokenMismatchException);

    expect($response->getStatusCode())->toBe(302)
        ->and($response->headers->get('Location'))->toBe(route('login'))
        ->and(session('status'))->toBe($message === null ? null : __($message));
})->with([
    'logout' => ['/logout', null],
    'any other form' => ['/vouchers', 'app.auth.session_expired'],
]);

it('signs in with a full page load to the dashboard', function () {
    $user = User::factory()->create(['username' => 'tester', 'password' => 'secret-123']);
    $user->assignRole('admin');

    Volt::test('pages.auth.login')
        ->set('form.username', 'tester')
        ->set('form.password', 'secret-123')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticatedAs($user);
});

it('builds https links when the site is served over https', function () {
    config(['app.url' => 'https://cars.example.ly']);
    (new AppServiceProvider(app()))->boot();

    expect(url('/dashboard'))->toStartWith('https://');

    URL::forceScheme('http');
});
