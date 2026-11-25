<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $replySubject }}</title>
    <style>
        .reply-rich-content p { margin: 0 0 12px 0; }
        .reply-rich-content p:last-child { margin-bottom: 0; }
        .reply-rich-content ul, .reply-rich-content ol { margin: 8px 0 12px 0; padding-left: 22px; }
        .reply-rich-content li { margin-bottom: 4px; }
        .reply-rich-content blockquote { border-left: 3px solid #06b6d4; margin: 12px 0; padding: 6px 12px; color: #475569; background: #f8fafc; }
        .reply-rich-content a { color: #0284c7; text-decoration: underline; }
        .reply-rich-content table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        .reply-rich-content th, .reply-rich-content td { border: 1px solid #cbd5e1; padding: 6px 10px; text-align: left; }
        .reply-rich-content th { background-color: #f1f5f9; font-weight: bold; }
        .reply-rich-content figure.image { margin: 12px 0; text-align: center; }
        .reply-rich-content img { max-width: 100%; height: auto; border-radius: 8px; display: inline-block; margin: 6px 0; }
        .reply-rich-content figcaption { font-size: 12px; color: #64748b; margin-top: 4px; }
    </style>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f1f5f9; color: #1e293b; margin: 0; padding: 24px;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        
        <!-- Header -->
        <tr>
            <td style="background-color: #0f172a; padding: 28px 32px; text-align: left;">
                <h1 style="color: #06b6d4; font-size: 20px; font-weight: 800; margin: 0; text-transform: uppercase; letter-spacing: 1px;">
                    {{ $profile->company_name ?? config('app.name', 'UPA. TIK') }}
                </h1>
                <p style="color: #94a3b8; font-size: 12px; margin: 6px 0 0 0; font-weight: 500;">
                    {{ $profile->tagline ?? 'Layanan Tanggapan & IT Support Ticket' }}
                </p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 32px;">
                <p style="font-size: 15px; margin-top: 0;">Halo <strong>{{ $lead->name }}</strong>,</p>

                @php
                    $hasHtmlReply = $replyMessage !== strip_tags($replyMessage);
                @endphp
                <div class="reply-rich-content" style="font-size: 14px; line-height: 1.7; color: #334155; margin: 20px 0;">
                    @if($hasHtmlReply)
                        {!! $replyMessage !!}
                    @else
                        {!! nl2br(e($replyMessage)) !!}
                    @endif
                </div>

                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 28px 0;">

                <!-- Quoted Original Message -->
                <div style="background-color: #f8fafc; border-left: 4px solid #06b6d4; padding: 16px; border-radius: 4px; font-size: 12px; color: #64748b;">
                    <p style="margin: 0 0 8px 0; font-weight: bold; color: #475569;">Pesan Asal Anda:</p>
                    <p style="margin: 0 0 4px 0;"><strong>Subjek:</strong> {{ $lead->subject ?? '-' }}</p>
                    <p style="margin: 0 0 8px 0;"><strong>Tanggal:</strong> {{ $lead->created_at->format('d M Y, H:i') }} WIB</p>
                    <p style="margin: 0; font-style: italic; white-space: pre-line;">"{{ $lead->message }}"</p>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8fafc; padding: 20px 32px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 12px; color: #94a3b8;">
                <p style="margin: 0;">&copy; {{ date('Y') }} {{ $profile->company_name ?? config('app.name', 'UPA. TIK') }}. All rights reserved.</p>
                <p style="margin: 4px 0 0 0;">Pesan ini dikirim secara otomatis melalui portal Layanan CMS.</p>
            </td>
        </tr>
    </table>
</body>
</html>
