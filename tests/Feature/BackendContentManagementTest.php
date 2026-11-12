<?php

namespace Tests\Feature;

use App\Models\Curriculum;
use App\Models\Events;
use App\Models\PublicationVideo;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class BackendContentManagementTest extends TestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createAdminTables();

        foreach (['Admin', 'Guru', 'Staf', 'Guest', 'Murid', 'PPDB', 'Perpustakaan', 'Bendahara'] as $roleName) {
            Role::create([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
        }

        $this->admin = User::create([
            'name' => 'Admin Tes',
            'username' => 'admin-tes',
            'email' => 'admin@example.test',
            'role' => 'Admin',
            'status' => 'Aktif',
            'password' => bcrypt('password'),
        ]);
        $this->admin->assignRole('Admin');
    }

    public function test_admin_content_pages_render_with_their_separate_menus(): void
    {
        $this->actingAs($this->admin)
            ->get(route('backend-kurikulum.index'))
            ->assertOk()
            ->assertSee('Kurikulum')
            ->assertSee('Video publikasi')
            ->assertDontSee('Program studi');

        $this->get(route('backend-video-publikasi.index'))
            ->assertOk()
            ->assertSee('Video publikasi')
            ->assertSee('halaman Publikasi')
            ->assertSee('km-delete-dialog')
            ->assertSee('km-delete-confirmation.js');
    }

    public function test_admin_bendahara_management_menu_and_routes_are_removed(): void
    {
        $this->actingAs($this->admin)
            ->get(route('backend-kurikulum.index'))
            ->assertOk()
            ->assertDontSee('backend-pengguna-bendahara');

        $this->get('/backend-pengguna-bendahara')
            ->assertNotFound();
    }

    public function test_settings_page_shows_only_notification_preferences(): void
    {
        $this->actingAs($this->admin)
            ->get(route('settings'))
            ->assertOk()
            ->assertSee('km-settings-page')
            ->assertSee('km-page km-settings-page')
            ->assertSee('km-theme-toggle')
            ->assertSee('km-theme-toggle__label')
            ->assertDontSee('p-0 km-page km-settings-page')
            ->assertSee('Pemberitahuan')
            ->assertSee('Email')
            ->assertSee('WhatsApp')
            ->assertDontSee('Account Bank')
            ->assertDontSee('Setting SPP')
            ->assertSee('km-notification__empty');
    }

    public function test_header_notification_panel_shows_flashing_messages(): void
    {
        $this->actingAs($this->admin)
            ->withSession(['success' => 'Acara berhasil ditambahkan.'])
            ->get(route('settings'))
            ->assertOk()
            ->assertSee('1 baru')
            ->assertSee('Acara berhasil ditambahkan.');
    }

    public function test_admin_can_create_a_student_when_its_application_role_has_not_been_seeded(): void
    {
        Role::query()->where('name', '!=', 'Admin')->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $response = $this->actingAs($this->admin)
            ->post(route('backend-pengguna-murid.store'), [
                'name' => 'Murid Baru',
                'email' => 'murid-baru@example.test',
                'password' => 'password-aman',
            ]);

        $response->assertRedirect(route('backend-pengguna-murid.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Murid Baru',
            'email' => 'murid-baru@example.test',
            'role' => 'Guest',
            'status' => 'Aktif',
        ]);
        $this->assertDatabaseHas('roles', [
            'name' => 'Guest',
            'guard_name' => 'web',
        ]);

        $murid = User::query()->where('email', 'murid-baru@example.test')->firstOrFail();
        $this->assertSame(['Guest'], $murid->getRoleNames()->all());
    }

    public function test_admin_can_create_update_and_delete_curriculum_items(): void
    {
        $this->actingAs($this->admin)
            ->post(route('backend-kurikulum.store'), [
                'label' => 'ATP',
                'title' => 'Alur Tujuan Pembelajaran',
                'description' => 'Urutan target pembelajaran.',
                'is_active' => '1',
                'sort_order' => 2,
            ])
            ->assertRedirect(route('backend-kurikulum.index'));

        $curriculum = Curriculum::query()->firstOrFail();
        $this->assertDatabaseHas('curricula', [
            'id' => $curriculum->id,
            'title' => 'Alur Tujuan Pembelajaran',
            'is_active' => 1,
        ]);

        $this->put(route('backend-kurikulum.update', $curriculum), [
            'label' => 'TP',
            'title' => 'Tujuan Pembelajaran',
            'description' => 'Tujuan tiap unit belajar.',
            'is_active' => '0',
            'sort_order' => 3,
        ])->assertRedirect(route('backend-kurikulum.index'));

        $this->assertDatabaseHas('curricula', [
            'id' => $curriculum->id,
            'title' => 'Tujuan Pembelajaran',
            'is_active' => 0,
            'sort_order' => 3,
        ]);

        $this->delete(route('backend-kurikulum.destroy', $curriculum))
            ->assertRedirect(route('backend-kurikulum.index'));

        $this->assertDatabaseMissing('curricula', ['id' => $curriculum->id]);
    }

    public function test_teacher_can_view_and_download_admin_uploaded_module_ajar(): void
    {
        Storage::fake('local');

        $this->actingAs($this->admin)
            ->post(route('backend-kurikulum.store'), [
                'label' => 'CP',
                'title' => 'Capaian Pembelajaran',
                'description' => 'Target pembelajaran.',
                'is_active' => '1',
                'sort_order' => 1,
                'module_ajar' => UploadedFile::fake()->create('capaian.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect(route('backend-kurikulum.index'));

        $curriculum = Curriculum::query()->firstOrFail();
        Storage::disk('local')->assertExists($curriculum->module_ajar_path);

        $teacher = User::create([
            'name' => 'Guru Tes',
            'username' => 'guru-tes',
            'email' => 'guru@example.test',
            'role' => 'Guru',
            'status' => 'Aktif',
            'password' => bcrypt('password'),
        ]);
        $teacher->assignRole('Guru');

        $this->actingAs($teacher)
            ->get(route('guru.kurikulum'))
            ->assertOk()
            ->assertSee('Capaian Pembelajaran')
            ->assertSee(route('guru.kurikulum'), false)
            ->assertSee(route('backend-kurikulum.modul-ajar', $curriculum), false);

        $this->get(route('backend-kurikulum.modul-ajar', $curriculum))
            ->assertDownload('capaian-pembelajaran.pdf');

        $oldModuleAjarPath = $curriculum->module_ajar_path;

        $this->actingAs($this->admin)
            ->put(route('backend-kurikulum.update', $curriculum), [
                'label' => 'CP',
                'title' => 'Capaian Pembelajaran',
                'description' => 'Target pembelajaran.',
                'is_active' => '1',
                'sort_order' => 1,
                'module_ajar' => UploadedFile::fake()->create('capaian-baru.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect(route('backend-kurikulum.index'));

        $curriculum->refresh();
        Storage::disk('local')->assertMissing($oldModuleAjarPath);
        Storage::disk('local')->assertExists($curriculum->module_ajar_path);

        $this->delete(route('backend-kurikulum.destroy', $curriculum))
            ->assertRedirect(route('backend-kurikulum.index'));

        Storage::disk('local')->assertMissing($curriculum->module_ajar_path);
    }

    public function test_non_teachers_cannot_open_the_teacher_curriculum_page(): void
    {
        $staff = User::create([
            'name' => 'Staf Tes',
            'username' => 'staf-kurikulum',
            'email' => 'staf-kurikulum@example.test',
            'role' => 'Staf',
            'status' => 'Aktif',
            'password' => bcrypt('password'),
        ]);
        $staff->assignRole('Staf');
        $curriculum = Curriculum::create([
            'label' => 'CP',
            'title' => 'Capaian Pembelajaran',
            'description' => 'Target pembelajaran.',
            'is_active' => true,
            'sort_order' => 1,
            'module_ajar_path' => 'kurikulum/modul-ajar/capaian.pdf',
        ]);

        $this->actingAs($staff)
            ->get(route('guru.kurikulum'))
            ->assertForbidden();

        $this->get(route('backend-kurikulum.modul-ajar', $curriculum))
            ->assertForbidden();
    }

    public function test_admin_cannot_upload_a_non_pdf_as_module_ajar(): void
    {
        Storage::fake('local');

        $this->actingAs($this->admin)
            ->from(route('backend-kurikulum.create'))
            ->post(route('backend-kurikulum.store'), [
                'label' => 'Modul',
                'title' => 'Modul Ajar',
                'description' => 'Panduan pembelajaran.',
                'is_active' => '1',
                'sort_order' => 1,
                'module_ajar' => UploadedFile::fake()->create('modul.txt', 10, 'text/plain'),
            ])
            ->assertSessionHasErrors('module_ajar');

        $this->assertDatabaseCount('curricula', 0);
    }

    public function test_admin_can_create_update_and_delete_publication_videos(): void
    {
        $this->actingAs($this->admin)
            ->post(route('backend-video-publikasi.store'), [
                'title' => 'Dokumentasi kegiatan',
                'desc' => 'Kegiatan sekolah.',
                'url' => 'https://www.youtube.com/watch?v=abcDEF12345',
                'is_active' => '0',
            ])
            ->assertRedirect(route('backend-video-publikasi.index'));

        $video = PublicationVideo::query()->firstOrFail();
        $this->assertDatabaseHas('publication_videos', [
            'id' => $video->id,
            'title' => 'Dokumentasi kegiatan',
        ]);

        $this->put(route('backend-video-publikasi.update', $video), [
            'title' => 'Dokumentasi terbaru',
            'desc' => 'Kegiatan sekolah terbaru.',
            'url' => 'https://youtu.be/bcdEFG23456',
            'is_active' => '1',
        ])->assertRedirect(route('backend-video-publikasi.index'));

        $this->assertDatabaseHas('publication_videos', [
            'id' => $video->id,
            'title' => 'Dokumentasi terbaru',
            'is_active' => '1',
        ]);

        $this->delete(route('backend-video-publikasi.destroy', $video))
            ->assertRedirect(route('backend-video-publikasi.index'));

        $this->assertDatabaseMissing('publication_videos', ['id' => $video->id]);
    }

    public function test_admin_can_create_events_with_validated_thumbnails(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post(route('backend-event.store'), [
                'title' => 'Acara sekolah',
                'desc' => 'Deskripsi acara sekolah.',
                'content' => 'Informasi lengkap acara sekolah.',
                'acara' => '2026-12-01T10:00',
                'lokasi' => 'Taliabu',
                'thumbnail' => UploadedFile::fake()->image('acara.png'),
            ])
            ->assertRedirect(route('backend-event.index'));

        $event = Events::query()->firstOrFail();
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Acara sekolah',
        ]);
        Storage::disk('public')->assertExists('images/event/'.$event->thumbnail);
    }

    public function test_non_admin_users_cannot_create_curriculum_items(): void
    {
        $staff = User::create([
            'name' => 'Staf Tes',
            'username' => 'staf-tes',
            'email' => 'staff@example.test',
            'role' => 'Staf',
            'status' => 'Aktif',
            'password' => bcrypt('password'),
        ]);
        $staff->assignRole('Staf');

        $this->actingAs($staff)
            ->post(route('backend-kurikulum.store'), [])
            ->assertForbidden();

        $this->assertDatabaseCount('curricula', 0);
    }

    public function test_admin_can_create_students_with_repeated_names(): void
    {
        $this->actingAs($this->admin);

        foreach (['pertama', 'kedua'] as $suffix) {
            $this->post(route('backend-pengguna-murid.store'), [
                'name' => 'Murid Contoh',
                'email' => 'murid-'.$suffix.'@example.test',
                'password' => 'password123',
            ])->assertRedirect(route('backend-pengguna-murid.index'));
        }

        $firstUsername = User::query()->where('email', 'murid-pertama@example.test')->value('username');
        $secondUsername = User::query()->where('email', 'murid-kedua@example.test')->value('username');

        $this->assertNotSame($firstUsername, $secondUsername);
        $this->assertDatabaseHas('users', [
            'email' => 'murid-pertama@example.test',
            'status' => 'Aktif',
        ]);
        $this->assertDatabaseCount('data_murids', 2);
    }

    public function test_admin_receives_validation_error_when_library_staff_email_is_already_used(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post(route('backend-pengguna-perpus.store'), [
                'name' => 'Petugas Perpustakaan',
                'email' => $this->admin->email,
                'nip' => '123456',
                'foto_profile' => UploadedFile::fake()->image('petugas.png'),
            ])
            ->assertSessionHasErrors([
                'email' => 'Email sudah pernah digunakan.',
            ]);

        $this->assertDatabaseCount('users', 1);
    }

    private function createAdminTables(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('role')->nullable();
            $table->string('status');
            $table->string('foto_profile')->nullable();
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

        Schema::create('footers', function (Blueprint $table): void {
            $table->id();
            $table->string('logo')->nullable();
        });

        Schema::create('data_murids', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->boolean('isEmail')->default(false);
            $table->string('email')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug');
            $table->text('desc');
            $table->text('content');
            $table->string('thumbnail');
            $table->dateTime('acara');
            $table->string('lokasi');
            $table->string('is_active')->default('0');
            $table->timestamps();
        });

        Schema::create('curricula', function (Blueprint $table): void {
            $table->id();
            $table->string('label', 50);
            $table->string('title');
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('module_ajar_path')->nullable();
            $table->timestamps();
        });

        Schema::create('publication_videos', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->text('desc');
            $table->string('url', 2048);
            $table->string('is_active')->default('0');
            $table->timestamps();
        });
    }
}
