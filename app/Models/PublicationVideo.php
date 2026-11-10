<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicationVideo extends Model
{
    use Concerns\HasVideoPlayback;

    protected $fillable = [
        'title',
        'desc',
        'url',
        'is_active',
    ];
}
