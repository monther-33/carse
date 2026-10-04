<?php declare(strict_types = 1);

// odsl-C:\laravel project\cars\app\Actions\Accounting\CloseFiscalPeriod.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Actions\Accounting\CloseFiscalPeriod
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.73.0.5-8.2.12-4160d7cf7a93aad8211700a2ff2f17c6b1fd8830a8d96ca1cbc1192958f6d95f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Actions\\Accounting\\CloseFiscalPeriod',
        'filename' => 'C:/laravel project/cars/app/Actions/Accounting/CloseFiscalPeriod.php',
      ),
    ),
    'namespace' => 'App\\Actions\\Accounting',
    'name' => 'App\\Actions\\Accounting\\CloseFiscalPeriod',
    'shortName' => 'CloseFiscalPeriod',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Closing takes an exclusive lock on the period row, so it waits for any posting
 * in flight (PostingService holds a shared lock) and blocks new ones afterwards.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 34,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
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
    ),
    'immediateMethods' => 
    array (
      'handle' => 
      array (
        'name' => 'handle',
        'parameters' => 
        array (
          'period' => 
          array (
            'name' => 'period',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Models\\FiscalPeriod',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 16,
            'endLine' => 16,
            'startColumn' => 28,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Models\\FiscalPeriod',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 16,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Actions\\Accounting',
        'declaringClassName' => 'App\\Actions\\Accounting\\CloseFiscalPeriod',
        'implementingClassName' => 'App\\Actions\\Accounting\\CloseFiscalPeriod',
        'currentClassName' => 'App\\Actions\\Accounting\\CloseFiscalPeriod',
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