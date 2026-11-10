<?php

use App\Http\Controllers\Backend\Pengguna\BendaharaController;
use App\Http\Controllers\Backend\Pengguna\MuridController;
use App\Http\Controllers\Backend\Pengguna\PengajarController;
use App\Http\Controllers\Backend\Pengguna\PerpusController;
use App\Http\Controllers\Backend\Pengguna\PPDBController;
use App\Http\Controllers\Backend\Pengguna\StafController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\Website\AboutController;
use App\Http\Controllers\Backend\Website\BeritaController;
use App\Http\Controllers\Backend\Website\EventsController;
use App\Http\Controllers\Backend\Website\FasilitasController;
use App\Http\Controllers\Backend\Website\FooterController;
use App\Http\Controllers\Backend\Website\FooterGalleryController;
use App\Http\Controllers\Backend\Website\ImageSliderController;
use App\Http\Controllers\Backend\Website\KategoriBeritaController;
use App\Http\Controllers\Backend\Website\KegiatanController;
use App\Http\Controllers\Backend\Website\ProfilSekolahController;
use App\Http\Controllers\Backend\Website\ProgramController;
use App\Http\Controllers\Backend\Website\VideoController;
use App\Http\Controllers\Backend\Website\VisidanMisiController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\MenuController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\TrackPublicVisits;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ======= FRONTEND ======= \\

Route::post('/language/{locale}', function (string $locale): RedirectResponse {
    session(['locale' => $locale]);

    return back();
})->where('locale', 'id|en')->name('language.switch');

Route::middleware([SetLocale::class, TrackPublicVisits::class])->group(function () {
    Route::get('/', [IndexController::class, 'index']);

    // /// MENU \\\\\
    // // PROFILE SEKOLAH \\\\
    Route::get('profile-sekolah', [IndexController::class, 'profileSekolah'])->name('profile.sekolah');

    // // VISI dan MISI
    Route::get('visi-dan-misi', [IndexController::class, 'visimisi'])->name('visimisi.sekolah');

    // // KURIKULUM \\
    Route::get('kurikulum', [IndexController::class, 'kurikulum'])->name('kurikulum');
    Route::get('publikasi', [IndexController::class, 'publikasi'])->name('publikasi');
    Route::get('alumni/semua', [IndexController::class, 'alumniSemua'])->name('alumni.semua');
    Route::get('alumni', [IndexController::class, 'alumni'])->name('alumni');
    Route::get('katalog-perpustakaan', [IndexController::class, 'katalogPerpustakaan'])->name('katalog.perpustakaan');

    // // PROGRAM STUDI \\\\
    Route::get('program/{slug}', [MenuController::class, 'programStudi']);
    // // PROGRAM STUDI \\\\
    Route::get('kegiatan/{slug}', [MenuController::class, 'kegiatan']);

    // / BERITA \\\
    Route::get('berita', [IndexController::class, 'berita'])->name('berita');
    Route::get('berita/{slug}', [IndexController::class, 'detailBerita'])->name('detail.berita');

    // / EVENT \\\
    Route::get('event/{slug}', [IndexController::class, 'detailEvent'])->name('detail.event');
    Route::get('event', [IndexController::class, 'events'])->name('event');
});

Route::middleware(SetLocale::class)->group(function () {
    Auth::routes(['register' => false]);
});

// ======= BACKEND ======= \\
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // / PROFILE \\\
    Route::resource('profile-settings', ProfileController::class);

    // / SETTINGS \\\
    Route::prefix('settings')->group(function () {
        // BANK
        Route::get('/', [SettingController::class, 'index'])->name('settings');
        // TAMBAH BANK
        Route::post('add-bank', [SettingController::class, 'addBank'])->name('settings.add.bank');
        // NOTIFICATIONS
        Route::put('notifications/{id}', [SettingController::class, 'notifications']);
    });

