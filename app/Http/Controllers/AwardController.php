<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    public function index()
    {
        $awards = Award::active()->ordered()->get();
        
        // Fetch showcase video for awards page
        $showcaseVideo = Testimonial::active()
            ->where('is_showcase', true)
            ->where('video_category', 'award')
            ->whereNotNull('youtube_video_url')
            ->first();

        // Get other award videos, excluding the showcase one if it exists
        $awardVideosQuery = Testimonial::awardVideos()->ordered();
        if ($showcaseVideo) {
            $awardVideosQuery->where('id', '!=', $showcaseVideo->id);
        }
        $awardVideos = $awardVideosQuery->get();

        return view('awards.index', compact('awards', 'awardVideos', 'showcaseVideo'));
    }
}
