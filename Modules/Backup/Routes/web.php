<?php

use Modules\Backup\Http\Controllers\BackupController;

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

Route::prefix('backup')->group(function () {
    Route::get('/', 'BackupController@index')->name('backup.index');
    Route::post('/create', [BackupController::class, 'create'])->name('backup.create');
    Route::get('/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
    Route::get('/delete/{filename}', [BackupController::class, 'destroy'])->name('backup.delete');
    Route::get('/restore/{filename}', [BackupController::class, 'restore'])->name('backup.restore');
    Route::post('/upload', [BackupController::class, 'upload'])->name('backup.upload');
});
