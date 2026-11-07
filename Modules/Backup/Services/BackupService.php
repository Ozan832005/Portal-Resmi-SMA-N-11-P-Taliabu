<?php

namespace Modules\Backup\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    /**
     * Path mysqldump divalidasi secara lazy di dalam operasi backup/restore,
     * supaya modul Backup tetap bisa diakses meski DUMP_BINARY_PATH belum di-set.
     */
    protected function validateDumpPath()
    {
        $dumpPath = config('database.connections.mysql.dump.dump_binary_path');

        if (empty($dumpPath)) {
            throw new Exception('Environment variable DUMP_BINARY_PATH belum di-set.');
        }

        // Cek apakah path folder ada
        if (! is_dir($dumpPath)) {
            throw new Exception("Environment variable DUMP_BINARY_PATH tidak ditemukan: {$dumpPath}");
        }

        // Cek apakah file mysqldump tersedia
        $mysqlDumpPath = PHP_OS_FAMILY === 'Windows' ? "$dumpPath/mysqldump.exe" : "$dumpPath/mysqldump";

        if (! file_exists($mysqlDumpPath)) {
            throw new Exception("File mysqldump tidak ditemukan di path: {$mysqlDumpPath}");
        }

        return true;
    }

    public function createDatabaseBackup(?string $filename = null): array
    {
        try {
            $this->validateDumpPath(); // validasi path mysqldump

            // Generate filename
            $filename = $filename ?: date('Y-m-d_Hi');
            $filename = preg_replace('/[^A-Za-z0-9\-_]/', '', $filename).'.zip';

            // Jalankan perintah artisan
            $command = 'cd '.base_path().' && php artisan backup:run --only-db';
            $output = shell_exec($command);
            if (str_contains($output, 'command not found')) {
                throw new Exception('Perintah mysqldump gagal dijalankan. Pastikan path benar dan mysqldump tersedia.');
            }

            Log::info('Backup command output:', ['output' => $output]);

            if (strpos($output, 'Backup failed') !== false) {
                throw new Exception('Backup gagal: '.$output);
            }

            // Dapatkan lokasi penyimpanan backup
            $diskName = config('backup.backup.destination.disks')[0];
            $disk = Storage::disk($diskName);
            $backupDiskPath = $disk->getDriver()->getAdapter()->getPathPrefix();

            // Cari file backup terbaru
            $latestBackup = $this->getLatestBackupFile($backupDiskPath);

            if (! $latestBackup) {
                throw new Exception('Tidak ada file backup ditemukan.');
            }

            // Pindahkan ke direktori tujuan
            $customBackupPath = storage_path('app/backup-db');

            if (! File::exists($customBackupPath)) {
                File::makeDirectory($customBackupPath, 0755, true);
            }

            $newPath = $customBackupPath.'/'.$filename;
            File::move($latestBackup->getPathname(), $newPath);

            return [
                'success' => true,
                'message' => 'Backup berhasil dibuat.',
                'backup_path' => $newPath,
            ];
        } catch (Exception $e) {
            Log::error('Backup error: '.$e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    protected function getLatestBackupFile(string $path)
    {
        $files = collect(File::allFiles($path))
            ->filter(fn ($file) => $file->getExtension() === 'zip')
            ->sortByDesc(fn ($file) => $file->getMTime());

        return $files->first();
    }

    // konversi nilai
    public static function formatBytes($bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        } elseif ($bytes > 1) {
            return $bytes.' bytes';
        } elseif ($bytes == 1) {
            return $bytes.' byte';
        } else {
            return '0 bytes';
        }
    }

    public function deleteBackup(string $filename): array
    {
        $path = storage_path('app/backup-db/'.$filename);

        if (! File::exists($path)) {
            return [
                'success' => false,
                'message' => 'File tidak ditemukan.',
            ];
        }

        File::delete($path);

        return [
            'success' => true,
            'message' => 'File backup berhasil dihapus.',
        ];
    }

    public function getBackupFilePath(string $filename): ?string
    {
        $path = storage_path('app/backup-db/'.$filename);

        return File::exists($path) ? $path : null;
    }

    // untuk fitur restore gunakan teknik pdo, karena menggunakan mysql melalui Symfony\Component\Process\Process langsung akan menimbulkan masalah tcp/ip socket error
    public function restoreDatabase(string $filename): array
    {
        try {
            $this->validateDumpPath();

            // Uji koneksi database sebelum restore
            try {
                DB::connection()->getPdo();
                Log::info('Database connection successful.');
            } catch (Exception $e) {
                throw new Exception('Gagal terhubung ke database: '.$e->getMessage());
            }

            // Path ke file backup
            $backupPath = storage_path('app/backup-db/'.$filename);
            if (! File::exists($backupPath)) {
                throw new Exception("File backup tidak ditemukan: {$backupPath}");
            }

            // Direktori sementara untuk ekstraksi
            $extractPath = storage_path('app/backup-temp/restore');
            if (! File::exists($extractPath)) {
                File::makeDirectory($extractPath, 0755, true);
            }

            // Ekstrak file .zip jika diperlukan
            $sqlFilePath = null;
            if (pathinfo($backupPath, PATHINFO_EXTENSION) === 'zip') {
                $zip = new \ZipArchive;
                if ($zip->open($backupPath) === true) {
                    $zip->extractTo($extractPath);
                    $zip->close();

                    // Pindahkan file SQL keluar dari db-dumps
                    $subFolder = "{$extractPath}/db-dumps";
                    if (File::isDirectory($subFolder)) {
                        $files = File::allFiles($subFolder);
                        foreach ($files as $file) {
                            $newPath = "{$extractPath}/".$file->getFilename();
                            File::move($file->getPathname(), $newPath);
                        }

                        File::deleteDirectory($subFolder);
                    }

                    // Cari file .sql di direktori ekstraksi
                    $sqlFiles = collect(File::files($extractPath))
                        ->filter(function ($file) {
                            return $file->getExtension() === 'sql';
                        });

                    if ($sqlFiles->isEmpty()) {
                        throw new Exception('Tidak ada file .sql ditemukan di direktori ekstraksi.');
                    }

                    $sqlFilePath = $sqlFiles->first()->getPathname();
                    Log::info('SQL file path used for restore: '.$sqlFilePath);
                } else {
                    throw new Exception("Gagal membuka file .zip: {$backupPath}");
                }
            } else {
                $sqlFilePath = $backupPath;
                Log::info('SQL file path used for restore: '.$sqlFilePath);
            }

            // Baca file .sql dan jalankan menggunakan PDO
            $sqlContent = file_get_contents($sqlFilePath);
            if ($sqlContent === false) {
                throw new Exception("Gagal membaca file SQL: {$sqlFilePath}");
            }

            // Jalankan query menggunakan PDO
            $pdo = DB::connection()->getPdo();
            // Pisahkan perintah SQL berdasarkan semicolon
            $statements = array_filter(array_map('trim', explode(';', $sqlContent)));
            foreach ($statements as $statement) {
                if (! empty($statement)) {
                    $pdo->exec($statement);
                }
            }

            Log::info('Database restored successfully using PDO from: '.$backupPath);

            // Bersihkan file sementara
            if ($sqlFilePath !== $backupPath) {
                File::deleteDirectory($extractPath);
            }

            return [
                'success' => true,
                'message' => 'Database berhasil dipulihkan dari file: '.$filename,
            ];
        } catch (Exception $e) {
            Log::error('Restore error: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal memulihkan database: '.$e->getMessage(),
            ];
        }
    }

    public function uploadBackup(UploadedFile $file): array
    {
        try {
            // Validasi file
            if ($file->getClientOriginalExtension() !== 'zip') {
                throw new Exception('File harus berupa .zip.');
            }

            $filename = $file->getClientOriginalName();
            $destinationPath = storage_path('app/backup-db');

            // Pastikan direktori ada
            if (! File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // Simpan file
            $file->move($destinationPath, $filename);

            Log::info('Backup file uploaded: '.$filename);

            return [
                'success' => true,
                'message' => 'File backup berhasil diunggah: '.$filename,
            ];
        } catch (Exception $e) {
            Log::error('Upload error: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal mengunggah file backup: '.$e->getMessage(),
            ];
        }
    }
}
