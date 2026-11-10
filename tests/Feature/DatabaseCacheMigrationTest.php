<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseCacheMigrationTest extends TestCase
{
    public function test_permission_migration_runs_with_database_cache_store(): void
    {
        config(['cache.default' => 'database']);

        Artisan::call('migrate', [
            '--database' => 'sqlite',
            '--path' => [
                'database/migrations/2022_03_25_102914_create_cache_table.php',
                'database/migrations/2022_03_25_102915_create_permission_tables.php',
            ],
            '--force' => true,
            '--no-interaction' => true,
        ]);

        $this->assertTrue(Schema::hasTable('cache'));
        $this->assertTrue(Schema::hasTable('cache_locks'));
        $this->assertTrue(Schema::hasTable('permissions'));
    }
}
