<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    protected $fillable = [
        'label',
        'title',
        'description',
        'is_active',
        'sort_order',
        'module_ajar_path',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
