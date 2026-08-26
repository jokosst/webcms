<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InboxReplyMail;
use App\Models\CompanyProfile;
use App\Models\InboxLead;
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
            'reply_message' => 'required|string|max:5000',
        ], [
            'subject.required' => 'Subjek email balasan wajib diisi.',
            'reply_message.required' => 'Isi pesan balasan tidak boleh kosong.',
        ]);

        try {
            $mailerDriver = config('mail.default', env('MAIL_MAILER', 'smtp'));

            if ($mailerDriver === 'gmail_api' || config('services.gmail.refresh_token') || env('GMAIL_REFRESH_TOKEN')) {
                // Send email via Gmail API REST HTTP Endpoint (HTTPS Port 443)
                $gmailApi = new \App\Services\GmailApiService();
                $htmlContent = view('emails.inbox_reply', [
                    'replySubject' => $validated['subject'],
                    'replyMessage' => $validated['reply_message'],
                    'lead' => $inboxLead,
                    'profile' => \App\Models\CompanyProfile::first(),
                ])->render();

                $gmailApi->sendEmail($inboxLead->email, $validated['subject'], $htmlContent);
            } else {
                // Send email via standard SMTP
                Mail::to($inboxLead->email)->send(new InboxReplyMail(
                    $validated['subject'],
                    $validated['reply_message'],
                    $inboxLead
                ));
            }

            $inboxLead->update([
                'status' => 'replied',
                'reply_subject' => $validated['subject'],
                'reply_message' => $validated['reply_message'],
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
            'reply_message' => 'nullable|string|max:5000',
        ]);

        $profile = CompanyProfile::first();
        $companyName = $profile->company_name ?? 'UPA. TIK UNTAN';
        $tagline = $profile->tagline ?? 'Pusat Pengelolaan Sistem Informasi & Jaringan';

        $subject = !empty($validated['subject']) ? $validated['subject'] : ('Re: ' . ($inboxLead->subject ?? 'Tanggapan Pesan Helpdesk'));
        $replyMessage = !empty($validated['reply_message']) ? $validated['reply_message'] : 'Terima kasih telah menghubungi kami. Mengenai pesan yang Anda sampaikan, berikut tanggapan dari tim kami.';

        $body = "Halo {$inboxLead->name},\n\n";
        $body .= "{$replyMessage}\n\n";
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
            'reply_message' => $replyMessage,
            'replied_at' => now(),
        ]);

        // Fetch Gmail user email from env or config
        $gmailUser = config('services.gmail.user_email') ?: env('GMAIL_USER_EMAIL', 'tik@untan.ac.id');
        
        $composeUrl = "https://mail.google.com/mail/?view=cm&fs=1"
            . "&to=" . urlencode($inboxLead->email)
            . "&su=" . urlencode($subject)
            . "&body=" . urlencode($body);

        // Google Account Chooser URL forces Google Accounts to select/switch to tik@untan.ac.id
        $accountChooserUrl = "https://accounts.google.com/AccountChooser?Email=" . urlencode($gmailUser)
            . "&continue=" . urlencode($composeUrl);

        return redirect()->away($accountChooserUrl);
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
