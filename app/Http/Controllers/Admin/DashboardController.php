<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Payment;
use App\Services\DashboardMetrics;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $stats = [
            'total_consultations' => Consultation::count(),
            'pending_consultations' => Consultation::where('status', 'pending')->count(),
            'confirmed_consultations' => Consultation::where('status', 'confirmed')->count(),
            'completed_consultations' => Consultation::where('status', 'completed')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'total_test_signups' => \App\Models\ServiceSignup::count(),
            'total_school_applications' => \App\Models\SchoolApplication::count(),
            'total_job_applications' => \App\Models\JobApplication::count(),
            'total_leads' => \App\Models\Lead::count(),
            'hot_leads' => \App\Models\Lead::where('rating', 'HOT')->count(),
            'new_leads' => \App\Models\Lead::where('status', 'new')->count(),
        ];

        $recent_consultations = Consultation::with('payment')
            ->latest()
            ->take(10)
            ->get();

        $recent_leads = \App\Models\Lead::latestFirst()->take(8)->get();

        // Chart data — every chart is scoped by the single range filter.
        $metrics = new DashboardMetrics((string) $request->query('range', '90'));

        $charts = [
            'range'       => $metrics->range(),
            'rangeLabel'  => $metrics->rangeLabel(),
            'ranges'      => DashboardMetrics::RANGES,
            'headline'    => $metrics->headline(),
            'overTime'    => $metrics->leadsOverTime(),
            'rating'      => $metrics->ratingSplit(),
            'goal'        => $metrics->goalSplit(),
            'funnel'      => $metrics->funnel(),
            'countries'   => $metrics->topCountries(),
            'channels'    => $metrics->channelMix(),
        ];

        return view('admin.dashboard', compact('stats', 'recent_consultations', 'recent_leads', 'charts'));
    }
}
