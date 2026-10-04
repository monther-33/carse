<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Feature tests run on the seeded base data (chart of accounts, roles, currencies...).
     */
    protected bool $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        // Never let a misconfigured run wipe the development database.
        if (config('database.connections.mysql.database') !== 'cars_test') {
            throw new RuntimeException('Tests must run against the cars_test database.');
        }
    }
}
