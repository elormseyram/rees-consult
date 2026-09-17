<!DOCTYPE html>
<html>
<head>
    <title>Reply from Rees Consult</title>
</head>
<body>
    <p>Dear {{ $contactMessage->name }},</p>
    
    <div>
        {!! nl2br(e($replyBody)) !!}
    </div>

    <br>
    <p>Best regards,</p>
    <p><strong>Rees Consult Team</strong></p>
    <hr>
    <p><small>Original Message:</small></p>
    <blockquote style="border-left: 2px solid #ccc; padding-left: 10px; color: #666;">
        {{ $contactMessage->message }}
    </blockquote>
</body>
</html>
