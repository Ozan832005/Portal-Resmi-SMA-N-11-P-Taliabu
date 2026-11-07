<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = [
            [
                'name' => 'Perpustakaan',
                'guard_name' => 'web',

            ],
            [
                'name' => 'PPDB',
                'guard_name' => 'web',
            ],
        ];

        foreach ($role as $value) {
            $createdRole = Role::firstOrCreate($value);

            if ($createdRole->wasRecentlyCreated) {
                $this->command?->info('Data Role '.$value['name'].' has been saved.');
            }
        }
    }
}
