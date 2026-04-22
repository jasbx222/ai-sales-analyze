<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectText }}</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f5f7fb;">
    <div style="max-width:600px;margin:30px auto;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">
        
        <div style="background:#0F5BFF;padding:20px;text-align:center;color:#fff;">
            <h1 style="margin:0;font-size:24px;">{{ $title }}</h1>
        </div>

        <div style="padding:30px;color:#111827;line-height:1.9;font-size:16px;">
            <p style="margin-top:0;">
                {!! nl2br(e($messageText)) !!}
            </p>

            @if($buttonText && $buttonUrl)
                <div style="text-align:center;margin:30px 0;">
                    <a href="{{ $buttonUrl }}"
                       style="display:inline-block;background:#0F5BFF;color:#fff;text-decoration:none;padding:14px 24px;border-radius:8px;font-size:15px;">
                        {{ $buttonText }}
                    </a>
                </div>
            @endif

            <p style="margin-bottom:0;color:#6b7280;font-size:14px;">
                شكراً لك
            </p>
        </div>
    </div>
</body>
</html>