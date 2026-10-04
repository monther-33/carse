<?php declare(strict_types = 1);

// osfsl-C:/laravel project/cars/vendor/composer/../brick/math/src/BigNumber.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Brick\Math\BigNumber
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-f072704b198c38dd8db967c01bc9daf20107deb71a11011343a564a1f1375e03-8.2.12-6.73.0.5',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Brick\\Math\\BigNumber',
        'filename' => 'C:/laravel project/cars/vendor/composer/../brick/math/src/BigNumber.php',
      ),
    ),
    'namespace' => 'Brick\\Math',
    'name' => 'Brick\\Math\\BigNumber',
    'shortName' => 'BigNumber',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 65600,
    'docComment' => '/**
 * Base class for arbitrary-precision numbers.
 *
 * This class is sealed: it is part of the public API but should not be subclassed in userland.
 * Protected methods may change in any version.
 *
 * @phpstan-sealed BigInteger|BigDecimal|BigRational
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 43,
    'endLine' => 712,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'JsonSerializable',
      1 => 'Stringable',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'PARSE_REGEXP_NUMERICAL' => 
      array (
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'name' => 'PARSE_REGEXP_NUMERICAL',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/^\' . \'(?<sign>[\\-\\+])?\' . \'(?<integral>[0-9]+)?\' . \'(?<point>\\.)?\' . \'(?<fractional>[0-9]+)?\' . \'(?:[eE](?<exponent>[\\-\\+]?[0-9]+))?\' . \'$/\'',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 55,
            'startTokenPos' => 203,
            'startFilePos' => 1225,
            'endTokenPos' => 227,
            'endFilePos' => 1414,
          ),
        ),
        'docComment' => '/**
 * The regular expression used to parse integer or decimal numbers.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 13,
      ),
      'PARSE_REGEXP_RATIONAL' => 
      array (
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'name' => 'PARSE_REGEXP_RATIONAL',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/^\' . \'(?<sign>[\\-\\+])?\' . \'(?<numerator>[0-9]+)\' . \'\\/\' . \'(?<denominator>[0-9]+)\' . \'$/\'',
          'attributes' => 
          array (
            'startLine' => 61,
            'endLine' => 66,
            'startTokenPos' => 240,
            'startFilePos' => 1546,
            'endTokenPos' => 260,
            'endFilePos' => 1676,
          ),
        ),
        'docComment' => '/**
 * The regular expression used to parse rational numbers.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 13,
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
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 90,
            'endLine' => 90,
            'startColumn' => 37,
            'endColumn' => 69,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a BigNumber of the given value.
 *
 * When of() is called on BigNumber, the concrete return type is dependent on the given value, with the following
 * rules:
 *
 * - BigNumber instances are returned as is
 * - integer numbers are returned as BigInteger
 * - floating point numbers are converted to a string then parsed as such (deprecated, will be removed in 0.15)
 * - strings containing a `/` character are returned as BigRational
 * - strings containing a `.` character or using an exponential notation are returned as BigDecimal
 * - strings containing only digits with an optional leading `+` or `-` sign are returned as BigInteger
 *
 * When of() is called on BigInteger, BigDecimal, or BigRational, the resulting number is converted to an instance
 * of the subclass when possible; otherwise a RoundingNecessaryException exception is thrown.
 *
 * @throws NumberFormatException      If the format of the number is not valid.
 * @throws DivisionByZeroException    If the value represents a rational number with a denominator of zero.
 * @throws RoundingNecessaryException If the value cannot be converted to an instance of the subclass without rounding.
 *
 * @pure
 */',
        'startLine' => 90,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 49,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'ofNullable' => 
      array (
        'name' => 'ofNullable',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  4 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 116,
            'endLine' => 116,
            'startColumn' => 45,
            'endColumn' => 82,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'static',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a BigNumber of the given value, or returns null if the input is null.
 *
 * Behaves like of() for non-null values.
 *
 * @see BigNumber::of()
 *
 * @throws NumberFormatException      If the format of the number is not valid.
 * @throws DivisionByZeroException    If the value represents a rational number with a denominator of zero.
 * @throws RoundingNecessaryException If the value cannot be converted to an instance of the subclass without rounding.
 *
 * @pure
 */',
        'startLine' => 116,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 49,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'min' => 
      array (
        'name' => 'min',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 140,
            'endLine' => 140,
            'startColumn' => 38,
            'endColumn' => 74,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the minimum of the given values.
 *
 * If several values are equal and minimal, the first one is returned.
 * This can affect the concrete return type when calling this method on BigNumber.
 *
 * @param BigNumber|int|float|string ...$values The numbers to compare. All the numbers must be convertible to an
 *                                              instance of the class this method is called on.
 *
 * @throws InvalidArgumentException If no values are given.
 * @throws MathException            If a number is not valid, or is not convertible to an instance of the class
 *                                  this method is called on.
 *
 * @pure
 */',
        'startLine' => 140,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 49,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'max' => 
      array (
        'name' => 'max',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 174,
            'endLine' => 174,
            'startColumn' => 38,
            'endColumn' => 74,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the maximum of the given values.
 *
 * If several values are equal and maximal, the first one is returned.
 * This can affect the concrete return type when calling this method on BigNumber.
 *
 * @param BigNumber|int|float|string ...$values The numbers to compare. All the numbers must be convertible to an
 *                                              instance of the class this method is called on.
 *
 * @throws InvalidArgumentException If no values are given.
 * @throws MathException            If a number is not valid, or is not convertible to an instance of the class
 *                                  this method is called on.
 *
 * @pure
 */',
        'startLine' => 174,
        'endLine' => 191,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 49,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
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
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 211,
            'endLine' => 211,
            'startColumn' => 38,
            'endColumn' => 74,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the sum of the given values.
 *
 * When called on BigNumber, sum() accepts any supported type and returns a result whose type is the widest among
 * the given values (BigInteger < BigDecimal < BigRational).
 *
 * When called on BigInteger, BigDecimal, or BigRational, sum() requires that all values can be converted to that
 * specific subclass, and returns a result of the same type.
 *
 * @param BigNumber|int|float|string ...$values The numbers to add. All the numbers must be convertible to an
 *                                              instance of the class this method is called on.
 *
 * @throws InvalidArgumentException If no values are given.
 * @throws MathException            If a number is not valid, or is not convertible to an instance of the class
 *                                  this method is called on.
 *
 * @pure
 */',
        'startLine' => 211,
        'endLine' => 228,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 49,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'isEqualTo' => 
      array (
        'name' => 'isEqualTo',
        'parameters' => 
        array (
          'that' => 
          array (
            'name' => 'that',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 237,
            'endLine' => 237,
            'startColumn' => 37,
            'endColumn' => 68,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Checks if this number is equal to the given one.
 *
 * @throws MathException If the given number is not valid.
 *
 * @pure
 */',
        'startLine' => 237,
        'endLine' => 240,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'isLessThan' => 
      array (
        'name' => 'isLessThan',
        'parameters' => 
        array (
          'that' => 
          array (
            'name' => 'that',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 249,
            'endLine' => 249,
            'startColumn' => 38,
            'endColumn' => 69,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Checks if this number is strictly less than the given one.
 *
 * @throws MathException If the given number is not valid.
 *
 * @pure
 */',
        'startLine' => 249,
        'endLine' => 252,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'isLessThanOrEqualTo' => 
      array (
        'name' => 'isLessThanOrEqualTo',
        'parameters' => 
        array (
          'that' => 
          array (
            'name' => 'that',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 261,
            'endLine' => 261,
            'startColumn' => 47,
            'endColumn' => 78,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Checks if this number is less than or equal to the given one.
 *
 * @throws MathException If the given number is not valid.
 *
 * @pure
 */',
        'startLine' => 261,
        'endLine' => 264,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'isGreaterThan' => 
      array (
        'name' => 'isGreaterThan',
        'parameters' => 
        array (
          'that' => 
          array (
            'name' => 'that',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 273,
            'endLine' => 273,
            'startColumn' => 41,
            'endColumn' => 72,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Checks if this number is strictly greater than the given one.
 *
 * @throws MathException If the given number is not valid.
 *
 * @pure
 */',
        'startLine' => 273,
        'endLine' => 276,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'isGreaterThanOrEqualTo' => 
      array (
        'name' => 'isGreaterThanOrEqualTo',
        'parameters' => 
        array (
          'that' => 
          array (
            'name' => 'that',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 285,
            'endLine' => 285,
            'startColumn' => 50,
            'endColumn' => 81,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Checks if this number is greater than or equal to the given one.
 *
 * @throws MathException If the given number is not valid.
 *
 * @pure
 */',
        'startLine' => 285,
        'endLine' => 288,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'isZero' => 
      array (
        'name' => 'isZero',
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
        'docComment' => '/**
 * Checks if this number equals zero.
 *
 * @pure
 */',
        'startLine' => 295,
        'endLine' => 298,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'isNegative' => 
      array (
        'name' => 'isNegative',
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
        'docComment' => '/**
 * Checks if this number is strictly negative.
 *
 * @pure
 */',
        'startLine' => 305,
        'endLine' => 308,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'isNegativeOrZero' => 
      array (
        'name' => 'isNegativeOrZero',
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
        'docComment' => '/**
 * Checks if this number is negative or zero.
 *
 * @pure
 */',
        'startLine' => 315,
        'endLine' => 318,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'isPositive' => 
      array (
        'name' => 'isPositive',
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
        'docComment' => '/**
 * Checks if this number is strictly positive.
 *
 * @pure
 */',
        'startLine' => 325,
        'endLine' => 328,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'isPositiveOrZero' => 
      array (
        'name' => 'isPositiveOrZero',
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
        'docComment' => '/**
 * Checks if this number is positive or zero.
 *
 * @pure
 */',
        'startLine' => 335,
        'endLine' => 338,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'abs' => 
      array (
        'name' => 'abs',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the absolute value of this number.
 *
 * @pure
 */',
        'startLine' => 345,
        'endLine' => 348,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'negated' => 
      array (
        'name' => 'negated',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the negated value of this number.
 *
 * @pure
 */',
        'startLine' => 355,
        'endLine' => 355,
        'startColumn' => 5,
        'endColumn' => 47,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'getSign' => 
      array (
        'name' => 'getSign',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the sign of this number.
 *
 * Returns -1 if the number is negative, 0 if zero, 1 if positive.
 *
 * @return -1|0|1
 *
 * @pure
 */',
        'startLine' => 366,
        'endLine' => 366,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'compareTo' => 
      array (
        'name' => 'compareTo',
        'parameters' => 
        array (
          'that' => 
          array (
            'name' => 'that',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 379,
            'endLine' => 379,
            'startColumn' => 40,
            'endColumn' => 71,
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
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Compares this number to the given one.
 *
 * Returns -1 if `$this` is lower than, 0 if equal to, 1 if greater than `$that`.
 *
 * @return -1|0|1
 *
 * @throws MathException If the number is not valid.
 *
 * @pure
 */',
        'startLine' => 379,
        'endLine' => 379,
        'startColumn' => 5,
        'endColumn' => 78,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'clamp' => 
      array (
        'name' => 'clamp',
        'parameters' => 
        array (
          'min' => 
          array (
            'name' => 'min',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 396,
            'endLine' => 396,
            'startColumn' => 33,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'max' => 
          array (
            'name' => 'max',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 396,
            'endLine' => 396,
            'startColumn' => 66,
            'endColumn' => 96,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Limits (clamps) this number between the given minimum and maximum values.
 *
 * If the number is lower than $min, returns $min.
 * If the number is greater than $max, returns $max.
 * Otherwise, returns this number unchanged.
 *
 * @param BigNumber|int|float|string $min The minimum. Must be convertible to an instance of the class this method is called on.
 * @param BigNumber|int|float|string $max The maximum. Must be convertible to an instance of the class this method is called on.
 *
 * @throws MathException            If min/max are not convertible to an instance of the class this method is called on.
 * @throws InvalidArgumentException If min is greater than max.
 *
 * @pure
 */',
        'startLine' => 396,
        'endLine' => 414,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'toBigInteger' => 
      array (
        'name' => 'toBigInteger',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Converts this number to a BigInteger.
 *
 * @throws RoundingNecessaryException If this number cannot be converted to a BigInteger without rounding.
 *
 * @pure
 */',
        'startLine' => 423,
        'endLine' => 423,
        'startColumn' => 5,
        'endColumn' => 56,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'toBigDecimal' => 
      array (
        'name' => 'toBigDecimal',
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
        'docComment' => '/**
 * Converts this number to a BigDecimal.
 *
 * @throws RoundingNecessaryException If this number cannot be converted to a BigDecimal without rounding.
 *
 * @pure
 */',
        'startLine' => 432,
        'endLine' => 432,
        'startColumn' => 5,
        'endColumn' => 56,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'toBigRational' => 
      array (
        'name' => 'toBigRational',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Brick\\Math\\BigRational',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Converts this number to a BigRational.
 *
 * @pure
 */',
        'startLine' => 439,
        'endLine' => 439,
        'startColumn' => 5,
        'endColumn' => 58,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'toScale' => 
      array (
        'name' => 'toScale',
        'parameters' => 
        array (
          'scale' => 
          array (
            'name' => 'scale',
            'default' => NULL,
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
            'startLine' => 453,
            'endLine' => 453,
            'startColumn' => 38,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'roundingMode' => 
          array (
            'name' => 'roundingMode',
            'default' => 
            array (
              'code' => '\\Brick\\Math\\RoundingMode::Unnecessary',
              'attributes' => 
              array (
                'startLine' => 453,
                'endLine' => 453,
                'startTokenPos' => 1462,
                'startFilePos' => 13828,
                'endTokenPos' => 1464,
                'endFilePos' => 13852,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Brick\\Math\\RoundingMode',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 453,
            'endLine' => 453,
            'startColumn' => 50,
            'endColumn' => 103,
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
            'name' => 'Brick\\Math\\BigDecimal',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Converts this number to a BigDecimal with the given scale, using rounding if necessary.
 *
 * @param int          $scale        The scale of the resulting `BigDecimal`. Must be non-negative.
 * @param RoundingMode $roundingMode An optional rounding mode, defaults to Unnecessary.
 *
 * @throws InvalidArgumentException   If the scale is negative.
 * @throws RoundingNecessaryException If RoundingMode::Unnecessary is used, and this number cannot be converted to
 *                                    the given scale without rounding.
 *
 * @pure
 */',
        'startLine' => 453,
        'endLine' => 453,
        'startColumn' => 5,
        'endColumn' => 117,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'toInt' => 
      array (
        'name' => 'toInt',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the exact value of this number as a native integer.
 *
 * If this number cannot be converted to a native integer without losing precision, an exception is thrown.
 * Note that the acceptable range for an integer depends on the platform and differs for 32-bit and 64-bit.
 *
 * @throws MathException If this number cannot be exactly converted to a native integer.
 *
 * @pure
 */',
        'startLine' => 465,
        'endLine' => 465,
        'startColumn' => 5,
        'endColumn' => 42,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'toFloat' => 
      array (
        'name' => 'toFloat',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'float',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns an approximation of this number as a floating-point value.
 *
 * Note that this method can discard information as the precision of a floating-point value
 * is inherently limited.
 *
 * If the number is greater than the largest representable floating point number, positive infinity is returned.
 * If the number is less than the smallest representable floating point number, negative infinity is returned.
 * This method never returns NaN.
 *
 * @pure
 */',
        'startLine' => 479,
        'endLine' => 479,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'toString' => 
      array (
        'name' => 'toString',
        'parameters' => 
        array (
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
 * Returns a string representation of this number.
 *
 * The output of this method can be parsed by the `of()` factory method; this will yield an object equal to this
 * one, but possibly of a different type if instantiated through `BigNumber::of()`.
 *
 * @pure
 */',
        'startLine' => 489,
        'endLine' => 489,
        'startColumn' => 5,
        'endColumn' => 48,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 65,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'jsonSerialize' => 
      array (
        'name' => 'jsonSerialize',
        'parameters' => 
        array (
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
          0 => 
          array (
            'name' => 'Override',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => NULL,
        'startLine' => 491,
        'endLine' => 495,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      '__toString' => 
      array (
        'name' => '__toString',
        'parameters' => 
        array (
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
 * @pure
 */',
        'startLine' => 500,
        'endLine' => 503,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 33,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'from' => 
      array (
        'name' => 'from',
        'parameters' => 
        array (
          'number' => 
          array (
            'name' => 'number',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Brick\\Math\\BigNumber',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 512,
            'endLine' => 512,
            'startColumn' => 45,
            'endColumn' => 61,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Overridden by subclasses to convert a BigNumber to an instance of the subclass.
 *
 * @throws RoundingNecessaryException If the value cannot be converted.
 *
 * @pure
 */',
        'startLine' => 512,
        'endLine' => 512,
        'startColumn' => 5,
        'endColumn' => 71,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 82,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'newBigInteger' => 
      array (
        'name' => 'newBigInteger',
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
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 521,
            'endLine' => 521,
            'startColumn' => 44,
            'endColumn' => 56,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Proxy method to access BigInteger\'s protected constructor from sibling classes.
 *
 * @internal
 *
 * @pure
 */',
        'startLine' => 521,
        'endLine' => 524,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 34,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'newBigDecimal' => 
      array (
        'name' => 'newBigDecimal',
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
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 533,
            'endLine' => 533,
            'startColumn' => 44,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'scale' => 
          array (
            'name' => 'scale',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 533,
                'endLine' => 533,
                'startTokenPos' => 1651,
                'startFilePos' => 16270,
                'endTokenPos' => 1651,
                'endFilePos' => 16270,
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
            'startLine' => 533,
            'endLine' => 533,
            'startColumn' => 59,
            'endColumn' => 72,
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
            'name' => 'Brick\\Math\\BigDecimal',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Proxy method to access BigDecimal\'s protected constructor from sibling classes.
 *
 * @internal
 *
 * @pure
 */',
        'startLine' => 533,
        'endLine' => 536,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 34,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'newBigRational' => 
      array (
        'name' => 'newBigRational',
        'parameters' => 
        array (
          'numerator' => 
          array (
            'name' => 'numerator',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Brick\\Math\\BigInteger',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 545,
            'endLine' => 545,
            'startColumn' => 45,
            'endColumn' => 65,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'denominator' => 
          array (
            'name' => 'denominator',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Brick\\Math\\BigInteger',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 545,
            'endLine' => 545,
            'startColumn' => 68,
            'endColumn' => 90,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'checkDenominator' => 
          array (
            'name' => 'checkDenominator',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 545,
            'endLine' => 545,
            'startColumn' => 93,
            'endColumn' => 114,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Brick\\Math\\BigRational',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Proxy method to access BigRational\'s protected constructor from sibling classes.
 *
 * @internal
 *
 * @pure
 */',
        'startLine' => 545,
        'endLine' => 548,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 34,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      '_of' => 
      array (
        'name' => '_of',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Brick\\Math\\BigNumber',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'int',
                      'isIdentifier' => true,
                    ),
                  ),
                  2 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'float',
                      'isIdentifier' => true,
                    ),
                  ),
                  3 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 556,
            'endLine' => 556,
            'startColumn' => 33,
            'endColumn' => 65,
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
            'name' => 'Brick\\Math\\BigNumber',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @throws NumberFormatException   If the format of the number is not valid.
 * @throws DivisionByZeroException If the value represents a rational number with a denominator of zero.
 *
 * @pure
 */',
        'startLine' => 556,
        'endLine' => 666,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'cleanUp' => 
      array (
        'name' => 'cleanUp',
        'parameters' => 
        array (
          'sign' => 
          array (
            'name' => 'sign',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 676,
            'endLine' => 676,
            'startColumn' => 37,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'number' => 
          array (
            'name' => 'number',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 676,
            'endLine' => 676,
            'startColumn' => 56,
            'endColumn' => 69,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Removes optional leading zeros and applies sign.
 *
 * @param string|null $sign   The sign, \'+\' or \'-\', optional. Null is allowed for convenience and treated as \'+\'.
 * @param string      $number The number, validated as a string of digits.
 *
 * @pure
 */',
        'startLine' => 676,
        'endLine' => 685,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
        'aliasName' => NULL,
      ),
      'add' => 
      array (
        'name' => 'add',
        'parameters' => 
        array (
          'a' => 
          array (
            'name' => 'a',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Brick\\Math\\BigNumber',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 692,
            'endLine' => 692,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'b' => 
          array (
            'name' => 'b',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Brick\\Math\\BigNumber',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 692,
            'endLine' => 692,
            'startColumn' => 47,
            'endColumn' => 58,
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
            'name' => 'Brick\\Math\\BigNumber',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Adds two BigNumber instances in the correct order to avoid a RoundingNecessaryException.
 *
 * @pure
 */',
        'startLine' => 692,
        'endLine' => 711,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigNumber',
        'implementingClassName' => 'Brick\\Math\\BigNumber',
        'currentClassName' => 'Brick\\Math\\BigNumber',
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