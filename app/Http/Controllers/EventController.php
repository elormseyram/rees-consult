<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('is_active', true)
            ->where('end_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->paginate(9);
            
        return view('events.index', compact('events'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
            
        return view('events.show', compact('event'));
    }
}
