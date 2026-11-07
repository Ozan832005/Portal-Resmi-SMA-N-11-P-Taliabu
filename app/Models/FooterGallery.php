<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class FooterGallery extends Model
{
    /**
     * Foto galeri footer yang dikelola dari panel admin.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'caption',
        'urutan',
        'is_active',
    ];

    /**
     * Daftar foto aktif, terurut.
     *
     * @return Collection<int, self>
     */
    public static function aktif()
    {
        return static::where('is_active', '0')->orderBy('urutan')->orderBy('id')->get();
    }
}
