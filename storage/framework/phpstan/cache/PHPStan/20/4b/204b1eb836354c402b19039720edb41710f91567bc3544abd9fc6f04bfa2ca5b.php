<?php declare(strict_types = 1);

// odsl-C:\laravel project\cars\app\Models\JournalEntry.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Models\JournalEntry
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.73.0.5-8.2.12-69876646da9f8719795b33ea0197834db10d885d3217c2ef426c90c9101ec62c',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Models\\JournalEntry',
        'filename' => 'C:/laravel project/cars/app/Models/JournalEntry.php',
      ),
    ),
    'namespace' => 'App\\Models',
    'name' => 'App\\Models\\JournalEntry',
    'shortName' => 'JournalEntry',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Written exclusively by App\\Services\\Accounting\\PostingService. Never deleted.
 *
 * @property int $id
 * @property int $branch_id
 * @property string $number
 * @property Carbon $date
 * @property string $description
 * @property string|null $source_type
 * @property int|null $source_id
 * @property DocumentStatus $status
 * @property int $period_id
 * @property int|null $reversed_by_id
 * @property int|null $reverses_id
 * @property string $total_base
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 32,
    'endLine' => 96,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'App\\Models\\Concerns\\Auditable',
      1 => 'App\\Models\\Concerns\\HasUserstamps',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Models\\JournalEntry',
        'implementingClassName' => 'App\\Models\\JournalEntry',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'branch_id\', \'number\', \'date\', \'description\', \'source_type\', \'source_id\', \'status\', \'period_id\', \'reversed_by_id\', \'reverses_id\', \'total_base\']',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 39,
            'startTokenPos' => 83,
            'startFilePos' => 1038,
            'endTokenPos' => 118,
            'endFilePos' => 1204,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 39,
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
        'startLine' => 41,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalEntry',
        'implementingClassName' => 'App\\Models\\JournalEntry',
        'currentClassName' => 'App\\Models\\JournalEntry',
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
        'startLine' => 50,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalEntry',
        'implementingClassName' => 'App\\Models\\JournalEntry',
        'currentClassName' => 'App\\Models\\JournalEntry',
        'aliasName' => NULL,
      ),
      'lines' => 
      array (
        'name' => 'lines',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return HasMany<JournalLine, $this> */',
        'startLine' => 57,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalEntry',
        'implementingClassName' => 'App\\Models\\JournalEntry',
        'currentClassName' => 'App\\Models\\JournalEntry',
        'aliasName' => NULL,
      ),
      'source' => 
      array (
        'name' => 'source',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return MorphTo<Model, $this> */',
        'startLine' => 63,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalEntry',
        'implementingClassName' => 'App\\Models\\JournalEntry',
        'currentClassName' => 'App\\Models\\JournalEntry',
        'aliasName' => NULL,
      ),
      'branch' => 
      array (
        'name' => 'branch',
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
        'docComment' => '/** @return BelongsTo<Branch, $this> */',
        'startLine' => 69,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalEntry',
        'implementingClassName' => 'App\\Models\\JournalEntry',
        'currentClassName' => 'App\\Models\\JournalEntry',
        'aliasName' => NULL,
      ),
      'period' => 
      array (
        'name' => 'period',
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
        'docComment' => '/** @return BelongsTo<FiscalPeriod, $this> */',
        'startLine' => 75,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalEntry',
        'implementingClassName' => 'App\\Models\\JournalEntry',
        'currentClassName' => 'App\\Models\\JournalEntry',
        'aliasName' => NULL,
      ),
      'reversedBy' => 
      array (
        'name' => 'reversedBy',
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
        'startLine' => 81,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalEntry',
        'implementingClassName' => 'App\\Models\\JournalEntry',
        'currentClassName' => 'App\\Models\\JournalEntry',
        'aliasName' => NULL,
      ),
      'reverses' => 
      array (
        'name' => 'reverses',
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
        'startLine' => 87,
        'endLine' => 90,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalEntry',
        'implementingClassName' => 'App\\Models\\JournalEntry',
        'currentClassName' => 'App\\Models\\JournalEntry',
        'aliasName' => NULL,
      ),
      'isReversed' => 
      array (
        'name' => 'isReversed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 92,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\JournalEntry',
        'implementingClassName' => 'App\\Models\\JournalEntry',
        'currentClassName' => 'App\\Models\\JournalEntry',
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