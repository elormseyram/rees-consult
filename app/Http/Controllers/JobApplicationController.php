<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\JobApplication;
use App\Services\AutomatedMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewServiceApplicationNotification;

class JobApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'current_occupation' => 'required|string|max:255',
            'highest_education' => 'required|string|max:255',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'notes' => 'nullable|string',
        ]);

        // Process File Uploads
        if ($request->hasFile('resume')) {
            $validated['resume_path'] = $request->file('resume')->store('job_applications/resumes', 'public');
        }

        $application = JobApplication::create($validated);

        $service = Service::find($validated['service_id']);

        // Meta Pixel & CAPI Tracking
        try {
            $eventIdSubmit = (string) \Illuminate\Support\Str::uuid();
            $eventIdLead = (string) \Illuminate\Support\Str::uuid();

            $userData = [
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
            ];

            $customData = [
                'content_name' => $service->title ?? null,
                'content_category' => 'Job Application',
            ];

            \App\Jobs\SendMetaConversionsEvent::dispatch('SubmitApplication', $eventIdSubmit, $userData, $customData);
            \App\Jobs\SendMetaConversionsEvent::dispatch('Lead', $eventIdLead, $userData, $customData);

            session()->push('fb_events', [
                'name' => 'SubmitApplication',
                'id' => $eventIdSubmit,
                'data' => $customData,
            ]);
            session()->push('fb_events', [
                'name' => 'Lead',
                'id' => $eventIdLead,
                'data' => $customData,
            ]);
        } catch (\Throwable $e) {
            logger()->error('Meta Pixel/CAPI error in JobApplicationController: ' . $e->getMessage());
        }

        try {
            Mail::to('reesconsult1@gmail.com')->send(new NewServiceApplicationNotification('job', $application));
        } catch (\Exception $e) {
            logger()->error('Failed sending job application notification: ' . $e->getMessage());
        }

        // Confirmation to the applicant.
        app(AutomatedMailer::class)->send('job_application.confirmation', $application->email, [
            'first_name'       => $application->first_name,
            'last_name'        => $application->last_name,
            'full_name'        => trim($application->first_name . ' ' . $application->last_name),
            'email'            => $application->email,
            'phone'            => $application->phone,
            'occupation'       => $application->current_occupation ?: 'your field',
            'experience_years' => $application->experience_years,
        ]);

        return back()->with('success', "Application received! You have successfully applied for the {$service->title} slot in {$service->country}. A recruitment officer will review your experience and schedule an intake interview.");
    }
}
