<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatbotLogController extends Controller
{
    /**
     * Display a listing of chatbot logs with analytics and search filters.
     */
    public function index(Request $request): View
    {
        // 1. Compute Overall Analytics & Metrics
        $totalChats = ChatbotLog::count();
        $totalAnswered = ChatbotLog::where('is_answered', true)->count();
        $answeredRate = $totalChats > 0 ? round(($totalAnswered / $totalChats) * 100, 1) : 100;
        
        $aiCount = ChatbotLog::where('response_type', 'ai_gemini')->count();
        $faqCount = ChatbotLog::where('response_type', 'faq_database')->count();
        $smallTalkCount = ChatbotLog::where('response_type', 'small_talk')->count();
        $unansweredCount = ChatbotLog::where('is_answered', false)->count();
        $ticketTrackingCount = ChatbotLog::where('response_type', 'ticket_tracking')->count();

        // 2. Build Query with Dynamic Filters
        $query = ChatbotLog::with('faq')->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('user_query', 'like', "%{$search}%")
                  ->orWhere('bot_reply', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('response_type') && $request->response_type !== 'all') {
            $query->where('response_type', $request->response_type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'answered') {
                $query->where('is_answered', true);
            } elseif ($request->status === 'unanswered') {
                $query->where('is_answered', false);
            }
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $perPage = (int)$request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $logs = $query->paginate($perPage)->withQueryString();

        return view('admin.chatbot_logs.index', compact(
            'logs',
            'perPage',
            'totalChats',
            'answeredRate',
            'aiCount',
            'faqCount',
            'smallTalkCount',
            'unansweredCount',
            'ticketTrackingCount'
        ));
    }

    /**
     * Show single log detail as JSON for quick modal preview.
     */
    public function show(ChatbotLog $chatbotLog): JsonResponse
    {
        $chatbotLog->load('faq');

        return response()->json([
            'id' => $chatbotLog->id,
            'user_query' => $chatbotLog->user_query,
            'bot_reply' => $chatbotLog->bot_reply,
            'response_type' => $chatbotLog->response_type,
            'response_type_label' => $chatbotLog->response_type_label,
            'response_type_badge' => $chatbotLog->response_type_badge,
            'is_answered' => $chatbotLog->is_answered,
            'matched_faq' => $chatbotLog->faq ? [
                'id' => $chatbotLog->faq->id,
                'question' => $chatbotLog->faq->question,
            ] : null,
            'ip_address' => $chatbotLog->ip_address ?? '-',
            'user_agent' => $chatbotLog->user_agent ?? '-',
            'created_at' => $chatbotLog->created_at ? $chatbotLog->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i:s') . ' WIB' : '-',
            'time_ago' => $chatbotLog->created_at ? $chatbotLog->created_at->timezone('Asia/Jakarta')->diffForHumans() : '-',
        ]);
    }

    /**
     * Delete a single log entry.
     */
    public function destroy(ChatbotLog $chatbotLog): RedirectResponse
    {
        $chatbotLog->delete();

        return back()->with('success', 'Riwayat obrolan chatbot berhasil dihapus.');
    }

    /**
     * Clear old logs (e.g. older than 30 days or all logs).
     */
    public function clear(Request $request): RedirectResponse
    {
        $days = (int)$request->input('days', 30);

        if ($days === 0) {
            ChatbotLog::truncate();
            return back()->with('success', 'Seluruh riwayat obrolan chatbot telah dibersihkan.');
        }

        $cutoff = now()->subDays($days);
        $deleted = ChatbotLog::where('created_at', '<', $cutoff)->delete();

        return back()->with('success', "Sebanyak {$deleted} riwayat obrolan yang lebih lama dari {$days} hari berhasil dibersihkan.");
    }

    /**
     * Export chatbot query logs to CSV (Excel compatible with UTF-8 BOM).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = ChatbotLog::with('faq')->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('user_query', 'like', "%{$search}%")
                  ->orWhere('bot_reply', 'like', "%{$search}%");
            });
        }

        if ($request->filled('response_type') && $request->response_type !== 'all') {
            $query->where('response_type', $request->response_type);
        }

        $filename = 'chatbot-logs-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            
            // Write UTF-8 BOM for Microsoft Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Write CSV Header
            fputcsv($handle, [
                'ID',
                'Waktu Percakapan',
                'Pertanyaan Pengunjung',
                'Balasan Chatbot',
                'Tipe Respon',
                'Status Terjawab',
                'FAQ Terkait',
                'Alamat IP',
            ]);

            $query->chunk(200, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->id,
                        $log->created_at ? $log->created_at->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') . ' WIB' : '',
                        $log->user_query,
                        $log->bot_reply,
                        $log->response_type_label,
                        $log->is_answered ? 'Terjawab' : 'Tidak Terjawab',
                        $log->faq ? $log->faq->question : '-',
                        $log->ip_address ?? '-',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
