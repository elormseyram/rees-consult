@php
    $rating = $assessment['rating'] ?? 'NEW';
    $ratingColors = ['HOT' => '#16a34a', 'WARM' => '#d68a05', 'COLD' => '#6b7280'];
    $ratingColor = $ratingColors[$rating] ?? '#07294D';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Application Lead</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6; font-family: Arial, Helvetica, sans-serif; color:#1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 6px 24px rgba(0,0,0,0.06);">
                    <!-- Header -->
                    <tr>
                        <td style="background:#07294D; padding:24px 28px;">
                            <h1 style="margin:0; color:#ffffff; font-size:20px;">New Application Lead</h1>
                            <p style="margin:6px 0 0; color:#cbd5e1; font-size:13px;">Submitted via the Apply Now form on reesconsult.com</p>
                        </td>
                    </tr>

                    <!-- Verdict banner -->
                    <tr>
                        <td style="padding:20px 28px 4px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <span style="display:inline-block; background:{{ $ratingColor }}; color:#ffffff; font-weight:bold; font-size:13px; letter-spacing:0.5px; padding:6px 14px; border-radius:999px;">
                                            {{ $rating }} LEAD
                                        </span>
                                        <span style="display:inline-block; margin-left:10px; font-size:13px; color:#374151;">
                                            Score: {{ $assessment['total'] ?? 0 }}/12
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Willing / Able summary -->
                    <tr>
                        <td style="padding:14px 28px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb; border-radius:8px;">
                                <tr>
                                    <td width="50%" style="padding:14px 18px; border-right:1px solid #e5e7eb;">
                                        <div style="font-size:12px; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Willing to buy?</div>
                                        <div style="font-size:18px; font-weight:bold; color:{{ ($assessment['willing'] ?? false) ? '#16a34a' : '#b91c1c' }};">
                                            {{ ($assessment['willing'] ?? false) ? 'YES' : 'NOT YET' }}
                                            <span style="font-size:12px; color:#9ca3af; font-weight:normal;">({{ $assessment['willing_score'] ?? 0 }}/6 intent)</span>
                                        </div>
                                    </td>
                                    <td width="50%" style="padding:14px 18px;">
                                        <div style="font-size:12px; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Able to buy?</div>
                                        <div style="font-size:18px; font-weight:bold; color:{{ ($assessment['able'] ?? false) ? '#16a34a' : '#b91c1c' }};">
                                            {{ ($assessment['able'] ?? false) ? 'YES' : 'UNCLEAR' }}
                                            <span style="font-size:12px; color:#9ca3af; font-weight:normal;">({{ $assessment['able_score'] ?? 0 }}/6 capacity)</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:14px 0 0; padding:12px 16px; background:#f9fafb; border-left:4px solid {{ $ratingColor }}; border-radius:4px; font-size:14px; line-height:1.5; color:#374151;">
                                <strong>Recommended action:</strong> {{ $assessment['verdict'] ?? '' }}
                            </p>
                        </td>
                    </tr>

                    <!-- Full transcript -->
                    <tr>
                        <td style="padding:10px 28px 8px;">
                            <h2 style="margin:0 0 8px; font-size:15px; color:#07294D; border-bottom:2px solid #F8A706; padding-bottom:6px; display:inline-block;">Full Submission Transcript</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 28px 24px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">
                                @foreach ($transcript as $label => $value)
                                    <tr>
                                        <td style="padding:9px 12px; background:{{ $loop->even ? '#ffffff' : '#f9fafb' }}; width:42%; color:#6b7280; vertical-align:top; border-bottom:1px solid #f0f0f0;">
                                            {{ $label }}
                                        </td>
                                        <td style="padding:9px 12px; background:{{ $loop->even ? '#ffffff' : '#f9fafb' }}; color:#111827; font-weight:600; border-bottom:1px solid #f0f0f0;">
                                            {{ $value !== null && $value !== '' ? $value : '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f9fafb; padding:16px 28px; text-align:center; color:#9ca3af; font-size:12px; border-top:1px solid #e5e7eb;">
                            Reply directly to this email to reach {{ trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')) }} at {{ $data['email'] ?? '' }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
