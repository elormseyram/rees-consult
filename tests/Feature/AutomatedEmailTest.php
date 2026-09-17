<?php

namespace Tests\Feature;

use App\Mail\TemplatedMail;
use App\Models\EmailTemplate;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Services\AutomatedMailer;
use App\Services\MailSettings;
use Database\Seeders\EmailTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Resend/SMTP delivery settings, admin-editable templates, and the
 * automated emails triggered by public form submissions.
 */
class AutomatedEmailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(EmailTemplateSeeder::class);
    }

    private function admin(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => 'secret123', 'role' => 'admin', 'is_active' => true]
        );
    }

    private function employee(): User
    {
        return User::firstOrCreate(
            ['email' => 'rep@example.com'],
            ['name' => 'Sales Rep', 'password' => 'secret123', 'role' => 'employee', 'is_active' => true]
        );
    }

    // ------------------------------------------------------------ templates

    public function test_every_defined_event_has_a_seeded_template(): void
    {
        foreach (array_keys(AutomatedMailer::EVENTS) as $key) {
            $this->assertDatabaseHas('email_templates', ['key' => $key, 'is_enabled' => true]);
        }
    }

    public function test_seeder_does_not_overwrite_admin_edits(): void
    {
        EmailTemplate::where('key', 'lead.welcome')->update(['subject' => 'My custom subject']);

        $this->seed(EmailTemplateSeeder::class);

        $this->assertSame(
            'My custom subject',
            EmailTemplate::where('key', 'lead.welcome')->first()->subject
        );
    }

    public function test_placeholders_are_replaced_and_unknown_tokens_stripped(): void
    {
        $template = new EmailTemplate([
            'subject' => 'Hi {{ first_name }}',
            'body'    => '<p>{{ first_name }} — {{ nonexistent }} — {{ phone }}</p>',
        ]);

        $this->assertSame('Hi Ada', $template->render('subject', ['first_name' => 'Ada']));

        $body = $template->render('body', ['first_name' => 'Ada', 'phone' => '+234800']);
        $this->assertStringContainsString('Ada', $body);
        $this->assertStringContainsString('+234800', $body);
        $this->assertStringNotContainsString('{{', $body);
        $this->assertStringNotContainsString('nonexistent', $body);
    }

    // --------------------------------------------------------------- mailer

    public function test_lead_welcome_email_is_sent_on_apply_submission(): void
    {
        Mail::fake();

        $this->post(route('apply.store'), $this->applyPayload())->assertSessionHasNoErrors();

        Mail::assertSent(TemplatedMail::class, function (TemplatedMail $mail) {
            return $mail->hasTo('ada@example.com')
                && str_contains($mail->renderedSubject, 'Ada')
                && str_contains($mail->renderedBody, '+2348001112222');
        });
    }

    public function test_no_customer_email_when_automation_is_switched_off(): void
    {
        Mail::fake();
        Setting::put('mail_automation_enabled', '0', MailSettings::GROUP);
        Setting::flushCache();

        $this->post(route('apply.store'), $this->applyPayload());

        Mail::assertNotSent(TemplatedMail::class);
    }

    public function test_no_email_when_the_individual_template_is_disabled(): void
    {
        Mail::fake();
        EmailTemplate::where('key', 'lead.welcome')->update(['is_enabled' => false]);

        $this->post(route('apply.store'), $this->applyPayload());

        Mail::assertNotSent(TemplatedMail::class);
    }

    public function test_mailer_ignores_invalid_recipients_and_unknown_keys(): void
    {
        Mail::fake();
        $mailer = app(AutomatedMailer::class);

        $this->assertFalse($mailer->send('lead.welcome', 'not-an-email', []));
        $this->assertFalse($mailer->send('no.such.template', 'ada@example.com', []));

        Mail::assertNothingSent();
    }

    public function test_a_mail_failure_does_not_break_the_public_form(): void
    {
        // Force the transport to blow up mid-send.
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $response = $this->post(route('apply.store'), $this->applyPayload());

        // The lead is still captured and the visitor still gets a success page.
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('leads', ['email' => 'ada@example.com']);
    }

    public function test_consultation_booking_sends_a_confirmation(): void
    {
        Mail::fake();

        $this->post(route('consultations.store'), [
            'first_name' => 'Ada', 'last_name' => 'Okoro',
            'email' => 'ada@example.com', 'phone' => '+2348001112222',
            'service' => 'Study Abroad Consultation',
            'preferred_date_time' => now()->addWeek()->format('Y-m-d H:i:s'),
        ]);

        Mail::assertSent(TemplatedMail::class, fn (TemplatedMail $m) => $m->hasTo('ada@example.com'));
    }

    public function test_service_signup_sends_a_confirmation(): void
    {
        Mail::fake();

        $service = Service::create([
            'title' => 'IELTS Preparation', 'slug' => 'ielts-prep',
            'category' => 'standardized_test', 'description' => 'Prep classes.',
            'price' => 150.00, 'status' => 'active',
        ]);

        $this->post(route('services.signup'), [
            'service_id' => $service->id,
            'first_name' => 'Ada', 'last_name' => 'Okoro',
            'email' => 'ada@example.com', 'phone' => '+2348001112222',
            'preferred_class_type' => 'group_online',
        ]);

        Mail::assertSent(TemplatedMail::class, function (TemplatedMail $mail) {
            return $mail->hasTo('ada@example.com')
                && str_contains($mail->renderedSubject, 'IELTS Preparation');
        });
    }

    // ------------------------------------------------------------- settings

    public function test_admin_can_save_email_settings(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.settings.email.update'), [
                'mail_driver' => 'resend',
                'resend_api_key' => 're_test_key_123',
                'mail_from_address' => 'hello@reesconsult.com',
                'mail_from_name' => "Ree's Consult",
                'mail_admin_recipients' => 'team@reesconsult.com, sales@reesconsult.com',
                'mail_automation_enabled' => '1',
            ])
            ->assertRedirect();

        Setting::flushCache();
        $this->assertSame('resend', Setting::get('mail_driver'));
        $this->assertSame('re_test_key_123', Setting::get('resend_api_key'));
        $this->assertSame("Ree's Consult", Setting::get('mail_from_name'));
    }

    public function test_blank_api_key_keeps_the_stored_one(): void
    {
        Setting::put('resend_api_key', 're_existing_key', MailSettings::GROUP);
        Setting::flushCache();

        $this->actingAs($this->admin())->put(route('admin.settings.email.update'), [
            'mail_driver' => 'resend',
            'resend_api_key' => '',
            'mail_from_address' => 'hello@reesconsult.com',
            'mail_from_name' => "Ree's Consult",
        ]);

        Setting::flushCache();
        $this->assertSame('re_existing_key', Setting::get('resend_api_key'));
    }

    public function test_resend_without_any_api_key_is_rejected(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.settings.email.update'), [
                'mail_driver' => 'resend',
                'resend_api_key' => '',
                'mail_from_address' => 'hello@reesconsult.com',
                'mail_from_name' => "Ree's Consult",
            ])
            ->assertSessionHasErrors('resend_api_key');
    }

    public function test_settings_are_applied_to_runtime_mail_config(): void
    {
        Setting::put('mail_driver', 'resend', MailSettings::GROUP);
        Setting::put('resend_api_key', 're_runtime_key', MailSettings::GROUP);
        Setting::put('mail_from_address', 'hello@reesconsult.com', MailSettings::GROUP);
        Setting::put('mail_from_name', "Ree's Consult", MailSettings::GROUP);
        Setting::flushCache();

        (new MailSettings())->apply();

        $this->assertSame('resend', config('mail.default'));
        $this->assertSame('re_runtime_key', config('services.resend.key'));
        $this->assertSame('hello@reesconsult.com', config('mail.from.address'));
    }

    public function test_resend_is_not_activated_without_a_key(): void
    {
        config(['mail.default' => 'smtp']);
        Setting::put('mail_driver', 'resend', MailSettings::GROUP);
        Setting::put('resend_api_key', '', MailSettings::GROUP);
        Setting::flushCache();

        (new MailSettings())->apply();

        // Falling back to SMTP beats throwing on every send.
        $this->assertSame('smtp', config('mail.default'));
    }

    public function test_admin_recipients_are_parsed_and_validated(): void
    {
        Setting::put('mail_admin_recipients', 'a@x.com, bad-address , b@x.com, a@x.com', MailSettings::GROUP);
        Setting::flushCache();

        $this->assertSame(['a@x.com', 'b@x.com'], MailSettings::adminRecipients());
    }

    // ---------------------------------------------------------- permissions

    public function test_employees_cannot_reach_email_settings_or_templates(): void
    {
        $this->actingAs($this->employee())->get(route('admin.settings.email'))->assertForbidden();
        $this->actingAs($this->employee())->get(route('admin.email-templates.index'))->assertForbidden();
    }

    public function test_guests_cannot_reach_email_settings(): void
    {
        $this->get(route('admin.settings.email'))->assertRedirect(route('login'));
    }

    public function test_admin_can_edit_a_template(): void
    {
        $template = EmailTemplate::where('key', 'lead.welcome')->first();

        $this->actingAs($this->admin())
            ->put(route('admin.email-templates.update', $template), [
                'subject' => 'Welcome aboard, {{ first_name }}',
                'body' => '<p>Hello {{ first_name }}, we will call {{ phone }}.</p>',
                'is_enabled' => '1',
            ])
            ->assertRedirect(route('admin.email-templates.index'));

        $template->refresh();
        $this->assertSame('Welcome aboard, {{ first_name }}', $template->subject);
        $this->assertTrue($template->is_enabled);
    }

    public function test_admin_can_toggle_a_template_off(): void
    {
        $template = EmailTemplate::where('key', 'lead.welcome')->first();

        $this->actingAs($this->admin())
            ->patch(route('admin.email-templates.toggle', $template))
            ->assertRedirect();

        $this->assertFalse($template->fresh()->is_enabled);
    }

    /**
     * Minimum valid Apply Now submission. `fbclid` marks it as ad traffic,
     * which is the controller's documented bypass for the JS-token and
     * time-trap spam layers that a test client cannot satisfy.
     */
    private function applyPayload(): array
    {
        return [
            'fbclid' => 'IwAR-test-click-id',
            'goal' => 'study_abroad',
            'sex' => 'female', 'age_range' => '25_34',
            'country' => 'Nigeria', 'city' => 'Lagos', 'nationality' => 'Nigerian',
            'timeline' => 'within_1_month', 'budget_status' => 'ready_now',
            'funding_source' => 'self', 'commitment' => 'pay_now',
            'first_name' => 'Ada', 'last_name' => 'Okoro',
            'email' => 'ada@example.com', 'phone' => '+2348001112222',
        ];
    }
}
