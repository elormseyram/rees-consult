<?php

namespace App\Services;

use App\Models\Setting;

/**
 * Applies the mail credentials the admin entered in Settings on top of the
 * .env defaults, so switching to Resend never needs a file edit or a deploy.
 */
class MailSettings
{
    public const GROUP = 'email';

    /** Settings keys owned by the email settings screen. */
    public const KEYS = [
        'mail_driver',        // smtp | resend | log
        'resend_api_key',
        'mail_from_address',
        'mail_from_name',
        'mail_reply_to',
        'mail_admin_recipients', // comma-separated internal notification inbox list
        'mail_automation_enabled',
    ];

    /**
     * Push the stored settings into the runtime config. Called from a service
     * provider on every request, so it must stay cheap and never throw when
     * the settings table is missing (e.g. during the first migrate).
     */
    public function apply(): void
    {
        try {
            $values = Setting::all_values();
        } catch (\Throwable $e) {
            return; // table not migrated yet — fall back to .env
        }

        $driver = $values['mail_driver'] ?? null;

        if ($driver === 'resend') {
            $key = $values['resend_api_key'] ?? null;

            // Without a key the Resend transport would throw on every send;
            // stay on the configured .env mailer instead.
            if (! empty($key)) {
                config([
                    'services.resend.key' => $key,
                    'mail.default'        => 'resend',
                ]);
            }
        } elseif (in_array($driver, ['smtp', 'log'], true)) {
            config(['mail.default' => $driver]);
        }

        if (! empty($values['mail_from_address'])) {
            config(['mail.from.address' => $values['mail_from_address']]);
        }
        if (! empty($values['mail_from_name'])) {
            config(['mail.from.name' => $values['mail_from_name']]);
        }
    }

    /** Where internal "new lead" notifications go. */
    public static function adminRecipients(): array
    {
        $raw = Setting::get('mail_admin_recipients', config('mail.from.address'));

        return collect(explode(',', (string) $raw))
            ->map(fn ($email) => trim($email))
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();
    }

    /** Master switch for all automated customer-facing email. */
    public static function automationEnabled(): bool
    {
        return (bool) Setting::get('mail_automation_enabled', '1');
    }

    public static function replyTo(): ?string
    {
        $email = Setting::get('mail_reply_to');

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }
}
