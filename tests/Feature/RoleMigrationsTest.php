<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RoleMigrationsTest extends TestCase
{
    public function test_role_migrations_allow_every_application_role_on_sqlite(): void
    {
        Artisan::call('migrate', [
            '--database' => 'sqlite',
            '--path' => [
                'database/migrations/2014_10_12_000000_create_users_table.php',
                'database/migrations/2022_03_27_074151_create_users_details_table.php',
                'database/migrations/2022_03_30_172737_add_value_role_in_users_table.php',
                'database/migrations/2022_03_30_194727_add_value_role_in_users_details_table.php',
                'database/migrations/2022_07_16_145332_add_value_role_bendahara_in_users_table.php',
                'database/migrations/2022_07_16_145418_add_value_role_bendahara_in_users_details_table.php',
            ],
            '--force' => true,
            '--no-interaction' => true,
        ]);

        foreach ([
            'Admin',
            'Guru',
            'Staf',
            'Murid',
            'Orang Tua',
            'Alumni',
            'Guest',
            'Perpustakaan',
            'PPDB',
            'Bendahara',
        ] as $role) {
            DB::table('users')->insert([
                'name' => 'Test User',
                'username' => str_replace(' ', '-', strtolower($role)),
                'email' => str_replace(' ', '-', strtolower($role)).'@example.test',
                'role' => $role,
                'status' => 'Aktif',
                'password' => 'test-password',
            ]);

            DB::table('users_details')->insert([
                'user_id' => 1,
                'role' => $role,
            ]);
        }

        $this->assertSame(10, DB::table('users')->count());
        $this->assertSame(10, DB::table('users_details')->count());
    }
}
