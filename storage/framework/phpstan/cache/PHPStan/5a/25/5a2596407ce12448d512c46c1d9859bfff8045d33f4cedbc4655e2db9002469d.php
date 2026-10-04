<?php declare(strict_types = 1);

// odsl-C:\laravel project\cars\app\Services\Accounting\JournalWriteGuard.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Services\Accounting\JournalWriteGuard
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.73.0.5-8.2.12-11594dace61195c90c92cacd9da925f9a557603d441d05a5fac2d79ad62299f1',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Services\\Accounting\\JournalWriteGuard',
        'filename' => 'C:/laravel project/cars/app/Services/Accounting/JournalWriteGuard.php',
      ),
    ),
    'namespace' => 'App\\Services\\Accounting',
    'name' => 'App\\Services\\Accounting\\JournalWriteGuard',
    'shortName' => 'JournalWriteGuard',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Enforces the golden rule: only PostingService writes journal_entries / journal_lines.
 * The models call assertAllowed() on save; PostingService opens the gate around its writes.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 39,
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
      'depth' => 
      array (
        'declaringClassName' => 'App\\Services\\Accounting\\JournalWriteGuard',
        'implementingClassName' => 'App\\Services\\Accounting\\JournalWriteGuard',
        'name' => 'depth',
        'modifiers' => 20,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 37,
            'startFilePos' => 374,
            'endTokenPos' => 37,
            'endFilePos' => 374,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 34,
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
      'allow' => 
      array (
        'name' => 'allow',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Closure',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 34,
            'endColumn' => 50,
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
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @template T
 *
 * @param  Closure(): T  $callback
 * @return T
 */',
        'startLine' => 22,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Services\\Accounting',
        'declaringClassName' => 'App\\Services\\Accounting\\JournalWriteGuard',
        'implementingClassName' => 'App\\Services\\Accounting\\JournalWriteGuard',
        'currentClassName' => 'App\\Services\\Accounting\\JournalWriteGuard',
        'aliasName' => NULL,
      ),
      'assertAllowed' => 
      array (
        'name' => 'assertAllowed',
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
        'startLine' => 33,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'App\\Services\\Accounting',
        'declaringClassName' => 'App\\Services\\Accounting\\JournalWriteGuard',
        'implementingClassName' => 'App\\Services\\Accounting\\JournalWriteGuard',
        'currentClassName' => 'App\\Services\\Accounting\\JournalWriteGuard',
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