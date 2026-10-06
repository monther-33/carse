<?php

use App\Support\Settings;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('the manifest makes the system installable with the showroom name, icons and shortcuts', function () {
    app(Settings::class)->set(['company.name' => 'معرض النجمة للسيارات']);

    $manifest = $this->get('/manifest.webmanifest')->assertOk()
        ->assertHeader('Content-Type', 'application/manifest+json')
        ->json();

    expect($manifest['name'])->toBe('معرض النجمة للسيارات')
        ->and($manifest['display'])->toBe('standalone')
        ->and($manifest['start_url'])->toBe('/dashboard')
        ->and($manifest['dir'])->toBe('rtl')
        ->and(collect($manifest['icons'])->pluck('sizes')->unique()->values()->all())->toBe(['192x192', '512x512'])
        ->and(collect($manifest['icons'])->pluck('purpose')->unique()->values()->all())->toBe(['any', 'maskable'])
        ->and($manifest['shortcuts'])->toHaveCount(3);
});

test('icons are real PNGs at their size, drawn from the logo when there is one', function () {
    Storage::fake('public');
    app(Settings::class)->set(['company.logo' => null]);

    foreach ([192, 512] as $size) {
        $png = $this->get("/pwa/icon-{$size}-maskable.png")->assertOk()->assertHeader('Content-Type', 'image/png')->getContent();
        expect(getimagesizefromstring($png)[0])->toBe($size);
    }

    $path = UploadedFile::fake()->image('logo.png', 300, 120)->store('branding', 'public');
    app(Settings::class)->set(['company.logo' => $path]);
    $png = $this->get('/pwa/icon-512-any.png')->assertOk()->getContent();
    expect(getimagesizefromstring($png)[0])->toBe(512);

    $this->get('/pwa/icon-100-any.png')->assertNotFound();
});

test('the offline page needs no sign-in and every layout links the manifest and the service worker', function () {
    $this->get('/offline')->assertOk()->assertSee(__('pwa.offline_title'));
    $this->get('/login')->assertSee('/manifest.webmanifest', false)->assertSee("register('/sw.js')", false);

    $this->actingAs(userWithRole('sales'))->get(route('dashboard'))
        ->assertSee('/manifest.webmanifest', false)->assertSee(__('pwa.install'));

    $sw = file_get_contents(public_path('sw.js'));
    expect($sw)->toContain("request.method !== 'GET'")->toContain("startsWith('/build/')")->toContain('OFFLINE_URL');
});

test('the one-time install hint is on the sign-in page and inside the app', function () {
    $this->get('/login')->assertSee('data-pwa-hint', false)->assertSee(__('pwa.ios_step2'))->assertSee('pwa-install-hint-v1', false);
    $this->actingAs(userWithRole('cashier'))->get(route('dashboard'))->assertSee('data-pwa-hint', false);
});
