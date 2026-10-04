<?php declare(strict_types = 1);

// phpinternal-PHPStan\BetterReflection\Reflection\ReflectionClass-datetime
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v3-6.73.0.5-dev-master@e4f5f6c-8.2.12-',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\InternalLocatedSource',
      'data' => 
      array (
        'name' => 'DateTime',
        'filename' => 'phpstorm-stubs:date/date_c.stub',
        'extensionName' => 'date',
        'aliasName' => NULL,
      ),
    ),
    'namespace' => NULL,
    'name' => 'DateTime',
    'shortName' => 'DateTime',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Representation of date and time.
 * @link https://php.net/manual/en/class.datetime.php
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 398,
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
              'code' => '\'now\'',
              'attributes' => 
              array (
                'startLine' => 41,
                'endLine' => 41,
                'startTokenPos' => 68,
                'startFilePos' => 1717,
                'endTokenPos' => 68,
                'endFilePos' => 1721,
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
                      'startLine' => 40,
                      'endLine' => 40,
                      'startTokenPos' => 46,
                      'startFilePos' => 1651,
                      'endTokenPos' => 52,
                      'endFilePos' => 1669,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 40,
                      'endLine' => 40,
                      'startTokenPos' => 58,
                      'startFilePos' => 1681,
                      'endTokenPos' => 58,
                      'endFilePos' => 1682,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 40,
            'endLine' => 41,
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
                'startLine' => 43,
                'endLine' => 43,
                'startTokenPos' => 98,
                'startFilePos' => 1890,
                'endTokenPos' => 98,
                'endFilePos' => 1893,
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
                      'startLine' => 42,
                      'endLine' => 42,
                      'startTokenPos' => 74,
                      'startFilePos' => 1790,
                      'endTokenPos' => 80,
                      'endFilePos' => 1819,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'DateTimeZone\'',
                    'attributes' => 
                    array (
                      'startLine' => 42,
                      'endLine' => 42,
                      'startTokenPos' => 86,
                      'startFilePos' => 1831,
                      'endTokenPos' => 86,
                      'endFilePos' => 1844,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 42,
            'endLine' => 43,
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
                'code' => '\'5.3\'',
                'attributes' => 
                array (
                  'startLine' => 38,
                  'endLine' => 38,
                  'startTokenPos' => 26,
                  'startFilePos' => 1529,
                  'endTokenPos' => 26,
                  'endFilePos' => 1533,
                ),
              ),
              'to' => 
              array (
                'code' => '\'8.2\'',
                'attributes' => 
                array (
                  'startLine' => 38,
                  'endLine' => 38,
                  'startTokenPos' => 32,
                  'startFilePos' => 1540,
                  'endTokenPos' => 32,
                  'endFilePos' => 1544,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * (PHP 5 &gt;=5.2.0)<br/>
 * @link https://php.net/manual/en/datetime.construct.php
 * @param string $datetime [optional]
 * <p>A date/time string. Valid formats are explained in {@link https://php.net/manual/en/datetime.formats.php Date and Time Formats}.</p>
 * <p>
 * Enter <b>now</b> here to obtain the current time when using
 * the <em>$timezone</em> parameter.
 * </p>
 * @param null|DateTimeZone $timezone [optional] <p>
 * A {@link https://php.net/manual/en/class.datetimezone.php DateTimeZone} object representing the
 * timezone of <em>$datetime</em>.
 * </p>
 * <p>
 * If <em>$timezone</em> is omitted,
 * the current timezone will be used.
 * </p>
 * <blockquote><p><b>Note</b>:
 * </p><p>
 * The <em>$timezone</em> parameter
 * and the current timezone are ignored when the
 * <em>$time</em> parameter either
 * is a UNIX timestamp (e.g. <em>@946684800</em>)
 * or specifies a timezone
 * (e.g. <em>2010-01-28T15:00:00+02:00</em>).
 * </p> <p></p></blockquote>
 * @throws Exception Emits Exception in case of an error.
 */',
        'startLine' => 38,
        'endLine' => 46,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
                  'startLine' => 54,
                  'endLine' => 54,
                  'startTokenPos' => 118,
                  'startFilePos' => 2230,
                  'endTokenPos' => 118,
                  'endFilePos' => 2234,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * The __wakeup handler
 * @return void Initializes a DateTime object.
 * @link https://php.net/manual/en/datetime.wakeup.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 53,
        'endLine' => 57,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
                      'startLine' => 117,
                      'endLine' => 117,
                      'startTokenPos' => 160,
                      'startFilePos' => 7800,
                      'endTokenPos' => 166,
                      'endFilePos' => 7818,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 117,
                      'endLine' => 117,
                      'startTokenPos' => 172,
                      'startFilePos' => 7830,
                      'endTokenPos' => 172,
                      'endFilePos' => 7831,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 117,
            'endLine' => 118,
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
                  'startLine' => 114,
                  'endLine' => 114,
                  'startTokenPos' => 142,
                  'startFilePos' => 7641,
                  'endTokenPos' => 142,
                  'endFilePos' => 7644,
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
 * Returns date formatted according to given format.
 * @param string $format The format of the outputted date string. See the formatting options
 * below. There are also several predefined date constants that may be used instead, so for
 * example DATE_RSS contains the format string \'D, d M Y H:i:s\'. The following characters are
 * recognized in the format parameter string format character Description Example returned
 * values Day --- --- d Day of the month, 2 digits with leading zeros 01 to 31 D A textual
 * representation of a day, three letters Mon through Sun j Day of the month without leading
 * zeros 1 to 31 l (lowercase \'L\') A full textual representation of the day of the week Sunday
 * through Saturday N ISO 8601 numeric representation of the day of the week 1 (for Monday)
 * through 7 (for Sunday) S English ordinal suffix for the day of the month, 2 characters st,
 * nd, rd or th. Works well with j w Numeric representation of the day of the week 0 (for
 * Sunday) through 6 (for Saturday) z The day of the year (starting from 0) 0 through 365 Week
 * --- --- W ISO 8601 week number of year, weeks starting on Monday Example: 42 (the 42nd week
 * in the year) Month --- --- F A full textual representation of a month, such as January or
 * March January through December m Numeric representation of a month, with leading zeros 01
 * through 12 M A short textual representation of a month, three letters Jan through Dec n
 * Numeric representation of a month, without leading zeros 1 through 12 t Number of days in the
 * given month 28 through 31 Year --- --- L Whether it\'s a leap year 1 if it is a leap year, 0
 * otherwise. o ISO 8601 week-numbering year. This has the same value as Y, except that if the
 * ISO week number (W) belongs to the previous or next year, that year is used instead.
 * Examples: 1999 or 2003 X An expanded full numeric representation of a year, at least 4
 * digits, with - for years BCE, and + for years CE. Examples: -0055, +0787, +1999, +10191 x An
 * expanded full numeric representation if required, or a standard full numeral representation
 * if possible (like Y). At least four digits. Years BCE are prefixed with a -. Years beyond
 * (and including) 10000 are prefixed by a +. Examples: -0055, 0787, 1999, +10191 Y A full
 * numeric representation of a year, at least 4 digits, with - for years BCE. Examples: -0055,
 * 0787, 1999, 2003, 10191 y A two digit representation of a year Examples: 99 or 03 Time ---
 * --- a Lowercase Ante meridiem and Post meridiem am or pm A Uppercase Ante meridiem and Post
 * meridiem AM or PM B Swatch Internet time 000 through 999 g 12-hour format of an hour without
 * leading zeros 1 through 12 G 24-hour format of an hour without leading zeros 0 through 23 h
 * 12-hour format of an hour with leading zeros 01 through 12 H 24-hour format of an hour with
 * leading zeros 00 through 23 i Minutes with leading zeros 00 to 59 s Seconds with leading
 * zeros 00 through 59 u Microseconds. Note that date will always generate 000000 since it takes
 * an int parameter, whereas DateTimeInterface::format does support microseconds if an object of
 * type DateTimeInterface was created with microseconds. Example: 654321 v Milliseconds. Same
 * note applies as for u. Example: 654 Timezone --- --- e Timezone identifier Examples: UTC,
 * GMT, Atlantic/Azores I (capital i) Whether or not the date is in daylight saving time 1 if
 * Daylight Saving Time, 0 otherwise. O Difference to Greenwich time (GMT) without colon between
 * hours and minutes Example: +0200 P Difference to Greenwich time (GMT) with colon between
 * hours and minutes Example: +02:00 p The same as P, but returns Z instead of +00:00 (available
 * as of PHP 8.0.0) Examples: Z or +02:00 T Timezone abbreviation, if known; otherwise the GMT
 * offset. Examples: EST, MDT, +05 Z Timezone offset in seconds. The offset for timezones west
 * of UTC is always negative, and for those east of UTC is always positive. -43200 through 50400
 * Full Date/Time --- --- c ISO 8601 date. Only compatible with the non-expanded format (up to
 * year 9999). Later dates will result in an invalid string. For later dates and expanded
 * format, see x and X. 2004-02-12T15:19:21+00:00 r RFC 2822/RFC 5322 formatted date Example:
 * Thu, 21 Dec 2000 16:01:07 +0200 U Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
 * See also time Unrecognized characters in the format string will be printed as-is. The Z
 * format will always return 0 when using gmdate. Since this function only accepts int
 * timestamps the u format character is only useful when using the date_format function with
 * user based timestamps created with date_create.
 * @return string Returns the formatted date string on success.
 * @link https://php.net/manual/en/datetime.format.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 114,
        'endLine' => 121,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
                      'startLine' => 134,
                      'endLine' => 134,
                      'startTokenPos' => 240,
                      'startFilePos' => 8802,
                      'endTokenPos' => 246,
                      'endFilePos' => 8820,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 134,
                      'endLine' => 134,
                      'startTokenPos' => 252,
                      'startFilePos' => 8832,
                      'endTokenPos' => 252,
                      'endFilePos' => 8833,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 134,
            'endLine' => 135,
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
                'code' => '\'5.3\'',
                'attributes' => 
                array (
                  'startLine' => 130,
                  'endLine' => 130,
                  'startTokenPos' => 197,
                  'startFilePos' => 8520,
                  'endTokenPos' => 197,
                  'endFilePos' => 8524,
                ),
              ),
              'to' => 
              array (
                'code' => '\'8.2\'',
                'attributes' => 
                array (
                  'startLine' => 130,
                  'endLine' => 130,
                  'startTokenPos' => 203,
                  'startFilePos' => 8531,
                  'endTokenPos' => 203,
                  'endFilePos' => 8535,
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
          2 => 
          array (
            'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
            'isRepeated' => false,
            'arguments' => 
            array (
              0 => 
              array (
                'code' => '[\'8.4\' => \'DateTime\']',
                'attributes' => 
                array (
                  'startLine' => 132,
                  'endLine' => 132,
                  'startTokenPos' => 214,
                  'startFilePos' => 8655,
                  'endTokenPos' => 220,
                  'endFilePos' => 8675,
                ),
              ),
              'default' => 
              array (
                'code' => '\'static|false\'',
                'attributes' => 
                array (
                  'startLine' => 132,
                  'endLine' => 132,
                  'startTokenPos' => 226,
                  'startFilePos' => 8687,
                  'endTokenPos' => 226,
                  'endFilePos' => 8700,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * Alter the timestamp of a DateTime object by incrementing or decrementing
 * in a format accepted by strtotime().
 * @param string $modifier A date/time string. Valid formats are explained in <a href="https://secure.php.net/manual/en/datetime.formats.php">Date and Time Formats</a>.
 * @return static|false Returns the DateTime object for method chaining or FALSE on failure.
 * @link https://php.net/manual/en/datetime.modify.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 130,
        'endLine' => 138,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
            'startLine' => 147,
            'endLine' => 147,
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
            'name' => 'DateTime',
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
 * Adds an amount of days, months, years, hours, minutes and seconds to a DateTime object
 * @param DateInterval $interval A DateInterval object
 * @return static Returns the modified DateTime object for method chaining.
 * @link https://php.net/manual/en/datetime.add.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 146,
        'endLine' => 149,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
        'aliasName' => NULL,
      ),
      'createFromImmutable' => 
      array (
        'name' => 'createFromImmutable',
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
                'name' => 'DateTimeImmutable',
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
            'startColumn' => 52,
            'endColumn' => 77,
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
                  'startTokenPos' => 299,
                  'startFilePos' => 10136,
                  'endTokenPos' => 305,
                  'endFilePos' => 10154,
                ),
              ),
              'default' => 
              array (
                'code' => '\'DateTime\'',
                'attributes' => 
                array (
                  'startLine' => 161,
                  'endLine' => 161,
                  'startTokenPos' => 311,
                  'startFilePos' => 10166,
                  'endTokenPos' => 311,
                  'endFilePos' => 10175,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * Returns new DateTime instance encapsulating the given DateTimeImmutable object
 * @link https://php.net/manual/en/datetime.createfromimmutable.php
 * @param DateTimeImmutable $object The immutable DateTimeImmutable object that needs to be
 * converted to a mutable version. This object is not modified, but instead a new DateTime
 * instance is created containing the same date, time, and timezone information.
 * @return DateTime Returns a new DateTime instance.
 * @since 7.3
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
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
            'startLine' => 174,
            'endLine' => 174,
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
            'name' => 'DateTime',
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
 * Subtracts an amount of days, months, years, hours, minutes and seconds from a DateTime object
 * @param DateInterval $interval A DateInterval object
 * @return static Returns the modified DateTime object for method chaining.
 * @link https://php.net/manual/en/datetime.sub.php
 * @throws DateInvalidOperationException
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 173,
        'endLine' => 176,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
 * Get the TimeZone associated with the DateTime
 * @return DateTimeZone|false Returns a DateTimeZone object on success or false on failure.
 * @link https://php.net/manual/en/datetime.gettimezone.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 183,
        'endLine' => 186,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
              0 => 
              array (
                'name' => 'JetBrains\\PhpStorm\\Internal\\LanguageLevelTypeAware',
                'isRepeated' => false,
                'arguments' => 
                array (
                  0 => 
                  array (
                    'code' => '[\'8.0\' => \'DateTimeZone\']',
                    'attributes' => 
                    array (
                      'startLine' => 197,
                      'endLine' => 197,
                      'startTokenPos' => 395,
                      'startFilePos' => 11888,
                      'endTokenPos' => 401,
                      'endFilePos' => 11912,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 197,
                      'endLine' => 197,
                      'startTokenPos' => 407,
                      'startFilePos' => 11924,
                      'endTokenPos' => 407,
                      'endFilePos' => 11925,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 197,
            'endLine' => 198,
            'startColumn' => 13,
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
            'name' => 'DateTime',
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
 * Set the TimeZone associated with the DateTime
 * @param DateTimeZone $timezone A DateTimeZone object representing the desired time zone.
 * @return static Returns the DateTime object for method chaining. The underlying point-in-time
 * is not changed when calling this method.
 * @link https://php.net/manual/en/datetime.settimezone.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 195,
        'endLine' => 201,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
 * Returns the timezone offset
 * @return int Returns the timezone offset in seconds from UTC on success.
 * @link https://php.net/manual/en/datetime.getoffset.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 208,
        'endLine' => 211,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
                      'startLine' => 224,
                      'endLine' => 224,
                      'startTokenPos' => 461,
                      'startFilePos' => 13027,
                      'endTokenPos' => 467,
                      'endFilePos' => 13042,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 224,
                      'endLine' => 224,
                      'startTokenPos' => 473,
                      'startFilePos' => 13054,
                      'endTokenPos' => 473,
                      'endFilePos' => 13055,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 224,
            'endLine' => 225,
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
                      'startLine' => 226,
                      'endLine' => 226,
                      'startTokenPos' => 485,
                      'startFilePos' => 13148,
                      'endTokenPos' => 491,
                      'endFilePos' => 13163,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 226,
                      'endLine' => 226,
                      'startTokenPos' => 497,
                      'startFilePos' => 13175,
                      'endTokenPos' => 497,
                      'endFilePos' => 13176,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 226,
            'endLine' => 227,
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
                'startLine' => 229,
                'endLine' => 229,
                'startTokenPos' => 531,
                'startFilePos' => 13329,
                'endTokenPos' => 531,
                'endFilePos' => 13329,
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
                      'startLine' => 228,
                      'endLine' => 228,
                      'startTokenPos' => 509,
                      'startFilePos' => 13271,
                      'endTokenPos' => 515,
                      'endFilePos' => 13286,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 228,
                      'endLine' => 228,
                      'startTokenPos' => 521,
                      'startFilePos' => 13298,
                      'endTokenPos' => 521,
                      'endFilePos' => 13299,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 228,
            'endLine' => 229,
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
                'startLine' => 232,
                'endLine' => 232,
                'startTokenPos' => 569,
                'startFilePos' => 13548,
                'endTokenPos' => 569,
                'endFilePos' => 13548,
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
                      'startLine' => 230,
                      'endLine' => 230,
                      'startTokenPos' => 540,
                      'startFilePos' => 13411,
                      'endTokenPos' => 540,
                      'endFilePos' => 13415,
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
                      'startLine' => 231,
                      'endLine' => 231,
                      'startTokenPos' => 547,
                      'startFilePos' => 13485,
                      'endTokenPos' => 553,
                      'endFilePos' => 13500,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 231,
                      'endLine' => 231,
                      'startTokenPos' => 559,
                      'startFilePos' => 13512,
                      'endTokenPos' => 559,
                      'endFilePos' => 13513,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 230,
            'endLine' => 232,
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
            'name' => 'DateTime',
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
 * Sets the current time of the DateTime object to a different time.
 * @param int $hour Hour of the time.
 * @param int $minute Minute of the time.
 * @param int $second Second of the time.
 * @param int $microsecond Added since 7.1
 * @return static Returns the modified DateTime object for method chaining.
 * @link https://php.net/manual/en/datetime.settime.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 222,
        'endLine' => 235,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
                      'startLine' => 247,
                      'endLine' => 247,
                      'startTokenPos' => 596,
                      'startFilePos' => 14179,
                      'endTokenPos' => 602,
                      'endFilePos' => 14194,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 247,
                      'endLine' => 247,
                      'startTokenPos' => 608,
                      'startFilePos' => 14206,
                      'endTokenPos' => 608,
                      'endFilePos' => 14207,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 247,
            'endLine' => 248,
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
                      'startLine' => 249,
                      'endLine' => 249,
                      'startTokenPos' => 620,
                      'startFilePos' => 14300,
                      'endTokenPos' => 626,
                      'endFilePos' => 14315,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 249,
                      'endLine' => 249,
                      'startTokenPos' => 632,
                      'startFilePos' => 14327,
                      'endTokenPos' => 632,
                      'endFilePos' => 14328,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 249,
            'endLine' => 250,
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
                      'startLine' => 251,
                      'endLine' => 251,
                      'startTokenPos' => 644,
                      'startFilePos' => 14422,
                      'endTokenPos' => 650,
                      'endFilePos' => 14437,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 251,
                      'endLine' => 251,
                      'startTokenPos' => 656,
                      'startFilePos' => 14449,
                      'endTokenPos' => 656,
                      'endFilePos' => 14450,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 251,
            'endLine' => 252,
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
            'name' => 'DateTime',
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
 * Sets the current date of the DateTime object to a different date.
 * @param int $year Year of the date.
 * @param int $month Month of the date.
 * @param int $day Day of the date.
 * @return static Returns the modified DateTime object for method chaining.
 * @link https://php.net/manual/en/datetime.setdate.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 245,
        'endLine' => 255,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
                      'startLine' => 267,
                      'endLine' => 267,
                      'startTokenPos' => 689,
                      'startFilePos' => 15174,
                      'endTokenPos' => 695,
                      'endFilePos' => 15189,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 267,
                      'endLine' => 267,
                      'startTokenPos' => 701,
                      'startFilePos' => 15201,
                      'endTokenPos' => 701,
                      'endFilePos' => 15202,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 267,
            'endLine' => 268,
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
                      'startLine' => 269,
                      'endLine' => 269,
                      'startTokenPos' => 713,
                      'startFilePos' => 15295,
                      'endTokenPos' => 719,
                      'endFilePos' => 15310,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 269,
                      'endLine' => 269,
                      'startTokenPos' => 725,
                      'startFilePos' => 15322,
                      'endTokenPos' => 725,
                      'endFilePos' => 15323,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 269,
            'endLine' => 270,
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
                'startLine' => 272,
                'endLine' => 272,
                'startTokenPos' => 759,
                'startFilePos' => 15477,
                'endTokenPos' => 759,
                'endFilePos' => 15477,
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
                      'startLine' => 271,
                      'endLine' => 271,
                      'startTokenPos' => 737,
                      'startFilePos' => 15416,
                      'endTokenPos' => 743,
                      'endFilePos' => 15431,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 271,
                      'endLine' => 271,
                      'startTokenPos' => 749,
                      'startFilePos' => 15443,
                      'endTokenPos' => 749,
                      'endFilePos' => 15444,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 271,
            'endLine' => 272,
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
            'name' => 'DateTime',
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
 * Set a date according to the ISO 8601 standard - using weeks and day offsets rather than specific dates.
 * @param int $year Year of the date.
 * @param int $week Week of the date.
 * @param int $dayOfWeek Offset from the first day of the week.
 * @return static Returns the modified DateTime object for method chaining.
 * @link https://php.net/manual/en/datetime.setisodate.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 265,
        'endLine' => 275,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
                      'startLine' => 286,
                      'endLine' => 286,
                      'startTokenPos' => 786,
                      'startFilePos' => 16160,
                      'endTokenPos' => 792,
                      'endFilePos' => 16175,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 286,
                      'endLine' => 286,
                      'startTokenPos' => 798,
                      'startFilePos' => 16187,
                      'endTokenPos' => 798,
                      'endFilePos' => 16188,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 286,
            'endLine' => 287,
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
            'name' => 'DateTime',
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
 * Sets the date and time based on a Unix timestamp.
 * @param int $timestamp Unix timestamp representing the date. Setting timestamps outside the
 * range of integer is possible by using DateTimeImmutable::modify with the @ format.
 * @return static Returns the modified DateTime object for method chaining.
 * @link https://php.net/manual/en/datetime.settimestamp.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 284,
        'endLine' => 290,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
 * Gets the Unix timestamp.
 * @return int Returns the Unix timestamp representing the date.
 * @link https://php.net/manual/en/datetime.gettimestamp.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 297,
        'endLine' => 300,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
                      'startLine' => 311,
                      'endLine' => 311,
                      'startTokenPos' => 852,
                      'startFilePos' => 17269,
                      'endTokenPos' => 858,
                      'endFilePos' => 17298,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 311,
                      'endLine' => 311,
                      'startTokenPos' => 864,
                      'startFilePos' => 17310,
                      'endTokenPos' => 864,
                      'endFilePos' => 17311,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 311,
            'endLine' => 312,
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
                'startLine' => 314,
                'endLine' => 314,
                'startTokenPos' => 898,
                'startFilePos' => 17488,
                'endTokenPos' => 898,
                'endFilePos' => 17492,
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
                      'startLine' => 313,
                      'endLine' => 313,
                      'startTokenPos' => 876,
                      'startFilePos' => 17426,
                      'endTokenPos' => 882,
                      'endFilePos' => 17442,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 313,
                      'endLine' => 313,
                      'startTokenPos' => 888,
                      'startFilePos' => 17454,
                      'endTokenPos' => 888,
                      'endFilePos' => 17455,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 313,
            'endLine' => 314,
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
 * Returns the difference between two DateTime objects represented as a DateInterval.
 * @param DateTimeInterface $targetObject The date to compare to.
 * @param bool $absolute [optional] Whether to return absolute difference.
 * @return DateInterval The DateInterval object representing the difference between the two dates.
 * @link https://php.net/manual/en/datetime.diff.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 309,
        'endLine' => 317,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
                      'startLine' => 331,
                      'endLine' => 331,
                      'startTokenPos' => 937,
                      'startFilePos' => 18408,
                      'endTokenPos' => 943,
                      'endFilePos' => 18426,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 331,
                      'endLine' => 331,
                      'startTokenPos' => 949,
                      'startFilePos' => 18438,
                      'endTokenPos' => 949,
                      'endFilePos' => 18439,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 331,
            'endLine' => 332,
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
                      'startLine' => 333,
                      'endLine' => 333,
                      'startTokenPos' => 961,
                      'startFilePos' => 18537,
                      'endTokenPos' => 967,
                      'endFilePos' => 18555,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'\'',
                    'attributes' => 
                    array (
                      'startLine' => 333,
                      'endLine' => 333,
                      'startTokenPos' => 973,
                      'startFilePos' => 18567,
                      'endTokenPos' => 973,
                      'endFilePos' => 18568,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 333,
            'endLine' => 334,
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
                'startLine' => 336,
                'endLine' => 336,
                'startTokenPos' => 1009,
                'startFilePos' => 18768,
                'endTokenPos' => 1009,
                'endFilePos' => 18771,
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
                      'startLine' => 335,
                      'endLine' => 335,
                      'startTokenPos' => 985,
                      'startFilePos' => 18668,
                      'endTokenPos' => 991,
                      'endFilePos' => 18697,
                    ),
                  ),
                  'default' => 
                  array (
                    'code' => '\'DateTimeZone\'',
                    'attributes' => 
                    array (
                      'startLine' => 335,
                      'endLine' => 335,
                      'startTokenPos' => 997,
                      'startFilePos' => 18709,
                      'endTokenPos' => 997,
                      'endFilePos' => 18722,
                    ),
                  ),
                ),
              ),
            ),
            'startLine' => 335,
            'endLine' => 336,
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
                  'name' => 'DateTime',
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
                  'startLine' => 329,
                  'endLine' => 329,
                  'startTokenPos' => 921,
                  'startFilePos' => 18285,
                  'endTokenPos' => 921,
                  'endFilePos' => 18289,
                ),
              ),
            ),
          ),
        ),
        'docComment' => '/**
 * Parse a string into a new DateTime object according to the specified format
 * @param string $format Format accepted by date().
 * @param string $datetime String representing the time.
 * @param null|DateTimeZone $timezone A DateTimeZone object representing the desired time zone.
 * @return DateTime|false Returns a new DateTime instance or false on failure.
 * @link https://php.net/manual/en/datetime.createfromformat.php
 * @throws ValueError when the datetime contains NULL-bytes.
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 328,
        'endLine' => 339,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
                  'startLine' => 346,
                  'endLine' => 346,
                  'startTokenPos' => 1027,
                  'startFilePos' => 19124,
                  'endTokenPos' => 1054,
                  'endFilePos' => 19223,
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
 * Returns an array of warnings and errors found while parsing a date/time string
 * @return array|false
 * @link https://php.net/manual/en/datetime.getlasterrors.php
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 346,
        'endLine' => 350,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
            'startLine' => 359,
            'endLine' => 359,
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
 * The __set_state handler
 * @link https://php.net/manual/en/datetime.set-state.php
 * @param array $array <p>Initialization array.</p>
 * @return DateTime <p>Returns a new instance of a DateTime object.</p>
 * @betterReflectionTentativeReturnType
 */',
        'startLine' => 358,
        'endLine' => 361,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
            'startLine' => 371,
            'endLine' => 371,
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
            'name' => 'DateTime',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns new DateTime object encapsulating the given DateTimeInterface object
 * @link https://php.net/manual/en/datetime.createfrominterface.php
 * @param DateTimeInterface $object The DateTimeInterface object that needs to be converted to a
 * mutable version. This object is not modified, but instead a new DateTime object is created
 * containing the same date, time, and timezone information.
 * @return static Returns a new DateTime instance.
 * @since 8.0
 */',
        'startLine' => 371,
        'endLine' => 373,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
                  'startLine' => 382,
                  'endLine' => 382,
                  'startTokenPos' => 1137,
                  'startFilePos' => 20804,
                  'endTokenPos' => 1137,
                  'endFilePos' => 20808,
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
        'startLine' => 382,
        'endLine' => 385,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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
            'startLine' => 395,
            'endLine' => 395,
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
                  'startLine' => 394,
                  'endLine' => 394,
                  'startTokenPos' => 1164,
                  'startFilePos' => 21196,
                  'endTokenPos' => 1164,
                  'endFilePos' => 21200,
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
        'startLine' => 394,
        'endLine' => 397,
        'startColumn' => 9,
        'endColumn' => 9,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => NULL,
        'declaringClassName' => 'DateTime',
        'implementingClassName' => 'DateTime',
        'currentClassName' => 'DateTime',
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