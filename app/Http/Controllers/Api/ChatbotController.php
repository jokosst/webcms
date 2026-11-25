<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatbotLog;
use App\Models\Faq;
use App\Models\InboxLead;
use App\Models\SiteSetting;
use App\Services\GeminiAiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Get initial chatbot configuration and quick topic chips.
     */
    public function init(): JsonResponse
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        $enabled = ($settings['chatbot_enabled'] ?? '1') === '1';

        // Get top 4 active FAQs as quick question chips
        $quickFaqs = Faq::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->take(5)
            ->get(['id', 'question']);

        return response()->json([
            'enabled' => $enabled,
            'bot_name' => $settings['chatbot_bot_name'] ?? 'Asisten IT UPA. TIK',
            'bot_tagline' => $settings['chatbot_bot_tagline'] ?? 'Layanan Bantuan IT Cepat & Tiket Support',
            'welcome_message' => $settings['chatbot_welcome_message'] ?? 'Halo Kak! Selamat datang di Pusat Bantuan UPA. TIK. Ada kendala seputar layanan TIK kampus yang bisa saya bantu hari ini? Silakan klik topik di bawah atau ketik pertanyaan langsung ya.',
            'quick_questions' => $quickFaqs,
        ]);
    }

    /**
     * Handle incoming user message, search FAQ knowledge base, and return conversational reply.
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        if (($settings['chatbot_enabled'] ?? '1') !== '1') {
            return response()->json([
                'success' => false,
                'message' => 'Layanan chatbot saat ini sedang dinonaktifkan oleh administrator.',
            ], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string|min:2|max:300',
        ]);

        $userQuery = trim($validated['message']);
        $normalizedQuery = $this->normalizeText($userQuery);

        // 1. Check if user is asking to track a support ticket (e.g. "TKT-00005", "cek tiket TKT-00001", "status tiket 5")
        if (preg_match('/(?:TKT|tkt)[\s\-#]*([0-9]+)/i', $userQuery, $matches) || 
            (preg_match('/(?:cek|lacak|status|nomor|tiket)[\s\w]*?(\d{1,6})/i', $userQuery, $matches) && (str_contains(strtolower($userQuery), 'tiket') || str_contains(strtolower($userQuery), 'status')))) {
            
            $ticketNumericId = (int)$matches[1];
            $ticket = InboxLead::find($ticketNumericId);

            if ($ticket) {
                $paddedId = 'TKT-' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT);
                $statusLabel = match($ticket->status) {
                    'replied' => '🟢 **Selesai / Sudah Dibalas**',
                    'read', 'in_progress' => '🔵 **Sedang Diproses oleh Tim Teknis**',
                    'closed' => '⚪ **Ditutup**',
                    default => '🟡 **Menunggu Penanganan Petugas**',
                };
                
                $createdAt = $ticket->created_at ? $ticket->created_at->translatedFormat('d F Y, H:i') . ' WIB' : '-';
                $cleanSubject = str_replace('[Helpdesk Bot] ', '', $ticket->subject);
                $maskedName = Str::mask($ticket->name, '*', 2, -2);

                $replyText = "🎫 **INFORMASI STATUS TIKET SUPPORT**\n"
                    . "━━━━━━━━━━━━━━━━━━━━━━\n"
                    . "📌 **Nomor Tiket** : `#{$paddedId}`\n"
                    . "👤 **Pemohon** : `{$maskedName}`\n"
                    . "📋 **Judul Kendala** : {$cleanSubject}\n"
                    . "📅 **Tgl Pengajuan** : {$createdAt}\n"
                    . "📊 **Status Saat Ini** : {$statusLabel}\n"
                    . "━━━━━━━━━━━━━━━━━━━━━━";

                if ($ticket->status === 'replied' && !empty($ticket->reply_message)) {
                    $repliedAt = $ticket->replied_at ? \Carbon\Carbon::parse($ticket->replied_at)->translatedFormat('d F Y, H:i') . ' WIB' : '-';
                    $cleanReply = strip_tags($ticket->reply_message);
                    $replyText .= "\n\n💬 **Balasan Petugas Helpdesk:**\n\"{$cleanReply}\"\n\n⏱️ *Dibalas pada: {$repliedAt}*\n📧 *Salinan balasan resmi juga telah dikirimkan ke email pemohon.*";
                } elseif ($ticket->status === 'read' || $ticket->status === 'in_progress') {
                    $replyText .= "\n\nℹ️ *Tiket Kakak telah diterima dan saat ini sedang ditindaklanjuti oleh staf IT UPA. TIK. Kami akan mengabari via email segera setelah penanganan selesai.*";
                } else {
                    $replyText .= "\n\nℹ️ *Tiket Kakak telah masuk ke antrean Helpdesk kami dan sedang menunggu giliran penanganan oleh petugas piket.*";
                }

                $this->recordLog($request, $userQuery, $replyText, 'ticket_tracking', true);

                return response()->json([
                    'success' => true,
                    'found' => true,
                    'is_ticket_tracking' => true,
                    'ticket_id' => $paddedId,
                    'bot_reply' => $replyText,
                    'offer_ticket' => false,
                ]);
            } else {
                $paddedId = 'TKT-' . str_pad($ticketNumericId, 5, '0', STR_PAD_LEFT);
                $notFoundReply = "🔍 **Hasil Pelacakan Tiket:**\n\nMohon maaf Kak, Nomor Tiket **#{$paddedId}** tidak ditemukan di database Helpdesk kami.\n\nSilakan periksa kembali nomor tiket yang tertera pada saat pengiriman tiket Kakak.";
                
                $this->recordLog($request, $userQuery, $notFoundReply, 'ticket_tracking', false);

                return response()->json([
                    'success' => true,
                    'found' => true,
                    'is_ticket_tracking' => true,
                    'bot_reply' => $notFoundReply,
                    'offer_ticket' => true,
                ]);
            }
        }

        // 2. Check for Greetings, Politeness, and Small-Talk Conversational Intents
        $botName = $settings['chatbot_bot_name'] ?? 'Asisten IT UPA. TIK';
        $smallTalkReply = $this->handleSmallTalk($normalizedQuery, $botName);
        if ($smallTalkReply !== null) {
            $this->recordLog($request, $userQuery, $smallTalkReply, 'small_talk', true);

            return response()->json([
                'success' => true,
                'found' => true,
                'is_small_talk' => true,
                'bot_reply' => $smallTalkReply,
                'offer_ticket' => false,
            ]);
        }

        // 3. Smart Generative AI (Google Gemini) with Grounded Knowledge Base
        $aiEnabled = ($settings['chatbot_ai_enabled'] ?? '0') === '1';
        $geminiApiKey = $settings['chatbot_gemini_api_key'] ?? '';
        if ($aiEnabled && !empty($geminiApiKey)) {
            $aiReply = $this->generateAiResponse($userQuery, $botName, $settings->toArray());
            if ($aiReply !== null) {
                $needsTicket = str_contains(strtolower($aiReply), 'tiket') && (str_contains(strtolower($aiReply), 'buat') || str_contains(strtolower($aiReply), 'ajukan'));
                $this->recordLog($request, $userQuery, $aiReply, 'ai_gemini', true);

                return response()->json([
                    'success' => true,
                    'found' => true,
                    'is_ai_generated' => true,
                    'bot_reply' => $aiReply,
                    'offer_ticket' => $needsTicket,
                ]);
            }
        }

        // 4. Fallback / Non-AI: Semantic FAQ Knowledge Base Searching
        $allFaqs = Faq::all();

        if ($allFaqs->isEmpty()) {
            $fallbackMsg = $settings['chatbot_fallback_message'] ?? 'Mohon maaf, data basis pengetahuan kami masih dalam pembaharuan. Silakan klik tombol "Buat Tiket Support" di bawah ini agar tim Helpdesk kami dapat segera membantu.';
            $this->recordLog($request, $userQuery, $fallbackMsg, 'fallback_unanswered', false);

            return response()->json([
                'success' => true,
                'found' => false,
                'bot_reply' => $fallbackMsg,
                'offer_ticket' => true,
            ]);
        }

        // Calculate relevance scores for all FAQs
        $scoredFaqs = [];
        foreach ($allFaqs as $faq) {
            $score = $this->calculateRelevanceScore($normalizedQuery, $faq);
            if ($score > 25) {
                $scoredFaqs[] = [
                    'faq' => $faq,
                    'score' => $score,
                ];
            }
        }

        // Sort by score descending
        usort($scoredFaqs, fn($a, $b) => $b['score'] <=> $a['score']);

        if (!empty($scoredFaqs)) {
            $bestMatch = $scoredFaqs[0]['faq'];

            // Prepare related questions
            $related = [];
            for ($i = 1; $i < min(count($scoredFaqs), 3); $i++) {
                $related[] = [
                    'id' => $scoredFaqs[$i]['faq']->id,
                    'question' => $scoredFaqs[$i]['faq']->question,
                ];
            }

            // Conversational formatting
            $greetings = [
                'Terkait pertanyaan Kakak mengenai',
                'Baik, berikut informasi panduan seputar',
                'Berikut solusi praktis terkait kendala',
            ];
            $selectedGreeting = $greetings[array_rand($greetings)];

            $formattedReply = "{$selectedGreeting} **\"{$bestMatch->question}\"**:\n\n"
                . "{$bestMatch->answer}\n\n"
                . "✨ *Apakah panduan di atas sudah menyelesaikan kendala Kakak?*";

            $this->recordLog($request, $userQuery, $formattedReply, 'faq_database', true, $bestMatch->id);

            return response()->json([
                'success' => true,
                'found' => true,
                'faq_id' => $bestMatch->id,
                'question_title' => $bestMatch->question,
                'bot_reply' => $formattedReply,
                'raw_answer' => $bestMatch->answer,
                'related_questions' => $related,
                'offer_ticket' => false,
            ]);
        }

        // 5. Ultimate Fallback (No matching FAQ found)
        $fallbackMsg = $settings['chatbot_fallback_message'] ?? 'Wah, mohon maaf ya Kak, saya belum menemukan jawaban yang tepat dari data FAQ. Namun jangan khawatir, silakan klik tombol "Buat Tiket Support" di bawah agar segera ditindaklanjuti oleh petugas Helpdesk kami!';
        $this->recordLog($request, $userQuery, $fallbackMsg, 'fallback_unanswered', false);

        return response()->json([
            'success' => true,
            'found' => false,
            'bot_reply' => $fallbackMsg,
            'offer_ticket' => true,
        ]);
    }

    /**
     * Submit an escalated support ticket directly into inbox_leads.
     */
    public function submitTicket(Request $request): JsonResponse
    {
        // Anti-bot honeypot check
        if (!empty($request->input('hp_security_check'))) {
            return response()->json([
                'success' => true,
                'message' => 'Tiket Anda telah berhasil dicatat.',
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'captcha_num1' => 'required|integer',
            'captcha_num2' => 'required|integer',
            'captcha_input' => 'required|integer',
        ]);

        // Verify Anti-Bot Math Captcha
        if ((int)$validated['captcha_input'] !== ((int)$validated['captcha_num1'] + (int)$validated['captcha_num2'])) {
            return response()->json([
                'success' => false,
                'message' => 'Jawaban verifikasi keamanan (Anti-Bot) salah. Silakan coba lagi.',
            ], 422);
        }

        try {
            $ticket = InboxLead::create([
                'name' => strip_tags($validated['name']),
                'email' => filter_var($validated['email'], FILTER_SANITIZE_EMAIL),
                'phone' => isset($validated['phone']) ? strip_tags($validated['phone']) : null,
                'subject' => '[Helpdesk Bot] ' . strip_tags($validated['subject']),
                'message' => strip_tags($validated['message']),
                'status' => 'unread',
                'ip_address' => $request->ip(),
            ]);

            $ticketId = 'TKT-' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT);

            return response()->json([
                'success' => true,
                'ticket_id' => $ticketId,
                'message' => "Tiket support Kakak dengan Nomor **#{$ticketId}** telah berhasil diteruskan ke tim IT Helpdesk kami. Petugas kami akan segera meninjau dan membalas melalui email {$validated['email']}.",
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to submit chatbot ticket: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kendala pada database server saat menyimpan tiket: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Dedicated Ticket Tracking API Endpoint.
     */
    public function trackTicket(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ticket_id' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        try {
            $rawTicket = trim($validated['ticket_id']);
            if (preg_match('/(\d+)/', $rawTicket, $matches)) {
                $numericId = (int)$matches[1];
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Format nomor tiket tidak valid. Contoh format nomor tiket: TKT-00005 atau 5.',
                ], 422);
            }

            $query = InboxLead::where('id', $numericId);
            if (!empty($validated['email'])) {
                $query->where('email', strtolower(trim($validated['email'])));
            }

            $ticket = $query->first();

            if (!$ticket) {
                $paddedId = 'TKT-' . str_pad($numericId, 5, '0', STR_PAD_LEFT);
                return response()->json([
                    'success' => false,
                    'message' => "Nomor tiket **#{$paddedId}** tidak ditemukan di sistem kami. Pastikan nomor tiket dan email yang dimasukkan sudah sesuai.",
                ], 404);
            }

            $paddedId = 'TKT-' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT);
            $statusLabel = match($ticket->status) {
                'replied' => '🟢 Selesai / Sudah Dibalas',
                'read', 'in_progress' => '🔵 Sedang Diproses oleh Tim Teknis',
                'closed' => '⚪ Ditutup',
                default => '🟡 Menunggu Penanganan Petugas',
            };

            $createdAt = $ticket->created_at ? $ticket->created_at->translatedFormat('d F Y, H:i') . ' WIB' : '-';
            $repliedAt = $ticket->replied_at ? \Carbon\Carbon::parse($ticket->replied_at)->translatedFormat('d F Y, H:i') . ' WIB' : null;
            $cleanSubject = str_replace('[Helpdesk Bot] ', '', $ticket->subject);

            return response()->json([
                'success' => true,
                'ticket' => [
                    'ticket_id' => $paddedId,
                    'name' => $ticket->name,
                    'email' => $ticket->email,
                    'subject' => $cleanSubject,
                    'message' => $ticket->message,
                    'status' => $ticket->status,
                    'status_label' => $statusLabel,
                    'created_at' => $createdAt,
                    'replied_at' => $repliedAt,
                    'reply_subject' => $ticket->reply_subject,
                    'reply_message' => $ticket->reply_message,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to track ticket: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kendala saat melacak tiket: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Normalize string for semantic matching.
     */
    private function normalizeText(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        // Remove special punctuation
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    /**
     * Calculate semantic relevance score between user query and FAQ record.
     */
    private function calculateRelevanceScore(string $query, Faq $faq): float
    {
        $normQuestion = $this->normalizeText($faq->question);
        $normAnswer = $this->normalizeText(strip_tags($faq->answer));

        $score = 0.0;

        // 1. Exact or Substring Matching in Question
        if ($normQuestion === $query) {
            return 100.0;
        }
        if (str_contains($normQuestion, $query)) {
            $score += 65.0;
        }
        if (str_contains($query, $normQuestion)) {
            $score += 60.0;
        }

        // 2. Keyword Synonym Expansion
        $synonymMap = [
            'wifi' => ['wifi', 'wi fi', 'hotspot', 'internet', 'sinyal', 'jaringan', 'eduroam', 'koneksi', 'konek', 'lemot'],
            'sso' => ['sso', 'akun', 'login', 'masuk', 'email', 'untan', 'mail', 'nim', 'nip', 'identitas'],
            'password' => ['password', 'sandi', 'pass', 'reset', 'lupa', 'ganti', 'pulihkan', 'terkunci', 'lock'],
            'siakad' => ['siakad', 'lms', 'elearning', 'moodle', 'krs', 'nilai', 'presensi', 'akademik'],
            'aplikasi' => ['aplikasi', 'sistem', 'software', 'web', 'website', 'domain', 'subdomain', 'hosting', 'server', 'vps'],
            'gangguan' => ['gangguan', 'lapor', 'rusak', 'error', 'kendala', 'masalah', 'helpdesk', 'tiket', 'komplain'],
        ];

        $queryTokens = explode(' ', $query);
        $questionTokens = explode(' ', $normQuestion);

        $matchedTokenCount = 0;
        foreach ($queryTokens as $qToken) {
            if (strlen($qToken) < 3) continue; // Ignore very short words like 'di', 'ke'

            // Direct token match in question
            if (in_array($qToken, $questionTokens)) {
                $score += 20.0;
                $matchedTokenCount++;
                continue;
            }

            // Synonym match
            foreach ($synonymMap as $group => $synonyms) {
                if (in_array($qToken, $synonyms)) {
                    foreach ($questionTokens as $targetToken) {
                        if (in_array($targetToken, $synonyms)) {
                            $score += 15.0;
                            $matchedTokenCount++;
                            break 2;
                        }
                    }
                }
            }

            // Direct token match in answer body
            if (str_contains($normAnswer, $qToken)) {
                $score += 8.0;
            }
        }

        // 3. Fuzzy similarity check for typo resilience
        similar_text($query, $normQuestion, $percentSimilarity);
        if ($percentSimilarity > 40) {
            $score += ($percentSimilarity * 0.4);
        }

        return min($score, 100.0);
    }

    /**
     * Handle small talk, polite greetings, identity inquiries, and gratitude.
     */
    private function handleSmallTalk(string $query, string $botName): ?string
    {
        // If the query contains substantive technical terms, let the FAQ engine handle it
        $technicalKeywords = ['wifi', 'sso', 'password', 'sandi', 'siakad', 'lms', 'server', 'jaringan', 'hosting', 'domain', 'krs', 'email', 'error', 'reset', 'gangguan', 'aplikasi', 'lab'];
        foreach ($technicalKeywords as $tech) {
            if (str_contains($query, $tech)) {
                return null;
            }
        }

        // Determine current time of day in Pontianak timezone (WIB / UTC+7)
        $hour = (int) now()->timezone('Asia/Pontianak')->format('H');
        $timeGreeting = match(true) {
            $hour >= 4 && $hour < 11 => 'Pagi',
            $hour >= 11 && $hour < 15 => 'Siang',
            $hour >= 15 && $hour < 18 => 'Sore',
            default => 'Malam',
        };

        // 1. Identity Queries
        if (preg_match('/\b(kamu siapa|siapa kamu|namamu|nama kamu|ini siapa|kamu bot|apakah kamu bot|bot apa)\b/i', $query)) {
            return "Halo Kak! Saya adalah **{$botName}**, asisten cerdas resmi dari UPA. TIK Universitas Tanjungpura. 🎧✨\n\nSaya siap membantu Kakak menjawab pertanyaan seputar akun SSO, Wi-Fi kampus, sistem perkuliahan, server, atau meneruskan tiket pengaduan ke tim Helpdesk kami 24/7. Ada yang bisa saya bantu hari ini?";
        }

        // 2. Office & Contact Info
        if (preg_match('/\b(lokasi|alamat|jam kerja|jam operasional|kantor|kontak|no wa|whatsapp)\b/i', $query) && (str_contains($query, 'tik') || str_contains($query, 'kantor') || str_contains($query, 'helpdesk') || str_contains($query, 'layanan'))) {
            return "🏢 **Informasi Layanan & Kantor UPA. TIK Universitas Tanjungpura:**\n━━━━━━━━━━━━━━━━━━━━━━\n📍 **Lokasi Kantor** : Gedung UPA. TIK, Universitas Tanjungpura, Pontianak\n⏰ **Jam Operasional Staf** : Senin - Jumat (07.30 - 16.00 WIB)\n🌐 **Layanan Asisten Virtual** : Online 24 Jam Non-Stop\n━━━━━━━━━━━━━━━━━━━━━━\n\nAda kendala teknis atau pertanyaan yang ingin Kakak tanyakan langsung kepada saya?";
        }

        // 3. Gratitude / Thanks
        if (preg_match('/\b(makasih|terimakasih|terima kasih|thanks|thank you|thx|suwun|matur suwun|tq|syukron|alhamdulillah|membantu sekali|sangat membantu)\b/i', $query)) {
            return "Sama-sama Kak! Senang sekali bisa membantu kendala Kakak. 😊\n\nJika nanti ada kendala teknis atau pertanyaan lain seputar layanan TIK kampus, jangan ragu untuk menyapa saya kembali ya. Semoga hari Kakak menyenangkan dan lancar selalu! ✨";
        }

        // 4. Closing / Acknowledgement
        if (preg_match('/\b(oke|ok|okay|sip|siap|baik|baiklah|mantap|keren|siap kak|bye|dadah|good bye|sampai jumpa|good luck)\b/i', $query) && strlen($query) <= 30) {
            return "Siap Kak! Tetap semangat dan sukses selalu untuk aktivitasnya di Universitas Tanjungpura ya. 🙌 Jika butuh bantuan lagi, saya selalu siap di sini!";
        }

        // 5. Continuation & Follow-up Inquiries (e.g. "bisakah membantu lagi", "ada pertanyaan saya lagi", "mau tanya lagi")
        if (preg_match('/\b(bisa bantu lagi|bisakah membantu lagi|bisa tolong lagi|bantu lagi|bantu saya lagi|tolong lagi|ada pertanyaan lagi|ada pertanyaan saya lagi|mau tanya lagi|mau nanya lagi|saya mau tanya lagi|ingin bertanya lagi|nanya lagi|masih ada pertanyaan|ada satu lagi|satu lagi kak|tanya lagi)\b/i', $query)) {
            return "Tentu saja Kak, dengan senang hati! 🙌 Silakan sampaikan pertanyaan atau kendala berikutnya yang ingin Kakak tanyakan. Saya siap mendengarkan dan memandu solusinya.";
        }

        // 6. Help Request Intents (e.g. "tolong bantu", "butuh bantuan", "minta tolong", "bisa bantu saya")
        if (preg_match('/\b(tolong bantu|tolong bantu saya|butuh bantuan|bisa bantu gak|bisa bantu saya|bisa tolong saya|minta tolong|tolong dong|mohon bantuan|bantu saya|bisa tolong|tolong kak|bantu kak)\b/i', $query) && strlen($query) <= 40) {
            return "Tentu saja Kak! Saya siap membantu sepenuh hati. 😊\n\nSilakan ceritakan apa kendala yang sedang Kakak hadapi seputar akun SSO, jaringan Wi-Fi kampus, sistem perkuliahan, server, atau layanan TIK lainnya ya.";
        }

        // 7. General Question Starters (e.g. "mau tanya", "izin bertanya", "boleh tanya")
        if (preg_match('/\b(mau tanya|mau nanya|mau tanya dong|nanya dong|ingin bertanya|izin bertanya|boleh tanya|boleh nanya|mau konsultasi)\b/i', $query) && strlen($query) <= 35) {
            return "Boleh sekali Kak! Silakan ketik apa yang ingin Kakak tanyakan seputar layanan TIK kampus. Saya akan bantu carikan solusi terbaiknya. 😊";
        }

        // 8. Islamic Greeting
        if (preg_match('/\b(assalamualaikum|assalamu alaikum|assalamu alaykum|samlikum|mikum|salaam|salam)\b/i', $query)) {
            return "Wa'alaikumussalam Warahmatullahi Wabarakatuh Kak! Selamat {$timeGreeting} 😊\n\nAda kendala seputar akun SSO, jaringan Wi-Fi, aplikasi web, atau layanan TIK kampus yang bisa saya bantu hari ini?";
        }

        // 9. General & Time-based Greetings
        $greetingPatterns = [
            '/\b(halo|hai|helo|hello|hi|hay|hy|hei|oy|woy|ping|p|tes|test|testing)\b/i',
            '/\b(pagi|selamat pagi|pgi)\b/i',
            '/\b(siang|selamat siang)\b/i',
            '/\b(sore|selamat sore)\b/i',
            '/\b(malam|selamat malam|mlm)\b/i',
        ];

        foreach ($greetingPatterns as $pattern) {
            if (preg_match($pattern, $query)) {
                // If user specifies a specific time of day (e.g. "selamat pagi")
                if (preg_match('/\b(pagi|pgi)\b/i', $query)) $specificTime = 'Pagi';
                elseif (preg_match('/\b(siang)\b/i', $query)) $specificTime = 'Siang';
                elseif (preg_match('/\b(sore)\b/i', $query)) $specificTime = 'Sore';
                elseif (preg_match('/\b(malam|mlm)\b/i', $query)) $specificTime = 'Malam';
                else $specificTime = $timeGreeting;

                return "Halo juga Kak! Selamat {$specificTime} 😊 Senang bisa menyapa Kakak.\n\nAda kendala seputar akun SSO, jaringan Wi-Fi, aplikasi web, atau layanan TIK kampus yang bisa saya bantu hari ini? Silakan ketik pertanyaan Kakak ya.";
            }
        }

        return null;
    }

    /**
     * Generate grounded AI response using Google Gemini API with seamless fail-safe fallback.
     */
    private function generateAiResponse(string $userQuery, string $botName, array $settings): ?string
    {
        $geminiService = app(GeminiAiService::class);
        $customPrompt = $settings['chatbot_ai_system_prompt'] ?? "Kamu adalah Asisten Cerdas IT Helpdesk UPA. TIK Universitas Tanjungpura (Pontianak). Jawablah pertanyaan pengunjung secara ramah, sopan, jelas, dan membantu dalam Bahasa Indonesia.";
        $faqContext = $geminiService->buildFaqContext();

        $systemPrompt = "{$customPrompt}\n\n"
            . "PANDUAN & BASIS PENGETAHUAN RESMI UPA. TIK UNIVERSITAS TANJUNGPURA:\n"
            . "===============================================================\n"
            . "{$faqContext}\n\n"
            . "===============================================================\n\n"
            . "PETUNJUK JAWABAN:\n"
            . "1. Gunakan Bahasa Indonesia yang ramah, sopan, dan solutif (panggil pengguna dengan 'Kakak' atau 'Kak').\n"
            . "2. Utamakan menjawab berdasarkan fakta dan solusi yang tertera di data FAQ resmi di atas jika pertanyaan berkaitan dengan layanan kampus.\n"
            . "3. Jika pertanyaan pengguna TIDAK DITEMUKAN di data FAQ resmi (seperti seputar tips komputer, troubleshooting umum, edukasi teknologi, atau pengetahuan umum), kamu DIPERBOLEHKAN dan SANGAT DIANJURKAN menjawab menggunakan pengetahuan umum cerdasmu sendiri secara jelas, solutif, dan informatif.\n"
            . "4. Namun jika kendala tersebut membutuhkan akses ke server kampus, reset akun privat, atau tindakan langsung staf IT instansi, sarankan dengan ramah untuk membuat tiket bantuan helpdesk.\n"
            . "5. Gunakan format Markdown yang rapi (gunakan bold **kata penting**, list nomor/bullet jika ada urutan langkah).\n"
            . "6. Jangan mengarang kontak atau link internal yang tidak terdaftar.";

        return $geminiService->tryGenerateContent(
            $systemPrompt . "\n\nPertanyaan Pengguna: " . $userQuery,
            [
                'temperature' => 0.5,
                'maxOutputTokens' => 2048,
                'timeout' => 20,
            ],
            $settings
        );
    }

    /**
     * Record interaction to ChatbotLog for admin monitoring and analytics.
     */
    private function recordLog(Request $request, string $userQuery, string $botReply, string $responseType, bool $isAnswered = true, ?int $faqId = null): void
    {
        try {
            ChatbotLog::create([
                'user_query' => $userQuery,
                'bot_reply' => $botReply,
                'response_type' => $responseType,
                'is_answered' => $isAnswered,
                'matched_faq_id' => $faqId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to record chatbot interaction log: ' . $e->getMessage());
        }
    }
}
