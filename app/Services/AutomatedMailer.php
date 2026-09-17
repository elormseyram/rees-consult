<?php

namespace App\Services;

use App\Mail\TemplatedMail;
use App\Models\EmailTemplate;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Sends the admin-authored automated emails.
 *
 * Sends are inline (not queued) and every failure is swallowed + logged: a
 * mail outage must never break a public form submission.
 */
class AutomatedMailer
{
    /**
     * Catalogue of automated emails. Drives both the seeder and the
     * placeholder help shown on the template edit screen.
     */
    public const EVENTS = [
        'lead.welcome' => [
            'name'        => 'New Lead — Welcome',
            'description' => 'Sent to anyone who completes the Apply Now qualification form.',
            'placeholders' => ['first_name', 'last_name', 'full_name', 'email', 'phone', 'goal', 'company', 'site_url', 'year'],
        ],
        'service_signup.confirmation' => [
            'name'        => 'Course Signup — Confirmation',
            'description' => 'Sent when someone registers for a prep course.',
            'placeholders' => ['first_name', 'last_name', 'full_name', 'email', 'phone', 'service', 'class_type', 'company', 'site_url', 'year'],
        ],
        'school_application.confirmation' => [
            'name'        => 'Study Abroad Application — Confirmation',
            'description' => 'Sent when someone submits a school/study abroad application.',
            'placeholders' => ['first_name', 'last_name', 'full_name', 'email', 'phone', 'course', 'countries', 'company', 'site_url', 'year'],
        ],
        'job_application.confirmation' => [
            'name'        => 'Work Abroad Application — Confirmation',
            'description' => 'Sent when someone submits a work abroad application.',
            'placeholders' => ['first_name', 'last_name', 'full_name', 'email', 'phone', 'occupation', 'experience_years', 'company', 'site_url', 'year'],
        ],
        'consultation.confirmation' => [
            'name'        => 'Consultation Booking — Confirmation',
            'description' => 'Sent when someone books a consultation.',
            'placeholders' => ['first_name', 'last_name', 'full_name', 'email', 'phone', 'service', 'preferred_date', 'company', 'site_url', 'year'],
        ],
    ];

    /**
     * Render and send the template registered for $key.
     *
     * @return bool Whether the email was actually handed to the transport.
     */
    public function send(string $key, string $recipient, array $data = []): bool
    {
        if (! MailSettings::automationEnabled()) {
            return false;
        }

        if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        try {
            $template = EmailTemplate::where('key', $key)->first();

            if (! $template || ! $template->is_enabled) {
                return false;
            }

            $payload = array_merge($this->globals(), $data);

            Mail::to($recipient)->send(new TemplatedMail(
                $template->render('subject', $payload),
                $template->render('body', $payload),
            ));

            return true;
        } catch (\Throwable $e) {
            Log::error("Automated email [{$key}] to {$recipient} failed: " . $e->getMessage());

            return false;
        }
    }

    /** Placeholders available to every template. */
    private function globals(): array
    {
        return [
            'company'  => Setting::get('mail_from_name', "Ree's Consult"),
            'site_url' => config('app.url'),
            'year'     => date('Y'),
        ];
    }
}
