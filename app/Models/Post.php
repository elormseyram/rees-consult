<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'published_at',
        'author_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the post's image URL.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return Storage::disk('public')->url($this->image);
    }
}
