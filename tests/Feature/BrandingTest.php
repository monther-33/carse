<?php

use App\Livewire\Settings\Index as SettingsIndex;
use App\Support\Branding;
use App\Support\Settings;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('the sign-in page shows the showroom, not Laravel, and an emblem until a logo is uploaded', function () {
    app(Settings::class)->set(['company.name' => 'معرض النجمة للسيارات', 'company.logo' => null, 'company.cover' => null]);

    $this->get('/login')->assertOk()
        ->assertSee('معرض النجمة للسيارات')
        ->assertSee(__('app.auth.welcome'))
        ->assertDontSee('laravel', false)
        ->assertSee('ن');   // emblem initial (the name without "معرض")

    expect(app(Branding::class)->initial())->toBe('ن');
});

test('the logo and the showroom photo are uploaded in settings and shown on sign-in and the dashboard', function () {
    Storage::fake('public');
    $this->actingAs(userWithRole('admin'));

    Livewire::test(SettingsIndex::class)
        ->set('logo', UploadedFile::fake()->image('logo.png', 200, 200))
        ->set('cover', UploadedFile::fake()->image('showroom.jpg', 1600, 900))
        ->call('save')
        ->assertHasNoErrors();

    $settings = app(Settings::class);
    $settings->flush();
    $logo = '/storage/'.$settings->get('company.logo');
    $cover = '/storage/'.$settings->get('company.cover');
    expect(Storage::disk('public')->exists($settings->get('company.cover')))->toBeTrue();

    $this->get(route('dashboard'))->assertSee($logo)->assertSee($cover);
    auth()->logout();
    $this->get('/login')->assertSee($logo)->assertSee($cover);

    // Removing the photo goes back to the gradient.
    $this->actingAs(userWithRole('admin'));
    Livewire::test(SettingsIndex::class)->set('company_cover', null)->call('save');
    $settings->flush();
    expect(app(Branding::class)->coverUrl())->toBeNull();
});
