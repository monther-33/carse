<?php

/**
 * Every key in lang/ar must exist in lang/en and vice versa.
 */
function flattenKeys(array $array, string $prefix = ''): array
{
    $keys = [];
    foreach ($array as $key => $value) {
        $full = $prefix === '' ? (string) $key : $prefix.'.'.$key;
        $keys = is_array($value) && $value !== [] ? [...$keys, ...flattenKeys($value, $full)] : [...$keys, $full];
    }

    return $keys;
}

test('arabic and english translation files have the same keys', function (string $file) {
    $ar = flattenKeys(require __DIR__."/../../lang/ar/{$file}.php");
    $en = flattenKeys(require __DIR__."/../../lang/en/{$file}.php");

    expect(array_values(array_diff($ar, $en)))->toBe([], "missing in en/{$file}")
        ->and(array_values(array_diff($en, $ar)))->toBe([], "missing in ar/{$file}");
})->with(['app', 'accounting', 'enums', 'permissions', 'documents', 'vehicles', 'purchases', 'expenses', 'vouchers', 'parties', 'reports', 'sales', 'reservations', 'installments', 'commissions', 'print', 'journals', 'dashboard']);

test('every configured permission has a label in both languages', function () {
    $config = require __DIR__.'/../../config/permissions.php';

    foreach (['ar', 'en'] as $locale) {
        $labels = require __DIR__."/../../lang/{$locale}/permissions.php";

        foreach ($config['permissions'] as $module => $actions) {
            expect($labels['modules'])->toHaveKey($module);
            foreach ($actions as $action) {
                expect(isset($labels['actions'][$module][$action]))->toBeTrue("{$locale}: {$module}.{$action}");
            }
        }
        foreach (array_keys($config['roles']) as $role) {
            expect($labels['roles'])->toHaveKey($role);
        }
    }
});
