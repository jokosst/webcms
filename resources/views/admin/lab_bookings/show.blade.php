@extends('layouts.admin')

@section('title', 'Detail Permohonan Peminjaman ' . $booking->booking_code)
@section('header_title', 'Verifikasi Permohonan Lab #' . $booking->booking_code)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Top Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.lab-bookings.index') }}" class="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-slate-800 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-mono text-cyan-400 font-extrabold text-base">{{ $booking->booking_code }}</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $booking->status_badge_class }}">
                        {{ $booking->status_label }}
                    </span>
                </div>
                <p class="text-[11px] text-slate-400">Diajukan pada: {{ $booking->created_at->translatedFormat('l, d F Y - H:i') }} WIB</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->phone) }}" target="_blank"
               class="px-3.5 py-2 rounded-xl bg-emerald-600/20 text-emerald-400 hover:bg-emerald-600 hover:text-white border border-emerald-500/30 text-xs font-bold transition-all flex items-center gap-1.5">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <span>Hubungi via WhatsApp</span>
            </a>
        </div>
    </div>

    <!-- 2 Columns Grid: Detail Info & Verification Action Box -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Booking & Borrower Details -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Detail Kegiatan & Waktu -->
            <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg text-xs">
                <div class="flex items-center gap-2.5 border-b border-slate-800 pb-3 text-cyan-400">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <h3 class="text-sm font-bold text-white font-heading">Informasi Agenda & Waktu</h3>
                </div>

                <div class="space-y-3">
                    <div>
                        <span class="text-slate-500 block text-[11px] uppercase tracking-wider">Nama Kegiatan / Acara:</span>
                        <h4 class="text-base font-bold text-white mt-0.5 leading-snug">{{ $booking->event_name }}</h4>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-3.5 rounded-xl bg-slate-800/60 border border-slate-700/80">
                        <div>
                            <span class="text-slate-500 block text-[11px] uppercase tracking-wider">Ruang Laboratorium:</span>
                            @if($booking->is_multi_lab)
                                <div class="mt-1 space-y-1.5">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        <i data-lucide="layers" class="w-3 h-3"></i>
                                        <span>Multi-Lab ({{ $booking->group_laboratories->count() }} Ruangan)</span>
                                    </span>
                                    <div class="space-y-1 pt-1">
                                        @foreach($booking->group_laboratories as $glab)
                                            <div class="text-xs font-bold text-white flex items-center justify-between border-b border-slate-800/60 pb-0.5">
                                                <span>{{ $glab->name }}</span>
                                                <span class="text-cyan-400 font-mono text-[11px]">{{ $glab->capacity }} PC</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <span class="font-bold text-cyan-400 text-sm">{{ $booking->laboratory->name ?? '-' }}</span>
                                <span class="text-[11px] text-slate-400 block">{{ $booking->laboratory->room_number ?? 'Gedung TIK' }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[11px] uppercase tracking-wider">Jumlah Peserta Estimasi:</span>
                            <span class="font-bold text-white text-sm">{{ $booking->participant_count }} Orang</span>
                            <span class="text-[11px] text-slate-400 block mt-0.5">
                                @if($booking->is_multi_lab)
                                    Total Kapasitas Gabungan: <strong class="text-cyan-400">{{ $booking->group_laboratories->sum('capacity') }} PC</strong>
                                @else
                                    Kapasitas Maksimal: {{ $booking->laboratory->capacity ?? 0 }} PC
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-slate-500 block text-[11px] uppercase tracking-wider">Tanggal Pelaksanaan:</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="font-bold text-white text-sm">{{ $booking->formatted_date_range }}</span>
                                @if($booking->duration_days > 1)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        {{ $booking->duration_days }} Hari
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[11px] uppercase tracking-wider">Jam Pelaksanaan:</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="font-bold text-white text-sm">{{ $booking->formatted_time_range }}</span>
                                @if($booking->duration_days > 1)
                                    <span class="text-[10px] text-slate-400">/ hari</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($booking->event_description)
                        <div class="pt-2 border-t border-slate-800">
                            <span class="text-slate-500 block text-[11px] uppercase tracking-wider">Kebutuhan Tambahan / Keterangan:</span>
                            <p class="text-slate-300 mt-1 whitespace-pre-line leading-relaxed">{{ $booking->event_description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detail Pemohon -->
            <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg text-xs">
                <div class="flex items-center gap-2.5 border-b border-slate-800 pb-3 text-indigo-400">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                    <h3 class="text-sm font-bold text-white font-heading">Identitas & Kontak Pemohon</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-slate-500 block text-[11px] uppercase tracking-wider">Nama Pemohon:</span>
                        <span class="font-bold text-white text-sm">{{ $booking->borrower_name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[11px] uppercase tracking-wider">Kategori Pemohon:</span>
                        <span class="font-bold text-indigo-400">{{ $booking->borrower_type_label }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[11px] uppercase tracking-wider">Fakultas / Unit / Instansi:</span>
                        <span class="font-semibold text-slate-200">{{ $booking->institution_name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[11px] uppercase tracking-wider">NIM / NIP / NIDN:</span>
                        <span class="font-mono text-slate-300">{{ $booking->borrower_id_number ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[11px] uppercase tracking-wider">Email Pemohon:</span>
                        <a href="mailto:{{ $booking->email }}" class="text-cyan-400 hover:underline font-mono">{{ $booking->email }}</a>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[11px] uppercase tracking-wider">Nomor WhatsApp:</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->phone) }}" target="_blank" class="text-emerald-400 hover:underline font-mono">{{ $booking->phone }}</a>
                    </div>
                </div>
            </div>

            <!-- Dokumen Lampiran Surat Permohonan -->
            <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-3 shadow-lg text-xs">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2 text-amber-400">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <h3 class="text-sm font-bold text-white font-heading">Berkas Surat Permohonan Resmi</h3>
                    </div>
                </div>

                @if($booking->document_path)
                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-950 border border-slate-800">
                        <div class="flex items-center gap-3">
                            <i data-lucide="file-check-2" class="w-6 h-6 text-cyan-400"></i>
                            <div>
                                <span class="font-bold text-white text-xs block">Surat Pengantar / Proposal</span>
                                <span class="text-[10px] text-slate-500 font-mono">{{ basename($booking->document_path) }}</span>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $booking->document_path) }}" target="_blank"
                           class="px-4 py-2 rounded-xl bg-indigo-600/20 text-indigo-400 hover:bg-indigo-600 hover:text-white font-bold text-xs transition-colors flex items-center gap-1.5">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                            <span>Unduh Berkas</span>
                        </a>
                    </div>
                @else
                    <p class="text-slate-500 italic">Pemohon tidak melampirkan berkas dokumen digital saat mengajukan formulir.</p>
                @endif
            </div>

            <!-- Catatan Admin & Log Approval -->
            @if($booking->admin_notes || $booking->approved_at)
                <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-2 shadow-lg text-xs">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Riwayat Verifikasi Petugas TIK:</span>
                    <p class="text-white italic bg-slate-950 p-3 rounded-xl border border-slate-800">
                        "{{ $booking->admin_notes ?? 'Tidak ada catatan tambahan.' }}"
                    </p>
                    @if($booking->approved_at)
                        <p class="text-[10px] text-slate-500">
                            Diproses oleh: <strong>{{ $booking->approver->name ?? 'Petugas TIK' }}</strong> pada {{ $booking->approved_at->translatedFormat('d M Y, H:i') }} WIB
                        </p>
                    @endif
                </div>
            @endif

        </div>

        <!-- Right: Action Box & Conflict Checker -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- VERIFICATION ACTION BOX -->
            <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-5 shadow-xl text-xs">
                <div class="flex items-center gap-2.5 border-b border-slate-800 pb-3 text-cyan-400">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    <h3 class="text-sm font-bold text-white font-heading">Tindakan Verifikasi Petugas</h3>
                </div>

                @if($booking->status === 'pending')
                    <div class="space-y-4">
                        <p class="text-slate-300">
                            Pastikan surat pengantar sah dan jadwal tidak tumpang tindih sebelum menyetujui permohonan.
                        </p>

                        @if(!empty($booking->email) && !$booking->is_internal_agenda)
                            <div class="p-3 rounded-xl bg-cyan-500/10 border border-cyan-500/25 text-cyan-300 text-xs flex items-center gap-2.5">
                                <i data-lucide="mail-check" class="w-4 h-4 text-cyan-400 shrink-0"></i>
                                <span>Email notifikasi resmi akan otomatis dikirimkan ke: <strong class="text-white underline">{{ $booking->email }}</strong> saat Anda menyetujui atau menolak tiket ini.</span>
                            </div>
                        @endif

                        <!-- APPROVE FORM -->
                        <form method="POST" action="{{ route('admin.lab-bookings.approve', $booking->id) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                                    Catatan Persetujuan (Opsional)
                                </label>
                                <textarea name="admin_notes" rows="2.5" placeholder="Contoh: Disetujui. Didampingi teknisi Pak Budi."
                                          class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500/30 text-xs transition-all">Disetujui. Silakan gunakan ruangan sesuai jadwal.</textarea>
                            </div>
                            @if($booking->is_multi_lab)
                                <div class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/25 text-amber-300 text-[11px] flex items-start gap-2">
                                    <i data-lucide="info" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5"></i>
                                    <span>Persetujuan ini akan otomatis menyetujui seluruh <strong>{{ $booking->group_laboratories->count() }} ruangan laboratorium</strong> yang diajukan dalam paket ini.</span>
                                </div>
                            @endif
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MENYETUJUI jadwal permohonan lab ini? Slot akan dikunci di kalender publik.')"
                                    class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                <span>SETUJUI PERMOHONAN (APPROVE)</span>
                            </button>
                        </form>

                        <div class="relative py-2">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-800"></div></div>
                            <div class="relative flex justify-center text-[10px] uppercase"><span class="bg-slate-900 px-2 text-slate-500 font-bold">atau</span></div>
                        </div>

                        <!-- REJECT FORM -->
                        <form method="POST" action="{{ route('admin.lab-bookings.reject', $booking->id) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                                    Alasan Penolakan <span class="text-rose-400">*</span>
                                </label>
                                <textarea name="admin_notes" rows="2.5" required placeholder="Contoh: Jadwal bentrok dengan ujian praktikum fakultas / perbaikan jaringan."
                                          class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500/30 text-xs transition-all"></textarea>
                            </div>
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MENOLAK permohonan ini? Alasan penolakan akan dikirimkan ke pemohon.')"
                                    class="w-full py-2.5 bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/30 font-bold text-xs rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                <span>TOLAK PERMOHONAN (REJECT)</span>
                            </button>
                        </form>
                    </div>

                @elseif($booking->status === 'approved')
                    <div class="space-y-4">
                        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 space-y-1">
                            <span class="font-bold flex items-center gap-1.5">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
                                <span>Permohonan Telah Disetujui</span>
                            </span>
                            <p class="text-[11px] text-slate-400">Jadwal ini aktif dan tampil sebagai slot terpakai pada kalender publik.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.lab-bookings.complete', $booking->id) }}">
                            @csrf
                            <button type="submit" onclick="return confirm('Tandai kegiatan ini telah selesai dilaksanakan?')"
                                    class="w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-2 cursor-pointer">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                <span>Tandai Acara Selesai (Completed)</span>
                            </button>
                        </form>
                    </div>

                @elseif($booking->status === 'rejected')
                    <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 space-y-1">
                        <span class="font-bold flex items-center gap-1.5">
                            <i data-lucide="x-circle" class="w-4 h-4 text-rose-400"></i>
                            <span>Permohonan Ditolak</span>
                        </span>
                        <p class="text-[11px] text-slate-400">Alasan: {{ $booking->admin_notes }}</p>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-blue-500/10 border border-blue-500/30 text-blue-300">
                        <span class="font-bold block">Kegiatan Telah Selesai</span>
                        <p class="text-[11px] text-slate-400">Arsip riwayat penggunaan fasilitas laboratorium TIK.</p>
                    </div>
                @endif
            </div>

            <!-- CONFLICT CHECKER (AGENDA LAIN PADA TANGGAL YANG SAMA) -->
            <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-3 shadow-lg text-xs">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <span class="font-bold text-white text-xs uppercase tracking-wider flex items-center gap-1.5">
                        <i data-lucide="clock" class="w-3.5 h-3.5 text-cyan-400"></i>
                        <span>Agenda Lain di Rentang Tanggal</span>
                    </span>
                    <span class="text-[10px] text-slate-400 font-mono">{{ $booking->formatted_date_range }}</span>
                </div>

                @if($conflicts->isEmpty())
                    <div class="p-3.5 text-center rounded-xl bg-slate-800/60 border border-slate-700/80 text-slate-400">
                        <span class="text-emerald-400 font-bold block mb-0.5">✓ Bebas Konflik</span>
                        Tidak ada agenda lain yang terdaftar di {{ $booking->laboratory->name ?? 'lab ini' }} pada rentang tanggal ini.
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($conflicts as $c)
                            <div class="p-3.5 rounded-xl bg-slate-800/80 border border-slate-700 space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-cyan-400 font-mono">{{ $c->formatted_time_range }}</span>
                                    <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase {{ $c->status_badge_class }}">
                                        {{ $c->status_label }}
                                    </span>
                                </div>
                                <div class="text-[10px] text-slate-400 flex items-center gap-1">
                                    <i data-lucide="calendar" class="w-3 h-3 text-slate-500"></i>
                                    <span>{{ $c->formatted_date_range }}</span>
                                </div>
                                <p class="text-white font-semibold truncate">{{ $c->event_name }}</p>
                                <span class="text-[10px] text-slate-400 block">{{ $c->borrower_name }} ({{ $c->institution_name }})</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
