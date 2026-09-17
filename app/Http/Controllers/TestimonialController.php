<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');

        // Fetch the main showcase video for testimonials (exclude award/other categories, not a Short)
        $showcaseVideo = Testimonial::active()
            ->where('is_showcase', true)
            ->whereNotNull('youtube_video_url')
            ->where(function ($q) {
                $q->whereNull('video_category')
                  ->orWhere('video_category', 'testimonial');
            })
            ->first();

        $query = Testimonial::active()->ordered();

        if ($type === 'video') {
            // Regular landscape videos only (no Shorts), testimonial category
            $query->testimonialVideos();
        } elseif ($type === 'shorts') {
            // YouTube Shorts only — can span all categories
            $query->shorts();
        } elseif ($type === 'written') {
            $query->whereNull('youtube_video_url');
        } else {
            // Default: written + testimonial-category videos (both regular and Shorts)
            $query->where(function ($q) {
                $q->whereNull('youtube_video_url')
                  ->orWhereNull('video_category')
                  ->orWhere('video_category', 'testimonial');
            });
        }

        // Exclude the showcase video from the main grid
        if ($showcaseVideo) {
            $query->where('id', '!=', $showcaseVideo->id);
        }

        $testimonials = $query->paginate(9)->withQueryString();

        return view('testimonials.index', compact('testimonials', 'type', 'showcaseVideo'));
    }
}
