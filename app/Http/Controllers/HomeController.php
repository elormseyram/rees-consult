<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $services = \App\Models\Service::active()->ordered()->take(6)->get();
        // Text testimonials limit by 4 for better design (was 3), but limit to those without video
        $testimonials = \App\Models\Testimonial::active()->whereNull('youtube_video_url')->ordered()->take(4)->get();
        $videoTestimonials = \App\Models\Testimonial::active()->whereNotNull('youtube_video_url')->ordered()->get();
        $teamMembers = \App\Models\TeamMember::active()->ordered()->take(4)->get();
        
        return view('home', compact('services', 'testimonials', 'videoTestimonials', 'teamMembers'));
    }
}
