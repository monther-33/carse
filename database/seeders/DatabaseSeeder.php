<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Every seeder here is idempotent and safe to run on a live database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            FoundationSeeder::class,
            ReferenceDataSeeder::class,
        ]);
    }
}
