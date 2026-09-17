<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_slug',
        'page_title',
        'image_url',
        'image_path',
        'overlay_opacity',
        'is_active',
    ];

    protected $casts = [
        'overlay_opacity' => 'float',
        'is_active' => 'boolean',
    ];

    // Scope to get active hero images
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get hero image by page slug
    public function scopeByPage($query, $pageSlug)
    {
        return $query->where('page_slug', $pageSlug)->active()->first();
    }

    /**
     * Get the formatted image URL (path or external URL).
     */
    public function getFormattedImageUrlAttribute()
    {
        if ($this->image_path) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->image_path);
        }

        return $this->image_url;
    }
}
