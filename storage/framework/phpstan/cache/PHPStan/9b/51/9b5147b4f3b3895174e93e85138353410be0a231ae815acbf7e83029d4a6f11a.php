<?php declare(strict_types = 1);

// osfsl-C:/laravel project/cars/vendor/composer/../brick/math/src/BigInteger.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Brick\Math\BigInteger
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2c57b5b9d3b704c133ea7f105eb6cd91d987dd620e2021644b991f7a421da93c-8.2.12-6.73.0.5',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Brick\\Math\\BigInteger',
        'filename' => 'C:/laravel project/cars/vendor/composer/../brick/math/src/BigInteger.php',
      ),
    ),
    'namespace' => 'Brick\\Math',
    'name' => 'Brick\\Math\\BigInteger',
    'shortName' => 'BigInteger',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 65568,
    'docComment' => '/**
 * An arbitrarily large integer number.
 *
 * This class is immutable.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 48,
    'endLine' => 1351,
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
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
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
 * The value, as a string of digits with optional leading minus sign.
 *
 * No leading zeros must be present.
 * No leading minus sign must be present if the number is zero.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 26,
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
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Protected constructor. Use a factory method to obtain an instance.
 *
 * @param string $value A string of digits, with optional leading minus sign.
 *
 * @pure
 */',
        'startLine' => 65,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'fromBase' => 
      array (
        'name' => 'fromBase',
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
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 37,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'base' => 
          array (
            'name' => 'base',
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
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 53,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a number from a string in a given base.
 *
 * The string can optionally be prefixed with the `+` or `-` sign.
 *
 * Bases greater than 36 are not supported by this method, as there is no clear consensus on which of the lowercase
 * or uppercase characters should come first. Instead, this method accepts any base up to 36, and does not
 * differentiate lowercase and uppercase characters, which are considered equal.
 *
 * For bases greater than 36, and/or custom alphabets, use the fromArbitraryBase() method.
 *
 * @param string $number The number to convert, in the given base.
 * @param int    $base   The base of the number, between 2 and 36.
 *
 * @throws NumberFormatException    If the number is empty, or contains invalid chars for the given base.
 * @throws InvalidArgumentException If the base is out of range.
 *
 * @pure
 */',
        'startLine' => 89,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'fromArbitraryBase' => 
      array (
        'name' => 'fromArbitraryBase',
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
            'startLine' => 157,
            'endLine' => 157,
            'startColumn' => 46,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'alphabet' => 
          array (
            'name' => 'alphabet',
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
            'startLine' => 157,
            'endLine' => 157,
            'startColumn' => 62,
            'endColumn' => 77,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Parses a string containing an integer in an arbitrary base, using a custom alphabet.
 *
 * This method is byte-oriented: the alphabet is interpreted as a sequence of single-byte characters.
 * Multibyte UTF-8 characters are not supported.
 *
 * Because this method accepts any single-byte character, including dash, it does not handle negative numbers.
 *
 * @param string $number   The number to parse.
 * @param string $alphabet The alphabet, for example \'01\' for base 2, or \'01234567\' for base 8.
 *
 * @throws NumberFormatException    If the given number is empty or contains invalid chars for the given alphabet.
 * @throws InvalidArgumentException If the alphabet does not contain at least 2 chars, or contains duplicates.
 *
 * @pure
 */',
        'startLine' => 157,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'fromBytes' => 
      array (
        'name' => 'fromBytes',
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
            'startLine' => 203,
            'endLine' => 203,
            'startColumn' => 38,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'signed' => 
          array (
            'name' => 'signed',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 203,
                'endLine' => 203,
                'startTokenPos' => 908,
                'startFilePos' => 7023,
                'endTokenPos' => 908,
                'endFilePos' => 7026,
              ),
            ),
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
            'startLine' => 203,
            'endLine' => 203,
            'startColumn' => 53,
            'endColumn' => 71,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Translates a string of bytes containing the binary representation of a BigInteger into a BigInteger.
 *
 * The input string is assumed to be in big-endian byte-order: the most significant byte is in the zeroth element.
 *
 * If `$signed` is true, the input is assumed to be in two\'s-complement representation, and the leading bit is
 * interpreted as a sign bit. If `$signed` is false, the input is interpreted as an unsigned number, and the
 * resulting BigInteger will always be positive or zero.
 *
 * This method can be used to retrieve a number exported by `toBytes()`, as long as the `$signed` flags match.
 *
 * @param string $value  The byte string.
 * @param bool   $signed Whether to interpret as a signed number in two\'s-complement representation with a leading
 *                       sign bit.
 *
 * @throws NumberFormatException If the string is empty.
 *
 * @pure
 */',
        'startLine' => 203,
        'endLine' => 226,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'randomBits' => 
      array (
        'name' => 'randomBits',
        'parameters' => 
        array (
          'numBits' => 
          array (
            'name' => 'numBits',
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
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 39,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'randomBytesGenerator' => 
          array (
            'name' => 'randomBytesGenerator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 240,
                'endLine' => 240,
                'startTokenPos' => 1071,
                'startFilePos' => 8330,
                'endTokenPos' => 1071,
                'endFilePos' => 8333,
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
                      'name' => 'callable',
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
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 53,
            'endColumn' => 90,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generates a pseudo-random number in the range 0 to 2^numBits - 1.
 *
 * Using the default random bytes generator, this method is suitable for cryptographic use.
 *
 * @param int                          $numBits              The number of bits.
 * @param (callable(int): string)|null $randomBytesGenerator A function that accepts a number of bytes, and returns
 *                                                           a string of random bytes of the given length. Defaults
 *                                                           to the `random_bytes()` function.
 *
 * @throws InvalidArgumentException If $numBits is negative.
 */',
        'startLine' => 240,
        'endLine' => 264,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'randomRange' => 
      array (
        'name' => 'randomRange',
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
            'startLine' => 281,
            'endLine' => 281,
            'startColumn' => 9,
            'endColumn' => 39,
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
            'startLine' => 282,
            'endLine' => 282,
            'startColumn' => 9,
            'endColumn' => 39,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'randomBytesGenerator' => 
          array (
            'name' => 'randomBytesGenerator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 283,
                'endLine' => 283,
                'startTokenPos' => 1286,
                'startFilePos' => 10149,
                'endTokenPos' => 1286,
                'endFilePos' => 10152,
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
                      'name' => 'callable',
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
            'startLine' => 283,
            'endLine' => 283,
            'startColumn' => 9,
            'endColumn' => 46,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generates a pseudo-random number between `$min` and `$max`, inclusive.
 *
 * Using the default random bytes generator, this method is suitable for cryptographic use.
 *
 * @param BigNumber|int|float|string   $min                  The lower bound. Must be convertible to a BigInteger.
 * @param BigNumber|int|float|string   $max                  The upper bound. Must be convertible to a BigInteger.
 * @param (callable(int): string)|null $randomBytesGenerator A function that accepts a number of bytes, and returns
 *                                                           a string of random bytes of the given length. Defaults
 *                                                           to the `random_bytes()` function.
 *
 * @throws MathException If one of the parameters cannot be converted to a BigInteger,
 *                       or `$min` is greater than `$max`.
 */',
        'startLine' => 280,
        'endLine' => 305,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a BigInteger representing zero.
 *
 * @pure
 */',
        'startLine' => 312,
        'endLine' => 322,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a BigInteger representing one.
 *
 * @pure
 */',
        'startLine' => 329,
        'endLine' => 339,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a BigInteger representing ten.
 *
 * @pure
 */',
        'startLine' => 346,
        'endLine' => 356,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'gcdAll' => 
      array (
        'name' => 'gcdAll',
        'parameters' => 
        array (
          'a' => 
          array (
            'name' => 'a',
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
            'startLine' => 370,
            'endLine' => 370,
            'startColumn' => 35,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'n' => 
          array (
            'name' => 'n',
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
            'startLine' => 370,
            'endLine' => 370,
            'startColumn' => 66,
            'endColumn' => 97,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the greatest common divisor of the given numbers.
 *
 * The GCD is always positive, unless all numbers are zero, in which case it is zero.
 *
 * @param BigNumber|int|float|string $a    The first number. Must be convertible to a BigInteger.
 * @param BigNumber|int|float|string ...$n The additional numbers. Each number must be convertible to a BigInteger.
 *
 * @throws MathException If one of the parameters cannot be converted to a BigInteger.
 *
 * @pure
 */',
        'startLine' => 370,
        'endLine' => 383,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'lcmAll' => 
      array (
        'name' => 'lcmAll',
        'parameters' => 
        array (
          'a' => 
          array (
            'name' => 'a',
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
            'startLine' => 397,
            'endLine' => 397,
            'startColumn' => 35,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'n' => 
          array (
            'name' => 'n',
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
            'startLine' => 397,
            'endLine' => 397,
            'startColumn' => 66,
            'endColumn' => 97,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the least common multiple of the given numbers.
 *
 * The LCM is always positive, unless one of the numbers is zero, in which case it is zero.
 *
 * @param BigNumber|int|float|string $a    The first number. Must be convertible to a BigInteger.
 * @param BigNumber|int|float|string ...$n The additional numbers. Each number must be convertible to a BigInteger.
 *
 * @throws MathException If one of the parameters cannot be converted to a BigInteger.
 *
 * @pure
 */',
        'startLine' => 397,
        'endLine' => 410,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'gcdMultiple' => 
      array (
        'name' => 'gcdMultiple',
        'parameters' => 
        array (
          'a' => 
          array (
            'name' => 'a',
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
            'startLine' => 418,
            'endLine' => 418,
            'startColumn' => 40,
            'endColumn' => 68,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'n' => 
          array (
            'name' => 'n',
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
            'startLine' => 418,
            'endLine' => 418,
            'startColumn' => 71,
            'endColumn' => 102,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated Use gcdAll() instead.
 *
 * @param BigNumber|int|float|string $a    The first number. Must be convertible to a BigInteger.
 * @param BigNumber|int|float|string ...$n The subsequent numbers. Must be convertible to BigInteger.
 */',
        'startLine' => 418,
        'endLine' => 426,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'startLine' => 437,
            'endLine' => 437,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the sum of this number and the given one.
 *
 * @param BigNumber|int|float|string $that The number to add. Must be convertible to a BigInteger.
 *
 * @throws MathException If the number is not valid, or is not convertible to a BigInteger.
 *
 * @pure
 */',
        'startLine' => 437,
        'endLine' => 452,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'startLine' => 463,
            'endLine' => 463,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the difference of this number and the given one.
 *
 * @param BigNumber|int|float|string $that The number to subtract. Must be convertible to a BigInteger.
 *
 * @throws MathException If the number is not valid, or is not convertible to a BigInteger.
 *
 * @pure
 */',
        'startLine' => 463,
        'endLine' => 474,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'startLine' => 485,
            'endLine' => 485,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the product of this number and the given one.
 *
 * @param BigNumber|int|float|string $that The multiplier. Must be convertible to a BigInteger.
 *
 * @throws MathException If the multiplier is not valid, or is not convertible to a BigInteger.
 *
 * @pure
 */',
        'startLine' => 485,
        'endLine' => 500,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'startLine' => 514,
            'endLine' => 514,
            'startColumn' => 31,
            'endColumn' => 62,
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
                'startLine' => 514,
                'endLine' => 514,
                'startTokenPos' => 2238,
                'startFilePos' => 16918,
                'endTokenPos' => 2240,
                'endFilePos' => 16942,
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
            'startColumn' => 65,
            'endColumn' => 118,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the result of the division of this number by the given one.
 *
 * @param BigNumber|int|float|string $that         The divisor. Must be convertible to a BigInteger.
 * @param RoundingMode               $roundingMode An optional rounding mode, defaults to Unnecessary.
 *
 * @throws MathException              If the divisor is not valid, or is not convertible to a BigInteger.
 * @throws DivisionByZeroException    If the divisor is zero.
 * @throws RoundingNecessaryException If RoundingMode::Unnecessary is used and the remainder is not zero.
 *
 * @pure
 */',
        'startLine' => 514,
        'endLine' => 529,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'startLine' => 538,
            'endLine' => 538,
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
            'name' => 'Brick\\Math\\BigInteger',
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
        'startLine' => 538,
        'endLine' => 557,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'startLine' => 576,
            'endLine' => 576,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the quotient of the division of this number by the given one.
 *
 * Examples:
 *
 * - `7` quotient `3` returns `2`
 * - `7` quotient `-3` returns `-2`
 * - `-7` quotient `3` returns `-2`
 * - `-7` quotient `-3` returns `2`
 *
 * @param BigNumber|int|float|string $that The divisor. Must be convertible to a BigInteger.
 *
 * @throws MathException           If the divisor is not valid, or is not convertible to a BigInteger.
 * @throws DivisionByZeroException If the divisor is zero.
 *
 * @pure
 */',
        'startLine' => 576,
        'endLine' => 591,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'startLine' => 612,
            'endLine' => 612,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the remainder of the division of this number by the given one.
 *
 * The remainder, when non-zero, has the same sign as the dividend.
 *
 * Examples:
 *
 * - `7` remainder `3` returns `1`
 * - `7` remainder `-3` returns `1`
 * - `-7` remainder `3` returns `-1`
 * - `-7` remainder `-3` returns `-1`
 *
 * @param BigNumber|int|float|string $that The divisor. Must be convertible to a BigInteger.
 *
 * @throws MathException           If the divisor is not valid, or is not convertible to a BigInteger.
 * @throws DivisionByZeroException If the divisor is zero.
 *
 * @pure
 */',
        'startLine' => 612,
        'endLine' => 627,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'startLine' => 648,
            'endLine' => 648,
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
 * Examples:
 *
 * - `7` quotientAndRemainder `3` returns [`2`, `1`]
 * - `7` quotientAndRemainder `-3` returns [`-2`, `1`]
 * - `-7` quotientAndRemainder `3` returns [`-2`, `-1`]
 * - `-7` quotientAndRemainder `-3` returns [`2`, `-1`]
 *
 * @param BigNumber|int|float|string $that The divisor. Must be convertible to a BigInteger.
 *
 * @return array{BigInteger, BigInteger} An array containing the quotient and the remainder.
 *
 * @throws MathException           If the divisor is not valid, or is not convertible to a BigInteger.
 * @throws DivisionByZeroException If the divisor is zero.
 *
 * @pure
 */',
        'startLine' => 648,
        'endLine' => 662,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'mod' => 
      array (
        'name' => 'mod',
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
            'startLine' => 679,
            'endLine' => 679,
            'startColumn' => 25,
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
 * Returns the modulo of this number and the given one.
 *
 * The modulo operation yields the same result as the remainder operation when both operands are of the same sign,
 * and may differ when signs are different.
 *
 * The result of the modulo operation, when non-zero, has the same sign as the divisor.
 *
 * @param BigNumber|int|float|string $that The divisor. Must be convertible to a BigInteger.
 *
 * @throws MathException           If the divisor is not valid, or is not convertible to a BigInteger.
 * @throws DivisionByZeroException If the divisor is zero.
 *
 * @pure
 */',
        'startLine' => 679,
        'endLine' => 698,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'modInverse' => 
      array (
        'name' => 'modInverse',
        'parameters' => 
        array (
          'm' => 
          array (
            'name' => 'm',
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
            'startLine' => 713,
            'endLine' => 713,
            'startColumn' => 32,
            'endColumn' => 60,
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
 * Returns the modular multiplicative inverse of this BigInteger modulo $m.
 *
 * @param BigNumber|int|float|string $m The modulus. Must be convertible to a BigInteger.
 *
 * @throws MathException           If the modulus is not valid, or is not convertible to a BigInteger.
 * @throws DivisionByZeroException If $m is zero.
 * @throws NegativeNumberException If $m is negative.
 * @throws MathException           If this BigInteger has no multiplicative inverse mod m (that is, this BigInteger
 *                                 is not relatively prime to m).
 *
 * @pure
 */',
        'startLine' => 713,
        'endLine' => 736,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'modPow' => 
      array (
        'name' => 'modPow',
        'parameters' => 
        array (
          'exp' => 
          array (
            'name' => 'exp',
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
            'startLine' => 752,
            'endLine' => 752,
            'startColumn' => 28,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'mod' => 
          array (
            'name' => 'mod',
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
            'startLine' => 752,
            'endLine' => 752,
            'startColumn' => 61,
            'endColumn' => 91,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns this number raised into power with modulo.
 *
 * This operation requires a non-negative exponent and a strictly positive modulus.
 *
 * @param BigNumber|int|float|string $exp The exponent. Must be convertible to a BigInteger.
 * @param BigNumber|int|float|string $mod The modulus. Must be convertible to a BigInteger.
 *
 * @throws MathException           If the exponent or modulus is not valid, or is not convertible to a BigInteger.
 * @throws NegativeNumberException If the exponent or modulus is negative.
 * @throws DivisionByZeroException If the modulus is zero.
 *
 * @pure
 */',
        'startLine' => 752,
        'endLine' => 772,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'gcd' => 
      array (
        'name' => 'gcd',
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
            'startLine' => 785,
            'endLine' => 785,
            'startColumn' => 25,
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
 * Returns the greatest common divisor of this number and the given one.
 *
 * The GCD is always positive, unless both operands are zero, in which case it is zero.
 *
 * @param BigNumber|int|float|string $that The operand. Must be convertible to a BigInteger.
 *
 * @throws MathException If the operand is not valid, or is not convertible to a BigInteger.
 *
 * @pure
 */',
        'startLine' => 785,
        'endLine' => 800,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'lcm' => 
      array (
        'name' => 'lcm',
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
            'startLine' => 813,
            'endLine' => 813,
            'startColumn' => 25,
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
 * Returns the least common multiple of this number and the given one.
 *
 * The LCM is always positive, unless at least one operand is zero, in which case it is zero.
 *
 * @param BigNumber|int|float|string $that The operand. Must be convertible to a BigInteger.
 *
 * @throws MathException If the operand is not valid, or is not convertible to a BigInteger.
 *
 * @pure
 */',
        'startLine' => 813,
        'endLine' => 824,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'sqrt' => 
      array (
        'name' => 'sqrt',
        'parameters' => 
        array (
          'roundingMode' => 
          array (
            'name' => 'roundingMode',
            'default' => 
            array (
              'code' => '\\Brick\\Math\\RoundingMode::Down',
              'attributes' => 
              array (
                'startLine' => 839,
                'endLine' => 839,
                'startTokenPos' => 3546,
                'startFilePos' => 27870,
                'endTokenPos' => 3548,
                'endFilePos' => 27887,
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
            'startLine' => 839,
            'endLine' => 839,
            'startColumn' => 26,
            'endColumn' => 72,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the integer square root of this number, rounded according to the given rounding mode.
 *
 * @param RoundingMode $roundingMode The rounding mode to use, defaults to Down.
 *                                   ⚠️ WARNING: the default rounding mode was kept as Down for backward
 *                                   compatibility, but will change to Unnecessary in version 0.15. Pass a rounding
 *                                   mode explicitly to avoid this upcoming breaking change.
 *
 * @throws NegativeNumberException    If this number is negative.
 * @throws RoundingNecessaryException If RoundingMode::Unnecessary is used, and the number is not a perfect square.
 *
 * @pure
 */',
        'startLine' => 839,
        'endLine' => 900,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
        'startLine' => 902,
        'endLine' => 906,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'and' => 
      array (
        'name' => 'and',
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
            'startLine' => 919,
            'endLine' => 919,
            'startColumn' => 25,
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
 * Returns the integer bitwise-and combined with another integer.
 *
 * This method returns a negative BigInteger if and only if both operands are negative.
 *
 * @param BigNumber|int|float|string $that The operand. Must be convertible to a BigInteger.
 *
 * @throws MathException If the operand is not valid, or is not convertible to a BigInteger.
 *
 * @pure
 */',
        'startLine' => 919,
        'endLine' => 924,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'or' => 
      array (
        'name' => 'or',
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
            'startLine' => 937,
            'endLine' => 937,
            'startColumn' => 24,
            'endColumn' => 55,
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
 * Returns the integer bitwise-or combined with another integer.
 *
 * This method returns a negative BigInteger if and only if either of the operands is negative.
 *
 * @param BigNumber|int|float|string $that The operand. Must be convertible to a BigInteger.
 *
 * @throws MathException If the operand is not valid, or is not convertible to a BigInteger.
 *
 * @pure
 */',
        'startLine' => 937,
        'endLine' => 942,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'xor' => 
      array (
        'name' => 'xor',
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
            'startLine' => 955,
            'endLine' => 955,
            'startColumn' => 25,
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
 * Returns the integer bitwise-xor combined with another integer.
 *
 * This method returns a negative BigInteger if and only if exactly one of the operands is negative.
 *
 * @param BigNumber|int|float|string $that The operand. Must be convertible to a BigInteger.
 *
 * @throws MathException If the operand is not valid, or is not convertible to a BigInteger.
 *
 * @pure
 */',
        'startLine' => 955,
        'endLine' => 960,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'not' => 
      array (
        'name' => 'not',
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
 * Returns the bitwise-not of this BigInteger.
 *
 * @pure
 */',
        'startLine' => 967,
        'endLine' => 970,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'shiftedLeft' => 
      array (
        'name' => 'shiftedLeft',
        'parameters' => 
        array (
          'distance' => 
          array (
            'name' => 'distance',
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
            'startLine' => 979,
            'endLine' => 979,
            'startColumn' => 33,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the integer left shifted by a given number of bits.
 *
 * @throws InvalidArgumentException If the number of bits is out of range.
 *
 * @pure
 */',
        'startLine' => 979,
        'endLine' => 990,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'shiftedRight' => 
      array (
        'name' => 'shiftedRight',
        'parameters' => 
        array (
          'distance' => 
          array (
            'name' => 'distance',
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
            'startLine' => 999,
            'endLine' => 999,
            'startColumn' => 34,
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
            'name' => 'Brick\\Math\\BigInteger',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the integer right shifted by a given number of bits.
 *
 * @throws InvalidArgumentException If the number of bits is out of range.
 *
 * @pure
 */',
        'startLine' => 999,
        'endLine' => 1016,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'getBitLength' => 
      array (
        'name' => 'getBitLength',
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
 * Returns the number of bits in the minimal two\'s-complement representation of this BigInteger, excluding a sign bit.
 *
 * For positive BigIntegers, this is equivalent to the number of bits in the ordinary binary representation.
 * Computes (ceil(log2(this < 0 ? -this : this+1))).
 *
 * @pure
 */',
        'startLine' => 1026,
        'endLine' => 1037,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'getLowestSetBit' => 
      array (
        'name' => 'getLowestSetBit',
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
 * Returns the index of the rightmost (lowest-order) one bit in this BigInteger.
 *
 * Returns -1 if this BigInteger contains no one bits.
 *
 * @pure
 */',
        'startLine' => 1046,
        'endLine' => 1060,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'isBitSet' => 
      array (
        'name' => 'isBitSet',
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
            'startLine' => 1073,
            'endLine' => 1073,
            'startColumn' => 30,
            'endColumn' => 35,
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
 * Returns true if and only if the designated bit is set.
 *
 * Computes ((this & (1<<n)) != 0).
 *
 * @param int $n The bit to test, 0-based.
 *
 * @throws InvalidArgumentException If the bit to test is negative.
 *
 * @pure
 */',
        'startLine' => 1073,
        'endLine' => 1080,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'isEven' => 
      array (
        'name' => 'isEven',
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
 * Returns whether this number is even.
 *
 * @pure
 */',
        'startLine' => 1087,
        'endLine' => 1090,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'isOdd' => 
      array (
        'name' => 'isOdd',
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
 * Returns whether this number is odd.
 *
 * @pure
 */',
        'startLine' => 1097,
        'endLine' => 1100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'testBit' => 
      array (
        'name' => 'testBit',
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
            'startLine' => 1113,
            'endLine' => 1113,
            'startColumn' => 29,
            'endColumn' => 34,
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
 * Returns true if and only if the designated bit is set.
 *
 * Computes ((this & (1<<n)) != 0).
 *
 * @deprecated Use isBitSet().
 *
 * @param int $n The bit to test, 0-based.
 *
 * @throws InvalidArgumentException If the bit to test is negative.
 */',
        'startLine' => 1113,
        'endLine' => 1121,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'startLine' => 1124,
            'endLine' => 1124,
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
        'startLine' => 1123,
        'endLine' => 1133,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
        'startLine' => 1135,
        'endLine' => 1139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
        'startLine' => 1141,
        'endLine' => 1145,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
        'startLine' => 1147,
        'endLine' => 1151,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
        'startLine' => 1153,
        'endLine' => 1157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'startLine' => 1160,
            'endLine' => 1160,
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
                'startLine' => 1160,
                'endLine' => 1160,
                'startTokenPos' => 5039,
                'startFilePos' => 37206,
                'endTokenPos' => 5041,
                'endFilePos' => 37230,
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
            'startLine' => 1160,
            'endLine' => 1160,
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
        'startLine' => 1159,
        'endLine' => 1163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
        'startLine' => 1165,
        'endLine' => 1175,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
        'startLine' => 1177,
        'endLine' => 1181,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'toBase' => 
      array (
        'name' => 'toBase',
        'parameters' => 
        array (
          'base' => 
          array (
            'name' => 'base',
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
            'startLine' => 1192,
            'endLine' => 1192,
            'startColumn' => 28,
            'endColumn' => 36,
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
 * Returns a string representation of this number in the given base.
 *
 * The output will always be lowercase for bases greater than 10.
 *
 * @throws InvalidArgumentException If the base is out of range.
 *
 * @pure
 */',
        'startLine' => 1192,
        'endLine' => 1203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'toArbitraryBase' => 
      array (
        'name' => 'toArbitraryBase',
        'parameters' => 
        array (
          'alphabet' => 
          array (
            'name' => 'alphabet',
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
            'startLine' => 1221,
            'endLine' => 1221,
            'startColumn' => 37,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a string representation of this number in an arbitrary base with a custom alphabet.
 *
 * This method is byte-oriented: the alphabet is interpreted as a sequence of single-byte characters.
 * Multibyte UTF-8 characters are not supported.
 *
 * Because this method accepts any single-byte character, including dash, it does not handle negative numbers;
 * a NegativeNumberException will be thrown when attempting to call this method on a negative number.
 *
 * @param string $alphabet The alphabet, for example \'01\' for base 2, or \'01234567\' for base 8.
 *
 * @throws NegativeNumberException  If this number is negative.
 * @throws InvalidArgumentException If the alphabet does not contain at least 2 chars, or contains duplicates.
 *
 * @pure
 */',
        'startLine' => 1221,
        'endLine' => 1238,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
        'aliasName' => NULL,
      ),
      'toBytes' => 
      array (
        'name' => 'toBytes',
        'parameters' => 
        array (
          'signed' => 
          array (
            'name' => 'signed',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 1260,
                'endLine' => 1260,
                'startTokenPos' => 5413,
                'startFilePos' => 40844,
                'endTokenPos' => 5413,
                'endFilePos' => 40847,
              ),
            ),
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
            'startLine' => 1260,
            'endLine' => 1260,
            'startColumn' => 29,
            'endColumn' => 47,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a string of bytes containing the binary representation of this BigInteger.
 *
 * The string is in big-endian byte-order: the most significant byte is in the zeroth element.
 *
 * If `$signed` is true, the output will be in two\'s-complement representation, and a sign bit will be prepended to
 * the output. If `$signed` is false, no sign bit will be prepended, and this method will throw an exception if the
 * number is negative.
 *
 * The string will contain the minimum number of bytes required to represent this BigInteger, including a sign bit
 * if `$signed` is true.
 *
 * This representation is compatible with the `fromBytes()` factory method, as long as the `$signed` flags match.
 *
 * @param bool $signed Whether to output a signed number in two\'s-complement representation with a leading sign bit.
 *
 * @throws NegativeNumberException If $signed is false, and the number is negative.
 *
 * @pure
 */',
        'startLine' => 1260,
        'endLine' => 1302,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
        'startLine' => 1307,
        'endLine' => 1312,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
 * @return array{value: string}
 */',
        'startLine' => 1321,
        'endLine' => 1324,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'startLine' => 1335,
            'endLine' => 1335,
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
 * @param array{value: string} $data
 *
 * @throws LogicException
 */',
        'startLine' => 1335,
        'endLine' => 1344,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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
            'startLine' => 1347,
            'endLine' => 1347,
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
        'startLine' => 1346,
        'endLine' => 1350,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Brick\\Math',
        'declaringClassName' => 'Brick\\Math\\BigInteger',
        'implementingClassName' => 'Brick\\Math\\BigInteger',
        'currentClassName' => 'Brick\\Math\\BigInteger',
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