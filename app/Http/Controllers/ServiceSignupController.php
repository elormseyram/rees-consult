<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceSignup;
use App\Services\AutomatedMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewServiceApplicationNotification;

class ServiceSignupController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'preferred_class_type' => 'required|string|in:group_in_person,group_online,one_on_one_in_person,one_on_one_online',
            'notes' => 'nullable|string',
        ]);

        $signup = ServiceSignup::create($validated);

        $service = Service::find($validated['service_id']);

        // Meta Pixel & CAPI Tracking
        try {
            $eventId = (string) \Illuminate\Support\Str::uuid();
            $userData = [
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
            ];
            $customData = [
                'content_name' => $service->title ?? null,
                'content_category' => 'Service Signup',
            ];

            \App\Jobs\SendMetaConversionsEvent::dispatch('Lead', $eventId, $userData, $customData);

            session()->push('fb_events', [
                'name' => 'Lead',
                'id' => $eventId,
                'data' => $customData,
            ]);
        } catch (\Throwable $e) {
            logger()->error('Meta Pixel/CAPI error in ServiceSignupController: ' . $e->getMessage());
        }

        try {
            Mail::to('reesconsult1@gmail.com')->send(new NewServiceApplicationNotification('signup', $signup));
        } catch (\Exception $e) {
            logger()->error('Failed sending service signup notification: ' . $e->getMessage());
        }

        // Confirmation to the person who signed up.
        app(AutomatedMailer::class)->send('service_signup.confirmation', $signup->email, [
            'first_name' => $signup->first_name,
            'last_name'  => $signup->last_name,
            'full_name'  => trim($signup->first_name . ' ' . $signup->last_name),
            'email'      => $signup->email,
            'phone'      => $signup->phone,
            'service'    => $service->title ?? 'your course',
            'class_type' => ucwords(str_replace('_', ' ', (string) $signup->preferred_class_type)),
        ]);

        return back()->with('success', "Congratulations! You have successfully registered for the {$service->title} prep course. Our tuition coordinators will call you shortly.");
    }
}
