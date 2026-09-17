<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'message',
        'image',
        'youtube_video_url',
        'youtube_video_id',
        'is_short',
        'video_category',
        'rating',
        'is_active',
        'is_showcase',
        'order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'is_showcase'=> 'boolean',
        'is_short'   => 'boolean',
        'order'      => 'integer',
        'rating'     => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Videos that should appear on the Awards page.
     */
    public function scopeAwardVideos($query)
    {
        return $query->whereNotNull('youtube_video_url')
                     ->where('video_category', 'award')
                     ->where('is_active', true);
    }

    /**
     * Videos that belong on the Testimonials page.
     * Includes null (legacy) and explicit 'testimonial' category.
     * Excludes Shorts (use scopeShorts for those).
     */
    public function scopeTestimonialVideos($query)
    {
        return $query->whereNotNull('youtube_video_url')
                     ->where('is_short', false)
                     ->where(function ($q) {
                         $q->whereNull('video_category')
                           ->orWhere('video_category', 'testimonial');
                     })
                     ->where('is_active', true);
    }

    /**
     * YouTube Shorts (≤ 60 seconds) that sit on the Testimonials page.
     */
    public function scopeShorts($query)
    {
        return $query->whereNotNull('youtube_video_url')
                     ->where('is_short', true)
                     ->where('is_active', true);
    }

    /**
     * All videos (Shorts + regular) for a page, regardless of length.
     */
    public function scopeAllVideos($query)
    {
        return $query->whereNotNull('youtube_video_url')
                     ->where('is_active', true);
    }

    /**
     * Scope to get the showcase video for a specific category.
     */
    public function scopeShowcase($query, string $category = 'testimonial')
    {
        $query->where('is_showcase', true)
              ->where('is_active', true)
              ->whereNotNull('youtube_video_url');

        if ($category === 'testimonial') {
            return $query->where(function ($q) {
                $q->whereNull('video_category')
                  ->orWhere('video_category', 'testimonial');
            });
        }

        return $query->where('video_category', $category);
    }

    public function getYoutubeEmbedUrlAttribute()
    {
        if (!$this->youtube_video_url) {
            return null;
        }

        // Handle various YouTube URL formats to extract the ID
        $url = $this->youtube_video_url;
        $videoId = null;
        
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match)) {
            $videoId = $match[1];
        }

        return $videoId ? 'https://www.youtube.com/embed/' . $videoId : $url;
    }
}
