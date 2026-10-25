<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\LabBookingStatusMail;
use App\Models\CompanyProfile;
use App\Models\Laboratory;
use App\Models\LabBooking;
use App\Models\SiteSetting;
use App\Services\GmailApiService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LabBookingController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status', 'all');
        $labId = $request->input('lab_id');
        $search = $request->input('search');
        $perPage = (int)$request->input('per_page', 10);

        $query = LabBooking::with(['laboratory', 'approver'])
            ->latest('booking_date')
            ->latest('start_time');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($labId) {
            $query->where('laboratory_id', $labId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('borrower_name', 'like', "%{$search}%")
                  ->orWhere('institution_name', 'like', "%{$search}%")
                  ->orWhere('event_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate($perPage)->withQueryString();
        $laboratories = Laboratory::orderBy('name')->get();

        // Counts for tabs
        $counts = [
            'all' => LabBooking::count(),
            'pending' => LabBooking::where('status', 'pending')->count(),
            'approved' => LabBooking::where('status', 'approved')->count(),
            'rejected' => LabBooking::where('status', 'rejected')->count(),
            'completed' => LabBooking::where('status', 'completed')->count(),
        ];

        $labBookingEnabled = SiteSetting::get('lab_booking_enabled', '1') === '1';
        $labBookingClosedMessage = SiteSetting::get('lab_booking_closed_message', 'Mohon maaf, layanan pengajuan peminjaman laboratorium komputer sedang ditutup sementara. Silakan hubungi pengelola UPA TIK untuk informasi lebih lanjut.');

        return view('admin.lab_bookings.index', compact('bookings', 'laboratories', 'counts', 'status', 'labId', 'search', 'perPage', 'labBookingEnabled', 'labBookingClosedMessage'));
    }

    public function toggleService(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'is_enabled' => 'required|in:0,1',
            'closed_message' => 'nullable|string|max:500',
        ]);

        SiteSetting::setKey('lab_booking_enabled', $validated['is_enabled'], 'facility');
        
        if ($request->filled('closed_message')) {
            SiteSetting::setKey('lab_booking_closed_message', $validated['closed_message'], 'facility');
        }

        $statusText = $validated['is_enabled'] === '1' ? 'DIBUKA / AKTIF' : 'DITUTUP SEMENTARA';
        return back()->with('success', "Status layanan peminjaman lab online berhasil diubah: {$statusText}.");
    }

    public function create(): View
    {
        $laboratories = Laboratory::where('is_active', true)->orderBy('order_index')->get();
        return view('admin.lab_bookings.create', compact('laboratories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'borrower_type' => 'required|in:agenda_universitas,praktikum,workshop_internal,maintenance,unit_fakultas,umum',
            'lab_selection' => 'required|string',
            'event_name' => 'required|string|max:255',
            'institution_name' => 'required|string|max:255',
            'borrower_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'schedule_type' => 'required|in:dates,recurring',
            'booking_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:booking_date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'recurring_day' => 'nullable|integer|between:0,6',
            'participant_count' => 'nullable|integer|min:0',
            'event_description' => 'nullable|string|max:2000',
            'admin_notes' => 'nullable|string|max:1000',
            'force_override' => 'nullable|boolean',
        ], [
            'borrower_type.required' => 'Pilih kategori agenda lab.',
            'lab_selection.required' => 'Pilih laboratorium tujuan.',
            'event_name.required' => 'Nama kegiatan wajib diisi.',
            'institution_name.required' => 'Unit / penyelenggara wajib diisi.',
            'borrower_name.required' => 'Nama PIC / penanggung jawab wajib diisi.',
            'booking_date.required' => 'Tanggal pelaksanaan wajib diisi.',
            'start_time.required' => 'Jam mulai wajib diisi.',
            'end_time.required' => 'Jam selesai wajib diisi.',
        ]);

        // Resolve target laboratories
        if ($validated['lab_selection'] === 'all') {
            $targetLabs = Laboratory::where('is_active', true)->get();
        } else {
            $targetLabs = Laboratory::where('id', (int)$validated['lab_selection'])->get();
        }

        if ($targetLabs->isEmpty()) {
            return back()->withInput()->with('error', 'Laboratorium yang dipilih tidak ditemukan.');
        }

        // Build schedule slots (either single/multi-day date range OR recurring weekly sessions)
        $scheduleSlots = [];
        $startTime = substr($validated['start_time'], 0, 5);
        $endTime = substr($validated['end_time'], 0, 5);

        if ($validated['schedule_type'] === 'recurring') {
            $startDate = Carbon::parse($validated['booking_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'] ?: $validated['booking_date'])->startOfDay();
            $recurringDay = isset($validated['recurring_day']) ? (int)$validated['recurring_day'] : $startDate->dayOfWeek;

            $cursor = $startDate->copy();
            while ($cursor->lte($endDate)) {
                if ($cursor->dayOfWeek === $recurringDay) {
                    $scheduleSlots[] = [
                        'start_date' => $cursor->format('Y-m-d'),
                        'end_date' => $cursor->format('Y-m-d'),
                    ];
                }
                $cursor->addDay();
            }

            if (empty($scheduleSlots)) {
                return back()->withInput()->with('error', 'Tidak ada tanggal yang cocok untuk hari yang dipilih pada rentang tersebut.');
            }
        } else {
            $scheduleSlots[] = [
                'start_date' => Carbon::parse($validated['booking_date'])->format('Y-m-d'),
                'end_date' => Carbon::parse($validated['end_date'] ?: $validated['booking_date'])->format('Y-m-d'),
            ];
        }

        // Conflict check (unless force_override is checked)
        if (!$request->boolean('force_override')) {
            foreach ($targetLabs as $lab) {
                foreach ($scheduleSlots as $slot) {
                    if ($lab->isSlotBooked($slot['start_date'], $startTime, $endTime, null, $slot['end_date'])) {
                        return back()->withInput()->with('error', "Konflik Jadwal: Ruang {$lab->name} sudah memiliki jadwal yang disetujui pada tanggal {$slot['start_date']} s/d {$slot['end_date']} ({$startTime} - {$endTime} WIB). Centang 'Abaikan Peringatan Bentrok' jika Anda ingin tetap menyimpannya.");
                    }
                }
            }
        }

        // Generate Master Booking Group Code
        $datePrefix = date('Ymd');
        $randomSuffix = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        $masterGroupCode = "LAB-{$datePrefix}-{$randomSuffix}";

        $totalInserted = 0;
        foreach ($scheduleSlots as $slot) {
            foreach ($targetLabs as $lab) {
                $uniqueCode = "LAB-{$datePrefix}-" . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
                LabBooking::create([
                    'booking_code' => $uniqueCode,
                    'booking_group_code' => $masterGroupCode,
                    'laboratory_id' => $lab->id,
                    'borrower_name' => $validated['borrower_name'],
                    'borrower_type' => $validated['borrower_type'],
                    'institution_name' => $validated['institution_name'],
                    'borrower_id_number' => $request->input('borrower_id_number') ?: 'INTERNAL',
                    'email' => ($validated['email'] ?? null) ?: (Auth::user()->email ?? 'admin@untan.ac.id'),
                    'phone' => ($validated['phone'] ?? null) ?: '-',
                    'event_name' => $validated['event_name'],
                    'event_description' => $validated['event_description'] ?? null,
                    'booking_date' => $slot['start_date'],
                    'end_date' => $slot['end_date'],
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'participant_count' => ($validated['participant_count'] ?? null) ?: $lab->capacity,
                    'status' => 'approved',
                    'admin_notes' => ($validated['admin_notes'] ?? null) ?: 'Diinput langsung oleh Administrator untuk Agenda Resmi Lab.',
                    'approved_at' => now(),
                    'approved_by' => Auth::id(),
                ]);
                $totalInserted++;
            }
        }

        $labCountText = $targetLabs->count() > 1 ? "({$targetLabs->count()} Ruang Lab)" : $targetLabs->first()->name;
        return redirect()->route('admin.lab-bookings.index')
            ->with('success', "Agenda Lab berhasil ditambahkan ({$totalInserted} jadwal aktif pada {$labCountText}) dengan kode tiket {$masterGroupCode}.");
    }

    public function show(LabBooking $booking): View
    {
        $booking->load(['laboratory', 'approver']);

        $startDate = $booking->booking_date->format('Y-m-d');
        $endDate = ($booking->end_date ?: $booking->booking_date)->format('Y-m-d');

        // Check if there are other conflicting bookings on the same lab and overlapping date range
        $conflicts = LabBooking::where('laboratory_id', $booking->laboratory_id)
            ->where('id', '!=', $booking->id)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where('booking_date', '<=', $endDate)
                  ->whereRaw('COALESCE(end_date, booking_date) >= ?', [$startDate]);
            })
            ->whereIn('status', ['approved', 'pending'])
            ->get();

        return view('admin.lab_bookings.show', compact('booking', 'conflicts'));
    }

    public function approve(Request $request, LabBooking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $groupCode = $booking->booking_group_code ?: $booking->booking_code;
        $groupBookings = LabBooking::with('laboratory')->where('booking_group_code', $groupCode)->get();
        if ($groupBookings->isEmpty()) {
            $groupBookings = collect([$booking]);
        }

        // Check overlapping approved booking for each lab in the group
        foreach ($groupBookings as $gb) {
            $gbEndDate = $gb->end_date ? $gb->end_date->format('Y-m-d') : $gb->booking_date->format('Y-m-d');
            $isOverlap = $gb->laboratory->isSlotBooked(
                $gb->booking_date->format('Y-m-d'),
                $gb->start_time,
                $gb->end_time,
                $gb->id,
                $gbEndDate
            );

            if ($isOverlap) {
                return back()->with('error', "Gagal menyetujui: Terdapat jadwal lain yang sudah disetujui pada rentang tanggal/jam tersebut di ruang {$gb->laboratory->name}. Silakan periksa tab konflik jadwal.");
            }
        }

        foreach ($groupBookings as $gb) {
            $gb->update([
                'status' => 'approved',
                'admin_notes' => $validated['admin_notes'] ?? 'Permohonan peminjaman lab telah disetujui.',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);
        }

        // Send email notification to borrower if external booking
        $emailSent = false;
        if (!empty($booking->email) && !$booking->is_internal_agenda) {
            $emailSent = $this->sendBookingStatusEmail(
                $booking,
                'approved',
                $validated['admin_notes'] ?? 'Permohonan peminjaman lab telah disetujui. Silakan gunakan ruangan sesuai jadwal.',
                $groupBookings
            );
        }

        $msg = $groupBookings->count() > 1
            ? "Seluruh permohonan ({$groupBookings->count()} Ruang Lab: {$labNames}) dengan kode {$groupCode} berhasil DISETUJUI."
            : "Permohonan tiket {$booking->booking_code} berhasil DISETUJUI.";

        if ($emailSent) {
            $msg .= " Email notifikasi resmi telah otomatis dikirim ke pemohon ({$booking->email}).";
        } elseif (!empty($booking->email) && !$booking->is_internal_agenda) {
            $msg .= " (Catatan: Pengiriman email notifikasi tidak terkirim atau gagal terkoneksi).";
        }

        return redirect()->route('admin.lab-bookings.show', $booking->id)
            ->with('success', $msg);
    }

    public function reject(Request $request, LabBooking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ], [
            'admin_notes.required' => 'Wajib menyertakan alasan penolakan permohonan.',
        ]);

        $groupCode = $booking->booking_group_code ?: $booking->booking_code;
        $groupBookings = LabBooking::with('laboratory')->where('booking_group_code', $groupCode)->get();
        if ($groupBookings->isEmpty()) {
            $groupBookings = collect([$booking]);
        }

        foreach ($groupBookings as $gb) {
            $gb->update([
                'status' => 'rejected',
                'admin_notes' => $validated['admin_notes'],
                'approved_at' => null,
                'approved_by' => Auth::id(),
            ]);
        }

        // Send email notification to borrower if external booking
        $emailSent = false;
        if (!empty($booking->email) && !$booking->is_internal_agenda) {
            $emailSent = $this->sendBookingStatusEmail(
                $booking,
                'rejected',
                $validated['admin_notes'],
                $groupBookings
            );
        }

        $msg = $groupBookings->count() > 1
            ? "Seluruh permohonan ({$groupBookings->count()} Ruang Lab) dengan kode {$groupCode} telah DITOLAK."
            : "Permohonan tiket {$booking->booking_code} telah DITOLAK.";

        if ($emailSent) {
            $msg .= " Email pemberitahuan penolakan telah otomatis dikirim ke pemohon ({$booking->email}).";
        } elseif (!empty($booking->email) && !$booking->is_internal_agenda) {
            $msg .= " (Catatan: Pengiriman email penolakan tidak terkirim atau gagal terkoneksi).";
        }

        return redirect()->route('admin.lab-bookings.show', $booking->id)
            ->with('success', $msg);
    }

    public function complete(LabBooking $booking): RedirectResponse
    {
        $groupCode = $booking->booking_group_code ?: $booking->booking_code;
        LabBooking::where('booking_group_code', $groupCode)->update([
            'status' => 'completed',
        ]);

        return back()->with('success', "Status kegiatan peminjaman {$booking->booking_code} telah ditandai SELESAI.");
    }

    public function destroy(LabBooking $booking): RedirectResponse
    {
        $groupCode = $booking->booking_group_code ?: $booking->booking_code;
        $allBookings = LabBooking::where('booking_group_code', $groupCode)->get();

        if ($booking->document_path) {
            Storage::disk('public')->delete($booking->document_path);
        }

        foreach ($allBookings as $b) {
            $b->delete();
        }

        return redirect()->route('admin.lab-bookings.index')
            ->with('success', 'Data reservasi lab berhasil dihapus.');
    }

    /**
     * Send automated status notification email to borrower (Gmail API or standard Mailer)
     */
    protected function sendBookingStatusEmail(LabBooking $booking, string $statusType, ?string $adminNotes, $groupBookings = null): bool
    {
        if (empty($booking->email)) {
            return false;
        }

        try {
            $profile = CompanyProfile::first();
            if ($groupBookings && $groupBookings->count() > 0) {
                $labNames = $groupBookings->pluck('laboratory.name')->unique()->join(', ');
            } else {
                $labNames = $booking->laboratory->name ?? 'Laboratorium Komputer';
            }

            $statusPrefix = $statusType === 'approved' ? '[DISETUJUI]' : '[DITOLAK]';
            $subject = "{$statusPrefix} Status Permohonan Peminjaman Laboratorium Komputer - {$booking->booking_code}";

            $mailerDriver = config('mail.default');

            if ($mailerDriver === 'gmail_api' || config('services.gmail.refresh_token') || env('GMAIL_REFRESH_TOKEN')) {
                // Send via Google Gmail API (HTTPS REST endpoint)
                $gmailApi = new GmailApiService();
                $htmlContent = view('emails.lab_booking_status', [
                    'booking' => $booking,
                    'statusType' => $statusType,
                    'adminNotes' => $adminNotes,
                    'labNames' => $labNames,
                    'profile' => $profile,
                ])->render();

                $gmailApi->sendEmail($booking->email, $subject, $htmlContent);
            } else {
                // Send via standard Laravel Mailer / SMTP
                Mail::to($booking->email)->send(new LabBookingStatusMail(
                    $booking,
                    $statusType,
                    $adminNotes,
                    $labNames
                ));
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim email notifikasi peminjaman lab [{$booking->booking_code}]: " . $e->getMessage());
            return false;
        }
    }
}
