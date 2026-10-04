<?php declare(strict_types = 1);

// odsl-C:\laravel project\cars\app\Support\Money.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Support\Money
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.73.0.5-8.2.12-d0a446b4ff0352c34355301763bd1a36aba1682f2b4f949f399f89cee7718016',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Support\\Money',
        'filename' => 'C:/laravel project/cars/app/Support/Money.php',
      ),
    ),
    'namespace' => 'App\\Support',
    'name' => 'App\\Support\\Money',
    'shortName' => 'Money',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * All money math goes through here. Floats are rejected at runtime: PHP\'s coercive
 * typing would otherwise silently turn 0.1 into "0.1" through a string parameter.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 105,
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
      'SCALE' => 
      array (
        'declaringClassName' => 'App\\Support\\Money',
        'implementingClassName' => 'App\\Support\\Money',
        'name' => 'SCALE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '3',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 40,
            'startFilePos' => 338,
            'endTokenPos' => 40,
            'endFilePos' => 338,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 27,
      ),
      'RATE_SCALE' => 
      array (
        'declaringClassName' => 'App\\Support\\Money',
        'implementingClassName' => 'App\\Support\\Money',
        'name' => 'RATE_SCALE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '6',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 51,
            'startFilePos' => 372,
            'endTokenPos' => 51,
            'endFilePos' => 372,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 32,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'of' => 
      array (
        'name' => 'of',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 31,
            'endColumn' => 42,
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
            'name' => 'Brick\\Math\\BigDecimal',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Parse an amount and normalise it to 3 decimals (HALF_UP).
 *
 * @param  BigDecimal|string|int|null  $value
 */',
        'startLine' => 24,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Support',
        'declaringClassName' => 'App\\Support\\Money',
        'implementingClassName' => 'App\\Support\\Money',
        'currentClassName' => 'App\\Support\\Money',
        'aliasName' => NULL,
      ),
      'rate' => 
      array (
        'name' => 'rate',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 33,
            'endColumn' => 44,
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
            'name' => 'Brick\\Math\\BigDecimal',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  BigDecimal|string|int  $value
 */',
        'startLine' => 36,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Support',
        'declaringClassName' => 'App\\Support\\Money',
        'implementingClassName' => 'App\\Support\\Money',
        'currentClassName' => 'App\\Support\\Money',
        'aliasName' => NULL,
      ),
      'zero' => 
      array (
        'name' => 'zero',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Brick\\Math\\BigDecimal',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 41,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Support',
        'declaringClassName' => 'App\\Support\\Money',
        'implementingClassName' => 'App\\Support\\Money',
        'currentClassName' => 'App\\Support\\Money',
        'aliasName' => NULL,
      ),
      'toBase' => 
      array (
        'name' => 'toBase',
        'parameters' => 
        array (
          'amount' => 
          array (
            'name' => 'amount',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 35,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'rate' => 
          array (
            'name' => 'rate',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 52,
            'endLine' => 52,
            'startColumn' => 50,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Brick\\Math\\BigDecimal',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert an amount in a foreign currency to the base currency.
 *
 * @param  BigDecimal|string|int  $amount
 * @param  BigDecimal|string|int  $rate
 */',
        'startLine' => 52,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Support',
        'declaringClassName' => 'App\\Support\\Money',
        'implementingClassName' => 'App\\Support\\Money',
        'currentClassName' => 'App\\Support\\Money',
        'aliasName' => NULL,
      ),
      'sum' => 
      array (
        'name' => 'sum',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'iterable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 62,
            'endLine' => 62,
            'startColumn' => 32,
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
            'name' => 'Brick\\Math\\BigDecimal',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  iterable<BigDecimal|string|int>  $values
 */',
        'startLine' => 62,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Support',
        'declaringClassName' => 'App\\Support\\Money',
        'implementingClassName' => 'App\\Support\\Money',
        'currentClassName' => 'App\\Support\\Money',
        'aliasName' => NULL,
      ),
      'format' => 
      array (
        'name' => 'format',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 78,
            'endLine' => 78,
            'startColumn' => 35,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'decimals' => 
          array (
            'name' => 'decimals',
            'default' => 
            array (
              'code' => 'self::SCALE',
              'attributes' => 
              array (
                'startLine' => 78,
                'endLine' => 78,
                'startTokenPos' => 369,
                'startFilePos' => 2074,
                'endTokenPos' => 371,
                'endFilePos' => 2084,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 78,
            'endLine' => 78,
            'startColumn' => 49,
            'endColumn' => 75,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Display format: thousands separator and fixed decimals, e.g. 12,500.000
 *
 * @param  BigDecimal|string|int|null  $value
 */',
        'startLine' => 78,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Support',
        'declaringClassName' => 'App\\Support\\Money',
        'implementingClassName' => 'App\\Support\\Money',
        'currentClassName' => 'App\\Support\\Money',
        'aliasName' => NULL,
      ),
      'decimal' => 
      array (
        'name' => 'decimal',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 37,
            'endColumn' => 48,
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
            'name' => 'Brick\\Math\\BigDecimal',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 93,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'App\\Support',
        'declaringClassName' => 'App\\Support\\Money',
        'implementingClassName' => 'App\\Support\\Money',
        'currentClassName' => 'App\\Support\\Money',
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