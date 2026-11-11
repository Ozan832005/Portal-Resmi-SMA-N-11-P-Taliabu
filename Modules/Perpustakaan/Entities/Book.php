<?php

namespace Modules\Perpustakaan\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $guarded = '';

    public function thumbnailStoragePath(): string
    {
        $filename = basename(str_replace('\\', '/', $this->thumbnail));

        return 'images/thumbnail/'.$filename;
    }

    public function legacyThumbnailStoragePath(): string
    {
        return 'public/'.$this->thumbnailStoragePath();
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'book_id', 'id');
    }
}
