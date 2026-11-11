<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Modules\SPP\Entities\SppSetting;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PpdbAndPasswordActionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createTables();
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_user_can_change_password_with_their_current_password(): void
    {
        $user = User::create([
            'name' => 'Pengguna Tes',
            'username' => 'pengguna-tes',
            'email' => 'pengguna@example.test',
            'role' => 'Admin',
            'status' => 'Aktif',
            'password' => Hash::make('password-lama'),
        ]);

        $response = $this->actingAs($user)
            ->from('/home')
            ->put(route('profile.change-password', $user), [
                'current_password' => 'password-lama',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
            ]);

        $response->assertRedirect('/home')
            ->assertSessionHas('success', 'Password Berhasil diupdate !');
        $this->assertTrue(Hash::check('password-baru', $user->fresh()->password));
    }

    public function test_applicant_registration_handles_repeated_names_and_missing_guest_role(): void
    {
        User::create([
            'name' => 'Fauzan Sebelumnya',
            'username' => 'Fauzan',
            'email' => 'fauzan-lama@example.test',
            'role' => 'Guest',
            'status' => 'Aktif',
            'password' => Hash::make('password-lama'),
        ]);

        $response = $this->post(route('register.store'), [
            'name' => 'Fauzan Pendaftar Baru',
            'email' => 'fauzan-baru@example.test',
            'password' => 'password-aman',
            'confirm_password' => 'password-aman',
            'whatsapp' => '628123456789',
            'asal_sekolah' => 'SMP Contoh',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', [
            'name' => 'Fauzan Pendaftar Baru',
            'email' => 'fauzan-baru@example.test',
            'role' => 'Guest',
            'status' => 'Aktif',
        ]);
        $this->assertDatabaseHas('data_murids', [
            'whatsapp' => '628123456789',
            'asal_sekolah' => 'SMP Contoh',
        ]);
        $this->assertDatabaseHas('roles', [
            'name' => 'Guest',
            'guard_name' => 'web',
        ]);
    }

    public function test_ppdb_staff_can_accept_applicant_when_student_role_has_not_been_created(): void
    {
        $ppdbRole = Role::create([
            'name' => 'PPDB',
            'guard_name' => 'web',
        ]);
        $reviewer = User::create([
            'name' => 'Petugas PPDB',
            'username' => 'petugas-ppdb',
            'email' => 'petugas@example.test',
            'role' => 'PPDB',
            'status' => 'Aktif',
            'password' => Hash::make('password-aman'),
        ]);
        $reviewer->assignRole($ppdbRole);

        $applicant = User::create([
            'name' => 'Calon Murid',
            'username' => 'calon-murid',
            'email' => 'calon@example.test',
            'role' => 'Guest',
            'status' => 'Aktif',
            'password' => Hash::make('password-aman'),
        ]);
        DB::table('data_murids')->insert([
            'user_id' => $applicant->id,
            'nis' => null,
            'nisn' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::create('spp_setting', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('update_by');
            $table->timestamps();
        });
        Schema::create('payment_spps', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->bigInteger('year');
            $table->boolean('is_active');
            $table->timestamps();
        });
        Schema::create('detail_payment_spps', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('user_id');
            $table->string('month');
            $table->bigInteger('amount');
            $table->string('status');
            $table->string('file')->nullable();
            $table->timestamps();
        });
        SppSetting::create([
            'amount' => 150000,
            'update_by' => $reviewer->id,
        ]);

        $response = $this->actingAs($reviewer)
            ->put(route('data-murid.update', $applicant), [
                'nis' => '10001',
                'nisn' => '20001',
            ]);

        $response->assertRedirect(route('data-murid.index'));
        $this->assertDatabaseHas('users', [
            'id' => $applicant->id,
            'role' => 'Murid',
        ]);
        $this->assertDatabaseHas('data_murids', [
            'user_id' => $applicant->id,
            'nis' => 10001,
            'nisn' => 20001,
            'proses' => 'Murid',
        ]);
        $this->assertDatabaseHas('roles', [
            'name' => 'Murid',
            'guard_name' => 'web',
        ]);
        $this->assertDatabaseHas('payment_spps', [
            'user_id' => $applicant->id,
        ]);
        $this->assertDatabaseHas('detail_payment_spps', [
            'user_id' => $applicant->id,
            'amount' => 150000,
        ]);
    }

    public function test_ppdb_staff_can_open_current_and_legacy_uploaded_documents(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        Storage::disk('local')->put('images/berkas_murid/kartu-keluarga.pdf', 'Kartu keluarga');
        Storage::disk('local')->put('public/images/berkas_murid/akte-kelahiran.pdf', 'Akte kelahiran');

        $staff = $this->createUserWithRole('PPDB', 'petugas-berkas');
        $applicant = $this->createUser('Guest', 'calon-berkas');
        DB::table('berkas_murids')->insert([
            'user_id' => $applicant->id,
            'kartu_keluarga' => 'kartu-keluarga.pdf',
            'akte_kelahiran' => 'akte-kelahiran.pdf',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($staff)
            ->get(route('data-murid.berkas', [$applicant->id, 'kartu_keluarga']))
            ->assertOk()
            ->assertStreamedContent('Kartu keluarga');

        $this->get(route('data-murid.berkas', [$applicant->id, 'akte_kelahiran']))
            ->assertOk()
            ->assertStreamedContent('Akte kelahiran');
    }

    public function test_non_ppdb_users_cannot_open_applicant_documents(): void
    {
        $applicant = $this->createUser('Guest', 'calon-terlindungi');
        DB::table('berkas_murids')->insert([
            'user_id' => $applicant->id,
            'kartu_keluarga' => 'dokumen.pdf',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Storage::disk('public')->put('images/berkas_murid/dokumen.pdf', 'Dokumen');

        $this->actingAs($applicant)
            ->get(route('data-murid.berkas', [$applicant->id, 'kartu_keluarga']))
            ->assertForbidden();
    }

    public function test_applicant_document_uploads_are_saved_to_public_storage_with_their_own_files(): void
    {
        Storage::fake('local');
        $applicant = $this->createUserWithRole('Guest', 'calon-upload');
        $berkasId = DB::table('berkas_murids')->insertGetId([
            'user_id' => $applicant->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $fields = [
            'kartu_keluarga',
            'akte_kelahiran',
            'surat_kelakuan_baik',
            'surat_sehat',
            'surat_tidak_buta_warna',
            'rapor',
            'foto',
        ];
        $files = [];

        foreach ($fields as $field) {
            $files[$field] = UploadedFile::fake()->image($field.'.png');
        }

        $this->actingAs($applicant)
            ->put('/ppdb/form-berkas/'.$berkasId, $files)
            ->assertRedirect('/home');

        $savedBerkas = DB::table('berkas_murids')->where('id', $berkasId)->first();

        foreach ($fields as $field) {
            $this->assertIsString($savedBerkas->{$field});
            Storage::disk('local')->assertExists('images/berkas_murid/'.$savedBerkas->{$field});
            Storage::disk('public')->assertMissing('images/berkas_murid/'.$savedBerkas->{$field});
        }

        $this->assertNotSame($savedBerkas->kartu_keluarga, $savedBerkas->akte_kelahiran);
    }

    public function test_ppdb_login_and_registration_pages_use_the_school_logo(): void
    {
        DB::table('footers')->insert(['logo' => 'logo-sma11.png']);

        $this->get('/login')
            ->assertOk()
            ->assertSee('storage/images/logo/logo-sma11.png')
            ->assertSee('Logo SMA Negeri 11 Pulau Taliabu');

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('storage/images/logo/logo-sma11.png')
            ->assertSee('SMA Negeri 11 Pulau Taliabu')
            ->assertDontSee('SekolahKu');
    }

    public function test_applicant_detail_uses_the_school_name_as_its_heading(): void
    {
        $staff = $this->createUserWithRole('PPDB', 'petugas-detail');
        $applicant = $this->createUser('Guest', 'calon-detail');
        DB::table('data_murids')->insert([
            'user_id' => $applicant->id,
            'agama' => 'Islam',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('data_orang_tuas')->insert([
            'user_id' => $applicant->id,
            'nama_ayah' => 'Ayah Calon Murid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('berkas_murids')->insert([
            'user_id' => $applicant->id,
            'kartu_keluarga' => 'kartu-keluarga.pdf',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($staff)
            ->get(route('data-murid.show', $applicant))
            ->assertOk()
            ->assertSee('<h2>SMA Negeri 11 Pulau Taliabu</h2>', false)
            ->assertSee(route('data-murid.berkas', [$applicant->id, 'kartu_keluarga']), false)
            ->assertDontSee('SMK Yadika Natar');
    }

    private function createUser(string $roleName, string $username): User
    {
        return User::create([
            'name' => $username,
            'username' => $username,
            'email' => $username.'@example.test',
            'role' => $roleName,
            'status' => 'Aktif',
            'password' => Hash::make('password-aman'),
        ]);
    }

    private function createUserWithRole(string $roleName, string $username): User
    {
        $role = Role::findOrCreate($roleName, 'web');
        $user = $this->createUser($roleName, $username);
        $user->assignRole($role);

        return $user;
    }

    private function createTables(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('role');
            $table->string('status');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('model_has_roles', function (Blueprint $table): void {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::create('data_murids', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('nis')->nullable();
            $table->unsignedBigInteger('nisn')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('agama')->nullable();
            $table->string('telp')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('proses')->nullable();
            $table->timestamps();
        });

        Schema::create('data_orang_tuas', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama_ayah')->nullable();
            $table->string('pendidikan_ayah')->nullable();
            $table->string('telp_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('alamat_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('telp_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('alamat_ibu')->nullable();
            $table->timestamps();
        });

        Schema::create('berkas_murids', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('kartu_keluarga')->nullable();
            $table->string('akte_kelahiran')->nullable();
            $table->string('surat_kelakuan_baik')->nullable();
            $table->string('surat_sehat')->nullable();
            $table->string('surat_tidak_buta_warna')->nullable();
            $table->string('rapor')->nullable();
            $table->string('foto')->nullable();
            $table->string('ijazah')->nullable();
            $table->timestamps();
        });

        Schema::create('footers', function (Blueprint $table): void {
            $table->id();
            $table->string('logo')->nullable();
        });
    }
}
