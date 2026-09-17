<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Mail\NewsletterWelcome;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('email'),
            ], 422);
        }

        $subscriber = Subscriber::create([
            'email' => $request->email,
            'is_active' => true,
        ]);

        // Send Welcome Email
        try {
            Mail::to($subscriber->email)->send(new NewsletterWelcome($subscriber));
        } catch (\Exception $e) {
            // Log error but don't fail the response
            \Log::error('Newsletter email failed: ' . $e->getMessage());
        }

        // Meta Pixel & CAPI Tracking
        $fbEvents = [];
        try {
            $eventId = (string) \Illuminate\Support\Str::uuid();
            $userData = [
                'email' => $request->email,
            ];

            $customData = [
                'currency' => 'USD',
                'value' => 0.00
            ];

            \App\Jobs\SendMetaConversionsEvent::dispatch('CompleteRegistration', $eventId, $userData, $customData);

            $fbEvents = [
                [
                    'name' => 'CompleteRegistration',
                    'id' => $eventId,
                    'data' => $customData,
                ]
            ];
        } catch (\Throwable $e) {
            \Log::error('Meta Pixel/CAPI error in NewsletterController: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing to our newsletter!',
            'fb_events' => $fbEvents,
        ]);
    }
}
