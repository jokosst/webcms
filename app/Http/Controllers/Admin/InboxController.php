<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InboxReplyMail;
use App\Models\CompanyProfile;
use App\Models\InboxLead;
use App\Services\GeminiAiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InboxController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $status = $request->query('status');

        $query = InboxLead::query();

        if ($search) {
            $rawId = preg_replace('/[^0-9]/', '', $search);
            $query->where(function ($q) use ($search, $rawId) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");

                if (!empty($rawId)) {
                    $q->orWhere('id', (int)$rawId);
                }
            });
        }

        if ($status && in_array($status, ['unread', 'read', 'replied'])) {
            $query->where('status', $status);
        }

        $leads = $query->latest()->paginate(10)->withQueryString();
        $unreadCount = InboxLead::where('status', 'unread')->count();

        return view('admin.inbox.index', compact('leads', 'search', 'status', 'unreadCount'));
    }

    public function show(InboxLead $inboxLead): View
    {
        if ($inboxLead->status === 'unread') {
            $inboxLead->update(['status' => 'read']);
        }

        return view('admin.inbox.show', ['lead' => $inboxLead]);
    }

    public function reply(Request $request, InboxLead $inboxLead): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'reply_message' => 'required|string|max:20000',
        ], [
            'subject.required' => 'Subjek email balasan wajib diisi.',
            'reply_message.required' => 'Isi pesan balasan tidak boleh kosong.',
        ]);

        $replyMessage = $this->sanitizeReplyHtml($validated['reply_message']);
        if ($this->htmlToPlainText($replyMessage) === '' && !preg_match('/<img\b/i', $replyMessage)) {
            return redirect()->route('admin.inbox.show', $inboxLead->id)
                ->withErrors(['reply_message' => 'Isi pesan balasan tidak boleh kosong.'])
                ->withInput();
        }

        try {
            $mailerDriver = config('mail.default', env('MAIL_MAILER', 'smtp'));

            if ($mailerDriver === 'gmail_api' || config('services.gmail.refresh_token') || env('GMAIL_REFRESH_TOKEN')) {
                // Send email via Gmail API REST HTTP Endpoint (HTTPS Port 443)
                $gmailApi = new \App\Services\GmailApiService();
                $htmlContent = view('emails.inbox_reply', [
                    'replySubject' => $validated['subject'],
                    'replyMessage' => $replyMessage,
                    'lead' => $inboxLead,
                    'profile' => \App\Models\CompanyProfile::first(),
                ])->render();

                $gmailApi->sendEmail($inboxLead->email, $validated['subject'], $htmlContent);
            } else {
                // Send email via standard SMTP
                Mail::to($inboxLead->email)->send(new InboxReplyMail(
                    $validated['subject'],
                    $replyMessage,
                    $inboxLead
                ));
            }

            $inboxLead->update([
                'status' => 'replied',
                'reply_subject' => $validated['subject'],
                'reply_message' => $replyMessage,
                'replied_at' => now(),
            ]);

            return redirect()->route('admin.inbox.show', $inboxLead->id)
                ->with('success', 'Email balasan berhasil dikirim ke ' . $inboxLead->email . ' dan status berhasil diperbarui.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.inbox.show', $inboxLead->id)
                ->withInput()
                ->with('error', 'Gagal mengirim email balasan: ' . $e->getMessage());
        }
    }

    public function replyGmailWeb(Request $request, InboxLead $inboxLead): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => 'nullable|string|max:255',
            'reply_message' => 'nullable|string|max:20000',
        ]);

        $profile = CompanyProfile::first();
        $companyName = $profile->company_name ?? 'UPA. TIK UNTAN';
        $tagline = $profile->tagline ?? 'Pusat Pengelolaan Sistem Informasi & Jaringan';

        $subject = !empty($validated['subject']) ? $validated['subject'] : ('Re: ' . ($inboxLead->subject ?? 'Tanggapan Pesan Helpdesk'));
        $sanitizedHtml = !empty($validated['reply_message']) ? $this->sanitizeReplyHtml($validated['reply_message']) : '';
        $plainReplyText = $this->htmlToPlainText($sanitizedHtml);

        $defaultGreeting = 'Terima kasih telah menghubungi kami. Mengenai pesan yang Anda sampaikan, berikut tanggapan dari tim kami.';
        $hasContent = $plainReplyText !== '' || preg_match('/<img\b/i', $sanitizedHtml);
        $replyMessageForDb = $hasContent ? $sanitizedHtml : $defaultGreeting;
        $replyMessageForGmail = $hasContent ? $plainReplyText : $defaultGreeting;

        $body = "Halo {$inboxLead->name},\n\n";
        $body .= "{$replyMessageForGmail}\n\n";
        $body .= "==================================================\n";
        $body .= "{$companyName}\n";
        $body .= "{$tagline}\n";
        $body .= "© " . date('Y') . " {$companyName}. All rights reserved.\n\n";
        $body .= "--- Pesan Asal Anda ---\n";
        $body .= "Subjek : " . ($inboxLead->subject ?? '-') . "\n";
        $body .= "Tanggal: " . $inboxLead->created_at->format('d M Y, H:i') . " WIB\n";
        $body .= "\"" . $inboxLead->message . "\"\n";

        // Update lead status and save reply details in database
        $inboxLead->update([
            'status' => 'replied',
            'reply_subject' => $subject,
            'reply_message' => $replyMessageForDb,
            'replied_at' => now(),
        ]);

        // Fetch Gmail user email from env or config
        $gmailUser = config('services.gmail.user_email') ?: env('GMAIL_USER_EMAIL', 'tik@untan.ac.id');
        
        $composeUrl = "https://mail.google.com/mail/?view=cm&fs=1"
            . "&to=" . urlencode($inboxLead->email)
            . "&su=" . urlencode($subject)
            . "&body=" . urlencode($body);

        // Google Account Chooser URL forces Google Accounts to select/switch to configured email
        $accountChooserUrl = "https://accounts.google.com/AccountChooser?Email=" . urlencode($gmailUser)
            . "&continue=" . urlencode($composeUrl);

        return redirect()->away($accountChooserUrl);
    }

    /**
     * Handle CKEditor image upload for inbox email replies.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ], [
            'upload.required' => 'File gambar wajib dipilih.',
            'upload.image' => 'File yang diunggah harus berupa gambar.',
            'upload.mimes' => 'Format gambar yang didukung: JPEG, PNG, JPG, GIF, atau WebP.',
            'upload.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $path = $request->file('upload')->store('inbox-replies', 'public');

        return response()->json([
            'url' => asset('storage/' . $path),
        ]);
    }

    /**
     * Generate or reformat an alternative inbox reply draft using Gemini AI.
     */
    public function aiDraft(Request $request, InboxLead $inboxLead, GeminiAiService $geminiAiService): JsonResponse
    {
        $validated = $request->validate([
            'mode' => 'required|in:auto_draft,polish,steps,shorten',
            'tone' => 'nullable|in:formal,friendly,concise',
            'instruction' => 'nullable|string|max:2000',
            'current_html' => 'nullable|string|max:20000',
        ]);

        $mode = $validated['mode'];
        $instruction = trim((string) ($validated['instruction'] ?? ''));
        $currentHtml = (string) ($validated['current_html'] ?? '');

        if (in_array($mode, ['polish', 'steps', 'shorten'], true)
            && $this->htmlToPlainText($currentHtml) === ''
            && $instruction === ''
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Tuliskan dulu isi pesan di editor atau catatan singkat di kolom arahan sebelum meminta AI merapikan format.',
            ], 422);
        }

        try {
            $result = $geminiAiService->draftInboxReply($inboxLead, [
                'mode' => $mode,
                'tone' => $validated['tone'] ?? 'formal',
                'instruction' => $instruction,
                'current_html' => $currentHtml,
            ]);

            $sanitizedDraft = $this->sanitizeReplyHtml($result['draft_html']);
            if ($this->htmlToPlainText($sanitizedDraft) === '' && !preg_match('/<img\b/i', $sanitizedDraft)) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI tidak menghasilkan draf teks yang valid. Silakan coba lagi.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'draft_html' => $sanitizedDraft,
                'model' => $result['model'],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Sanitize rich-text HTML from CKEditor for safe storage and HTML email delivery.
     */
    private function sanitizeReplyHtml(string $html): string
    {
        $withoutBlocks = preg_replace('/<(script|style)\b[^>]*>[\s\S]*?<\/\1>/i', '', $html) ?? $html;

        $allowedTags = '<p><br><strong><b><em><i><u><s><ul><ol><li><blockquote><a><h1><h2><h3><h4><figure><figcaption><img><table><thead><tbody><tr><th><td><hr>';
        $clean = strip_tags($withoutBlocks, $allowedTags);

        // Strip inline JS event attributes and unsafe URI schemes
        $clean = preg_replace('/\s+on[a-z]+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $clean) ?? $clean;
        $clean = preg_replace('/(href|src)\s*=\s*(["\'])\s*(?:javascript|vbscript|data):.*?\2/i', '$1=$2#$2', $clean) ?? $clean;

        return trim($clean);
    }

    /**
     * Convert CKEditor HTML to clean readable plain text (for Gmail Web compose URL & empty checks).
     */
    private function htmlToPlainText(string $html): string
    {
        if ($html === '') {
            return '';
        }

        $text = preg_replace('/<img\b[^>]*\bsrc\s*=\s*(["\'])([^"\']+)\1[^>]*>/i', "\n[Gambar: $2]\n", $html) ?? $html;
        $text = preg_replace('/<\s*br\s*\/?>/i', "\n", $text) ?? $text;
        $text = preg_replace('/<\/(p|div|h[1-6]|blockquote|tr|figure)>/i', "\n\n", $text) ?? $text;
        $text = preg_replace('/<\s*li[^>]*>/i', '• ', $text) ?? $text;
        $text = preg_replace('/<\/li>/i', "\n", $text) ?? $text;
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace("\u{00A0}", ' ', $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? $text;

        return trim($text);
    }

    public function updateStatus(Request $request, InboxLead $inboxLead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:unread,read,replied',
        ]);

        $inboxLead->update($validated);

        return back()->with('success', 'Status pesan berhasil diperbarui.');
    }

    public function destroy(InboxLead $inboxLead): RedirectResponse
    {
        $inboxLead->delete();

        return redirect()->route('admin.inbox.index')->with('success', 'Pesan berhasil dihapus.');
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $search = $request->query('q');
        $status = $request->query('status');

        $query = InboxLead::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['unread', 'read', 'replied'])) {
            $query->where('status', $status);
        }

        $leads = $query->latest()->get();
        $filename = 'inbox_pesan_masuk_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($leads) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Microsoft Excel compatibility
            fprintf($file, "\xEF\xBB\xBF");

            // Header row
            fputcsv($file, [
                'No',
                'Nama Pengirim',
                'Email',
                'No. Telepon / WA',
                'Kategori / Subjek',
                'Rincian Pesan',
                'Status',
                'Tanggal Masuk',
                'IP Address'
            ]);

            foreach ($leads as $index => $lead) {
                $statusLabel = match ($lead->status) {
                    'unread' => 'Belum Dibaca (Unread)',
                    'read' => 'Sudah Dibaca (Read)',
                    'replied' => 'Sudah Dibalas (Replied)',
                    default => $lead->status,
                };

                fputcsv($file, [
                    $index + 1,
                    $lead->name,
                    $lead->email,
                    $lead->phone ?? '-',
                    $lead->subject ?? '-',
                    str_replace(["\r", "\n"], " ", $lead->message),
                    $statusLabel,
                    $lead->created_at->format('d/m/Y H:i:s'),
                    $lead->ip_address ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request): View
    {
        $search = $request->query('q');
        $status = $request->query('status');

        $query = InboxLead::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['unread', 'read', 'replied'])) {
            $query->where('status', $status);
        }

        $leads = $query->latest()->get();
        $profile = CompanyProfile::first();

        return view('admin.inbox.pdf', compact('leads', 'search', 'status', 'profile'));
    }
}
