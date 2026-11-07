<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Penanganan unggahan gambar aplikasi.
 *
 * Semua gambar disimpan pada disk "public" (storage/app/public/images/<folder>)
 * sehingga dapat diakses lewat symlink public/storage dan helper asset().
 */
class ImageStorage
{
    public const DISK = 'public';

    public const DIRECTORY = 'images';

    /**
     * Simpan berkas unggahan dan kembalikan nama berkasnya.
     */
    public static function store(?UploadedFile $file, string $folder): ?string
    {
        if (! $file instanceof UploadedFile) {
            return null;
        }

        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $baseName = $baseName !== '' ? Str::limit($baseName, 60, '') : 'gambar';

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');

        $filename = time().'_'.$baseName.'.'.$extension;

        $file->storeAs(self::DIRECTORY.'/'.$folder, $filename, self::DISK);

        return $filename;
    }

    /**
     * Hapus berkas lama agar tidak menumpuk di penyimpanan.
     */
    public static function delete(?string $filename, string $folder): void
    {
        if (empty($filename)) {
            return;
        }

        $path = self::DIRECTORY.'/'.$folder.'/'.$filename;

        if (Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    /**
     * URL publik sebuah gambar, atau $fallback ketika belum ada gambar.
     */
    public static function url(?string $filename, string $folder, ?string $fallback = null): ?string
    {
        if (empty($filename)) {
            return $fallback;
        }

        return asset('storage/'.self::DIRECTORY.'/'.$folder.'/'.rawurlencode($filename));
    }
}
