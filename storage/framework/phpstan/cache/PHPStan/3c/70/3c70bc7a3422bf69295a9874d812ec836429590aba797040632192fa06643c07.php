<?php declare(strict_types = 1);

// odsl-C:\laravel project\cars\app\Models\JournalLine.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Models\JournalLine
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.73.0.5-8.2.12-4d0db4e250ce1d88ab5261015760ef9a94097adbab90de00b042e3fd6222fa80',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Models\\JournalLine',
        'filename' => 'C:/laravel project/cars/app/Models/JournalLine.php',
      ),
    ),
    'namespace' => 'App\\Models',
    'name' => 'App\\Models\\JournalLine',
    'shortName' => 'JournalLine',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Written exclusively by App\\Services\\Accounting\\PostingService. Never updated or deleted.
 * Decimal columns are strings (decimal:N casts); use App\\Support\\Money for math.
 *
 * @property int $id
 * @property int $entry_id
 * @property int $line_no
 * @property int $account_id
 * @property int|null $party_id
 * @property int|null $vehicle_id
 * @property string $debit
 * @property string $credit
 * @property int $currency_id
 * @property string $rate
 * @property string $debit_base
 * @property string $credit_base
 * @property string|null $memo
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 28,
    'endLine' => 70,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Models\\JournalLine',
        'implementingClassName' => 'App\\Models\\JournalLine',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'entry_id\', \'line_no\', \'account_id\', \'party_id\', \'vehicle_id\', \'debit\', \'credit\', \'currency_id\', \'rate\', \'debit_base\', \'credit_base\', \'memo\']',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 33,
            'startTokenPos' => 45,
            'startFilePos' => 856,
            'endTokenPos' => 83,
            'endFilePos' => 1020,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'casts' => 
      array (
        'name' => 'casts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 35,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalLine',
        'implementingClassName' => 'App\\Models\\JournalLine',
        'currentClassName' => 'App\\Models\\JournalLine',
        'aliasName' => NULL,
      ),
      'booted' => 
      array (
        'name' => 'booted',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 46,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalLine',
        'implementingClassName' => 'App\\Models\\JournalLine',
        'currentClassName' => 'App\\Models\\JournalLine',
        'aliasName' => NULL,
      ),
      'entry' => 
      array (
        'name' => 'entry',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return BelongsTo<JournalEntry, $this> */',
        'startLine' => 54,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalLine',
        'implementingClassName' => 'App\\Models\\JournalLine',
        'currentClassName' => 'App\\Models\\JournalLine',
        'aliasName' => NULL,
      ),
      'account' => 
      array (
        'name' => 'account',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return BelongsTo<Account, $this> */',
        'startLine' => 60,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalLine',
        'implementingClassName' => 'App\\Models\\JournalLine',
        'currentClassName' => 'App\\Models\\JournalLine',
        'aliasName' => NULL,
      ),
      'currency' => 
      array (
        'name' => 'currency',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return BelongsTo<Currency, $this> */',
        'startLine' => 66,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalLine',
        'implementingClassName' => 'App\\Models\\JournalLine',
        'currentClassName' => 'App\\Models\\JournalLine',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));