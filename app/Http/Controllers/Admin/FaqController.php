<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQs with search & filter support.
     */
    public function index(Request $request): View
    {
        $query = Faq::orderBy('sort_order', 'asc')->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'chatbot_only') {
                $query->where('is_active', false);
            }
        }

        $perPage = (int)$request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $faqs = $query->paginate($perPage)->withQueryString();
        $totalCount = Faq::count();
        $publicCount = Faq::where('is_active', true)->count();
        $chatbotOnlyCount = Faq::where('is_active', false)->count();

        return view('admin.faqs.index', compact('faqs', 'perPage', 'totalCount', 'publicCount', 'chatbotOnlyCount'));
    }

    /**
     * Store a newly created FAQ in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? (Faq::max('sort_order') + 1);
        $validated['is_active'] = $request->has('is_active');

        Faq::create($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'Pertanyaan FAQ berhasil ditambahkan.');
    }

    /**
     * Update the specified FAQ in storage.
     */
    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'Pertanyaan FAQ berhasil diperbarui.');
    }

    /**
     * Toggle active status (Web & Chatbot vs Chatbot Only).
     */
    public function toggleActive(Faq $faq): RedirectResponse
    {
        $faq->update(['is_active' => !$faq->is_active]);

        $statusText = $faq->is_active ? 'ditampilkan di Web Publik & Chatbot' : 'diatur Khusus Data Dukung Chatbot (Hidden dari Web)';
        return back()->with('success', "Status FAQ \"{$faq->question}\" berhasil diubah: {$statusText}.");
    }

    /**
     * Remove the specified FAQ from storage.
     */
    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'Pertanyaan FAQ berhasil dihapus.');
    }

    /**
     * Export all FAQs to JSON format.
     */
    public function exportJson(): StreamedResponse
    {
        $faqs = Faq::orderBy('sort_order', 'asc')->get(['question', 'answer', 'is_active', 'sort_order']);
        $filename = 'faqs-export-' . date('Y-m-d') . '.json';

        $headers = [
            'Content-Type' => 'application/json; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($faqs) {
            echo json_encode($faqs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }, 200, $headers);
    }

    /**
     * Export all FAQs to CSV format (Excel compatible with UTF-8 BOM).
     */
    public function exportCsv(): StreamedResponse
    {
        $faqs = Faq::orderBy('sort_order', 'asc')->get();
        $filename = 'faqs-export-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($faqs) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for Microsoft Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($handle, ['question', 'answer', 'is_active', 'sort_order']);

            foreach ($faqs as $faq) {
                fputcsv($handle, [
                    $faq->question,
                    $faq->answer,
                    $faq->is_active ? 1 : 0,
                    $faq->sort_order,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Download Sample FAQ Template (.json or .csv).
     */
    public function downloadTemplate(string $format): StreamedResponse|JsonResponse
    {
        if ($format === 'json') {
            $sample = [
                [
                    'question' => 'Bagaimana cara reset password SSO Untan?',
                    'answer' => 'Kunjungi portal https://sso.untan.ac.id/lupa-password, masukkan NIM/NIP dan ikuti instruksi pemulihan via email.',
                    'is_active' => false,
                    'sort_order' => 1,
                ],
                [
                    'question' => 'Berapa kecepatan internet WiFi di area perpustakaan?',
                    'answer' => 'Kecepatan bandwidth WiFi di perpustakaan mencapai hingga 100 Mbps dengan login akun SSO aktif.',
                    'is_active' => false,
                    'sort_order' => 2,
                ],
            ];

            $filename = 'template-faq.json';
            return response()->stream(function () use ($sample) {
                echo json_encode($sample, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }, 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        }

        // CSV Template
        $filename = 'template-faq.csv';
        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['question', 'answer', 'is_active', 'sort_order']);
            fputcsv($handle, [
                'Bagaimana cara reset password SSO Untan?',
                'Kunjungi portal https://sso.untan.ac.id/lupa-password, masukkan NIM/NIP dan ikuti instruksi pemulihan.',
                0,
                1
            ]);
            fputcsv($handle, [
                'Berapa kecepatan internet WiFi di area perpustakaan?',
                'Kecepatan bandwidth WiFi di perpustakaan mencapai hingga 100 Mbps dengan login akun SSO.',
                0,
                2
            ]);
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Import FAQs from JSON, CSV, or Text file with Smart Fuzzy Column Parsing.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'faq_file' => 'required|file|max:10240', // 10MB max
            'import_mode' => 'required|in:append_skip,append_update,replace_all',
            'target_status' => 'required|in:chatbot_only,public_web,from_file',
        ]);

        $file = $request->file('faq_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $content = file_get_contents($file->getRealPath());

        if (empty($content)) {
            return back()->with('error', 'File yang diunggah kosong atau tidak dapat dibaca.');
        }

        // 1. Parse File Content into Standard Normalized Array of FAQs
        $parsedRows = [];

        if ($extension === 'json' || $this->isJson($content)) {
            $parsedRows = $this->parseJsonContent($content);
        } else {
            // Assume CSV / Delimited Text
            $parsedRows = $this->parseCsvContent($content);
        }

        if (empty($parsedRows)) {
            return back()->with('error', 'Gagal mengekstrak data dari file. Pastikan file berisi daftar pertanyaan dan jawaban yang valid.');
        }

        // 2. Handle Import Strategy
        $importMode = $request->input('import_mode');
        $targetStatus = $request->input('target_status');

        if ($importMode === 'replace_all') {
            Faq::truncate();
        }

        $insertedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $nextSortOrder = (int)(Faq::max('sort_order') ?? 0);

        foreach ($parsedRows as $row) {
            $question = trim($row['question'] ?? '');
            $answer = trim($row['answer'] ?? '');

            if (empty($question) || empty($answer)) {
                $skippedCount++;
                continue;
            }

            // Determine is_active status based on target_status option
            $isActive = match ($targetStatus) {
                'chatbot_only' => false,
                'public_web' => true,
                default => isset($row['is_active']) ? filter_var($row['is_active'], FILTER_VALIDATE_BOOLEAN) : false,
            };

            $sortOrder = isset($row['sort_order']) && is_numeric($row['sort_order']) 
                ? (int)$row['sort_order'] 
                : ++$nextSortOrder;

            // Check if Question already exists (case-insensitive)
            $existing = Faq::whereRaw('LOWER(question) = ?', [strtolower($question)])->first();

            if ($existing) {
                if ($importMode === 'append_update') {
                    $existing->update([
                        'answer' => $answer,
                        'is_active' => $isActive,
                    ]);
                    $updatedCount++;
                } else {
                    $skippedCount++;
                }
            } else {
                Faq::create([
                    'question' => $question,
                    'answer' => $answer,
                    'is_active' => $isActive,
                    'sort_order' => $sortOrder,
                ]);
                $insertedCount++;
            }
        }

        $statusMsg = $targetStatus === 'chatbot_only' 
            ? 'Khusus Data Dukung Chatbot & AI (Hidden dari Web)' 
            : ($targetStatus === 'public_web' ? 'Tampil di Web & Chatbot' : 'Sesuai Pengaturan File');

        $resultMsg = "Proses impor selesai! {$insertedCount} FAQ baru berhasil ditambahkan, {$updatedCount} diperbarui, dan {$skippedCount} data duplikat dilewati. (Mode: {$statusMsg})";

        return redirect()->route('admin.faqs.index')->with('success', $resultMsg);
    }

    /**
     * Smart Parser for JSON data: handles array of objects, dictionary, or nested structures.
     */
    private function parseJsonContent(string $jsonString): array
    {
        $data = json_decode($jsonString, true);
        if (!$data || !is_array($data)) {
            return [];
        }

        // If nested under 'data', 'faqs', 'items', 'questions'
        foreach (['data', 'faqs', 'items', 'questions', 'faq'] as $nestedKey) {
            if (isset($data[$nestedKey]) && is_array($data[$nestedKey])) {
                $data = $data[$nestedKey];
                break;
            }
        }

        $results = [];

        // Check if data is key-value dictionary (e.g. {"Apa itu SSO?": "SSO adalah..."})
        $isAssociativeDict = !array_is_list($data);
        if ($isAssociativeDict) {
            $i = 1;
            foreach ($data as $q => $a) {
                if (is_string($a)) {
                    $results[] = [
                        'question' => $q,
                        'answer' => $a,
                        'is_active' => false,
                        'sort_order' => $i++,
                    ];
                } elseif (is_array($a)) {
                    $extracted = $this->extractQuestionAnswerFromRow($a);
                    if ($extracted) {
                        $results[] = $extracted;
                    }
                }
            }
            return $results;
        }

        // Standard Array of Objects
        foreach ($data as $row) {
            if (!is_array($row)) continue;
            $extracted = $this->extractQuestionAnswerFromRow($row);
            if ($extracted) {
                $results[] = $extracted;
            }
        }

        return $results;
    }

    /**
     * Smart Parser for CSV / Delimited text data with Auto-Delimiter and Fuzzy Header Detection.
     */
    private function parseCsvContent(string $csvString): array
    {
        // Remove UTF-8 BOM if present
        $csvString = preg_replace('/^\xEF\xBB\xBF/', '', $csvString);
        
        $lines = preg_split('/\r\n|\r|\n/', trim($csvString));
        if (empty($lines)) {
            return [];
        }

        // Auto-detect delimiter from first line (comma, semicolon, tab, pipe)
        $firstLine = $lines[0];
        $delimiters = [',', ';', "\t", '|'];
        $bestDelimiter = ',';
        $maxCount = 0;

        foreach ($delimiters as $delim) {
            $count = count(str_getcsv($firstLine, $delim));
            if ($count > $maxCount) {
                $maxCount = $count;
                $bestDelimiter = $delim;
            }
        }

        $header = str_getcsv(array_shift($lines), $bestDelimiter);
        $header = array_map(fn($h) => strtolower(trim(str_replace(['"', "'"], '', $h))), $header);

        $questionIndex = -1;
        $answerIndex = -1;
        $statusIndex = -1;
        $sortIndex = -1;

        // Candidate keywords for Question and Answer
        $questionCandidates = ['question', 'pertanyaan', 'tanya', 'q', 'title', 'judul', 'query', 'masalah', 'kendala', 'topik', 'soal'];
        $answerCandidates = ['answer', 'jawaban', 'jawab', 'a', 'solusi', 'solution', 'deskripsi', 'description', 'content', 'isi', 'penjelasan'];
        $statusCandidates = ['is_active', 'status', 'active', 'aktif', 'publik', 'public'];
        $sortCandidates = ['sort_order', 'order', 'urutan', 'no', 'nomor'];

        foreach ($header as $idx => $colName) {
            if ($questionIndex === -1 && in_array($colName, $questionCandidates)) {
                $questionIndex = $idx;
            } elseif ($answerIndex === -1 && in_array($colName, $answerCandidates)) {
                $answerIndex = $idx;
            } elseif ($statusIndex === -1 && in_array($colName, $statusCandidates)) {
                $statusIndex = $idx;
            } elseif ($sortIndex === -1 && in_array($colName, $sortCandidates)) {
                $sortIndex = $idx;
            }
        }

        // Fallback: If header couldn't be mapped by name, use 1st column as Question and 2nd column as Answer
        if ($questionIndex === -1 && count($header) >= 1) $questionIndex = 0;
        if ($answerIndex === -1 && count($header) >= 2) $answerIndex = 1;

        $results = [];

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;

            $cols = str_getcsv($line, $bestDelimiter);
            if (!isset($cols[$questionIndex]) || !isset($cols[$answerIndex])) continue;

            $q = trim($cols[$questionIndex]);
            $a = trim($cols[$answerIndex]);

            if (empty($q) || empty($a)) continue;

            $isActive = ($statusIndex !== -1 && isset($cols[$statusIndex]))
                ? in_array(strtolower(trim($cols[$statusIndex])), ['1', 'true', 'aktif', 'yes', 'y'])
                : false;

            $sortOrder = ($sortIndex !== -1 && isset($cols[$sortIndex]) && is_numeric($cols[$sortIndex]))
                ? (int)$cols[$sortIndex]
                : null;

            $results[] = [
                'question' => $q,
                'answer' => $a,
                'is_active' => $isActive,
                'sort_order' => $sortOrder,
            ];
        }

        return $results;
    }

    /**
     * Extract Question & Answer from associative array using Fuzzy Key Matching.
     */
    private function extractQuestionAnswerFromRow(array $row): ?array
    {
        $question = null;
        $answer = null;
        $isActive = false;
        $sortOrder = null;

        $questionCandidates = ['question', 'pertanyaan', 'tanya', 'q', 'title', 'judul', 'query', 'masalah', 'kendala', 'topik', 'soal'];
        $answerCandidates = ['answer', 'jawaban', 'jawab', 'a', 'solusi', 'solution', 'deskripsi', 'description', 'content', 'isi', 'penjelasan'];

        foreach ($row as $key => $val) {
            $cleanKey = strtolower(trim((string)$key));

            if ($question === null && in_array($cleanKey, $questionCandidates) && is_string($val)) {
                $question = trim($val);
            } elseif ($answer === null && in_array($cleanKey, $answerCandidates) && is_string($val)) {
                $answer = trim($val);
            } elseif (in_array($cleanKey, ['is_active', 'status', 'active', 'aktif'])) {
                $isActive = filter_var($val, FILTER_VALIDATE_BOOLEAN);
            } elseif (in_array($cleanKey, ['sort_order', 'order', 'urutan', 'no']) && is_numeric($val)) {
                $sortOrder = (int)$val;
            }
        }

        // If not found by candidate keys, check first two string properties
        if (!$question || !$answer) {
            $stringValues = array_values(array_filter($row, fn($v) => is_string($v) && !empty(trim($v))));
            if (count($stringValues) >= 2) {
                $question = $stringValues[0];
                $answer = $stringValues[1];
            }
        }

        if ($question && $answer) {
            return [
                'question' => $question,
                'answer' => $answer,
                'is_active' => $isActive,
                'sort_order' => $sortOrder,
            ];
        }

        return null;
    }

    /**
     * Check if a string is a valid JSON string.
     */
    private function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
