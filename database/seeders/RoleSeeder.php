<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roleNames = [
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
        ];

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($roleNames as $roleName) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            if ($role->wasRecentlyCreated) {
                $this->command?->info('Data Role '.$roleName.' has been saved.');
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
