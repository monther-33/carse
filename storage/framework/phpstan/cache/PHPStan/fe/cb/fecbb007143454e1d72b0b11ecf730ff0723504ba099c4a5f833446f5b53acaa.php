<?php declare(strict_types = 1);

// ftm-C:\laravel project\cars\app\Models\JournalEntry.php
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v6-2.3.5',
   'data' => 
  array (
    0 => 
    array (
      'e3acb8e9555c3ae34c1b4614151b6bae' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'documentstatus' => 'App\\Enums\\DocumentStatus',
          'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
          'auditable' => 'App\\Models\\Concerns\\Auditable',
          'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
          'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'f1b338c861a52eb04bff9143e9db8457' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models\\Concerns',
         'uses' => 
        array (
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
          'logsactivity' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Models\\Concerns\\Auditable',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'App\\Models\\Concerns\\Auditable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'ae1b517021b83131fb82b4bdde034a8e' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '5d778df980bfead7728550256ea53523' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'getActivitylogOptions',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '8e452729d11ae6c5cd4f4db0c92c5e9a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'bootLogsActivity',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '265540a9f807c9837a5f56aa4274c105' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'addLogChange',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '815f5e49876cd8f979fabf84bd4d1b47' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'isLogEmpty',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '2d93860193d605dd95f50d704f9c60ff' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'disableLogging',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '6569ca0c6c069cd6c9ed7e659404d024' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'enableLogging',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      'a14f8b42084cd1c46015b92ba421ece9' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'activities',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '8afd7d7e5fa35cb52d1ed69cf907b9bb' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'getDescriptionForEvent',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '390650527e22c5cf4144583539f97f8a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'getLogNameToUse',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      'b6ea0289343c7d99eb20ac2eaaf17350' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'eventsToBeRecorded',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '1997f70480ed19c2cd8de2ab8ba0c46a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'shouldLogEvent',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '9ce7df053985091f8f8f08c5458976ed' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'isRestoring',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '8840c92d6325666d1ae1cbbeaff4734d' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'attributesToBeLogged',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      'bc882eaf2ae9216549bf1b7cb2ceefc6' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'shouldLogUnguarded',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '154f2b6630670d68fe3e5dacb046aebc' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'attributeValuesToBeLogged',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '81a1fec48426bea115df51f516314b0a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'logChanges',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '8607891a1c03bc593573b06eb1af4b5f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'getRelatedModelAttributeValue',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '7f8349fbf2182671a2856092dd9be4f7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'getRelatedModelRelationName',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '1ced682ef1959865b4dc89706568b023' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'Spatie\\Activitylog\\Traits',
         'uses' => 
        array (
          'carboninterval' => 'Carbon\\CarbonInterval',
          'dateinterval' => 'DateInterval',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'morphmany' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphMany',
          'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
          'pipeline' => 'Illuminate\\Pipeline\\Pipeline',
          'arr' => 'Illuminate\\Support\\Arr',
          'collection' => 'Illuminate\\Support\\Collection',
          'str' => 'Illuminate\\Support\\Str',
          'activitylogger' => 'Spatie\\Activitylog\\ActivityLogger',
          'activitylogserviceprovider' => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
          'activitylogstatus' => 'Spatie\\Activitylog\\ActivityLogStatus',
          'loggablepipe' => 'Spatie\\Activitylog\\Contracts\\LoggablePipe',
          'eventlogbag' => 'Spatie\\Activitylog\\EventLogBag',
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'getModelAttributeJsonValue',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          3 => 'App\\Models\\Concerns\\Auditable',
          4 => NULL,
        ),
      )),
      '573ef689e47578480bf51252aa9c5b5b' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models\\Concerns',
         'uses' => 
        array (
          'logoptions' => 'Spatie\\Activitylog\\LogOptions',
          'logsactivity' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'getActivitylogOptions',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models\\Concerns',
           'uses' => 
          array (
            'logoptions' => 'Spatie\\Activitylog\\LogOptions',
            'logsactivity' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
          ),
           'className' => 'App\\Models\\JournalEntry',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Models\\Concerns\\Auditable',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Models\\Concerns\\Auditable',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'App\\Models\\Concerns\\Auditable',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '14a6f3b4b9e98b0c538c70cfefa69b18' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models\\Concerns',
         'uses' => 
        array (
          'user' => 'App\\Models\\User',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'auth' => 'Illuminate\\Support\\Facades\\Auth',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => NULL,
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => NULL,
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Models\\Concerns\\HasUserstamps',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'App\\Models\\Concerns\\HasUserstamps',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'e8cb46c3f2dca6a3b6050366c3c79218' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models\\Concerns',
         'uses' => 
        array (
          'user' => 'App\\Models\\User',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'auth' => 'Illuminate\\Support\\Facades\\Auth',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'bootHasUserstamps',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models\\Concerns',
           'uses' => 
          array (
            'user' => 'App\\Models\\User',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'auth' => 'Illuminate\\Support\\Facades\\Auth',
          ),
           'className' => 'App\\Models\\JournalEntry',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Models\\Concerns\\HasUserstamps',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Models\\Concerns\\HasUserstamps',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'App\\Models\\Concerns\\HasUserstamps',
          3 => NULL,
          4 => NULL,
        ),
      )),
      'f33e899ef794b0b99aedc30d56ea93ac' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models\\Concerns',
         'uses' => 
        array (
          'user' => 'App\\Models\\User',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'auth' => 'Illuminate\\Support\\Facades\\Auth',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'creator',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models\\Concerns',
           'uses' => 
          array (
            'user' => 'App\\Models\\User',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'auth' => 'Illuminate\\Support\\Facades\\Auth',
          ),
           'className' => 'App\\Models\\JournalEntry',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Models\\Concerns\\HasUserstamps',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Models\\Concerns\\HasUserstamps',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'App\\Models\\Concerns\\HasUserstamps',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '6849f1ff4f2dadbad95dd94777513860' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models\\Concerns',
         'uses' => 
        array (
          'user' => 'App\\Models\\User',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'auth' => 'Illuminate\\Support\\Facades\\Auth',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'updater',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models\\Concerns',
           'uses' => 
          array (
            'user' => 'App\\Models\\User',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'auth' => 'Illuminate\\Support\\Facades\\Auth',
          ),
           'className' => 'App\\Models\\JournalEntry',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => 'App\\Models\\Concerns\\HasUserstamps',
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => 'App\\Models\\Concerns\\HasUserstamps',
         'traitData' => 
        array (
          0 => 'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php',
          1 => 'App\\Models\\JournalEntry',
          2 => 'App\\Models\\Concerns\\HasUserstamps',
          3 => NULL,
          4 => NULL,
        ),
      )),
      '49fa4fff3078ee2d1bb75abee095f1ad' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'documentstatus' => 'App\\Enums\\DocumentStatus',
          'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
          'auditable' => 'App\\Models\\Concerns\\Auditable',
          'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
          'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'casts',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models',
           'uses' => 
          array (
            'documentstatus' => 'App\\Enums\\DocumentStatus',
            'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
            'auditable' => 'App\\Models\\Concerns\\Auditable',
            'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
            'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Models\\JournalEntry',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '3c930913a3bc3568626b91f0c49b5245' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'documentstatus' => 'App\\Enums\\DocumentStatus',
          'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
          'auditable' => 'App\\Models\\Concerns\\Auditable',
          'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
          'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'booted',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models',
           'uses' => 
          array (
            'documentstatus' => 'App\\Enums\\DocumentStatus',
            'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
            'auditable' => 'App\\Models\\Concerns\\Auditable',
            'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
            'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Models\\JournalEntry',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '5a276a8a8a8ab1f9b2184665dc2c785f' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'documentstatus' => 'App\\Enums\\DocumentStatus',
          'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
          'auditable' => 'App\\Models\\Concerns\\Auditable',
          'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
          'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'lines',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models',
           'uses' => 
          array (
            'documentstatus' => 'App\\Enums\\DocumentStatus',
            'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
            'auditable' => 'App\\Models\\Concerns\\Auditable',
            'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
            'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Models\\JournalEntry',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '9afd11b123af5038bc763efa85edf3a1' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'documentstatus' => 'App\\Enums\\DocumentStatus',
          'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
          'auditable' => 'App\\Models\\Concerns\\Auditable',
          'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
          'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'source',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models',
           'uses' => 
          array (
            'documentstatus' => 'App\\Enums\\DocumentStatus',
            'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
            'auditable' => 'App\\Models\\Concerns\\Auditable',
            'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
            'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Models\\JournalEntry',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'e45fa52b71c69912af7fb925edbedc20' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'documentstatus' => 'App\\Enums\\DocumentStatus',
          'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
          'auditable' => 'App\\Models\\Concerns\\Auditable',
          'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
          'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'branch',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models',
           'uses' => 
          array (
            'documentstatus' => 'App\\Enums\\DocumentStatus',
            'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
            'auditable' => 'App\\Models\\Concerns\\Auditable',
            'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
            'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Models\\JournalEntry',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'e79129f853cb657cfb5a976bc24f058a' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'documentstatus' => 'App\\Enums\\DocumentStatus',
          'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
          'auditable' => 'App\\Models\\Concerns\\Auditable',
          'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
          'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'period',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models',
           'uses' => 
          array (
            'documentstatus' => 'App\\Enums\\DocumentStatus',
            'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
            'auditable' => 'App\\Models\\Concerns\\Auditable',
            'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
            'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Models\\JournalEntry',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      '7b8cf1445733fd17744a56389fc788c7' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'documentstatus' => 'App\\Enums\\DocumentStatus',
          'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
          'auditable' => 'App\\Models\\Concerns\\Auditable',
          'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
          'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'reversedBy',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models',
           'uses' => 
          array (
            'documentstatus' => 'App\\Enums\\DocumentStatus',
            'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
            'auditable' => 'App\\Models\\Concerns\\Auditable',
            'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
            'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Models\\JournalEntry',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'f4853d1909654c1fa7b6403f5b613637' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'documentstatus' => 'App\\Enums\\DocumentStatus',
          'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
          'auditable' => 'App\\Models\\Concerns\\Auditable',
          'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
          'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'reverses',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models',
           'uses' => 
          array (
            'documentstatus' => 'App\\Enums\\DocumentStatus',
            'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
            'auditable' => 'App\\Models\\Concerns\\Auditable',
            'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
            'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Models\\JournalEntry',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
      'dd65e4398caafc18a47422071ae90955' => 
      \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
         'namespace' => 'App\\Models',
         'uses' => 
        array (
          'documentstatus' => 'App\\Enums\\DocumentStatus',
          'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
          'auditable' => 'App\\Models\\Concerns\\Auditable',
          'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
          'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
          'model' => 'Illuminate\\Database\\Eloquent\\Model',
          'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
          'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
          'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
          'carbon' => 'Illuminate\\Support\\Carbon',
        ),
         'className' => 'App\\Models\\JournalEntry',
         'functionName' => 'isReversed',
         'templatePhpDocNodes' => 
        array (
        ),
         'parent' => 
        \PHPStan\Analyser\IntermediaryNameScope::__set_state(array(
           'namespace' => 'App\\Models',
           'uses' => 
          array (
            'documentstatus' => 'App\\Enums\\DocumentStatus',
            'journalwritenotallowedexception' => 'App\\Exceptions\\Accounting\\JournalWriteNotAllowedException',
            'auditable' => 'App\\Models\\Concerns\\Auditable',
            'hasuserstamps' => 'App\\Models\\Concerns\\HasUserstamps',
            'journalwriteguard' => 'App\\Services\\Accounting\\JournalWriteGuard',
            'model' => 'Illuminate\\Database\\Eloquent\\Model',
            'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'morphto' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'carbon' => 'Illuminate\\Support\\Carbon',
          ),
           'className' => 'App\\Models\\JournalEntry',
           'functionName' => NULL,
           'templatePhpDocNodes' => 
          array (
          ),
           'parent' => NULL,
           'typeAliasesMap' => 
          array (
          ),
           'bypassTypeAliases' => false,
           'constUses' => 
          array (
          ),
           'typeAliasClassName' => NULL,
           'traitData' => NULL,
        )),
         'typeAliasesMap' => 
        array (
        ),
         'bypassTypeAliases' => false,
         'constUses' => 
        array (
        ),
         'typeAliasClassName' => NULL,
         'traitData' => NULL,
      )),
    ),
    1 => 
    array (
      'C:\\laravel project\\cars\\app\\Models\\JournalEntry.php' => '69876646da9f8719795b33ea0197834db10d885d3217c2ef426c90c9101ec62c',
      'C:\\laravel project\\cars\\app\\Models\\Concerns\\Auditable.php' => '3474062e6b308d4a89a143ec762831dbf27385d139c20402e7e4188a1570607d',
      'C:\\laravel project\\cars\\vendor\\composer\\..\\spatie\\laravel-activitylog\\src\\Traits\\LogsActivity.php' => '405d0dcd20bf1b8ce7c5365c9f79b4a46a0e29b75a8e796eecd9a0ca8c766b62',
      'C:\\laravel project\\cars\\app\\Models\\Concerns\\HasUserstamps.php' => '6ba91e94f784e6cedee8418ba6d063b8ebd36fc01e36b38be216ac15ea1fd974',
    ),
  ),
));