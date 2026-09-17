<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = JobApplication::with('service')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->paginate(15);
        return view('admin.job-applications.index', compact('applications'));
    }

    public function show(JobApplication $jobApplication)
    {
        $jobApplication->load('service');
        return view('admin.job-applications.show', compact('jobApplication'));
    }

    public function updateStatus(Request $request, JobApplication $jobApplication)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewing,shortlisted,rejected,placed',
        ]);

        $jobApplication->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $jobApplication->notes,
        ]);

        return back()->with('success', 'Job application status updated to ' . ucfirst($request->status) . '.');
    }

    public function destroy(JobApplication $jobApplication)
    {
        $jobApplication->delete();
        return redirect()->route('admin.job-applications.index')->with('success', 'Job application record deleted.');
    }
}
