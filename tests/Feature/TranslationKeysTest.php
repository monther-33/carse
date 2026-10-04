<?php

use Illuminate\Support\Facades\Lang;
use Symfony\Component\Finder\Finder;

/**
 * Every literal key passed to __() / trans() / Lang in app/ and resources/views must exist
 * in lang/ar and lang/en. Dynamic keys (built with concatenation) are covered by the enum
 * and permission label tests.
 */
test('every translation key used in code exists in both languages', function () {
    $files = Finder::create()->files()->in([app_path(), resource_path('views')])->name(['*.php']);
    $used = [];

    foreach ($files as $file) {
        preg_match_all("/(?:__|trans|Lang::has|Exception::make)\\(\\s*'([a-z_]+\\.[a-z0-9_.]+)'/", $file->getContents(), $matches);
        foreach ($matches[1] as $key) {
            if (! str_ends_with($key, '.')) {
                $used[$key][] = $file->getRelativePathname();
            }
        }
    }

    $missing = [];
    foreach (array_keys($used) as $key) {
        foreach (['ar', 'en'] as $locale) {
            if (! Lang::hasForLocale($key, $locale)) {
                $missing[] = "{$locale}: {$key}";
            }
        }
    }

    sort($missing);
    expect($missing)->toBe([]);
});

test('every enum case has a label in both languages', function () {
    $enums = Finder::create()->files()->in(app_path('Enums'))->name('*.php');

    $missing = [];
    foreach ($enums as $file) {
        $class = 'App\\Enums\\'.$file->getBasename('.php');
        if (! method_exists($class, 'label') || ! method_exists($class, 'cases')) {
            continue;
        }
        foreach ($class::cases() as $case) {
            foreach (['ar', 'en'] as $locale) {
                app()->setLocale($locale);
                $label = $case->label();
                if (str_starts_with($label, 'enums.')) {
                    $missing[] = "{$locale}: {$label}";
                }
            }
        }
    }
    app()->setLocale('ar');

    expect($missing)->toBe([]);
});
