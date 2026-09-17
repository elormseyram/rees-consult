<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use App\Services\AutomatedMailer;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Install the default copy for every automated email. Existing rows are
     * left untouched so re-seeding never overwrites the admin's edits.
     */
    public function run(): void
    {
        foreach ($this->defaults() as $key => $default) {
            $meta = AutomatedMailer::EVENTS[$key];

            EmailTemplate::firstOrCreate(
                ['key' => $key],
                [
                    'name'        => $meta['name'],
                    'description' => $meta['description'],
                    'subject'     => $default['subject'],
                    'body'        => $default['body'],
                    'is_enabled'  => true,
                ]
            );
        }
    }

    private function defaults(): array
    {
        return [
            'lead.welcome' => [
                'subject' => 'Welcome to {{ company }}, {{ first_name }} — we’ll call you shortly',
                'body'    => $this->wrap(
                    'Thank you for reaching out to <strong>{{ company }}</strong>. We’ve received your application and one of our consultants will call you on <strong>{{ phone }}</strong> shortly to talk through your goals.',
                    [
                        'Here’s what happens next:',
                        '<ul style="padding-left:18px; margin:10px 0;">'
                            . '<li>A consultant reviews the details you shared.</li>'
                            . '<li>We call you to confirm your goals and answer your questions.</li>'
                            . '<li>You get a clear, personalised plan and pricing.</li>'
                            . '</ul>',
                        'If you’d like to reach us before then, just reply to this email.',
                    ]
                ),
            ],

            'service_signup.confirmation' => [
                'subject' => 'You’re registered for {{ service }}',
                'body'    => $this->wrap(
                    'Thanks for registering for <strong>{{ service }}</strong>. Your preferred format is <strong>{{ class_type }}</strong>.',
                    [
                        'Our tuition coordinators will call you on <strong>{{ phone }}</strong> shortly to confirm your class schedule and get you started.',
                        'If anything changes in the meantime, simply reply to this email.',
                    ]
                ),
            ],

            'school_application.confirmation' => [
                'subject' => 'We’ve received your study abroad application',
                'body'    => $this->wrap(
                    'Thanks for submitting your study abroad application for <strong>{{ course }}</strong>.',
                    [
                        'Target destinations: <strong>{{ countries }}</strong>',
                        'Our admissions team is reviewing your details and will call you on <strong>{{ phone }}</strong> to discuss suitable schools, requirements and timelines.',
                    ]
                ),
            ],

            'job_application.confirmation' => [
                'subject' => 'We’ve received your work abroad application',
                'body'    => $this->wrap(
                    'Thanks for submitting your work abroad application.',
                    [
                        'We’ve logged your background as <strong>{{ occupation }}</strong> with <strong>{{ experience_years }}</strong> of experience.',
                        'One of our placement consultants will call you on <strong>{{ phone }}</strong> to discuss the opportunities that match your profile.',
                    ]
                ),
            ],

            'consultation.confirmation' => [
                'subject' => 'Your consultation booking is confirmed',
                'body'    => $this->wrap(
                    'Thanks for booking a consultation with <strong>{{ company }}</strong>.',
                    [
                        'Service: <strong>{{ service }}</strong><br>Preferred time: <strong>{{ preferred_date }}</strong>',
                        'We’ll confirm the final time by phone on <strong>{{ phone }}</strong>. Please have any relevant documents handy for the call.',
                    ]
                ),
            ],
        ];
    }

    /** Shared greeting/sign-off scaffold around the body paragraphs. */
    private function wrap(string $lead, array $paragraphs): string
    {
        $html = '<p style="margin:0 0 14px;">Hi {{ first_name }},</p>';
        $html .= '<p style="margin:0 0 14px;">' . $lead . '</p>';

        foreach ($paragraphs as $paragraph) {
            $html .= '<p style="margin:0 0 14px;">' . $paragraph . '</p>';
        }

        return $html . '<p style="margin:18px 0 0;">Warm regards,<br><strong>The {{ company }} Team</strong></p>';
    }
}
