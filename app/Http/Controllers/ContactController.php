<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Mail\NewContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contactMessage = ContactMessage::create($validated);

        // Meta Conversions API & Pixel Tracking
        try {
            $eventId = (string) \Illuminate\Support\Str::uuid();
            $nameParts = explode(' ', trim($validated['name']), 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';

            $userData = [
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'first_name' => $firstName,
                'last_name' => $lastName,
            ];

            // Dispatch server-side Conversions API event
            \App\Jobs\SendMetaConversionsEvent::dispatch('Contact', $eventId, $userData);

            // Flash to session for client-side Pixel tracking
            session()->push('fb_events', [
                'name' => 'Contact',
                'id' => $eventId,
                'data' => [],
            ]);
        } catch (\Throwable $e) {
            \Log::error('Meta Pixel/CAPI error in ContactController: ' . $e->getMessage());
        }

        // Send email to admin
        // Note: In production, queue this job
        try {
            Mail::to('contact@reesconsult.com')->send(new NewContactMessage($contactMessage));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send contact email: ' . $e->getMessage());
        }

        return redirect()->route('contact.index')->with('success', 'Your message has been sent successfully! We will get back to you soon.');
    }
}
