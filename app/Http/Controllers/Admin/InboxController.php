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
            Mail::to($inboxLead->email)->send(new InboxReplyMail(
                $validated['subject'],
                $validated['reply_message'],
                $inboxLead
            ));

            $inboxLead->update(['status' => 'replied']);

            return redirect()->route('admin.inbox.show', $inboxLead->id)
                ->with('success', 'Email balasan berhasil dikirim ke ' . $inboxLead->email . ' dan status berhasil diperbarui menjadi Replied.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.inbox.show', $inboxLead->id)
                ->withInput()
                ->with('error', 'Gagal mengirim email balasan: ' . $e->getMessage() . '. Harap periksa pengaturan SMTP di file .env.');
        }
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
