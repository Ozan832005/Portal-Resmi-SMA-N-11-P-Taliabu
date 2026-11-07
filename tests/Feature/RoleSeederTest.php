<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RoleSeederTest extends TestCase
{
    private const APPLICATION_ROLES = [
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->dropRoleTestTables();
        $this->createRoleTestTables();
    }

    protected function tearDown(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->dropRoleTestTables();

        parent::tearDown();
    }

    public function test_application_roles_are_seeded_idempotently_and_can_be_assigned(): void
    {
        $this->seed(RoleSeeder::class);
        $this->seed(RoleSeeder::class);

        $this->assertDatabaseCount('roles', count(self::APPLICATION_ROLES));

        foreach (self::APPLICATION_ROLES as $roleName) {
            $user = User::create([
                'name' => 'Pengguna '.$roleName,
                'email' => str_replace(' ', '-', strtolower($roleName)).'@example.test',
                'username' => strtolower(str_replace(' ', '-', $roleName)),
                'role' => $roleName,
                'status' => 'Aktif',
                'password' => 'test-password',
            ]);

            $user->assignRole($roleName);

            $this->assertDatabaseHas('model_has_roles', [
                'role_id' => Role::query()->where('name', $roleName)->where('guard_name', 'web')->value('id'),
                'model_type' => User::class,
                'model_id' => $user->getKey(),
            ]);
        }
    }

    private function createRoleTestTables(): void
    {
        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('username');
            $table->string('email');
            $table->string('role');
            $table->string('status');
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('model_has_roles', function (Blueprint $table): void {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['role_id', 'model_id', 'model_type']);
        });
    }

    private function dropRoleTestTables(): void
    {
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
}
