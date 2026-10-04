<?php declare(strict_types = 1);

// odsl-C:\laravel project\cars\app\Models\ExchangeRate.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Models\ExchangeRate
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.73.0.5-8.2.12-502ee35e22cb07222f618a00aa178e787dd433edf571531265223b380666171a',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Models\\ExchangeRate',
        'filename' => 'C:/laravel project/cars/app/Models/ExchangeRate.php',
      ),
    ),
    'namespace' => 'App\\Models',
    'name' => 'App\\Models\\ExchangeRate',
    'shortName' => 'ExchangeRate',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @property int $id
 * @property int $currency_id
 * @property string $rate
 * @property Carbon $date
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 39,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'App\\Models\\Concerns\\Auditable',
      1 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      2 => 'App\\Models\\Concerns\\HasUserstamps',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Models\\ExchangeRate',
        'implementingClassName' => 'App\\Models\\ExchangeRate',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'currency_id\', \'rate\', \'date\']',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 24,
            'startTokenPos' => 73,
            'startFilePos' => 596,
            'endTokenPos' => 81,
            'endFilePos' => 626,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 58,
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
        'startLine' => 26,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\ExchangeRate',
        'implementingClassName' => 'App\\Models\\ExchangeRate',
        'currentClassName' => 'App\\Models\\ExchangeRate',
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
        'startLine' => 35,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\ExchangeRate',
        'implementingClassName' => 'App\\Models\\ExchangeRate',
        'currentClassName' => 'App\\Models\\ExchangeRate',
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