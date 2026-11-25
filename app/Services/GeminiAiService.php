<?php

namespace App\Services;

use App\Models\CompanyProfile;
use App\Models\Faq;
use App\Models\InboxLead;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAiService
{
    /**
     * Build formatted FAQ context text for grounded AI prompts.
     */
    public function buildFaqContext(): string
    {
        $faqs = Faq::query()->orderBy('sort_order', 'asc')->get();
        if ($faqs->isEmpty()) {
            return '';
        }

        $faqContext = '';
        foreach ($faqs as $i => $faq) {
            $num = $i + 1;
            $faqContext .= "FAQ #{$num}:\nQ: {$faq->question}\nA: " . trim(strip_tags((string) $faq->answer)) . "\n\n";
        }

        return trim($faqContext);
    }

    /**
     * Fail-safe Gemini generation returning null on missing key or network error.
     *
     * @param  array<string, mixed>  $options
     * @param  array<string, string>|null  $settings
     */
    public function tryGenerateContent(string $prompt, array $options = [], ?array $settings = null): ?string
    {
        try {
            return $this->generateContent($prompt, $options, $settings);
        } catch (\Throwable $e) {
            Log::warning('Gemini AI tryGenerateContent skipped/failed: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Call Google Gemini API with automatic model failover.
     *
     * @param  array<string, mixed>  $options
     * @param  array<string, string>|null  $settings
     *
     * @throws \RuntimeException
     */
    public function generateContent(string $prompt, array $options = [], ?array $settings = null): string
    {
        $resolvedSettings = $settings ?? SiteSetting::all()->pluck('value', 'key')->toArray();
        $apiKey = trim((string) ($resolvedSettings['chatbot_gemini_api_key'] ?? ''));

        if ($apiKey === '') {
            throw new \RuntimeException('API Key Gemini AI belum dikonfigurasi pada menu Pengaturan Chatbot & AI.');
        }

        $primaryModel = !empty($resolvedSettings['chatbot_ai_model'])
            ? (string) $resolvedSettings['chatbot_ai_model']
            : 'gemini-3.5-flash-lite';

        $candidateModels = array_values(array_unique([
            $primaryModel,
            'gemini-3.5-flash-lite',
            'gemini-3.6-flash',
            'gemini-flash-latest',
        ]));

        $timeout = (int) ($options['timeout'] ?? 20);
        $generationConfig = [
            'temperature' => (float) ($options['temperature'] ?? 0.5),
            'maxOutputTokens' => (int) ($options['maxOutputTokens'] ?? 2048),
        ];

        $lastStatus = null;
        $lastMessage = null;

        foreach ($candidateModels as $model) {
            try {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

                $response = Http::timeout($timeout)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt],
                                ],
                            ],
                        ],
                        'generationConfig' => $generationConfig,
                    ]);

                if ($response->successful()) {
                    $parts = $response->json('candidates.0.content.parts') ?? [];
                    $aiText = '';
                    foreach ($parts as $part) {
                        if (isset($part['text']) && empty($part['thought'])) {
                            $aiText .= $part['text'];
                        }
                    }

                    $aiText = trim($aiText);
                    if ($aiText !== '') {
                        return $aiText;
                    }
                }

                $lastStatus = $response->status();
                $lastMessage = $response->json('error.message') ?: substr($response->body(), 0, 200);
                Log::warning("Gemini AI ({$model}) returned status {$lastStatus} - Body: " . substr($response->body(), 0, 150));
            } catch (\Throwable $e) {
                $lastMessage = $e->getMessage();
                Log::warning("Gemini AI ({$model}) request exception: " . $lastMessage);
            }
        }

        $detail = $lastMessage ? " ({$lastMessage})" : '';
        throw new \RuntimeException('Gagal menghasilkan respons dari Gemini AI saat ini' . $detail . '.');
    }

    /**
     * Draft or reformat an official inbox reply as clean CKEditor-ready HTML.
     *
     * @param  array{mode?: string, tone?: string, instruction?: string, current_html?: string}  $params
     * @param  array<string, string>|null  $settings
     * @return array{draft_html: string, model: string}
     */
    public function draftInboxReply(InboxLead $lead, array $params, ?array $settings = null): array
    {
        $resolvedSettings = $settings ?? SiteSetting::all()->pluck('value', 'key')->toArray();
        $mode = (string) ($params['mode'] ?? 'auto_draft');
        $tone = (string) ($params['tone'] ?? 'formal');
        $instruction = trim((string) ($params['instruction'] ?? ''));
        $currentHtml = trim((string) ($params['current_html'] ?? ''));

        // Preserve any existing uploaded image blocks from CKEditor so AI never drops or alters them.
        $preservedImages = [];
        $textOnlyCurrent = $this->extractImagesForPreservation($currentHtml, $preservedImages);
        $plainCurrent = trim(html_entity_decode(strip_tags($textOnlyCurrent), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        $profile = CompanyProfile::first();
        $companyName = $profile->company_name ?? 'UPA. TIK Universitas Tanjungpura';
        $faqContext = $this->buildFaqContext();

        $toneInstruction = match ($tone) {
            'friendly' => 'Ramah, empatik, hangat, dan solutif menggunakan sapaan "Anda".',
            'concise' => 'Sangat ringkas, langsung ke inti solusi, maksimal 2 paragraf atau 1 daftar singkat.',
            default => 'Resmi institusi kampus, sopan, jelas, terstruktur, menggunakan sapaan "Anda".',
        };

        $modeInstruction = match ($mode) {
            'polish' => 'TUGAS UTAMA: Rapikan, perbaiki ejaan/tata bahasa, dan kemas ulang "DRAF/CATATAN ADMIN DI EDITOR" di bawah menjadi balasan email resmi yang terstruktur. Pertahankan seluruh fakta, angka, akun, tautan, atau status tindakan dari catatan admin tersebut tanpa mengubah maknanya.',
            'steps' => 'TUGAS UTAMA: Ubah isi "DRAF/CATATAN ADMIN DI EDITOR" (atau solusi tiket jika editor kosong) menjadi panduan langkah demi langkah yang mudah diikuti menggunakan daftar bernomor (<ol><li>...</li></ol>) serta penekanan kata penting (<strong>).',
            'shorten' => 'TUGAS UTAMA: Ringkas "DRAF/CATATAN ADMIN DI EDITOR" (atau jawaban tiket) menjadi versi lebih singkat, padat, namun tetap sopan dan tidak menghilangkan informasi kunci.',
            default => 'TUGAS UTAMA: Susun alternatif draf balasan email untuk tiket masuk di bawah ini. Utamakan solusi berdasarkan DATA FAQ RESMI jika relevan, lalu gabungkan dengan ARAHAN PETUGAS jika ada. Jika penyelesaian membutuhkan tindakan teknis internal oleh petugas (misalnya reset password, verifikasi akun, perbaikan jaringan) dan tidak ada konfirmasi di ARAHAN PETUGAS bahwa tindakan itu sudah selesai, JANGAN mengarang bahwa tindakan sudah selesai — gunakan placeholder jelas seperti [tuliskan hasil penanganan / password sementara di sini].',
        };

        $prompt = "Kamu adalah Asisten Penulisan Email Resmi untuk petugas IT Helpdesk {$companyName}.\n\n"
            . "GAYA BAHASA: {$toneInstruction}\n"
            . "{$modeInstruction}\n\n"
            . "ATURAN FORMAT OUTPUT (WAJIB DIPATUHI):\n"
            . "1. Keluarkan HANYA potongan HTML bersih yang kompatibel dengan CKEditor 5: gunakan hanya tag <p>, <strong>, <em>, <ul>, <ol>, <li>, <blockquote>, dan <a href=\"...\">.\n"
            . "2. DILARANG membungkus output dengan markdown code block (seperti ```html atau ```).\n"
            . "3. DILARANG menulis tag <html>, <head>, <body>, <style>, atau <script>.\n"
            . "4. DILARANG menulis baris salam pembuka seperti 'Halo {$lead->name},' atau 'Kepada Yth...' di awal, dan DILARANG menulis tanda tangan/footer penutup ('Hormat kami / Salam / Copyright') di akhir, karena template email sistem sudah menambahkannya secara otomatis.\n"
            . "5. Gunakan Bahasa Indonesia yang baik dan benar.\n\n"
            . "DATA TIKET MASUK:\n"
            . "- Nama Pemohon: {$lead->name}\n"
            . "- Subjek: " . ($lead->subject ?: '-') . "\n"
            . "- Isi Pesan Pemohon:\n\"{$lead->message}\"\n\n";

        if ($instruction !== '') {
            $prompt .= "ARAHAN / CATATAN PETUGAS ADMIN:\n{$instruction}\n\n";
        }

        if ($plainCurrent !== '') {
            $prompt .= "DRAF/CATATAN ADMIN DI EDITOR SAAT INI:\n{$plainCurrent}\n\n";
        }

        if ($faqContext !== '') {
            $prompt .= "BASIS PENGETAHUAN FAQ RESMI (GUNAKAN SEBAGAI REFERENSI KEBIJAKAN/PROSEDUR):\n"
                . "===============================================================\n"
                . "{$faqContext}\n"
                . "===============================================================\n\n";
        }

        $prompt .= 'Berikan langsung potongan HTML isi balasan saja:';

        $rawOutput = $this->generateContent($prompt, [
            'temperature' => $mode === 'polish' || $mode === 'shorten' ? 0.35 : 0.45,
            'maxOutputTokens' => 1500,
            'timeout' => 25,
        ], $resolvedSettings);

        $cleanHtml = $this->normalizeAiHtmlOutput($rawOutput);

        if (!empty($preservedImages)) {
            $cleanHtml = trim($cleanHtml . "\n" . implode("\n", $preservedImages));
        }

        $activeModel = !empty($resolvedSettings['chatbot_ai_model'])
            ? (string) $resolvedSettings['chatbot_ai_model']
            : 'gemini-3.5-flash-lite';

        return [
            'draft_html' => $cleanHtml,
            'model' => $activeModel,
        ];
    }

    /**
     * Extract uploaded image figure/img blocks so they survive AI reformatting.
     *
     * @param  array<int, string>  $preservedImages
     */
    private function extractImagesForPreservation(string $html, array &$preservedImages): string
    {
        if ($html === '') {
            return '';
        }

        $withoutFigures = preg_replace_callback('/<figure\b[^>]*class\s*=\s*["\'][^"\']*\bimage\b[^"\']*["\'][^>]*>[\s\S]*?<\/figure>/i', function ($matches) use (&$preservedImages) {
            $preservedImages[] = $matches[0];

            return '';
        }, $html) ?? $html;

        return preg_replace_callback('/<img\b[^>]*>/i', function ($matches) use (&$preservedImages) {
            $preservedImages[] = '<figure class="image">' . $matches[0] . '</figure>';

            return '';
        }, $withoutFigures) ?? $withoutFigures;
    }

    /**
     * Strip accidental markdown code fences or duplicate greeting lines returned by Gemini.
     */
    private function normalizeAiHtmlOutput(string $rawOutput): string
    {
        $html = trim($rawOutput);

        // Remove markdown code block wrappers if present
        $html = preg_replace('/^```(?:html)?\s*/i', '', $html) ?? $html;
        $html = preg_replace('/\s*```$/', '', $html) ?? $html;
        $html = trim($html);

        // If Gemini responded with plain text instead of HTML tags, wrap paragraphs cleanly
        if ($html !== '' && $html === strip_tags($html)) {
            $paragraphs = preg_split("/\n{2,}/", $html) ?: [$html];
            $html = implode('', array_map(
                fn (string $p): string => '<p>' . nl2br(e(trim($p))) . '</p>',
                array_filter($paragraphs, fn (string $p): bool => trim($p) !== '')
            ));
        }

        // Remove accidental leading "Halo <Nama>," paragraph so template greeting is not duplicated
        $html = preg_replace('/^\s*<p>\s*(?:Halo|Hai|Yth\.?)\s+[^<]{1,80},?\s*<\/p>\s*/i', '', $html) ?? $html;

        return trim($html);
    }
}

