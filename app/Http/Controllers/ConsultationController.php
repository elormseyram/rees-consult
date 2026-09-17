<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Services\AutomatedMailer;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * Store a new consultation booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'service' => 'required|string',
            'preferred_date_time' => 'nullable|date',
            'message' => 'nullable|string|max:1000',
        ]);

        $consultation = Consultation::create($validated);

        // Meta Conversions API & Pixel Tracking
        try {
            $eventIdSchedule = (string) \Illuminate\Support\Str::uuid();
            $eventIdLead = (string) \Illuminate\Support\Str::uuid();

            $userData = [
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
            ];

            // Dispatch server-side events
            \App\Jobs\SendMetaConversionsEvent::dispatch('Schedule', $eventIdSchedule, $userData);
            \App\Jobs\SendMetaConversionsEvent::dispatch('Lead', $eventIdLead, $userData);

            // Flash to session for client-side Pixel tracking
            session()->push('fb_events', [
                'name' => 'Schedule',
                'id' => $eventIdSchedule,
                'data' => ['content_name' => $validated['service']],
            ]);
            session()->push('fb_events', [
                'name' => 'Lead',
                'id' => $eventIdLead,
                'data' => ['content_name' => $validated['service']],
            ]);
        } catch (\Throwable $e) {
            \Log::error('Meta Pixel/CAPI error in ConsultationController: ' . $e->getMessage());
        }

        // Confirmation to the person who booked.
        app(AutomatedMailer::class)->send('consultation.confirmation', $consultation->email, [
            'first_name'     => $consultation->first_name,
            'last_name'      => $consultation->last_name,
            'full_name'      => trim($consultation->first_name . ' ' . $consultation->last_name),
            'email'          => $consultation->email,
            'phone'          => $consultation->phone,
            'service'        => $consultation->service,
            'preferred_date' => $consultation->preferred_date_time
                ? $consultation->preferred_date_time->format('M d, Y \a\t H:i')
                : 'to be confirmed',
        ]);

        return redirect()->route('home')->with('success', 'Your consultation request has been submitted successfully! We will contact you soon.');
    }
}
