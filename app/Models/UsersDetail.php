<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersDetail extends Model
{
    use HasFactory;

    protected $table = 'users_details';

    /**
     * Kolom yang boleh diisi massal. Dibutuhkan karena controller pengguna
     * memakai firstOrNew(['user_id' => ...]) saat mengubah data.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'role',
        'mengajar',
        'nip',
        'email',
        'linkedln',
        'instagram',
        'twitter',
        'facebook',
        'youtube',
        'website',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nip' => 'integer',
            'is_active' => 'string',
        ];
    }
}
