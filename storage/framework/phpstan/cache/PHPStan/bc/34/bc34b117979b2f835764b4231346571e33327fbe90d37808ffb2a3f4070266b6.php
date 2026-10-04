<?php declare(strict_types = 1);

// osfsl-C:/laravel project/cars/vendor/composer/../brick/math/src/BigDecimal.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Brick\Math\BigDecimal
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-b7573fa263aa772fe8cb405cbe09d5b9f2587a9999149782434319747898d658-8.2.12-6.73.0.5',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Brick\\Math\\BigDecimal',
        'filename' => 'C:/laravel project/cars/vendor/composer/../brick/math/src/BigDecimal.php',
      ),
    ),
    'namespace' => 'Brick\\Math',
    'name' => 'Brick\\Math\\BigDecimal',
    'shortName' => 'BigDecimal',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 65568,
    'docComment' => '/**
 * An arbitrarily large decimal number.
 *
 * This class is immutable.
 *
 * The scale of the number is the number of digits after the decimal point. It is always positive or zero.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 39,
    'endLine' => 975,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Brick\\Math\\BigNumber',
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
      'value' => 
      array (
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'name' => 'value',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * The unscaled value of this decimal number.
 *
 * This is a string of digits with an optional leading minus sign.
 * No leading zero must be present.
 * No leading minus sign must be present if the value is 0.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'scale' => 
      array (
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'name' => 'scale',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * The scale (number of digits after the decimal point) of this decimal number.
 *
 * This must be zero or more.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 23,
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
      '__construct' => 
      array (
        'name' => '__construct',
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
            'startLine' => 65,
            'endLine' => 65,
            'startColumn' => 36,
            'endColumn' => 48,
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
                'startLine' => 65,
                'endLine' => 65,
                'startTokenPos' => 204,
                'startFilePos' => 1670,
                'endTokenPos' => 204,
                'endFilePos' => 1670,
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
            'startLine' => 65,
            'endLine' => 65,
            'startColumn' => 51,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Protected constructor. Use a factory method to obtain an instance.
 *
 * @param string $value The unscaled value, validated.
 * @param int    $scale The scale, validated.
 *
 * @pure
 */',
        'startLine' => 65,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'ofUnscaledValue' => 
      array (
        'name' => 'ofUnscaledValue',
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
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 44,
            'endColumn' => 76,
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
                'startLine' => 88,
                'endLine' => 88,
                'startTokenPos' => 256,
                'startFilePos' => 2620,
                'endTokenPos' => 256,
                'endFilePos' => 2620,
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
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 79,
            'endColumn' => 92,
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
 * Creates a BigDecimal from an unscaled value and a scale.
 *
 * Example: `(12345, 3)` will result in the BigDecimal `12.345`.
 *
 * A negative scale is normalized to zero by appending zeros to the unscaled value.
 *
 * Example: `(12345, -3)` will result in the BigDecimal `12345000`.
 *
 * @param BigNumber|int|float|string $value The unscaled value. Must be convertible to a BigInteger.
 * @param int                        $scale The scale of the number. If negative, the scale will be set to zero
 *                                          and the unscaled value will be adjusted accordingly.
 *
 * @throws MathException If the value is not valid, or is not convertible to a BigInteger.
 *
 * @pure
 */',
        'startLine' => 88,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
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
        'docComment' => '/**
 * Returns a BigDecimal representing zero, with a scale of zero.
 *
 * @pure
 */',
        'startLine' => 107,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'one' => 
      array (
        'name' => 'one',
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
 * Returns a BigDecimal representing one, with a scale of zero.
 *
 * @pure
 */',
        'startLine' => 124,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'ten' => 
      array (
        'name' => 'ten',
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
 * Returns a BigDecimal representing ten, with a scale of zero.
 *
 * @pure
 */',
        'startLine' => 141,
        'endLine' => 151,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'plus' => 
      array (
        'name' => 'plus',
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
            'startLine' => 164,
            'endLine' => 164,
            'startColumn' => 26,
            'endColumn' => 57,
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
 * Returns the sum of this number and the given one.
 *
 * The result has a scale of `max($this->scale, $that->scale)`.
 *
 * @param BigNumber|int|float|string $that The number to add. Must be convertible to a BigDecimal.
 *
 * @throws MathException If the number is not valid, or is not convertible to a BigDecimal.
 *
 * @pure
 */',
        'startLine' => 164,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'minus' => 
      array (
        'name' => 'minus',
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
            'startLine' => 195,
            'endLine' => 195,
            'startColumn' => 27,
            'endColumn' => 58,
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
 * Returns the difference of this number and the given one.
 *
 * The result has a scale of `max($this->scale, $that->scale)`.
 *
 * @param BigNumber|int|float|string $that The number to subtract. Must be convertible to a BigDecimal.
 *
 * @throws MathException If the number is not valid, or is not convertible to a BigDecimal.
 *
 * @pure
 */',
        'startLine' => 195,
        'endLine' => 209,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'multipliedBy' => 
      array (
        'name' => 'multipliedBy',
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
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 34,
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
            'name' => 'Brick\\Math\\BigDecimal',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the product of this number and the given one.
 *
 * The result has a scale of `$this->scale + $that->scale`.
 *
 * @param BigNumber|int|float|string $that The multiplier. Must be convertible to a BigDecimal.
 *
 * @throws MathException If the multiplier is not valid, or is not convertible to a BigDecimal.
 *
 * @pure
 */',
        'startLine' => 222,
        'endLine' => 238,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'dividedBy' => 
      array (
        'name' => 'dividedBy',
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
            'startLine' => 255,
            'endLine' => 255,
            'startColumn' => 31,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'scale' => 
          array (
            'name' => 'scale',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 255,
                'endLine' => 255,
                'startTokenPos' => 1005,
                'startFilePos' => 7534,
                'endTokenPos' => 1005,
                'endFilePos' => 7537,
              ),
            ),
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
                      'name' => 'int',
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
            'startLine' => 255,
            'endLine' => 255,
            'startColumn' => 65,
            'endColumn' => 82,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'roundingMode' => 
          array (
            'name' => 'roundingMode',
            'default' => 
            array (
              'code' => '\\Brick\\Math\\RoundingMode::Unnecessary',
              'attributes' => 
              array (
                'startLine' => 255,
                'endLine' => 255,
                'startTokenPos' => 1014,
                'startFilePos' => 7569,
                'endTokenPos' => 1016,
                'endFilePos' => 7593,
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
            'startLine' => 255,
            'endLine' => 255,
            'startColumn' => 85,
            'endColumn' => 138,
            'parameterIndex' => 2,
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
 * Returns the result of the division of this number by the given one, at the given scale.
 *
 * @param BigNumber|int|float|string $that         The divisor. Must be convertible to a BigDecimal.
 * @param int|null                   $scale        The desired scale. Omitting this parameter is deprecated; it will be required in 0.15.
 * @param RoundingMode               $roundingMode An optional rounding mode, defaults to Unnecessary.
 *
 * @throws InvalidArgumentException   If the scale is negative.
 * @throws MathException              If the divisor is not valid, or is not convertible to a BigDecimal.
 * @throws DivisionByZeroException    If the divisor is zero.
 * @throws RoundingNecessaryException If RoundingMode::Unnecessary is used and the result cannot be represented
 *                                    exactly at the given scale.
 *
 * @pure
 */',
        'startLine' => 255,
        'endLine' => 285,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'exactlyDividedBy' => 
      array (
        'name' => 'exactlyDividedBy',
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
            'startLine' => 299,
            'endLine' => 299,
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
            'name' => 'Brick\\Math\\BigDecimal',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the exact result of the division of this number by the given one.
 *
 * The scale of the result is automatically calculated to fit all the fraction digits.
 *
 * @deprecated Will be removed in 0.15. Use dividedByExact() instead.
 *
 * @param BigNumber|int|float|string $that The divisor. Must be convertible to a BigDecimal.
 *
 * @throws MathException If the divisor is not a valid number, is not convertible to a BigDecimal, is zero,
 *                       or the result yields an infinite number of digits.
 */',
        'startLine' => 299,
        'endLine' => 307,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'dividedByExact' => 
      array (
        'name' => 'dividedByExact',
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
            'startLine' => 322,
            'endLine' => 322,
            'startColumn' => 36,
            'endColumn' => 67,
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
 * Returns the exact result of the division of this number by the given one.
 *
 * The scale of the result is automatically calculated to fit all the fraction digits.
 *
 * @param BigNumber|int|float|string $that The divisor. Must be convertible to a BigDecimal.
 *
 * @throws MathException              If the divisor is not valid, or is not convertible to a BigDecimal.
 * @throws DivisionByZeroException    If the divisor is zero.
 * @throws RoundingNecessaryException If the result yields an infinite number of digits.
 *
 * @pure
 */',
        'startLine' => 322,
        'endLine' => 351,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'power' => 
      array (
        'name' => 'power',
        'parameters' => 
        array (
          'exponent' => 
          array (
            'name' => 'exponent',
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
            'startLine' => 362,
            'endLine' => 362,
            'startColumn' => 27,
            'endColumn' => 39,
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
 * Returns this number exponentiated to the given value.
 *
 * The result has a scale of `$this->scale * $exponent`.
 *
 * @throws InvalidArgumentException If the exponent is not in the range 0 to 1,000,000.
 *
 * @pure
 */',
        'startLine' => 362,
        'endLine' => 381,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'quotient' => 
      array (
        'name' => 'quotient',
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
            'startLine' => 402,
            'endLine' => 402,
            'startColumn' => 30,
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
            'name' => 'Brick\\Math\\BigDecimal',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the quotient of the division of this number by the given one.
 *
 * The quotient has a scale of `0`.
 *
 * Examples:
 *
 * - `7.5` quotient `3` returns `2`
 * - `7.5` quotient `-3` returns `-2`
 * - `-7.5` quotient `3` returns `-2`
 * - `-7.5` quotient `-3` returns `2`
 *
 * @param BigNumber|int|float|string $that The divisor. Must be convertible to a BigDecimal.
 *
 * @throws MathException           If the divisor is not valid, or is not convertible to a BigDecimal.
 * @throws DivisionByZeroException If the divisor is zero.
 *
 * @pure
 */',
        'startLine' => 402,
        'endLine' => 416,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'remainder' => 
      array (
        'name' => 'remainder',
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
            'startLine' => 438,
            'endLine' => 438,
            'startColumn' => 31,
            'endColumn' => 62,
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
 * Returns the remainder of the division of this number by the given one.
 *
 * The remainder has a scale of `max($this->scale, $that->scale)`.
 * The remainder, when non-zero, has the same sign as the dividend.
 *
 * Examples:
 *
 * - `7.5` remainder `3` returns `1.5`
 * - `7.5` remainder `-3` returns `1.5`
 * - `-7.5` remainder `3` returns `-1.5`
 * - `-7.5` remainder `-3` returns `-1.5`
 *
 * @param BigNumber|int|float|string $that The divisor. Must be convertible to a BigDecimal.
 *
 * @throws MathException           If the divisor is not valid, or is not convertible to a BigDecimal.
 * @throws DivisionByZeroException If the divisor is zero.
 *
 * @pure
 */',
        'startLine' => 438,
        'endLine' => 454,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'quotientAndRemainder' => 
      array (
        'name' => 'quotientAndRemainder',
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
            'startLine' => 477,
            'endLine' => 477,
            'startColumn' => 42,
            'endColumn' => 73,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the quotient and remainder of the division of this number by the given one.
 *
 * The quotient has a scale of `0`, and the remainder has a scale of `max($this->scale, $that->scale)`.
 *
 * Examples:
 *
 * - `7.5` quotientAndRemainder `3` returns [`2`, `1.5`]
 * - `7.5` quotientAndRemainder `-3` returns [`-2`, `1.5`]
 * - `-7.5` quotientAndRemainder `3` returns [`-2`, `-1.5`]
 * - `-7.5` quotientAndRemainder `-3` returns [`2`, `-1.5`]
 *
 * @param BigNumber|int|float|string $that The divisor. Must be convertible to a BigDecimal.
 *
 * @return array{BigDecimal, BigDecimal} An array containing the quotient and the remainder.
 *
 * @throws MathException           If the divisor is not valid, or is not convertible to a BigDecimal.
 * @throws DivisionByZeroException If the divisor is zero.
 *
 * @pure
 */',
        'startLine' => 477,
        'endLine' => 496,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'sqrt' => 
      array (
        'name' => 'sqrt',
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
            'startLine' => 514,
            'endLine' => 514,
            'startColumn' => 26,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'roundingMode' => 
          array (
            'name' => 'roundingMode',
            'default' => 
            array (
              'code' => '\\Brick\\Math\\RoundingMode::Down',
              'attributes' => 
              array (
                'startLine' => 514,
                'endLine' => 514,
                'startTokenPos' => 2103,
                'startFilePos' => 16722,
                'endTokenPos' => 2105,
                'endFilePos' => 16739,
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
            'startLine' => 514,
            'endLine' => 514,
            'startColumn' => 38,
            'endColumn' => 84,
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
 * Returns the square root of this number, rounded to the given scale according to the given rounding mode.
 *
 * @param int          $scale        The target scale. Must be non-negative.
 * @param RoundingMode $roundingMode The rounding mode to use, defaults to Down.
 *                                   ⚠️ WARNING: the default rounding mode was kept as Down for backward
 *                                   compatibility, but will change to Unnecessary in version 0.15. Pass a rounding
 *                                   mode explicitly to avoid this upcoming breaking change.
 *
 * @throws InvalidArgumentException   If the scale is negative.
 * @throws NegativeNumberException    If this number is negative.
 * @throws RoundingNecessaryException If RoundingMode::Unnecessary is used and the result cannot be represented
 *                                    exactly at the given scale.
 *
 * @pure
 */',
        'startLine' => 514,
        'endLine' => 572,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'withPointMovedLeft' => 
      array (
        'name' => 'withPointMovedLeft',
        'parameters' => 
        array (
          'n' => 
          array (
            'name' => 'n',
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
            'startLine' => 579,
            'endLine' => 579,
            'startColumn' => 40,
            'endColumn' => 45,
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
 * Returns a copy of this BigDecimal with the decimal point moved to the left by the given number of places.
 *
 * @pure
 */',
        'startLine' => 579,
        'endLine' => 590,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'withPointMovedRight' => 
      array (
        'name' => 'withPointMovedRight',
        'parameters' => 
        array (
          'n' => 
          array (
            'name' => 'n',
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
            'startLine' => 597,
            'endLine' => 597,
            'startColumn' => 41,
            'endColumn' => 46,
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
 * Returns a copy of this BigDecimal with the decimal point moved to the right by the given number of places.
 *
 * @pure
 */',
        'startLine' => 597,
        'endLine' => 618,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'stripTrailingZeros' => 
      array (
        'name' => 'stripTrailingZeros',
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
 * Returns a copy of this BigDecimal with any trailing zeros removed from the fractional part.
 *
 * @deprecated Use strippedOfTrailingZeros() instead.
 */',
        'startLine' => 625,
        'endLine' => 633,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'strippedOfTrailingZeros' => 
      array (
        'name' => 'strippedOfTrailingZeros',
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
 * Returns a copy of this BigDecimal with any trailing zeros removed from the fractional part.
 *
 * @pure
 */',
        'startLine' => 640,
        'endLine' => 666,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
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
        'startLine' => 668,
        'endLine' => 672,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
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
            'startLine' => 675,
            'endLine' => 675,
            'startColumn' => 31,
            'endColumn' => 62,
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
        'startLine' => 674,
        'endLine' => 690,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
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
        'startLine' => 692,
        'endLine' => 696,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'getUnscaledValue' => 
      array (
        'name' => 'getUnscaledValue',
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
 * @pure
 */',
        'startLine' => 701,
        'endLine' => 704,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'getScale' => 
      array (
        'name' => 'getScale',
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
 * @pure
 */',
        'startLine' => 709,
        'endLine' => 712,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'getPrecision' => 
      array (
        'name' => 'getPrecision',
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
 * Returns the number of significant digits in the number.
 *
 * This is the number of digits to both sides of the decimal point, stripped of leading zeros.
 * The sign has no impact on the result.
 *
 * Examples:
 *   0 => 0
 *   0.0 => 0
 *   123 => 3
 *   123.456 => 6
 *   0.00123 => 3
 *   0.0012300 => 5
 *
 * @pure
 */',
        'startLine' => 730,
        'endLine' => 741,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'getIntegralPart' => 
      array (
        'name' => 'getIntegralPart',
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
 * Returns a string representing the integral part of this decimal number.
 *
 * Example: `-123.456` => `-123`.
 *
 * @deprecated Will be removed in 0.15 and re-introduced as returning BigInteger in 0.16.
 */',
        'startLine' => 750,
        'endLine' => 764,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'getFractionalPart' => 
      array (
        'name' => 'getFractionalPart',
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
 * Returns a string representing the fractional part of this decimal number.
 *
 * If the scale is zero, an empty string is returned.
 *
 * Examples: `-123.456` => \'456\', `123` => \'\'.
 *
 * @deprecated Will be removed in 0.15 and re-introduced as returning BigDecimal with a different meaning in 0.16.
 */',
        'startLine' => 775,
        'endLine' => 789,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'hasNonZeroFractionalPart' => 
      array (
        'name' => 'hasNonZeroFractionalPart',
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
 * Returns whether this decimal number has a non-zero fractional part.
 *
 * @pure
 */',
        'startLine' => 796,
        'endLine' => 805,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
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
        'startLine' => 807,
        'endLine' => 813,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
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
        'startLine' => 815,
        'endLine' => 819,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
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
        'startLine' => 821,
        'endLine' => 828,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
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
            'startLine' => 831,
            'endLine' => 831,
            'startColumn' => 29,
            'endColumn' => 38,
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
                'startLine' => 831,
                'endLine' => 831,
                'startTokenPos' => 3748,
                'startFilePos' => 25559,
                'endTokenPos' => 3750,
                'endFilePos' => 25583,
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
            'startLine' => 831,
            'endLine' => 831,
            'startColumn' => 41,
            'endColumn' => 94,
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
        'startLine' => 830,
        'endLine' => 838,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
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
        'startLine' => 840,
        'endLine' => 844,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
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
        'startLine' => 846,
        'endLine' => 850,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
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
          0 => 
          array (
            'name' => 'Override',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * @return numeric-string
 */',
        'startLine' => 855,
        'endLine' => 867,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      '__serialize' => 
      array (
        'name' => '__serialize',
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
        'docComment' => '/**
 * This method is required for serializing the object and SHOULD NOT be accessed directly.
 *
 * @internal
 *
 * @return array{value: string, scale: int}
 */',
        'startLine' => 876,
        'endLine' => 879,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      '__unserialize' => 
      array (
        'name' => '__unserialize',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 890,
            'endLine' => 890,
            'startColumn' => 35,
            'endColumn' => 45,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * This method is only here to allow unserializing the object and cannot be accessed directly.
 *
 * @internal
 *
 * @param array{value: string, scale: int} $data
 *
 * @throws LogicException
 */',
        'startLine' => 890,
        'endLine' => 900,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
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
            'startLine' => 903,
            'endLine' => 903,
            'startColumn' => 36,
            'endColumn' => 52,
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
        'startLine' => 902,
        'endLine' => 906,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'scaleValues' => 
      array (
        'name' => 'scaleValues',
        'parameters' => 
        array (
          'x' => 
          array (
            'name' => 'x',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Brick\\Math\\BigDecimal',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 915,
            'endLine' => 915,
            'startColumn' => 34,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'y' => 
          array (
            'name' => 'y',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Brick\\Math\\BigDecimal',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 915,
            'endLine' => 915,
            'startColumn' => 49,
            'endColumn' => 61,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Puts the internal values of the given decimal numbers on the same scale.
 *
 * @return array{string, string} The scaled integer values of $x and $y.
 *
 * @pure
 */',
        'startLine' => 915,
        'endLine' => 927,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'valueWithMinScale' => 
      array (
        'name' => 'valueWithMinScale',
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
            'startLine' => 932,
            'endLine' => 932,
            'startColumn' => 40,
            'endColumn' => 49,
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
        'startLine' => 932,
        'endLine' => 941,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
        'aliasName' => NULL,
      ),
      'getUnscaledValueWithLeadingZeros' => 
      array (
        'name' => 'getUnscaledValueWithLeadingZeros',
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
 * Adds leading zeros if necessary to the unscaled value to represent the full decimal number.
 *
 * @pure
 */',
        'startLine' => 948,
        'endLine' => 974,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigDecimal',
        'implementingClassName' => 'Brick\\Math\\BigDecimal',
        'currentClassName' => 'Brick\\Math\\BigDecimal',
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