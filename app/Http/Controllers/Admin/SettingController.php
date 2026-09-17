<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TemplatedMail;
use App\Models\Setting;
use App\Services\MailSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    /** Email delivery settings screen. */
    public function email()
    {
        return view('admin.settings.email', [
            'settings' => Setting::all_values(),
            'envMailer' => config('mail.default'),
        ]);
    }

    /** Persist the email delivery settings. */
    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'mail_driver'             => 'required|in:smtp,resend,log',
            'resend_api_key'          => 'nullable|string|max:255',
            'mail_from_address'       => 'required|email|max:255',
            'mail_from_name'          => 'required|string|max:255',
            'mail_reply_to'           => 'nullable|email|max:255',
            'mail_admin_recipients'   => 'nullable|string|max:1000',
            'mail_automation_enabled' => 'nullable|boolean',
        ]);

        // An empty API key field means "keep the stored one" — the form never
        // renders the existing secret back to the browser.
        if ($validated['mail_driver'] === 'resend' && empty($validated['resend_api_key'])) {
            $validated['resend_api_key'] = Setting::get('resend_api_key');

            if (empty($validated['resend_api_key'])) {
                return back()
                    ->withInput()
                    ->withErrors(['resend_api_key' => 'A Resend API key is required to use Resend.']);
            }
        }

        if (empty($validated['resend_api_key'])) {
            unset($validated['resend_api_key']);
        }

        $validated['mail_automation_enabled'] = $request->boolean('mail_automation_enabled') ? '1' : '0';

        foreach ($validated as $key => $value) {
            Setting::put($key, $value, MailSettings::GROUP);
        }

        Setting::flushCache();

        return back()->with('success', 'Email settings saved.');
    }

    /**
     * Send a one-off test email using the settings as currently stored.
     */
    public function testEmail(Request $request)
    {
        $validated = $request->validate([
            'test_email' => 'required|email',
        ]);

        // Re-apply so a driver change saved moments ago is honoured here.
        (new MailSettings())->apply();

        try {
            Mail::to($validated['test_email'])->send(new TemplatedMail(
                'Test email from ' . Setting::get('mail_from_name', "Ree's Consult"),
                '<p style="margin:0 0 14px;">This is a test email.</p>'
                . '<p style="margin:0 0 14px;">If you can read this, your <strong>'
                . e(config('mail.default')) . '</strong> delivery settings are working.</p>'
                . '<p style="margin:0;">Sent ' . now()->format('M d, Y \a\t H:i') . '.</p>'
            ));
        } catch (\Throwable $e) {
            return back()->withErrors([
                'test_email' => 'Send failed: ' . $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Test email sent to ' . $validated['test_email'] . ' via ' . config('mail.default') . '.');
    }
}
