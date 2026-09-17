<?php

namespace App\Http\Controllers;

use App\Models\SchoolApplication;
use App\Services\AutomatedMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewServiceApplicationNotification;

class SchoolApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'course_of_interest' => 'required|string|max:255',
            'level_of_education' => 'required|string|max:255',
            'target_countries' => 'required|array',
            'target_countries.*' => 'string|max:255',
            'highest_qualification' => 'required|string|max:255',
            'has_passport' => 'nullable|string', // captured as string checkbox
            'budget' => 'required|string|max:255',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'transcript' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'notes' => 'nullable|string',
        ]);

        // Convert target_countries array to comma-separated string
        $validated['target_countries'] = implode(', ', $request->input('target_countries'));

        // Handle has_passport boolean
        $validated['has_passport'] = $request->has('has_passport');

        // Process File Uploads
        if ($request->hasFile('resume')) {
            $validated['resume_path'] = $request->file('resume')->store('school_applications/resumes', 'public');
        }
        if ($request->hasFile('transcript')) {
            $validated['transcript_path'] = $request->file('transcript')->store('school_applications/transcripts', 'public');
        }

        $app = SchoolApplication::create($validated);

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
                'content_name' => $validated['course_of_interest'] ?? null,
                'content_category' => 'School Application',
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
            logger()->error('Meta Pixel/CAPI error in SchoolApplicationController: ' . $e->getMessage());
        }

        try {
            Mail::to('reesconsult1@gmail.com')->send(new NewServiceApplicationNotification('school', $app));
        } catch (\Exception $e) {
            logger()->error('Failed sending school application notification: ' . $e->getMessage());
        }

        // Confirmation to the applicant.
        app(AutomatedMailer::class)->send('school_application.confirmation', $app->email, [
            'first_name' => $app->first_name,
            'last_name'  => $app->last_name,
            'full_name'  => trim($app->first_name . ' ' . $app->last_name),
            'email'      => $app->email,
            'phone'      => $app->phone,
            'course'     => $app->course_of_interest,
            'countries'  => $app->target_countries,
        ]);

        return back()->with('success', 'Your School Application query has been submitted successfully! An education advisor has been assigned to assess your profile and will email you within 24 hours.');
    }
}
