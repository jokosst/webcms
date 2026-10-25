@extends('layouts.admin')

@section('title', 'Tambah Agenda Lab')
@section('header_title', 'Penjadwalan Agenda Resmi Laboratorium')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" 
     x-data="{
        labSelection: '{{ old('lab_selection', 'all') }}',
        scheduleType: '{{ old('schedule_type', 'dates') }}',
        category: '{{ old('borrower_type', 'agenda_universitas') }}',
        isFullDay: {{ old('is_full_day') ? 'true' : 'false' }},
        startTime: '{{ old('start_time', '08:00') }}',
        endTime: '{{ old('end_time', '16:00') }}',
        toggleFullDay() {
            if (this.isFullDay) {
                this.startTime = '07:30';
                this.endTime = '17:00';
            }
        }
     }">

    <!-- Page Header & Back Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.lab-bookings.index') }}" class="hover:text-white transition-colors">Permohonan Lab</a>
                <span>/</span>
                <span class="text-indigo-400 font-semibold">Tambah Agenda Lab</span>
            </div>
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <i data-lucide="calendar-plus" class="w-6 h-6 text-cyan-400"></i>
                <span>Tambah Agenda Laboratorium</span>
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Input jadwal resmi universitas, ujian UTBK/CAT, praktikum rutin semester, atau pemeliharaan lab langsung ke kalender.</p>
        </div>
        <a href="{{ route('admin.lab-bookings.index') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700 flex items-center gap-1.5 transition-colors shrink-0">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs space-y-1 shadow-lg">
            <span class="font-bold flex items-center gap-1.5">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                <span>Terdapat kesalahan pada pengisian data:</span>
            </span>
            <ul class="list-disc list-inside space-y-0.5 pl-5 text-[11px]">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.lab-bookings.store') }}" class="space-y-6">
        @csrf

        <!-- SECTION 1: KATEGORI & RUANG LAB -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-7 space-y-5 shadow-xl text-xs text-slate-300">
            <div class="border-b border-slate-800 pb-3 flex items-center gap-2">
                <span class="w-6 h-6 rounded-lg bg-cyan-500/20 text-cyan-400 font-black text-xs flex items-center justify-center border border-cyan-500/30">1</span>
                <div>
                    <h3 class="text-sm font-bold text-white">Kategori & Pilihan Laboratorium</h3>
                    <p class="text-[11px] text-slate-400">Tentukan jenis agenda dan ruang laboratorium yang akan digunakan.</p>
                </div>
            </div>

            <!-- Kategori Agenda -->
            <div>
                <label class="block font-bold text-white mb-2 uppercase tracking-wider text-[11px]">
                    Kategori Agenda / Jenis Pemakaian <span class="text-rose-400">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                    <!-- Option 1: Agenda Universitas -->
                    <label class="p-3.5 rounded-xl border cursor-pointer transition-all flex flex-col justify-between"
                           :class="category === 'agenda_universitas' ? 'bg-purple-500/15 border-purple-500 text-white shadow-md' : 'bg-slate-800/60 border-slate-700/80 text-slate-400 hover:border-slate-600'">
                        <div class="flex items-start justify-between">
                            <span class="font-bold text-xs" :class="category === 'agenda_universitas' ? 'text-purple-300' : 'text-slate-300'">Ujian / Agenda Kampus</span>
                            <input type="radio" name="borrower_type" value="agenda_universitas" x-model="category" class="text-purple-600 focus:ring-0">
                        </div>
                        <p class="text-[10px] mt-1 text-slate-400">UTBK-SNBT, Ujian Mandiri, CAT BKN/CPNS, Uji Kompetensi</p>
                    </label>

                    <!-- Option 2: Praktikum Rutin -->
                    <label class="p-3.5 rounded-xl border cursor-pointer transition-all flex flex-col justify-between"
                           :class="category === 'praktikum' ? 'bg-blue-500/15 border-blue-500 text-white shadow-md' : 'bg-slate-800/60 border-slate-700/80 text-slate-400 hover:border-slate-600'">
                        <div class="flex items-start justify-between">
                            <span class="font-bold text-xs" :class="category === 'praktikum' ? 'text-blue-300' : 'text-slate-300'">Praktikum / Perkuliahan</span>
                            <input type="radio" name="borrower_type" value="praktikum" x-model="category" class="text-blue-600 focus:ring-0">
                        </div>
                        <p class="text-[10px] mt-1 text-slate-400">Jadwal praktikum semesteran fakultas/program studi</p>
                    </label>

                    <!-- Option 3: Workshop Internal -->
                    <label class="p-3.5 rounded-xl border cursor-pointer transition-all flex flex-col justify-between"
                           :class="category === 'workshop_internal' ? 'bg-cyan-500/15 border-cyan-500 text-white shadow-md' : 'bg-slate-800/60 border-slate-700/80 text-slate-400 hover:border-slate-600'">
                        <div class="flex items-start justify-between">
                            <span class="font-bold text-xs" :class="category === 'workshop_internal' ? 'text-cyan-300' : 'text-slate-300'">Workshop / Pelatihan</span>
                            <input type="radio" name="borrower_type" value="workshop_internal" x-model="category" class="text-cyan-600 focus:ring-0">
                        </div>
                        <p class="text-[10px] mt-1 text-slate-400">Pelatihan dosen, tenaga kependidikan, atau bimtek unit</p>
                    </label>

                    <!-- Option 4: Maintenance -->
                    <label class="p-3.5 rounded-xl border cursor-pointer transition-all flex flex-col justify-between"
                           :class="category === 'maintenance' ? 'bg-rose-500/15 border-rose-500 text-white shadow-md' : 'bg-slate-800/60 border-slate-700/80 text-slate-400 hover:border-slate-600'">
                        <div class="flex items-start justify-between">
                            <span class="font-bold text-xs" :class="category === 'maintenance' ? 'text-rose-300' : 'text-slate-300'">Maintenance Lab</span>
                            <input type="radio" name="borrower_type" value="maintenance" x-model="category" class="text-rose-600 focus:ring-0">
                        </div>
                        <p class="text-[10px] mt-1 text-slate-400">Lab ditutup untuk perawatan software, hardware, atau jaringan</p>
                    </label>
                </div>
            </div>

            <!-- Pilihan Laboratorium -->
            <div>
                <label class="block font-bold text-white mb-2 uppercase tracking-wider text-[11px]">
                    Laboratorium Tujuan <span class="text-rose-400">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Option: Semua Lab -->
                    <label class="p-3.5 rounded-xl border cursor-pointer transition-all flex items-center justify-between"
                           :class="labSelection === 'all' ? 'bg-indigo-600/15 border-indigo-500 text-white shadow-md' : 'bg-slate-800/60 border-slate-700/80 text-slate-300 hover:border-slate-600'">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-300 flex items-center justify-center shrink-0 border border-indigo-500/30">
                                <i data-lucide="layers" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="font-bold text-xs block text-white">Semua Laboratorium Sekaligus</span>
                                <span class="text-[10px] text-slate-400 block">{{ $laboratories->count() }} Ruang (Total {{ $laboratories->sum('capacity') }} PC Workstation)</span>
                            </div>
                        </div>
                        <input type="radio" name="lab_selection" value="all" x-model="labSelection" class="text-indigo-600 focus:ring-0">
                    </label>

                    <!-- Option: Lab Tertentu -->
                    <label class="p-3.5 rounded-xl border cursor-pointer transition-all flex items-center justify-between"
                           :class="labSelection !== 'all' ? 'bg-indigo-600/15 border-indigo-500 text-white shadow-md' : 'bg-slate-800/60 border-slate-700/80 text-slate-300 hover:border-slate-600'">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-700 text-slate-300 flex items-center justify-center shrink-0">
                                <i data-lucide="monitor" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="font-bold text-xs block text-white">Pilih Satu Ruang Spesifik</span>
                                <span class="text-[10px] text-slate-400 block">Pilih salah satu dari laboratorium aktif</span>
                            </div>
                        </div>
                        <input type="radio" name="lab_selection" :value="labSelection === 'all' ? '{{ $laboratories->first()->id ?? '' }}' : labSelection" x-model="labSelection" class="text-indigo-600 focus:ring-0">
                    </label>
                </div>

                <!-- Dropdown if specific lab chosen -->
                <div x-show="labSelection !== 'all'" x-transition class="mt-3">
                    <select name="lab_selection" 
                            x-model="labSelection"
                            :disabled="labSelection === 'all'"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 cursor-pointer">
                        @foreach($laboratories as $lab)
                            <option value="{{ $lab->id }}">{{ $lab->name }} — Kapasitas {{ $lab->capacity }} PC ({{ $lab->room_number ?? 'Lokasi UPA TIK' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION 2: WAKTU & RENTANG TANGGAL -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-7 space-y-5 shadow-xl text-xs text-slate-300">
            <div class="border-b border-slate-800 pb-3 flex items-center gap-2">
                <span class="w-6 h-6 rounded-lg bg-indigo-500/20 text-indigo-400 font-black text-xs flex items-center justify-center border border-indigo-500/30">2</span>
                <div>
                    <h3 class="text-sm font-bold text-white">Jadwal & Waktu Pelaksanaan</h3>
                    <p class="text-[11px] text-slate-400">Atur tanggal dan jam operasional kegiatan di laboratorium.</p>
                </div>
            </div>

            <!-- Tipe Jadwal (Rentang Tanggal vs Berulang Mingguan) -->
            <div>
                <label class="block font-bold text-white mb-2 uppercase tracking-wider text-[11px]">
                    Format Jadwal <span class="text-rose-400">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="p-3 rounded-xl border cursor-pointer transition-all flex items-center justify-between"
                           :class="scheduleType === 'dates' ? 'bg-indigo-600/15 border-indigo-500 text-white' : 'bg-slate-800/60 border-slate-700/80 text-slate-400 hover:border-slate-600'">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="calendar" class="w-4 h-4 text-cyan-400"></i>
                            <div>
                                <span class="font-bold text-xs block text-white">Rentang Tanggal Berurutan</span>
                                <span class="text-[10px] text-slate-400 block">Satu hari atau beberapa hari berturut-turut (misal UTBK)</span>
                            </div>
                        </div>
                        <input type="radio" name="schedule_type" value="dates" x-model="scheduleType" class="text-indigo-600 focus:ring-0">
                    </label>

                    <label class="p-3 rounded-xl border cursor-pointer transition-all flex items-center justify-between"
                           :class="scheduleType === 'recurring' ? 'bg-indigo-600/15 border-indigo-500 text-white' : 'bg-slate-800/60 border-slate-700/80 text-slate-400 hover:border-slate-600'">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="repeat" class="w-4 h-4 text-purple-400"></i>
                            <div>
                                <span class="font-bold text-xs block text-white">Jadwal Berulang Tiap Minggu</span>
                                <span class="text-[10px] text-slate-400 block">Setiap hari tertentu selama 1 semester (Praktikum/Kuliah)</span>
                            </div>
                        </div>
                        <input type="radio" name="schedule_type" value="recurring" x-model="scheduleType" class="text-indigo-600 focus:ring-0">
                    </label>
                </div>
            </div>

            <!-- Recurring Day Picker if scheduleType is recurring -->
            <div x-show="scheduleType === 'recurring'" x-transition class="p-4 rounded-xl bg-purple-500/10 border border-purple-500/25 space-y-2">
                <label class="block font-bold text-purple-300 text-xs">Pilih Hari Berulang:</label>
                <div class="grid grid-cols-2 sm:grid-cols-6 gap-2">
                    <label class="flex items-center gap-1.5 p-2 rounded-lg bg-slate-900 border border-slate-700 text-slate-200 cursor-pointer text-center justify-center font-bold text-xs hover:border-purple-400">
                        <input type="radio" name="recurring_day" value="1" {{ old('recurring_day', '1') == '1' ? 'checked' : '' }} class="text-purple-600 focus:ring-0">
                        <span>Senin</span>
                    </label>
                    <label class="flex items-center gap-1.5 p-2 rounded-lg bg-slate-900 border border-slate-700 text-slate-200 cursor-pointer text-center justify-center font-bold text-xs hover:border-purple-400">
                        <input type="radio" name="recurring_day" value="2" {{ old('recurring_day') == '2' ? 'checked' : '' }} class="text-purple-600 focus:ring-0">
                        <span>Selasa</span>
                    </label>
                    <label class="flex items-center gap-1.5 p-2 rounded-lg bg-slate-900 border border-slate-700 text-slate-200 cursor-pointer text-center justify-center font-bold text-xs hover:border-purple-400">
                        <input type="radio" name="recurring_day" value="3" {{ old('recurring_day') == '3' ? 'checked' : '' }} class="text-purple-600 focus:ring-0">
                        <span>Rabu</span>
                    </label>
                    <label class="flex items-center gap-1.5 p-2 rounded-lg bg-slate-900 border border-slate-700 text-slate-200 cursor-pointer text-center justify-center font-bold text-xs hover:border-purple-400">
                        <input type="radio" name="recurring_day" value="4" {{ old('recurring_day') == '4' ? 'checked' : '' }} class="text-purple-600 focus:ring-0">
                        <span>Kamis</span>
                    </label>
                    <label class="flex items-center gap-1.5 p-2 rounded-lg bg-slate-900 border border-slate-700 text-slate-200 cursor-pointer text-center justify-center font-bold text-xs hover:border-purple-400">
                        <input type="radio" name="recurring_day" value="5" {{ old('recurring_day') == '5' ? 'checked' : '' }} class="text-purple-600 focus:ring-0">
                        <span>Jumat</span>
                    </label>
                    <label class="flex items-center gap-1.5 p-2 rounded-lg bg-slate-900 border border-slate-700 text-slate-200 cursor-pointer text-center justify-center font-bold text-xs hover:border-purple-400">
                        <input type="radio" name="recurring_day" value="6" {{ old('recurring_day') == '6' ? 'checked' : '' }} class="text-purple-600 focus:ring-0">
                        <span>Sabtu</span>
                    </label>
                </div>
            </div>

            <!-- Date Range Inputs -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                        Tanggal Mulai <span class="text-rose-400">*</span>
                    </label>
                    <input type="date" name="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:outline-none focus:border-indigo-500 text-xs">
                </div>

                <div>
                    <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                        Tanggal Selesai <span class="text-slate-400 font-normal">(Kosongkan jika hanya 1 hari)</span>
                    </label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:outline-none focus:border-indigo-500 text-xs">
                </div>
            </div>

            <!-- Jam Operasional -->
            <div class="space-y-3 pt-2 border-t border-slate-800/70">
                <div class="flex items-center justify-between">
                    <label class="block font-bold text-white uppercase tracking-wider text-[11px]">
                        Jam Pelaksanaan <span class="text-rose-400">*</span>
                    </label>

                    <!-- Full Day Quick Checkbox -->
                    <label class="inline-flex items-center gap-2 cursor-pointer text-cyan-400 hover:text-cyan-300 font-bold text-xs select-none">
                        <input type="checkbox" name="is_full_day" value="1" 
                               x-model="isFullDay" 
                               @change="toggleFullDay()" 
                               class="rounded bg-slate-800 border-slate-700 text-cyan-500 focus:ring-0">
                        <span>Seharian Penuh (Full Day: 07:30 - 17:00 WIB)</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-[11px] text-slate-400 block mb-1">Jam Mulai:</span>
                        <input type="time" name="start_time" x-model="startTime" required
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white font-mono focus:outline-none focus:border-indigo-500 text-xs">
                    </div>

                    <div>
                        <span class="text-[11px] text-slate-400 block mb-1">Jam Selesai:</span>
                        <input type="time" name="end_time" x-model="endTime" required
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white font-mono focus:outline-none focus:border-indigo-500 text-xs">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: INFORMASI KEGIATAN & PIC -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-7 space-y-5 shadow-xl text-xs text-slate-300">
            <div class="border-b border-slate-800 pb-3 flex items-center gap-2">
                <span class="w-6 h-6 rounded-lg bg-purple-500/20 text-purple-400 font-black text-xs flex items-center justify-center border border-purple-500/30">3</span>
                <div>
                    <h3 class="text-sm font-bold text-white">Detail Kegiatan & Penanggung Jawab</h3>
                    <p class="text-[11px] text-slate-400">Informasi nama acara, unit kerja, kontak PIC, dan catatan operasional.</p>
                </div>
            </div>

            <!-- Nama Kegiatan -->
            <div>
                <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                    Nama Kegiatan / Agenda Lab <span class="text-rose-400">*</span>
                </label>
                <input type="text" name="event_name" value="{{ old('event_name') }}" required
                       placeholder="Contoh: Ujian UTBK-SNBT 2026 Gelombang 1 / Praktikum Jaringan Komputer Kelas B"
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:outline-none focus:border-indigo-500 text-xs">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Unit / Lembaga Penyelenggara -->
                <div>
                    <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                        Unit / Penyelenggara <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="institution_name" value="{{ old('institution_name', 'UPA TIK UNTAN') }}" required
                           placeholder="Contoh: Panitia UTBK Pusat / Fakultas Teknik / UPA TIK"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:outline-none focus:border-indigo-500 text-xs">
                </div>

                <!-- Estimasi Peserta -->
                <div>
                    <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                        Estimasi Peserta / Unit PC <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="number" name="participant_count" value="{{ old('participant_count') }}" min="0"
                           placeholder="Otomatis menyesuaikan kapasitas lab bila kosong"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:outline-none focus:border-indigo-500 text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Nama PIC -->
                <div>
                    <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                        Nama PIC / Penanggung Jawab <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="borrower_name" value="{{ old('borrower_name', auth()->user()->name ?? 'Pengelola Lab') }}" required
                           placeholder="Nama PIC ruangan"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:outline-none focus:border-indigo-500 text-xs">
                </div>

                <!-- No HP / WhatsApp -->
                <div>
                    <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                        No. HP / WhatsApp PIC
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="0812xxxxxxxx"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:outline-none focus:border-indigo-500 text-xs">
                </div>

                <!-- Email PIC -->
                <div>
                    <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                        Email PIC
                    </label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                           placeholder="pic@untan.ac.id"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:outline-none focus:border-indigo-500 text-xs">
                </div>
            </div>

            <!-- Deskripsi Acara -->
            <div>
                <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                    Keterangan Kegiatan / Kebutuhan Software Khusus <span class="text-slate-400 font-normal">(Opsional)</span>
                </label>
                <textarea name="event_description" rows="2"
                          placeholder="Contoh: Memerlukan browser Safe Exam Browser terinstal, koneksi internet LAN stabil, dll."
                          class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:outline-none focus:border-indigo-500 text-xs">{{ old('event_description') }}</textarea>
            </div>

            <!-- Catatan Admin -->
            <div>
                <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                    Catatan Internal Admin <span class="text-slate-400 font-normal">(Opsional)</span>
                </label>
                <input type="text" name="admin_notes" value="{{ old('admin_notes', 'Diinput langsung oleh Administrator untuk Agenda Resmi Lab.') }}"
                       placeholder="Catatan pengelola lab..."
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white focus:outline-none focus:border-indigo-500 text-xs">
            </div>

            <!-- Checkbox Force Override -->
            <div class="pt-3 border-t border-slate-800">
                <label class="inline-flex items-center gap-2.5 cursor-pointer text-amber-300 hover:text-amber-200 select-none">
                    <input type="checkbox" name="force_override" value="1" {{ old('force_override') ? 'checked' : '' }}
                           class="rounded bg-slate-800 border-slate-700 text-amber-500 focus:ring-0">
                    <span class="text-xs font-semibold">Abaikan Peringatan Bentrok (Tetap jadwalkan meskipun slot di jam tersebut terdeteksi ada jadwal lain)</span>
                </label>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between gap-4 pt-2">
            <a href="{{ route('admin.lab-bookings.index') }}" 
               class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition-colors border border-slate-700">
                Batal
            </a>

            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-xs shadow-lg shadow-cyan-500/25 flex items-center gap-2 cursor-pointer transition-all hover:scale-[1.02]">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-slate-950"></i>
                <span>Simpan & Aktifkan di Kalender</span>
            </button>
        </div>

    </form>

</div>
@endsection
