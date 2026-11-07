<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    /**
     * Fasilitas sekolah yang tampil pada bagian statistik halaman depan.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'desc',
        'image',
        'is_active',
        'urutan',
    ];

    /**
     * Daftar fasilitas aktif, terurut.
     *
     * @return Collection<int, self>
     */
    public static function aktif()
    {
        return static::where('is_active', '0')->orderBy('urutan')->orderBy('id')->get();
    }
}