//Tinker
Route::get('/tinker-web', function () {
    $user = App\Models\User::where('username', 'admin11')->first();
    
    if (!$user) {
        return "User admin11 tidak ditemukan di database.";
    }

    $log = [];

    // 1. Coba daftarkan role via package Spatie jika ada
    if (method_exists($user, 'assignRole')) {
        try {
            // Mencoba memberikan beberapa kemungkinan nama role tertinggi
            $user->assignRole('Admin');
            $user->assignRole('Super Admin');
            $user->assignRole('admin');
            $log[] = "Berhasil assignRole via Spatie.";
        } catch (\Exception $e) {
            $log[] = "Gagal assignRole: " . $e->getMessage();
        }
    }

    // 2. Coba sinkronisasi langsung via ID tabel jika menggunakan Spatie 
    // tapi role-nya belum terdaftar di tabel 'roles'
    try {
        $roleId = DB::table('roles')->whereIn('name', ['Admin', 'Super Admin', 'admin'])->value('id');
        if ($roleId) {
            DB::table('model_has_roles')->updateOrInsert(
                ['role_id' => $roleId, 'model_type' => get_class($user), 'model_id' => $user->id]
            );
            $log[] = "Berhasil memasukkan data langsung ke tabel model_has_roles.";
        } else {
            $log[] = "Nama role tertinggi tidak ditemukan di tabel 'roles'.";
        }
    } catch (\Exception $e) {
        $log[] = "Bukan masalah Spatie database: " . $e->getMessage();
    }

    // 3. Pastikan kolom string manual diubah ke role tertinggi (jika bukan pakai package)
    $user->role = 'Admin'; // Ubah jadi 'Super Admin' atau lainnya jika tahu role tertingginya
    $user->status = 'Aktif';
    $user->save();
    $log[] = "Kolom tabel users berhasil disimpan.";

    return response()->json([
        'status' => 'Proses Selesai',
        'catatan_sistem' => $log,
        'data_user_sekarang' => $user
    ]);
});



    // / CHANGE PASSWORD
    Route::put('profile-settings/change-password/{id}', [ProfileController::class, 'changePassword'])->name('profile.change-password');

    Route::prefix('/')->middleware('role:Admin')->group(function () {
        // /// WEBSITE \\\\\
        Route::resources([
            // / PROFILE SEKOLAH \\
            'backend-profile-sekolah' => ProfilSekolahController::class,
            // / VISI & MISI \\\
            'backend-visimisi' => VisidanMisiController::class,
            // // PROGRAM STUDI \\\\
            'program-studi' => ProgramController::class,
            // / KEGIATAN \\\
            'backend-kegiatan' => KegiatanController::class,
            // / IMAGE SLIDER \\\
            'backend-imageslider' => ImageSliderController::class,
            // / ABOUT \\\
            'backend-about' => AboutController::class,
            // / VIDEO \\\
            'backend-video' => VideoController::class,
            // / KATEGORI BERITA \\\
            'backend-kategori-berita' => KategoriBeritaController::class,
            // / BERITA \\\
            'backend-berita' => BeritaController::class,
            // / EVENT \\\
            'backend-event' => EventsController::class,
            // / FOOTER \\\
            'backend-footer' => FooterController::class,
            // / FASILITAS SEKOLAH \\\
            'backend-fasilitas' => FasilitasController::class,
            // / GALERI FOTO FOOTER \\\
            'backend-footer-gallery' => FooterGalleryController::class,
        ]);

        // /// PENGGUNA \\\\\
        Route::resources([
            // / PENGAJAR \\\
            'backend-pengguna-pengajar' => PengajarController::class,
            // / STAF \\\
            'backend-pengguna-staf' => StafController::class,
            // / MURID \\\
            'backend-pengguna-murid' => MuridController::class,
            // / PPDB \\\
            'backend-pengguna-ppdb' => PPDBController::class,
            // / PERPUSTAKAAN \\\
            'backend-pengguna-perpus' => PerpusController::class,
            // / BENDAHARA \\\
            'backend-pengguna-bendahara' => BendaharaController::class,
        ]);
    });
});
