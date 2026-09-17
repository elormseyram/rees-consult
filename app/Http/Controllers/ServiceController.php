<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Pricing;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $standardizedTests = Service::active()->standardizedTests()->ordered()->get();
        $schoolApplications = Service::active()->schoolApplications()->ordered()->get();
        $jobAbroad = Service::active()->jobAbroad()->ordered()->get();
        
        $pricings = Pricing::active()->ordered()->get();
        $pricingsMap = $pricings->keyBy(function ($pricing) {
            return strtoupper(explode(' ', trim($pricing->test_name))[0]);
        });

        return view('services.index', compact('standardizedTests', 'schoolApplications', 'jobAbroad', 'pricingsMap'));
    }

    public function show(Service $service)
    {
        // For standardized tests, find the matching pricing row by test name keyword
        $pricing = null;
        if ($service->category === 'standardized_test') {
            $firstWord = strtoupper(explode(' ', trim($service->title))[0]);
            $allPricings = Pricing::active()->get()->keyBy(function ($p) {
                return strtoupper(explode(' ', trim($p->test_name))[0]);
            });
            $pricing = $allPricings->get($firstWord);
        }

        $relatedServices = Service::active()
            ->where('id', '!=', $service->id)
            ->where('category', $service->category)
            ->take(3)
            ->get();

        if ($relatedServices->count() < 3) {
            $extra = Service::active()
                ->where('id', '!=', $service->id)
                ->whereNotIn('id', $relatedServices->pluck('id'))
                ->take(3 - $relatedServices->count())
                ->get();
            $relatedServices = $relatedServices->concat($extra);
        }

        $relatedPosts = \App\Models\Post::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest()
            ->take(3)
            ->get();

        return view('services.show', compact('service', 'pricing', 'relatedServices', 'relatedPosts'));
    }
}

