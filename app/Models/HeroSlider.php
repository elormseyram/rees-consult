<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image_url',
        'image_path',
        'overlay_opacity',
        'order',
        'is_active',
    ];

    protected $casts = [
        'overlay_opacity' => 'float',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    // Scope to get active hero sliders ordered
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order', 'asc');
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
