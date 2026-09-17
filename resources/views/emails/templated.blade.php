<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $renderedSubject }}</title>
</head>
<body style="margin:0; padding:0; background:#f4f5f7; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif; color:#22262b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f5f7; padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background:#0b2545; padding:22px 28px;">
                            <div style="color:#ffffff; font-size:19px; font-weight:700; letter-spacing:.2px;">
                                {{ \App\Models\Setting::get('mail_from_name', "Ree's Consult") }}
                            </div>
                            <div style="color:#F8A706; font-size:12px; margin-top:3px;">
                                Test Prep · Study Abroad · Work Abroad
                            </div>
                        </td>
                    </tr>

                    <!-- Body (admin-authored HTML) -->
                    <tr>
                        <td style="padding:28px; font-size:15px; line-height:1.65; color:#33383f;">
                            {!! $body !!}
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:18px 28px 24px; border-top:1px solid #eceef1; font-size:12px; line-height:1.6; color:#8a9099;">
                            &copy; {{ date('Y') }} {{ \App\Models\Setting::get('mail_from_name', "Ree's Consult") }}.<br>
                            <a href="{{ config('app.url') }}" style="color:#0b6fb8; text-decoration:none;">{{ parse_url(config('app.url'), PHP_URL_HOST) ?: config('app.url') }}</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
