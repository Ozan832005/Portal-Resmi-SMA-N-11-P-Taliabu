<?php

namespace Modules\SPP\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddRoleBendaharaSeederTableSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'Bendahara',
            'guard_name' => 'web',
        ]);

        if ($role->wasRecentlyCreated) {
            $this->command?->info('Data Role '.$role->name.' has been saved.');
        }
    }
}
