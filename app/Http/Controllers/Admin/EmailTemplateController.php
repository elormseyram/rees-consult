<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TemplatedMail;
use App\Models\EmailTemplate;
use App\Services\AutomatedMailer;
use App\Services\MailSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailTemplateController extends Controller
{
    public function index()
    {
        return view('admin.email-templates.index', [
            'templates' => EmailTemplate::orderBy('name')->get(),
            'events'    => AutomatedMailer::EVENTS,
        ]);
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email-templates.edit', [
            'template'     => $emailTemplate,
            'placeholders' => AutomatedMailer::EVENTS[$emailTemplate->key]['placeholders'] ?? [],
        ]);
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'subject'    => 'required|string|max:255',
            'body'       => 'required|string|max:50000',
            'is_enabled' => 'nullable|boolean',
        ]);

        $emailTemplate->update([
            'subject'    => $validated['subject'],
            'body'       => $validated['body'],
            'is_enabled' => $request->boolean('is_enabled'),
        ]);

        return redirect()
            ->route('admin.email-templates.index')
            ->with('success', '"' . $emailTemplate->name . '" saved.');
    }

    /** Toggle a template on/off straight from the list. */
    public function toggle(EmailTemplate $emailTemplate)
    {
        $emailTemplate->update(['is_enabled' => ! $emailTemplate->is_enabled]);

        return back()->with(
            'success',
            '"' . $emailTemplate->name . '" ' . ($emailTemplate->is_enabled ? 'enabled' : 'disabled') . '.'
        );
    }

    /**
     * Send this template to an address using sample data, so the admin can see
     * exactly what a recipient gets before it goes live.
     */
    public function preview(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'preview_email' => 'required|email',
        ]);

        (new MailSettings())->apply();

        $sample = $this->sampleData($emailTemplate->key);

        try {
            Mail::to($validated['preview_email'])->send(new TemplatedMail(
                $emailTemplate->render('subject', $sample),
                $emailTemplate->render('body', $sample),
            ));
        } catch (\Throwable $e) {
            return back()->withErrors(['preview_email' => 'Send failed: ' . $e->getMessage()]);
        }

        return back()->with('success', 'Preview sent to ' . $validated['preview_email'] . '.');
    }

    /** Placeholder values used for previews. */
    private function sampleData(string $key): array
    {
        $base = [
            'first_name' => 'Ada',
            'last_name'  => 'Okoro',
            'full_name'  => 'Ada Okoro',
            'email'      => 'ada.okoro@example.com',
            'phone'      => '+234 800 000 0000',
            'company'    => \App\Models\Setting::get('mail_from_name', "Ree's Consult"),
            'site_url'   => config('app.url'),
            'year'       => date('Y'),
        ];

        return array_merge($base, [
            'goal'             => 'Study Abroad',
            'service'          => 'IELTS Preparation',
            'class_type'       => 'Group (Online)',
            'course'           => 'MSc Computer Science',
            'countries'        => 'Canada, United Kingdom',
            'occupation'       => 'Registered Nurse',
            'experience_years' => '5 years',
            'preferred_date'   => now()->addDays(3)->format('M d, Y \a\t H:i'),
        ]);
    }
}
