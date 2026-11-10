<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Laboratory;
use App\Models\LabBooking;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LabScheduleController extends Controller
{
    public function index(Request $request)
    {
        $profile = CompanyProfile::first();
        $settings = SiteSetting::all()->pluck('value', 'key');

        // Laboratories list
        $laboratories = Laboratory::where('is_active', true)->orderBy('order_index')->get();

        // Selected laboratory filter
        $selectedLabId = $request->input('lab');
        $currentLab = null;
        if ($selectedLabId && $selectedLabId !== 'all') {
            $currentLab = $laboratories->firstWhere('id', (int)$selectedLabId) 
                        ?: $laboratories->firstWhere('slug', $selectedLabId);
        }

        // Selected month/year (defaults to current date, or 2026-10 if local system)
        $monthParam = $request->input('month');
        if ($monthParam && preg_match('/^\d{4}-\d{2}$/', $monthParam)) {
            $currentDate = Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth();
        } else {
            $now = Carbon::now();
            $currentDate = ($now->year < 2026) ? Carbon::create(2026, 10, 1) : $now->copy()->startOfMonth();
        }

        $prevMonth = $currentDate->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentDate->copy()->addMonth()->format('Y-m');

        $startOfMonth = $currentDate->copy()->startOfMonth()->toDateString();
        $endOfMonth = $currentDate->copy()->endOfMonth()->toDateString();

        // Query bookings for this month (single day or multi-day spanning into this month)
        $bookingsQuery = LabBooking::with('laboratory')
            ->whereIn('status', ['approved', 'pending'])
            ->where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->where('booking_date', '<=', $endOfMonth)
                  ->whereRaw('COALESCE(end_date, booking_date) >= ?', [$startOfMonth]);
            });

        if ($currentLab) {
            $bookingsQuery->where('laboratory_id', $currentLab->id);
        }

        $bookings = $bookingsQuery->orderBy('booking_date')->orderBy('start_time')->get();

        // Generate calendar matrix (weeks & days)
        $startOfCalendar = $currentDate->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $currentDate->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $calendarDays = [];
        $dayCursor = $startOfCalendar->copy();
        while ($dayCursor->lte($endOfCalendar)) {
            $dateString = $dayCursor->format('Y-m-d');

            // Filter all bookings that cover this specific day
            $dayBookings = $bookings->filter(function ($item) use ($dateString) {
                $startDate = $item->booking_date->format('Y-m-d');
                $endDate = ($item->end_date ?: $item->booking_date)->format('Y-m-d');
                return $dateString >= $startDate && $dateString <= $endDate;
            })->values();

            $approvedBookings = $dayBookings->where('status', 'approved');
            $pendingBookings = $dayBookings->where('status', 'pending');

            $calendarDays[] = [
                'date' => $dayCursor->copy(),
                'date_string' => $dateString,
                'day_number' => $dayCursor->day,
                'is_current_month' => $dayCursor->month === $currentDate->month,
                'is_today' => $dayCursor->isToday(),
                'is_weekend' => $dayCursor->isWeekend(),
                'bookings' => $dayBookings,
                'approved_count' => $approvedBookings->count(),
                'pending_count' => $pendingBookings->count(),
                'is_busy' => $approvedBookings->count() >= max(1, $laboratories->count()),
            ];

            $dayCursor->addDay();
        }

        // Summary Statistics
        $totalLabs = $laboratories->count();
        $totalCapacity = $laboratories->sum('capacity');
        $approvedBookingsCount = LabBooking::where('status', 'approved')
            ->where('booking_date', '<=', $endOfMonth)
            ->whereRaw('COALESCE(end_date, booking_date) >= ?', [$startOfMonth])
            ->count();

        // View mode: 'calendar' (default desktop) or 'card'
        $viewMode = $request->input('view', 'calendar');

        $isBookingEnabled = ($settings['lab_booking_enabled'] ?? '1') === '1';
        $closedMessage = $settings['lab_booking_closed_message'] ?? 'Mohon maaf, layanan pengajuan peminjaman laboratorium komputer sedang ditutup sementara. Silakan hubungi pengelola UPA TIK untuk informasi lebih lanjut.';

        // Generate Math Captcha Anti-Bot
        $captchaNum1 = rand(3, 9);
        $captchaNum2 = rand(2, 8);
        $captchaToken = hash_hmac('sha256', "{$captchaNum1}+{$captchaNum2}", config('app.key'));
        session(['lab_captcha_answer' => $captchaNum1 + $captchaNum2]);

        return view('lab_schedule.index', compact(
            'profile',
            'settings',
            'laboratories',
            'currentLab',
            'currentDate',
            'prevMonth',
            'nextMonth',
            'calendarDays',
            'bookings',
            'totalLabs',
            'totalCapacity',
            'approvedBookingsCount',
            'viewMode',
            'isBookingEnabled',
            'closedMessage',
            'captchaNum1',
            'captchaNum2',
            'captchaToken'
        ));
    }

    /**
     * Refresh math captcha numbers and signed token asynchronously
     */
    public function refreshCaptcha(): \Illuminate\Http\JsonResponse
    {
        $num1 = rand(3, 9);
        $num2 = rand(2, 8);
        $token = hash_hmac('sha256', "{$num1}+{$num2}", config('app.key'));
        session(['lab_captcha_answer' => $num1 + $num2]);

        return response()->json([
            'num1' => $num1,
            'num2' => $num2,
            'token' => $token,
        ]);
    }

    public function store(Request $request)
    {
        $isBookingEnabled = SiteSetting::get('lab_booking_enabled', '1') === '1';
        if (!$isBookingEnabled) {
            $closedMessage = SiteSetting::get('lab_booking_closed_message', 'Mohon maaf, layanan pengajuan peminjaman laboratorium komputer sedang ditutup sementara.');
            return back()->withInput()->with('error', $closedMessage);
        }

        // 1. Honeypot check (anti-bot trap)
        if ($request->filled('_hp_website_title')) {
            return back()->withInput()->with('captcha_error', 'Permintaan Anda terdeteksi sebagai spam otomatis (bot).');
        }

        // 2. Anti-Bot Math Captcha verification
        $request->validate([
            'captcha_input' => 'required|integer',
        ], [
            'captcha_input.required' => 'Verifikasi keamanan (Anti-Bot) wajib diisi.',
            'captcha_input.integer' => 'Hasil verifikasi anti-bot harus berupa angka.',
        ]);

        $captchaInput = (int) $request->input('captcha_input');
        $num1 = (int) $request->input('captcha_num1');
        $num2 = (int) $request->input('captcha_num2');
        $token = (string) $request->input('captcha_token');

        $expectedSum = $num1 + $num2;
        $isValidToken = hash_equals(hash_hmac('sha256', "{$num1}+{$num2}", config('app.key')), $token);
        $isSessionValid = session()->has('lab_captcha_answer') && $captchaInput === (int) session('lab_captcha_answer');

        if ((!$isValidToken || $captchaInput !== $expectedSum) && !$isSessionValid) {
            return back()
                ->withInput()
                ->with('captcha_error', 'Hasil penjumlahan verifikasi keamanan (Anti-Bot) tidak tepat. Silakan hitung dan coba lagi.');
        }

        session()->forget('lab_captcha_answer');

        $validated = $request->validate([
            'laboratory_ids' => 'nullable|array',
            'laboratory_ids.*' => 'integer|exists:laboratories,id',
            'laboratory_id' => 'nullable|exists:laboratories,id',
            'borrower_name' => 'required|string|max:255',
            'borrower_type' => 'required|string|in:mahasiswa,dosen,tendik,unit_fakultas,instansi_luar',
            'institution_name' => 'required|string|max:255',
            'borrower_id_number' => 'nullable|string|max:50',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'event_name' => 'required|string|max:255',
            'event_description' => 'nullable|string|max:2000',
            'booking_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:booking_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'participant_count' => 'required|integer|min:1|max:2000',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'borrower_name.required' => 'Nama lengkap pemohon wajib diisi.',
            'email.required' => 'Email aktif wajib diisi untuk pengiriman bukti dan konfirmasi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi agar mudah dihubungi petugas lab.',
            'event_name.required' => 'Nama acara atau kegiatan wajib diisi.',
            'booking_date.required' => 'Pilih tanggal mulai peminjaman.',
            'booking_date.after_or_equal' => 'Tanggal mulai peminjaman minimal hari ini.',
            'end_date.after_or_equal' => 'Tanggal selesai peminjaman tidak boleh sebelum tanggal mulai.',
            'start_time.required' => 'Jam mulai wajib diisi.',
            'end_time.required' => 'Jam selesai wajib diisi.',
            'end_time.after' => 'Jam selesai harus lebih akhir dari jam mulai.',
            'participant_count.required' => 'Jumlah peserta estimasi wajib diisi.',
            'document.mimes' => 'Format berkas permohonan harus PDF, DOCX, atau Gambar (JPG/PNG).',
            'document.max' => 'Ukuran berkas permohonan maksimal 5MB.',
        ]);

        $rawLabIds = $request->input('laboratory_ids');
        if (empty($rawLabIds) && $request->filled('laboratory_id')) {
            $rawLabIds = [$request->input('laboratory_id')];
        }

        if (empty($rawLabIds)) {
            return back()->withInput()->with('error', 'Wajib memilih minimal satu laboratorium yang ingin dipinjam.');
        }

        $labs = Laboratory::whereIn('id', $rawLabIds)->get();
        if ($labs->isEmpty()) {
            return back()->withInput()->with('error', 'Laboratorium yang dipilih tidak valid.');
        }

        $endDate = $request->input('end_date') ?: $validated['booking_date'];

        $totalCapacity = $labs->sum('capacity');
        if ($validated['participant_count'] > ($totalCapacity + 30)) {
            $labNames = $labs->pluck('name')->join(', ');
            return back()->withInput()->with('error', "Total kapasitas laboratorium terpilih ({$labNames}) adalah {$totalCapacity} orang. Jumlah peserta yang diajukan ({$validated['participant_count']}) melebihi daya tampung ruangan.");
        }

        // Check if ANY of the selected labs has an overlapping booking
        foreach ($labs as $lab) {
            $isBooked = $lab->isSlotBooked($validated['booking_date'], $validated['start_time'], $validated['end_time'], null, $endDate);
            if ($isBooked) {
                return back()->withInput()->with('error', "Maaf, ruang {$lab->name} telah memiliki jadwal kegiatan lain yang disetujui pada rentang tanggal {$validated['booking_date']} s/d {$endDate} antara jam {$validated['start_time']} - {$validated['end_time']} WIB. Silakan sesuaikan jam atau pilih laboratorium lain.");
            }
        }

        // Handle document upload
        $documentPath = null;
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = 'surat_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $documentPath = $file->storeAs('lab_documents', $filename, 'public');
        }

        $datePrefix = date('Ymd');
        $randomSuffix = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        $groupCode = "LAB-{$datePrefix}-{$randomSuffix}";

        \Illuminate\Support\Facades\DB::transaction(function () use ($labs, $groupCode, $validated, $endDate, $documentPath) {
            $isMulti = $labs->count() > 1;
            $index = 1;
            foreach ($labs as $lab) {
                $code = $isMulti ? "{$groupCode}-{$index}" : $groupCode;
                LabBooking::create([
                    'booking_code' => $code,
                    'booking_group_code' => $groupCode,
                    'laboratory_id' => $lab->id,
                    'borrower_name' => $validated['borrower_name'],
                    'borrower_type' => $validated['borrower_type'],
                    'institution_name' => $validated['institution_name'],
                    'borrower_id_number' => $validated['borrower_id_number'] ?? null,
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'event_name' => $validated['event_name'],
                    'event_description' => $validated['event_description'] ?? null,
                    'booking_date' => $validated['booking_date'],
                    'end_date' => $endDate,
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'participant_count' => $validated['participant_count'],
                    'document_path' => $documentPath,
                    'status' => 'pending',
                ]);
                $index++;
            }
        });

        $labNamesString = $labs->pluck('name')->join(' & ');
        if ($labs->count() > 1) {
            $labNamesString .= " ({$labs->count()} Ruang Lab)";
        }

        $startDateFormatted = Carbon::parse($validated['booking_date'])->translatedFormat('d M Y');
        $endDateFormatted = Carbon::parse($endDate)->translatedFormat('d M Y');
        $dateText = ($startDateFormatted === $endDateFormatted) 
            ? Carbon::parse($validated['booking_date'])->translatedFormat('l, d F Y')
            : "{$startDateFormatted} s/d {$endDateFormatted}";

        return redirect()->route('lab-schedule.index', ['lab' => 'all'])
            ->with('booking_success', [
                'code' => $groupCode,
                'lab_name' => $labNamesString,
                'date' => $dateText,
                'time' => substr($validated['start_time'], 0, 5) . ' - ' . substr($validated['end_time'], 0, 5) . ' WIB',
                'borrower' => $validated['borrower_name'],
            ]);
    }

    public function checkStatus(Request $request)
    {
        $code = trim((string)$request->input('code'));
        if (empty($code)) {
            return response()->json(['success' => false, 'message' => 'Masukkan kode reservasi tiket lab Anda.'], 400);
        }

        $booking = LabBooking::with('laboratory')
            ->where('booking_code', $code)
            ->orWhere('booking_group_code', $code)
            ->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Kode permohonan tiket lab tidak ditemukan. Pastikan format penulisan sesuai (contoh: LAB-202610-0001).'], 404);
        }

        $allGroupBookings = LabBooking::with('laboratory')
            ->where('booking_group_code', $booking->booking_group_code)
            ->get();

        $labNameText = $allGroupBookings->pluck('laboratory.name')->filter()->join(' & ');
        if ($allGroupBookings->count() > 1) {
            $labNameText .= " (" . $allGroupBookings->count() . " Ruang Lab)";
        }

        return response()->json([
            'success' => true,
            'booking' => [
                'code' => $booking->booking_group_code ?: $booking->booking_code,
                'lab_name' => $labNameText ?: ($booking->laboratory->name ?? '-'),
                'borrower_name' => $booking->borrower_name,
                'institution' => $booking->institution_name,
                'event_name' => $booking->event_name,
                'date' => $booking->formatted_date_range . ($booking->duration_days > 1 ? " ({$booking->duration_days} Hari)" : ''),
                'time' => $booking->formatted_time_range,
                'status' => $booking->status,
                'status_label' => $booking->status_label,
                'status_class' => $booking->status_badge_class,
                'admin_notes' => $booking->admin_notes,
                'created_at' => $booking->created_at->format('d M Y, H:i') . ' WIB',
            ],
        ]);
    }
}
