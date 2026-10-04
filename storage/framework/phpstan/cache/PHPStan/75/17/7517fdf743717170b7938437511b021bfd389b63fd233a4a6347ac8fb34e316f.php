<?php declare(strict_types = 1);

// osfsl-C:/laravel project/cars/vendor/composer/../brick/math/src/BigRational.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Brick\Math\BigRational
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-c7630a204516be223bcfd4dea30f64935183f5c6e2ede51c22a5ea836268ab2d-8.2.12-6.73.0.5',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Brick\\Math\\BigRational',
        'filename' => 'C:/laravel project/cars/vendor/composer/../brick/math/src/BigRational.php',
      ),
    ),
    'namespace' => 'Brick\\Math',
    'name' => 'Brick\\Math\\BigRational',
    'shortName' => 'BigRational',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 65568,
    'docComment' => '/**
 * An arbitrarily large rational number.
 *
 * This class is immutable.
 *
 * Fractions are automatically simplified to lowest terms. For example, `2/4` becomes `1/2`.
 * The denominator is always strictly positive; the sign is carried by the numerator.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 32,
    'endLine' => 606,
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
      'numerator' => 
      array (
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'name' => 'numerator',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * The numerator.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'denominator' => 
      array (
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'name' => 'denominator',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => '/**
 * The denominator. Always strictly positive.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 36,
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
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 36,
            'endColumn' => 56,
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
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 59,
            'endColumn' => 81,
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
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 84,
            'endColumn' => 105,
            'parameterIndex' => 2,
            'isOptional' => false,
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
 * @param BigInteger $numerator        The numerator.
 * @param BigInteger $denominator      The denominator.
 * @param bool       $checkDenominator Whether to check the denominator for negative and zero.
 *
 * @throws DivisionByZeroException If the denominator is zero.
 *
 * @pure
 */',
        'startLine' => 55,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
        'aliasName' => NULL,
      ),
      'nd' => 
      array (
        'name' => 'nd',
        'parameters' => 
        array (
          'numerator' => 
          array (
            'name' => 'numerator',
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
            'startColumn' => 9,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'denominator' => 
          array (
            'name' => 'denominator',
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
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 9,
            'endColumn' => 47,
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
            'name' => 'Brick\\Math\\BigRational',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a BigRational out of a numerator and a denominator.
 *
 * If the denominator is negative, the signs of both the numerator and the denominator
 * will be inverted to ensure that the denominator is always positive.
 *
 * @deprecated Use ofFraction() instead.
 *
 * @param BigNumber|int|float|string $numerator   The numerator. Must be convertible to a BigInteger.
 * @param BigNumber|int|float|string $denominator The denominator. Must be convertible to a BigInteger.
 *
 * @throws NumberFormatException      If an argument does not represent a valid number.
 * @throws RoundingNecessaryException If an argument represents a non-integer number.
 * @throws DivisionByZeroException    If the denominator is zero.
 */',
        'startLine' => 87,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
        'aliasName' => NULL,
      ),
      'ofFraction' => 
      array (
        'name' => 'ofFraction',
        'parameters' => 
        array (
          'numerator' => 
          array (
            'name' => 'numerator',
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
            'startLine' => 114,
            'endLine' => 114,
            'startColumn' => 9,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'denominator' => 
          array (
            'name' => 'denominator',
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
            'startLine' => 115,
            'endLine' => 115,
            'startColumn' => 9,
            'endColumn' => 47,
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
            'name' => 'Brick\\Math\\BigRational',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a BigRational out of a numerator and a denominator.
 *
 * If the denominator is negative, the signs of both the numerator and the denominator
 * will be inverted to ensure that the denominator is always positive.
 *
 * @param BigNumber|int|float|string $numerator   The numerator. Must be convertible to a BigInteger.
 * @param BigNumber|int|float|string $denominator The denominator. Must be convertible to a BigInteger.
 *
 * @throws MathException           If an argument is not valid, or is not convertible to a BigInteger.
 * @throws DivisionByZeroException If the denominator is zero.
 *
 * @pure
 */',
        'startLine' => 113,
        'endLine' => 121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'name' => 'Brick\\Math\\BigRational',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a BigRational representing zero.
 *
 * @pure
 */',
        'startLine' => 128,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'name' => 'Brick\\Math\\BigRational',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a BigRational representing one.
 *
 * @pure
 */',
        'startLine' => 145,
        'endLine' => 155,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'name' => 'Brick\\Math\\BigRational',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a BigRational representing ten.
 *
 * @pure
 */',
        'startLine' => 162,
        'endLine' => 172,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
        'aliasName' => NULL,
      ),
      'getNumerator' => 
      array (
        'name' => 'getNumerator',
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
        'startLine' => 177,
        'endLine' => 180,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
        'aliasName' => NULL,
      ),
      'getDenominator' => 
      array (
        'name' => 'getDenominator',
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
        'startLine' => 185,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
        'aliasName' => NULL,
      ),
      'quotient' => 
      array (
        'name' => 'quotient',
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
 * Returns the quotient of the division of the numerator by the denominator.
 *
 * @deprecated Will be removed in 0.15. Use getIntegralPart() instead.
 */',
        'startLine' => 195,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
        'aliasName' => NULL,
      ),
      'remainder' => 
      array (
        'name' => 'remainder',
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
 * Returns the remainder of the division of the numerator by the denominator.
 *
 * @deprecated Will be removed in 0.15. Use `$number->getNumerator()->remainder($number->getDenominator())` instead.
 */',
        'startLine' => 210,
        'endLine' => 218,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
        'aliasName' => NULL,
      ),
      'quotientAndRemainder' => 
      array (
        'name' => 'quotientAndRemainder',
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
 * Returns the quotient and remainder of the division of the numerator by the denominator.
 *
 * @deprecated Will be removed in 0.15. Use `$number->getNumerator()->quotientAndRemainder($number->getDenominator())` instead.
 *
 * @return array{BigInteger, BigInteger}
 */',
        'startLine' => 227,
        'endLine' => 235,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the integral part of this rational number.
 *
 * Examples:
 *
 * - `7/3` returns `2` (since 7/3 = 2 + 1/3)
 * - `-7/3` returns `-2` (since -7/3 = -2 + (-1/3))
 *
 * The following identity holds: `$r->isEqualTo($r->getFractionalPart()->plus($r->getIntegralPart()))`.
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
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'name' => 'Brick\\Math\\BigRational',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the fractional part of this rational number.
 *
 * Examples:
 *
 * - `7/3` returns `1/3` (since 7/3 = 2 + 1/3)
 * - `-7/3` returns `-1/3` (since -7/3 = -2 + (-1/3))
 *
 * The following identity holds: `$r->isEqualTo($r->getFractionalPart()->plus($r->getIntegralPart()))`.
 *
 * @pure
 */',
        'startLine' => 266,
        'endLine' => 269,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'startLine' => 280,
            'endLine' => 280,
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
            'name' => 'Brick\\Math\\BigRational',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the sum of this number and the given one.
 *
 * @param BigNumber|int|float|string $that The number to add.
 *
 * @throws MathException If the number is not valid.
 *
 * @pure
 */',
        'startLine' => 280,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'startLine' => 300,
            'endLine' => 300,
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
            'name' => 'Brick\\Math\\BigRational',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the difference of this number and the given one.
 *
 * @param BigNumber|int|float|string $that The number to subtract.
 *
 * @throws MathException If the number is not valid.
 *
 * @pure
 */',
        'startLine' => 300,
        'endLine' => 309,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'startLine' => 320,
            'endLine' => 320,
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
            'name' => 'Brick\\Math\\BigRational',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the product of this number and the given one.
 *
 * @param BigNumber|int|float|string $that The multiplier.
 *
 * @throws MathException If the multiplier is not valid.
 *
 * @pure
 */',
        'startLine' => 320,
        'endLine' => 328,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'startLine' => 340,
            'endLine' => 340,
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
            'name' => 'Brick\\Math\\BigRational',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the result of the division of this number by the given one.
 *
 * @param BigNumber|int|float|string $that The divisor.
 *
 * @throws MathException           If the divisor is not valid.
 * @throws DivisionByZeroException If the divisor is zero.
 *
 * @pure
 */',
        'startLine' => 340,
        'endLine' => 352,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'startLine' => 361,
            'endLine' => 361,
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
            'name' => 'Brick\\Math\\BigRational',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns this number exponentiated to the given value.
 *
 * @throws InvalidArgumentException If the exponent is not in the range 0 to 1,000,000.
 *
 * @pure
 */',
        'startLine' => 361,
        'endLine' => 376,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
        'aliasName' => NULL,
      ),
      'reciprocal' => 
      array (
        'name' => 'reciprocal',
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
 * Returns the reciprocal of this BigRational.
 *
 * The reciprocal has the numerator and denominator swapped.
 *
 * @throws DivisionByZeroException If the numerator is zero.
 *
 * @pure
 */',
        'startLine' => 387,
        'endLine' => 390,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
        'startLine' => 392,
        'endLine' => 396,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
        'aliasName' => NULL,
      ),
      'simplified' => 
      array (
        'name' => 'simplified',
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
 * Returns the simplified value of this BigRational.
 *
 * @pure
 */',
        'startLine' => 403,
        'endLine' => 411,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'startLine' => 414,
            'endLine' => 414,
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
        'startLine' => 413,
        'endLine' => 425,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
        'startLine' => 427,
        'endLine' => 431,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
        'startLine' => 433,
        'endLine' => 443,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
        'startLine' => 445,
        'endLine' => 449,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
        'startLine' => 451,
        'endLine' => 455,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'startLine' => 458,
            'endLine' => 458,
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
                'startLine' => 458,
                'endLine' => 458,
                'startTokenPos' => 1803,
                'startFilePos' => 13549,
                'endTokenPos' => 1805,
                'endFilePos' => 13573,
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
            'startLine' => 458,
            'endLine' => 458,
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
        'startLine' => 457,
        'endLine' => 461,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
        'startLine' => 463,
        'endLine' => 467,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
        'startLine' => 469,
        'endLine' => 492,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
        'docComment' => NULL,
        'startLine' => 494,
        'endLine' => 505,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
        'aliasName' => NULL,
      ),
      'toRepeatingDecimalString' => 
      array (
        'name' => 'toRepeatingDecimalString',
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
 * Returns the decimal representation of this rational number, with repeating decimals in parentheses.
 *
 * WARNING: This method is unbounded.
 *          The length of the repeating decimal period can be as large as `denominator - 1`.
 *          For fractions with large denominators, this method can use excessive memory and CPU time.
 *          For example, `1/100019` has a repeating period of 100,018 digits.
 *
 * Examples:
 *
 * - `10/3` returns `3.(3)`
 * - `171/70` returns `2.4(428571)`
 * - `1/2` returns `0.5`
 *
 * @pure
 */',
        'startLine' => 523,
        'endLine' => 566,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
 * @return array{numerator: BigInteger, denominator: BigInteger}
 */',
        'startLine' => 575,
        'endLine' => 578,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'startLine' => 589,
            'endLine' => 589,
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
 * @param array{numerator: BigInteger, denominator: BigInteger} $data
 *
 * @throws LogicException
 */',
        'startLine' => 589,
        'endLine' => 599,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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
            'startLine' => 602,
            'endLine' => 602,
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
        'startLine' => 601,
        'endLine' => 605,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigRational',
        'implementingClassName' => 'Brick\\Math\\BigRational',
        'currentClassName' => 'Brick\\Math\\BigRational',
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