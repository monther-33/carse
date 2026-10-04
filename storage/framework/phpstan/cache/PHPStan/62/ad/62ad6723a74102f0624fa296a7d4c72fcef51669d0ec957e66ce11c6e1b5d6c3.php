<?php declare(strict_types = 1);

// phpinternal-PHPStan\BetterReflection\Reflection\ReflectionClass-datetimeimmutable
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v3-6.73.0.5-dev-master@e4f5f6c-8.2.12-',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\InternalLocatedSource',
      'data' => 
      array (
        'name' => 'DateTimeImmutable',
        'filename' => 'phpstorm-stubs:date/date_c.stub',
        'extensionName' => 'date',
        'aliasName' => NULL,
      ),
    ),
    'namespace' => NULL,
    'name' => 'DateTimeImmutable',
    'shortName' => 'DateTimeImmutable',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Representation of date and time.
 *
 * This class behaves the same as DateTime except new objects are returned when modification methods
 * such as DateTime::modify are called.
 *
 * @link https://php.net/manual/en/class.datetimeimmutable.php
 * @since 5.5
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 466,
    'startColumn' => 5,
    'endColumn' => 5,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'DateTimeInterface',
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'datetime' => 
          array (
            'name' => 'datetime',
            'default' => 
            array (
              'code' => '"now"',
              'attributes' => 
              array (
                'startLine' => 35,
                'endLine' => 35,
                'startTokenPos' => 70,
                'startFilePos' => 1808,
                'endTokenPos' => 70,
                'endFilePos' => 1812,
              ),
            ),
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'string\']',
                    'attributes' => 
                    array (
                      'startLine' => 34,
                      'endLine' => 34,
                      'startTokenPos' => 48,
                      'startFilePos' => 1742,
                      'endTokenPos' => 54,
                      'endFilePos' => 1760,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 34,
                      'endLine' => 34,
                      'startTokenPos' => 60,
                      'startFilePos' => 1772,
                      'endTokenPos' => 60,
                      'endFilePos' => 1773,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 34,
            'endLine' => 35,
            'startColumn' => 13,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'timezone' => 
          array (
            'name' => 'timezone',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 37,
                'endLine' => 37,
                'startTokenPos' => 100,
                'startFilePos' => 1981,
                'endTokenPos' => 100,
                'endFilePos' => 1984,
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
                      'name' => 'DateTimeZone',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'DateTimeZone|null\']',
                    'attributes' => 
                    array (
                      'startLine' => 36,
                      'endLine' => 36,
                      'startTokenPos' => 76,
                      'startFilePos' => 1881,
                      'endTokenPos' => 82,
                      'endFilePos' => 1910,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'DateTimeZone\'',
                    'attributes' => 
                    array (
                      'startLine' => 36,
                      'endLine' => 36,
                      'startTokenPos' => 88,
                      'startFilePos' => 1922,
                      'endTokenPos' => 88,
                      'endFilePos' => 1935,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 36,
            'endLine' => 37,
            'startColumn' => 13,
            'endColumn' => 46,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\PhpStormStubsElementAvailable',
            'isRepeated' => false,
            'arguments' => 
            array (
              'from' => 
              array (
                'code' => '\'5.5\'',
                'attributes' => 
                array (
                  'startLine' => 32,
                  'endLine' => 32,
                  'startTokenPos' => 28,
                  'startFilePos' => 1620,
                  'endTokenPos' => 28,
                  'endFilePos' => 1624,
                ),
              ),
              'to' => 
              array (
                'code' => '\'8.2\'',
                'attributes' => 
                array (
                  'startLine' => 32,
                  'endLine' => 32,
                  'startTokenPos' => 34,
                  'startFilePos' => 1631,
                  'endTokenPos' => 34,
                  'endFilePos' => 1635,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * @link https://php.net/manual/en/datetimeimmutable.construct.php
 * @param string $datetime [optional]
 * <p>A date/time string. Valid formats are explained in {@link https://php.net/manual/en/datetime.formats.php Date and Time Formats}.</p>
 * <p>Enter <b>\'now\'</b> here to obtain the current time when using the <em>$timezone</em> parameter.</p>
 * @param null|DateTimeZone $timezone [optional] <p>
 * A {@link https://php.net/manual/en/class.datetimezone.php DateTimeZone} object representing the timezone of <em>$datetime</em>.
 * </p>
 * <p>If <em>$timezone</em> is omitted, the current timezone will be used.</p>
 * <blockquote><p><b>Note</b>:</p><p>
 * The <em>$timezone</em> parameter and the current timezone are ignored when the <em>$datetime</em> parameter either
 * is a UNIX timestamp (e.g. <em>@946684800</em>) or specifies a timezone (e.g. <em>2010-01-28T15:00:00+02:00</em>).
 * </p></blockquote>
 * @throws Exception Emits Exception in case of an error.
 */',
        'startLine' => 32,
        'endLine' => 40,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'add' => 
      array (
        'name' => 'add',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 29,
            'endColumn' => 51,
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
            'name' => 'DateTimeImmutable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
          1 => 
          array (
            'name' => 'NoDiscard',
            'isRepeated' => false,
            'arguments' => 
            array (
              'message' => 
              array (
                'code' => '"as DateTimeImmutable::add() does not modify the object itself"',
                'attributes' => 
                array (
                  'startLine' => 50,
                  'endLine' => 50,
                  'startTokenPos' => 120,
                  'startFilePos' => 2505,
                  'endTokenPos' => 120,
                  'endFilePos' => 2567,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Adds an amount of days, months, years, hours, minutes and seconds
 * @param DateInterval $interval A DateInterval object
 * @return static Returns a new DateTimeImmutable object with the modified data.
 * @link https://php.net/manual/en/datetimeimmutable.add.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 49,
        'endLine' => 53,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'createFromFormat' => 
      array (
        'name' => 'createFromFormat',
        'parameters' => 
        array (
          'format' => 
          array (
            'name' => 'format',
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'string\']',
                    'attributes' => 
                    array (
                      'startLine' => 143,
                      'endLine' => 143,
                      'startTokenPos' => 170,
                      'startFilePos' => 11217,
                      'endTokenPos' => 176,
                      'endFilePos' => 11235,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 143,
                      'endLine' => 143,
                      'startTokenPos' => 182,
                      'startFilePos' => 11247,
                      'endTokenPos' => 182,
                      'endFilePos' => 11248,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 143,
            'endLine' => 144,
            'startColumn' => 13,
            'endColumn' => 26,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'datetime' => 
          array (
            'name' => 'datetime',
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'string\']',
                    'attributes' => 
                    array (
                      'startLine' => 145,
                      'endLine' => 145,
                      'startTokenPos' => 194,
                      'startFilePos' => 11346,
                      'endTokenPos' => 200,
                      'endFilePos' => 11364,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 145,
                      'endLine' => 145,
                      'startTokenPos' => 206,
                      'startFilePos' => 11376,
                      'endTokenPos' => 206,
                      'endFilePos' => 11377,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 145,
            'endLine' => 146,
            'startColumn' => 13,
            'endColumn' => 28,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'timezone' => 
          array (
            'name' => 'timezone',
            'default' => 
            array (
              'code' => '\\null',
              'attributes' => 
              array (
                'startLine' => 148,
                'endLine' => 148,
                'startTokenPos' => 242,
                'startFilePos' => 11577,
                'endTokenPos' => 242,
                'endFilePos' => 11580,
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
                      'name' => 'DateTimeZone',
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'DateTimeZone|null\']',
                    'attributes' => 
                    array (
                      'startLine' => 147,
                      'endLine' => 147,
                      'startTokenPos' => 218,
                      'startFilePos' => 11477,
                      'endTokenPos' => 224,
                      'endFilePos' => 11506,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'DateTimeZone\'',
                    'attributes' => 
                    array (
                      'startLine' => 147,
                      'endLine' => 147,
                      'startTokenPos' => 230,
                      'startFilePos' => 11518,
                      'endTokenPos' => 230,
                      'endFilePos' => 11531,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 147,
            'endLine' => 148,
            'startColumn' => 13,
            'endColumn' => 46,
            'parameterIndex' => 2,
            'isOptional' => true,
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
                  'name' => 'DateTimeImmutable',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'false',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
          1 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\PhpStormStubsElementAvailable',
            'isRepeated' => false,
            'arguments' => 
            array (
              'from' => 
              array (
                'code' => '\'8.0\'',
                'attributes' => 
                array (
                  'startLine' => 141,
                  'endLine' => 141,
                  'startTokenPos' => 154,
                  'startFilePos' => 11094,
                  'endTokenPos' => 154,
                  'endFilePos' => 11098,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Returns new DateTimeImmutable object formatted according to the specified format
 * @link https://php.net/manual/en/datetimeimmutable.createfromformat.php
 * @param string $format The format that the passed in string should be in. See the formatting
 * options below. In most cases, the same letters as for the date can be used. All fields are
 * initialised with the current date/time. In most cases you would want to reset these to "zero"
 * (the Unix epoch, 1970-01-01 00:00:00 UTC). You do that by including the ! character as first
 * character in your format, or | as your last. Please see the documentation for each character
 * below for more information. The format is parsed from left to right, which means that in some
 * situations the order in which the format characters are present affects the result. In the
 * case of z (the day of the year), it is required that a year has already been parsed, for
 * example through the Y or y characters. Letters that are used for parsing numbers allow a wide
 * range of values, outside of what the logical range would be. For example, the d (day of the
 * month) accepts values in the range from 00 to 99. The only constraint is on the amount of
 * digits. The date/time parser\'s overflow mechanism is used when out-of-range values are given.
 * The examples below show some of this behaviour. This also means that the data parsed for a
 * format letter is greedy, and will read up to the amount of digits its format allows for. That
 * can then also mean that there are no longer enough characters in the datetime string for
 * following format characters. An example on this page also illustrates this issue. The
 * following characters are recognized in the format parameter string format character
 * Description Example parsable values Day --- --- d and j Day of the month, 2 digits with or
 * without leading zeros 01 to 31 or 1 to 31. (2 digit numbers higher than the number of days in
 * the month are accepted, in which case they will make the month overflow. For example using 33
 * with January, means February 2nd) D and l A textual representation of a day Mon through Sun
 * or Sunday through Saturday. If the day name given is different than the day name belonging to
 * a parsed (or default) date is different, then an overflow occurs to the next date with the
 * given day name. See the examples below for an explanation. S English ordinal suffix for the
 * day of the month, 2 characters. It\'s ignored while processing. st, nd, rd or th. z The day of
 * the year (starting from 0); must be preceded by Y or y. 0 through 365. (3 digit numbers
 * higher than the numbers in a year are accepted, in which case they will make the year
 * overflow. For example using 366 with 2022, means January 2nd, 2023) Month --- --- F and M A
 * textual representation of a month, such as January or Sept January through December or Jan
 * through Dec m and n Numeric representation of a month, with or without leading zeros 01
 * through 12 or 1 through 12. (2 digit numbers higher than 12 are accepted, in which case they
 * will make the year overflow. For example using 13 means January in the next year) Year ---
 * --- X and x A full numeric representation of a year, up to 19 digits, optionally prefixed by
 * + or - Examples: 0055, 787, 1999, -2003, +10191 Y A full numeric representation of a year, up
 * to 4 digits Examples: 25 (same as 0025), 787, 1999, 2003 y A two digit representation of a
 * year (which is assumed to be in the range 1970-2069, inclusive) Examples: 99 or 03 (which
 * will be interpreted as 1999 and 2003, respectively) Time --- --- a and A Ante meridiem and
 * Post meridiem am or pm g and h 12-hour format of an hour with or without leading zero 1
 * through 12 or 01 through 12 (2 digit numbers higher than 12 are accepted, in which case they
 * will make the day overflow. For example using 14 means 02 in the next AM/PM period) G and H
 * 24-hour format of an hour with or without leading zeros 0 through 23 or 00 through 23 (2
 * digit numbers higher than 24 are accepted, in which case they will make the day overflow. For
 * example using 26 means 02:00 the next day) i Minutes with leading zeros 00 to 59. (2 digit
 * numbers higher than 59 are accepted, in which case they will make the hour overflow. For
 * example using 66 means :06 the next hour) s Seconds, with leading zeros 00 through 59 (2
 * digit numbers higher than 59 are accepted, in which case they will make the minute overflow.
 * For example using 90 means :30 the next minute) v Fraction in milliseconds (up to three
 * digits) Example: 12 (0.12 seconds), 345 (0.345 seconds) u Fraction in microseconds (up to six
 * digits) Example: 45 (0.45 seconds), 654321 (0.654321 seconds) Timezone --- --- e, O, p, P and
 * T Timezone identifier, or difference to UTC in hours, or difference to UTC with colon between
 * hours and minutes, or timezone abbreviation Examples: UTC, GMT, Atlantic/Azores or +0200 or
 * +02:00 or EST, MDT Full Date/Time --- --- U Seconds since the Unix Epoch (January 1 1970
 * 00:00:00 GMT) Example: 1292177455 Whitespace and Separators --- --- (space) Zero or more
 * spaces, tabs, NBSP (U+A0), or NNBSP (U+202F) characters Example: "\\t", " " # One of the
 * following separation symbol: ;, :, /, ., ,, -, ( or ) Example: / ;, :, /, ., ,, -, ( or ) The
 * specified character. Example: - ? A random byte Example: ^ (Be aware that for UTF-8
 * characters you might need more than one ?. In this case, using * is probably what you want
 * instead) * Random bytes until the next separator or digit Example: * in Y-*-d with the string
 * 2009-aWord-08 will match aWord ! Resets all fields (year, month, day, hour, minute, second,
 * fraction and timezone information) to zero-like values ( 0 for hour, minute, second and
 * fraction, 1 for month and day, 1970 for year and the default timezone) Without !, all fields
 * will be set to the current date and time. | Resets all fields (year, month, day, hour,
 * minute, second, fraction and timezone information) to zero-like values if they have not been
 * parsed yet Y-m-d| will set the year, month and day to the information found in the string to
 * parse, and sets the hour, minute and second to 0. + If this format specifier is present,
 * trailing data in the string will not cause an error, but a warning instead Use
 * DateTimeImmutable::getLastErrors to find out whether trailing data was present. Unrecognized
 * characters in the format string will cause the parsing to fail and an error message is
 * appended to the returned structure. You can query error messages with
 * DateTimeImmutable::getLastErrors. To include literal characters in format, you have to escape
 * them with a backslash (\\). If format does not contain the character ! then portions of the
 * generated date/time which are not specified in format will be set to the current system time.
 * If format contains the character !, then portions of the generated date/time not provided in
 * format, as well as values to the left-hand side of the !, will be set to corresponding values
 * from the Unix epoch. If any time character is parsed, then all other time-related fields are
 * set to "0", unless also parsed. The Unix epoch is 1970-01-01 00:00:00 UTC.
 * @param string $datetime String representing the time.
 * @param null|DateTimeZone $timezone [optional]
 * @return DateTimeImmutable|false Returns a new DateTimeImmutable instance or false on failure.
 * @throws ValueError when the datetime contains NULL-bytes.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 140,
        'endLine' => 151,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'createFromMutable' => 
      array (
        'name' => 'createFromMutable',
        'parameters' => 
        array (
          'object' => 
          array (
            'name' => 'object',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateTime',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 162,
            'endLine' => 162,
            'startColumn' => 50,
            'endColumn' => 66,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
          1 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
            'isRepeated' => false,
            'arguments' => 
            array (
              0 => 
              array (
                'code' => '[\'8.2\' => \'static\']',
                'attributes' => 
                array (
                  'startLine' => 161,
                  'endLine' => 161,
                  'startTokenPos' => 264,
                  'startFilePos' => 12363,
                  'endTokenPos' => 270,
                  'endFilePos' => 12381,
                ),
              ),
              'default' => 
              array (
                'code' => '\'DateTimeImmutable\'',
                'attributes' => 
                array (
                  'startLine' => 161,
                  'endLine' => 161,
                  'startTokenPos' => 276,
                  'startFilePos' => 12393,
                  'endTokenPos' => 276,
                  'endFilePos' => 12411,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.6.0)<br/>
 * Returns new DateTimeImmutable object encapsulating the given DateTime object
 * @link https://php.net/manual/en/datetimeimmutable.createfrommutable.php
 * @param DateTime $object The mutable DateTime object that you want to convert to an immutable version. This object is not modified, but instead a new DateTimeImmutable object is created containing the same date time and timezone information.
 * @return DateTimeImmutable returns a new DateTimeImmutable instance.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 160,
        'endLine' => 164,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'getLastErrors' => 
      array (
        'name' => 'getLastErrors',
        'parameters' => 
        array (
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
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'false',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\ArrayShape',
            'isRepeated' => false,
            'arguments' => 
            array (
              0 => 
              array (
                'code' => '["warning_count" => "int", "warnings" => "string[]", "error_count" => "int", "errors" => "string[]"]',
                'attributes' => 
                array (
                  'startLine' => 172,
                  'endLine' => 172,
                  'startTokenPos' => 302,
                  'startFilePos' => 12861,
                  'endTokenPos' => 329,
                  'endFilePos' => 12960,
                ),
              ),
            ),
          ),
          1 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Returns the warnings and errors
 * @link https://php.net/manual/en/datetimeimmutable.getlasterrors.php
 * @return array|false Returns array containing info about warnings and errors.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 172,
        'endLine' => 176,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'modify' => 
      array (
        'name' => 'modify',
        'parameters' => 
        array (
          'modifier' => 
          array (
            'name' => 'modifier',
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'string\']',
                    'attributes' => 
                    array (
                      'startLine' => 192,
                      'endLine' => 192,
                      'startTokenPos' => 411,
                      'startFilePos' => 14137,
                      'endTokenPos' => 417,
                      'endFilePos' => 14155,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 192,
                      'endLine' => 192,
                      'startTokenPos' => 423,
                      'startFilePos' => 14167,
                      'endTokenPos' => 423,
                      'endFilePos' => 14168,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 192,
            'endLine' => 193,
            'startColumn' => 13,
            'endColumn' => 28,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\PhpStormStubsElementAvailable',
            'isRepeated' => false,
            'arguments' => 
            array (
              'from' => 
              array (
                'code' => '\'5.5\'',
                'attributes' => 
                array (
                  'startLine' => 187,
                  'endLine' => 187,
                  'startTokenPos' => 364,
                  'startFilePos' => 13810,
                  'endTokenPos' => 364,
                  'endFilePos' => 13814,
                ),
              ),
              'to' => 
              array (
                'code' => '\'8.2\'',
                'attributes' => 
                array (
                  'startLine' => 187,
                  'endLine' => 187,
                  'startTokenPos' => 370,
                  'startFilePos' => 13821,
                  'endTokenPos' => 370,
                  'endFilePos' => 13825,
                ),
              ),
            ),
          ),
          1 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Pure',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
          2 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
          3 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
            'isRepeated' => false,
            'arguments' => 
            array (
              0 => 
              array (
                'code' => '[\'8.4\' => \'DateTimeImmutable\']',
                'attributes' => 
                array (
                  'startLine' => 190,
                  'endLine' => 190,
                  'startTokenPos' => 385,
                  'startFilePos' => 13981,
                  'endTokenPos' => 391,
                  'endFilePos' => 14010,
                ),
              ),
              'default' => 
              array (
                'code' => '\'static|false\'',
                'attributes' => 
                array (
                  'startLine' => 190,
                  'endLine' => 190,
                  'startTokenPos' => 397,
                  'startFilePos' => 14022,
                  'endTokenPos' => 397,
                  'endFilePos' => 14035,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Alters the timestamp
 * @link https://php.net/manual/en/datetimeimmutable.modify.php
 * @param string $modifier <p>A date/time string. Valid formats are explained in
 * {@link https://php.net/manual/en/datetime.formats.php Date and Time Formats}.</p>
 * @return static|false Returns the newly created object or false on failure.
 * Returns the {@link https://php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining or <b>FALSE</b> on failure.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 187,
        'endLine' => 196,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      '__set_state' => 
      array (
        'name' => '__set_state',
        'parameters' => 
        array (
          'array' => 
          array (
            'name' => 'array',
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
            'startLine' => 207,
            'endLine' => 207,
            'startColumn' => 44,
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
            'name' => 'static',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * The __set_state handler
 * @link https://php.net/manual/en/datetimeimmutable.set-state.php
 * @param array $array <p>Initialization array.</p>
 * @return DateTimeImmutable
 * Returns a new instance of a {@link https://php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 206,
        'endLine' => 209,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'setDate' => 
      array (
        'name' => 'setDate',
        'parameters' => 
        array (
          'year' => 
          array (
            'name' => 'year',
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'int\']',
                    'attributes' => 
                    array (
                      'startLine' => 225,
                      'endLine' => 225,
                      'startTokenPos' => 489,
                      'startFilePos' => 15652,
                      'endTokenPos' => 495,
                      'endFilePos' => 15667,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 225,
                      'endLine' => 225,
                      'startTokenPos' => 501,
                      'startFilePos' => 15679,
                      'endTokenPos' => 501,
                      'endFilePos' => 15680,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 225,
            'endLine' => 226,
            'startColumn' => 13,
            'endColumn' => 21,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'month' => 
          array (
            'name' => 'month',
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'int\']',
                    'attributes' => 
                    array (
                      'startLine' => 227,
                      'endLine' => 227,
                      'startTokenPos' => 513,
                      'startFilePos' => 15773,
                      'endTokenPos' => 519,
                      'endFilePos' => 15788,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 227,
                      'endLine' => 227,
                      'startTokenPos' => 525,
                      'startFilePos' => 15800,
                      'endTokenPos' => 525,
                      'endFilePos' => 15801,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 227,
            'endLine' => 228,
            'startColumn' => 13,
            'endColumn' => 22,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'day' => 
          array (
            'name' => 'day',
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'int\']',
                    'attributes' => 
                    array (
                      'startLine' => 229,
                      'endLine' => 229,
                      'startTokenPos' => 537,
                      'startFilePos' => 15895,
                      'endTokenPos' => 543,
                      'endFilePos' => 15910,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 229,
                      'endLine' => 229,
                      'startTokenPos' => 549,
                      'startFilePos' => 15922,
                      'endTokenPos' => 549,
                      'endFilePos' => 15923,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 229,
            'endLine' => 230,
            'startColumn' => 13,
            'endColumn' => 20,
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
            'name' => 'DateTimeImmutable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
          1 => 
          array (
            'name' => 'NoDiscard',
            'isRepeated' => false,
            'arguments' => 
            array (
              'message' => 
              array (
                'code' => '"as DateTimeImmutable::setDate() does not modify the object itself"',
                'attributes' => 
                array (
                  'startLine' => 223,
                  'endLine' => 223,
                  'startTokenPos' => 475,
                  'startFilePos' => 15483,
                  'endTokenPos' => 475,
                  'endFilePos' => 15549,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Sets the date
 * @link https://php.net/manual/en/datetimeimmutable.setdate.php
 * @param int $year <p>Year of the date.</p>
 * @param int $month <p>Month of the date.</p>
 * @param int $day <p>Day of the date.</p>
 * @return static
 * Returns the {@link https://php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining.
 * Prior to PHP 8.1, <b>FALSE</b> was returned on failure.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 222,
        'endLine' => 233,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'setISODate' => 
      array (
        'name' => 'setISODate',
        'parameters' => 
        array (
          'year' => 
          array (
            'name' => 'year',
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'int\']',
                    'attributes' => 
                    array (
                      'startLine' => 249,
                      'endLine' => 249,
                      'startTokenPos' => 592,
                      'startFilePos' => 16884,
                      'endTokenPos' => 598,
                      'endFilePos' => 16899,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 249,
                      'endLine' => 249,
                      'startTokenPos' => 604,
                      'startFilePos' => 16911,
                      'endTokenPos' => 604,
                      'endFilePos' => 16912,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 249,
            'endLine' => 250,
            'startColumn' => 13,
            'endColumn' => 21,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'week' => 
          array (
            'name' => 'week',
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'int\']',
                    'attributes' => 
                    array (
                      'startLine' => 251,
                      'endLine' => 251,
                      'startTokenPos' => 616,
                      'startFilePos' => 17005,
                      'endTokenPos' => 622,
                      'endFilePos' => 17020,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 251,
                      'endLine' => 251,
                      'startTokenPos' => 628,
                      'startFilePos' => 17032,
                      'endTokenPos' => 628,
                      'endFilePos' => 17033,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 251,
            'endLine' => 252,
            'startColumn' => 13,
            'endColumn' => 21,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'dayOfWeek' => 
          array (
            'name' => 'dayOfWeek',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 254,
                'endLine' => 254,
                'startTokenPos' => 662,
                'startFilePos' => 17187,
                'endTokenPos' => 662,
                'endFilePos' => 17187,
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'int\']',
                    'attributes' => 
                    array (
                      'startLine' => 253,
                      'endLine' => 253,
                      'startTokenPos' => 640,
                      'startFilePos' => 17126,
                      'endTokenPos' => 646,
                      'endFilePos' => 17141,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 253,
                      'endLine' => 253,
                      'startTokenPos' => 652,
                      'startFilePos' => 17153,
                      'endTokenPos' => 652,
                      'endFilePos' => 17154,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 253,
            'endLine' => 254,
            'startColumn' => 13,
            'endColumn' => 30,
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
            'name' => 'DateTimeImmutable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
          1 => 
          array (
            'name' => 'NoDiscard',
            'isRepeated' => false,
            'arguments' => 
            array (
              'message' => 
              array (
                'code' => '"as DateTimeImmutable::setISODate() does not modify the object itself"',
                'attributes' => 
                array (
                  'startLine' => 247,
                  'endLine' => 247,
                  'startTokenPos' => 578,
                  'startFilePos' => 16709,
                  'endTokenPos' => 578,
                  'endFilePos' => 16778,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Sets the ISO date
 * @link https://php.net/manual/en/class.datetimeimmutable.php
 * @param int $year <p>Year of the date.</p>
 * @param int $week <p>Week of the date.</p>
 * @param int $dayOfWeek [optional] <p>Offset from the first day of the week.</p>
 * @return static
 * Returns the {@link https://php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining.
 * Prior to PHP 8.1, <b>FALSE</b> was returned on failure.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 246,
        'endLine' => 257,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'setTime' => 
      array (
        'name' => 'setTime',
        'parameters' => 
        array (
          'hour' => 
          array (
            'name' => 'hour',
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'int\']',
                    'attributes' => 
                    array (
                      'startLine' => 275,
                      'endLine' => 275,
                      'startTokenPos' => 703,
                      'startFilePos' => 18237,
                      'endTokenPos' => 709,
                      'endFilePos' => 18252,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 275,
                      'endLine' => 275,
                      'startTokenPos' => 715,
                      'startFilePos' => 18264,
                      'endTokenPos' => 715,
                      'endFilePos' => 18265,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 275,
            'endLine' => 276,
            'startColumn' => 13,
            'endColumn' => 21,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'minute' => 
          array (
            'name' => 'minute',
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'int\']',
                    'attributes' => 
                    array (
                      'startLine' => 277,
                      'endLine' => 277,
                      'startTokenPos' => 727,
                      'startFilePos' => 18358,
                      'endTokenPos' => 733,
                      'endFilePos' => 18373,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 277,
                      'endLine' => 277,
                      'startTokenPos' => 739,
                      'startFilePos' => 18385,
                      'endTokenPos' => 739,
                      'endFilePos' => 18386,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 277,
            'endLine' => 278,
            'startColumn' => 13,
            'endColumn' => 23,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'second' => 
          array (
            'name' => 'second',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 280,
                'endLine' => 280,
                'startTokenPos' => 773,
                'startFilePos' => 18539,
                'endTokenPos' => 773,
                'endFilePos' => 18539,
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'int\']',
                    'attributes' => 
                    array (
                      'startLine' => 279,
                      'endLine' => 279,
                      'startTokenPos' => 751,
                      'startFilePos' => 18481,
                      'endTokenPos' => 757,
                      'endFilePos' => 18496,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 279,
                      'endLine' => 279,
                      'startTokenPos' => 763,
                      'startFilePos' => 18508,
                      'endTokenPos' => 763,
                      'endFilePos' => 18509,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 279,
            'endLine' => 280,
            'startColumn' => 13,
            'endColumn' => 27,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'microsecond' => 
          array (
            'name' => 'microsecond',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 283,
                'endLine' => 283,
                'startTokenPos' => 811,
                'startFilePos' => 18758,
                'endTokenPos' => 811,
                'endFilePos' => 18758,
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\PhpStormStubsElementAvailable',
                'isRepeated' => false,
                'arguments' => 
                array (
                  'from' => 
                  array (
                    'code' => '\'7.1\'',
                    'attributes' => 
                    array (
                      'startLine' => 281,
                      'endLine' => 281,
                      'startTokenPos' => 782,
                      'startFilePos' => 18621,
                      'endTokenPos' => 782,
                      'endFilePos' => 18625,
                    ),
                  ),
                ),
              ),
              1 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'int\']',
                    'attributes' => 
                    array (
                      'startLine' => 282,
                      'endLine' => 282,
                      'startTokenPos' => 789,
                      'startFilePos' => 18695,
                      'endTokenPos' => 795,
                      'endFilePos' => 18710,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 282,
                      'endLine' => 282,
                      'startTokenPos' => 801,
                      'startFilePos' => 18722,
                      'endTokenPos' => 801,
                      'endFilePos' => 18723,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 281,
            'endLine' => 283,
            'startColumn' => 13,
            'endColumn' => 32,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'DateTimeImmutable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
          1 => 
          array (
            'name' => 'NoDiscard',
            'isRepeated' => false,
            'arguments' => 
            array (
              'message' => 
              array (
                'code' => '"as DateTimeImmutable::setTime() does not modify the object itself"',
                'attributes' => 
                array (
                  'startLine' => 272,
                  'endLine' => 272,
                  'startTokenPos' => 685,
                  'startFilePos' => 18032,
                  'endTokenPos' => 685,
                  'endFilePos' => 18098,
                ),
              ),
            ),
          ),
          2 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Pure',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Sets the time
 * @link https://php.net/manual/en/datetimeimmutable.settime.php
 * @param int $hour <p> Hour of the time. </p>
 * @param int $minute <p> Minute of the time. </p>
 * @param int $second [optional] <p> Second of the time. </p>
 * @param int $microsecond [optional] <p> Microseconds of the time. Added since 7.1</p>
 * @return static
 * Returns the {@link https://php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining.
 * Prior to PHP 8.1, <b>FALSE</b> was returned on failure.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 271,
        'endLine' => 286,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'setTimestamp' => 
      array (
        'name' => 'setTimestamp',
        'parameters' => 
        array (
          'timestamp' => 
          array (
            'name' => 'timestamp',
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'int\']',
                    'attributes' => 
                    array (
                      'startLine' => 300,
                      'endLine' => 300,
                      'startTokenPos' => 848,
                      'startFilePos' => 19621,
                      'endTokenPos' => 854,
                      'endFilePos' => 19636,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 300,
                      'endLine' => 300,
                      'startTokenPos' => 860,
                      'startFilePos' => 19648,
                      'endTokenPos' => 860,
                      'endFilePos' => 19649,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 300,
            'endLine' => 301,
            'startColumn' => 13,
            'endColumn' => 26,
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
            'name' => 'DateTimeImmutable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
          1 => 
          array (
            'name' => 'NoDiscard',
            'isRepeated' => false,
            'arguments' => 
            array (
              'message' => 
              array (
                'code' => '"as DateTimeImmutable::setTimestamp() does not modify the object itself"',
                'attributes' => 
                array (
                  'startLine' => 298,
                  'endLine' => 298,
                  'startTokenPos' => 834,
                  'startFilePos' => 19442,
                  'endTokenPos' => 834,
                  'endFilePos' => 19513,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Sets the date and time based on an Unix timestamp
 * @link https://php.net/manual/en/datetimeimmutable.settimestamp.php
 * @param int $timestamp <p>Unix timestamp representing the date.</p>
 * @return static
 * Returns the {@link https://php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining.
 * Prior to PHP 8.1, <b>FALSE</b> was returned on failure.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 297,
        'endLine' => 304,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'setTimezone' => 
      array (
        'name' => 'setTimezone',
        'parameters' => 
        array (
          'timezone' => 
          array (
            'name' => 'timezone',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateTimeZone',
                'isIdentifier' => false,
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
            'startColumn' => 37,
            'endColumn' => 59,
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
            'name' => 'DateTimeImmutable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
          1 => 
          array (
            'name' => 'NoDiscard',
            'isRepeated' => false,
            'arguments' => 
            array (
              'message' => 
              array (
                'code' => '"as DateTimeImmutable::setTimezone() does not modify the object itself"',
                'attributes' => 
                array (
                  'startLine' => 319,
                  'endLine' => 319,
                  'startTokenPos' => 889,
                  'startFilePos' => 20450,
                  'endTokenPos' => 889,
                  'endFilePos' => 20520,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Sets the time zone
 * @link https://php.net/manual/en/datetimeimmutable.settimezone.php
 * @param DateTimeZone $timezone <p>
 * A {@link https://php.net/manual/en/class.datetimezone.php DateTimeZone} object representing the
 * desired time zone.
 * </p>
 * @return static
 * Returns the {@link https://php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining.
 * Prior to PHP 8.1, <b>FALSE</b> was returned on failure.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 318,
        'endLine' => 322,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'sub' => 
      array (
        'name' => 'sub',
        'parameters' => 
        array (
          'interval' => 
          array (
            'name' => 'interval',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateInterval',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 337,
            'endLine' => 337,
            'startColumn' => 29,
            'endColumn' => 51,
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
            'name' => 'DateTimeImmutable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
          1 => 
          array (
            'name' => 'NoDiscard',
            'isRepeated' => false,
            'arguments' => 
            array (
              'message' => 
              array (
                'code' => '"as DateTimeImmutable::sub() does not modify the object itself"',
                'attributes' => 
                array (
                  'startLine' => 336,
                  'endLine' => 336,
                  'startTokenPos' => 923,
                  'startFilePos' => 21414,
                  'endTokenPos' => 923,
                  'endFilePos' => 21476,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Subtracts an amount of days, months, years, hours, minutes and seconds
 * @link https://php.net/manual/en/datetimeimmutable.sub.php
 * @param DateInterval $interval <p>
 * A {@link https://php.net/manual/en/class.dateinterval.php DateInterval} object
 * </p>
 * @return static Returns a new DateTimeImmutable object with the modified data.
 * @throws DateInvalidOperationException
 * Returns the {@link https://php.net/manual/en/class.datetimeimmutable.php DateTimeImmutable} object for method chaining or <b>FALSE</b> on failure.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 335,
        'endLine' => 339,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'diff' => 
      array (
        'name' => 'diff',
        'parameters' => 
        array (
          'targetObject' => 
          array (
            'name' => 'targetObject',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateTimeInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'DateTimeInterface\']',
                    'attributes' => 
                    array (
                      'startLine' => 353,
                      'endLine' => 353,
                      'startTokenPos' => 961,
                      'startFilePos' => 22318,
                      'endTokenPos' => 967,
                      'endFilePos' => 22347,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 353,
                      'endLine' => 353,
                      'startTokenPos' => 973,
                      'startFilePos' => 22359,
                      'endTokenPos' => 973,
                      'endFilePos' => 22360,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 353,
            'endLine' => 354,
            'startColumn' => 13,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'absolute' => 
          array (
            'name' => 'absolute',
            'default' => 
            array (
              'code' => '\\false',
              'attributes' => 
              array (
                'startLine' => 356,
                'endLine' => 356,
                'startTokenPos' => 1007,
                'startFilePos' => 22537,
                'endTokenPos' => 1007,
                'endFilePos' => 22541,
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'bool\']',
                    'attributes' => 
                    array (
                      'startLine' => 355,
                      'endLine' => 355,
                      'startTokenPos' => 985,
                      'startFilePos' => 22475,
                      'endTokenPos' => 991,
                      'endFilePos' => 22491,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 355,
                      'endLine' => 355,
                      'startTokenPos' => 997,
                      'startFilePos' => 22503,
                      'endTokenPos' => 997,
                      'endFilePos' => 22504,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 355,
            'endLine' => 356,
            'startColumn' => 13,
            'endColumn' => 34,
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
            'name' => 'DateInterval',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Returns the difference between two DateTime objects
 * @link https://php.net/manual/en/datetime.diff.php
 * @param DateTimeInterface $targetObject <p>The date to compare to.</p>
 * @param bool $absolute [optional] <p>Should the interval be forced to be positive?</p>
 * @return DateInterval
 * The {@link https://php.net/manual/en/class.dateinterval.php DateInterval} object representing the
 * difference between the two dates.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 351,
        'endLine' => 359,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'format' => 
      array (
        'name' => 'format',
        'parameters' => 
        array (
          'format' => 
          array (
            'name' => 'format',
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'string\']',
                    'attributes' => 
                    array (
                      'startLine' => 375,
                      'endLine' => 375,
                      'startTokenPos' => 1041,
                      'startFilePos' => 23306,
                      'endTokenPos' => 1047,
                      'endFilePos' => 23324,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 375,
                      'endLine' => 375,
                      'startTokenPos' => 1053,
                      'startFilePos' => 23336,
                      'endTokenPos' => 1053,
                      'endFilePos' => 23337,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 375,
            'endLine' => 376,
            'startColumn' => 13,
            'endColumn' => 26,
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
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Pure',
            'isRepeated' => false,
            'arguments' => 
            array (
              0 => 
              array (
                'code' => '\\true',
                'attributes' => 
                array (
                  'startLine' => 372,
                  'endLine' => 372,
                  'startTokenPos' => 1023,
                  'startFilePos' => 23147,
                  'endTokenPos' => 1023,
                  'endFilePos' => 23150,
                ),
              ),
            ),
          ),
          1 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Returns date formatted according to given format
 * @link https://php.net/manual/en/datetime.format.php
 * @param string $format <p>
 * Format accepted by  {@link https://php.net/manual/en/function.date.php date()}.
 * </p>
 * @return string
 * Returns the formatted date string on success.
 * Prior to PHP 8.1, <b>FALSE</b> was returned on failure.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 372,
        'endLine' => 379,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'getOffset' => 
      array (
        'name' => 'getOffset',
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
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Returns the timezone offset
 * @link https://php.net/manual/en/datetime.getoffset.php
 * @return int
 * Returns the timezone offset in seconds from UTC on success.
 * Prior to PHP 8.1, <b>FALSE</b> was returned on failure.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 389,
        'endLine' => 392,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'getTimestamp' => 
      array (
        'name' => 'getTimestamp',
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
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Gets the Unix timestamp
 * @link https://php.net/manual/en/datetime.gettimestamp.php
 * @return int
 * Returns the Unix timestamp representing the date.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 401,
        'endLine' => 404,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'getTimezone' => 
      array (
        'name' => 'getTimezone',
        'parameters' => 
        array (
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
                  'name' => 'DateTimeZone',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'false',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Return time zone relative to given DateTime
 * @link https://php.net/manual/en/datetime.gettimezone.php
 * @return DateTimeZone|false
 * Returns a {@link https://php.net/manual/en/class.datetimezone.php DateTimeZone} object on success
 * or <b>FALSE</b> on failure.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 414,
        'endLine' => 417,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      '__wakeup' => 
      array (
        'name' => '__wakeup',
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
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\TentativeType',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
          1 => 
          array (
            'name' => 'Deprecated',
            'isRepeated' => false,
            'arguments' => 
            array (
              'since' => 
              array (
                'code' => '\'8.5\'',
                'attributes' => 
                array (
                  'startLine' => 426,
                  'endLine' => 426,
                  'startTokenPos' => 1147,
                  'startFilePos' => 25196,
                  'endTokenPos' => 1147,
                  'endFilePos' => 25200,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * The __wakeup handler
 * @link https://php.net/manual/en/datetime.wakeup.php
 * @return void Initializes a DateTime object.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 425,
        'endLine' => 429,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
        'aliasName' => NULL,
      ),
      'createFromInterface' => 
      array (
        'name' => 'createFromInterface',
        'parameters' => 
        array (
          'object' => 
          array (
            'name' => 'object',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'DateTimeInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 439,
            'endLine' => 439,
            'startColumn' => 52,
            'endColumn' => 77,
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
            'name' => 'DateTimeImmutable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns new DateTimeImmutable object encapsulating the given DateTimeInterface object
 * @link https://php.net/manual/en/datetimeimmutable.createfrominterface.php
 * @param DateTimeInterface $object The DateTimeInterface object that needs to be converted to
 * an immutable version. This object is not modified, but instead a new DateTimeImmutable object
 * is created containing the same date, time, and timezone information.
 * @return static Returns a new DateTimeImmutable instance.
 * @since 8.0
 */',
        'startLine' => 439,
        'endLine' => 441,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
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
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\PhpStormStubsElementAvailable',
            'isRepeated' => false,
            'arguments' => 
            array (
              'from' => 
              array (
                'code' => '\'8.2\'',
                'attributes' => 
                array (
                  'startLine' => 450,
                  'endLine' => 450,
                  'startTokenPos' => 1196,
                  'startFilePos' => 26304,
                  'endTokenPos' => 1196,
                  'endFilePos' => 26308,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * Serialize a DateTime
 *
 * The __serialize() handler.
 *
 * @link https://php.net/manual/en/datetime.serialize.php
 * @return array The serialized representation of the DateTime object.
 */',
        'startLine' => 450,
        'endLine' => 453,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
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
            'startLine' => 463,
            'endLine' => 463,
            'startColumn' => 39,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\PhpStormStubsElementAvailable',
            'isRepeated' => false,
            'arguments' => 
            array (
              'from' => 
              array (
                'code' => '\'8.2\'',
                'attributes' => 
                array (
                  'startLine' => 462,
                  'endLine' => 462,
                  'startTokenPos' => 1223,
                  'startFilePos' => 26696,
                  'endTokenPos' => 1223,
                  'endFilePos' => 26700,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * Unserialize an Datetime
 *
 * The __unserialize() handler.
 *
 * @link https://php.net/manual/en/datetime.unserialize.php
 * @param array $data The serialized DateTime.
 */',
        'startLine' => 462,
        'endLine' => 465,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTimeImmutable',
        'implementingClassName' => 'DateTimeImmutable',
        'currentClassName' => 'DateTimeImmutable',
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