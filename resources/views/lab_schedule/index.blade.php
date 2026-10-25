@extends('layouts.public')

@section('title', 'Jadwal & Peminjaman Laboratorium Komputer - ' . ($profile->company_name ?? 'UPA TIK UNTAN'))
@section('meta_description', 'Cek jadwal ketersediaan lab komputer UPA TIK Universitas Tanjungpura secara real-time dan ajukan peminjaman ruangan secara online.')

@section('content')
<div class="py-12 sm:py-16 transition-colors duration-300"
     x-data="{
        viewMode: '{{ $viewMode ?? 'calendar' }}',
        bookingModalOpen: false,
        trackModalOpen: false,
        detailDayModalOpen: false,
        labDetailModalOpen: false,
        selectedLabDetail: null,
        selectedDayData: null,
        selectedLabIds: [{{ $currentLab ? "'".$currentLab->id."'" : '' }}],
        prefillDate: '',
        endDate: '',
        allLabIds: [{{ $laboratories->pluck('id')->map(fn($id) => "'$id'")->join(',') }}],
        labCapacities: {
            @foreach($laboratories as $lab)
                '{{ $lab->id }}': {{ $lab->capacity }},
            @endforeach
        },

        getDaysDuration() {
            if (!this.prefillDate) return 1;
            const end = this.endDate || this.prefillDate;
            const start = new Date(this.prefillDate);
            const target = new Date(end);
            const diffTime = target.getTime() - start.getTime();
            const diffDays = Math.round(diffTime / (1000 * 3600 * 24)) + 1;
            return diffDays > 0 ? diffDays : 1;
        },

        toggleLab(labId) {
            labId = String(labId);
            if (this.selectedLabIds.includes(labId)) {
                this.selectedLabIds = this.selectedLabIds.filter(id => id !== labId);
            } else {
                this.selectedLabIds.push(labId);
            }
        },

        toggleAllLabs() {
            if (this.isAllLabsSelected()) {
                this.selectedLabIds = [];
            } else {
                this.selectedLabIds = [...this.allLabIds];
            }
        },

        isAllLabsSelected() {
            return this.allLabIds.length > 0 && this.selectedLabIds.length === this.allLabIds.length;
        },

        getTotalCapacity() {
            let total = 0;
            this.selectedLabIds.forEach(id => {
                if (this.labCapacities[id]) total += this.labCapacities[id];
            });
            return total;
        },

        // Track Ticket State
        ticketCode: '',
        trackingLoading: false,
        trackingResult: null,
        trackingError: '',

        openLabDetail(lab) {
            this.selectedLabDetail = lab;
            this.labDetailModalOpen = true;
            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
        },

        openBookingModal(date = '', labId = '') {
            @if(!$isBookingEnabled)
                return;
            @endif
            this.prefillDate = date || '{{ $currentDate->isFuture() ? $currentDate->format('Y-m-d') : date('Y-m-d') }}';
            this.endDate = this.prefillDate;
            if (labId) {
                this.selectedLabIds = [String(labId)];
            } else if (this.selectedLabIds.length === 0) {
                this.selectedLabIds = [...this.allLabIds];
            }
            this.bookingModalOpen = true;
            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
        },

        openDayDetail(day) {
            this.selectedDayData = day;
            this.detailDayModalOpen = true;
            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
        },

        async checkTicketStatus() {
            if (!this.ticketCode.trim()) return;
            this.trackingLoading = true;
            this.trackingResult = null;
            this.trackingError = '';

            try {
                const res = await fetch('{{ route('lab-schedule.check-status') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ code: this.ticketCode.trim() })
                });
                const data = await res.json();
                if (data.success) {
                    this.trackingResult = data.booking;
                } else {
                    this.trackingError = data.message || 'Tiket tidak ditemukan.';
                }
            } catch (err) {
                this.trackingError = 'Terjadi kesalahan jaringan saat mengecek tiket.';
            } finally {
                this.trackingLoading = false;
            }
        }
     }">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- SUCCESS ALERT IF REDIRECTED FROM BOOKING -->
        @if(session('booking_success'))
            @php $bs = session('booking_success'); @endphp
            <div x-data="{ showSuccess: true, copied: false }" x-show="showSuccess" x-transition.duration.300ms
                 class="p-6 sm:p-7 rounded-3xl border shadow-xl relative overflow-hidden transition-all duration-300 animate-fadeIn"
                 :class="darkMode 
                    ? 'bg-gradient-to-r from-emerald-950/50 via-slate-900/90 to-slate-950 border-emerald-500/40 shadow-emerald-950/30' 
                    : 'bg-gradient-to-br from-emerald-50 via-teal-50/50 to-white border-emerald-200 shadow-emerald-500/10'">
                
                <!-- Ambient decorative glow -->
                <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full blur-2xl pointer-events-none"
                     :class="darkMode ? 'bg-emerald-500/10' : 'bg-emerald-300/25'"></div>

                <div class="relative z-10 flex items-start gap-4 sm:gap-5">
                    <!-- Icon Badge -->
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 border transition-colors shadow-md"
                         :class="darkMode 
                            ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40 shadow-emerald-950/50' 
                            : 'bg-emerald-600 text-white border-emerald-500 shadow-emerald-600/30'">
                        <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="space-y-2.5 flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-base sm:text-lg font-black font-heading leading-snug"
                                :class="darkMode ? 'text-white' : 'text-emerald-950'">
                                Permohonan Peminjaman Berhasil Diajukan!
                            </h3>
                            <button type="button" @click="showSuccess = false" 
                                    class="p-1.5 rounded-xl transition-colors cursor-pointer shrink-0"
                                    :class="darkMode ? 'text-slate-400 hover:text-white hover:bg-slate-800' : 'text-emerald-700/60 hover:text-emerald-950 hover:bg-emerald-100/70'"
                                    title="Tutup Notifikasi">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <p class="text-xs sm:text-sm leading-relaxed"
                           :class="darkMode ? 'text-slate-300' : 'text-slate-700'">
                            Terima kasih, <strong :class="darkMode ? 'text-white font-bold' : 'text-slate-900 font-bold'">{{ $bs['borrower'] }}</strong>. 
                            Permohonan Anda untuk <strong :class="darkMode ? 'text-white font-bold' : 'text-slate-900 font-bold'">{{ $bs['lab_name'] }}</strong> 
                            pada hari <strong :class="darkMode ? 'text-white font-bold' : 'text-slate-900 font-bold'">{{ $bs['date'] }} ({{ $bs['time'] }})</strong> telah kami terima dan sedang dalam antrean verifikasi petugas.
                        </p>

                        <!-- Ticket Code Box & Copy Action -->
                        <div class="flex flex-wrap items-center gap-3 pt-1">
                            <div class="inline-flex items-center gap-3 px-4 py-2.5 rounded-xl border text-xs shadow-sm transition-all"
                                 :class="darkMode 
                                    ? 'bg-slate-950 border-slate-800 text-slate-300' 
                                    : 'bg-white border-emerald-200/90 text-emerald-950 shadow-sm'">
                                <span class="font-medium" :class="darkMode ? 'text-slate-400' : 'text-emerald-800 font-semibold'">
                                    Kode Tiket Reservasi:
                                </span>
                                <span class="font-mono font-black text-sm tracking-wider"
                                      :class="darkMode ? 'text-cyan-400' : 'text-emerald-700 font-extrabold'">
                                    {{ $bs['code'] }}
                                </span>
                            </div>

                            <button type="button" 
                                    @click="navigator.clipboard.writeText('{{ $bs['code'] }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="px-3.5 py-2.5 rounded-xl font-bold text-xs border flex items-center gap-1.5 transition-all cursor-pointer shadow-sm"
                                    :class="copied 
                                        ? (darkMode ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-emerald-600 text-white border-emerald-600')
                                        : (darkMode ? 'bg-slate-800 hover:bg-slate-700 text-slate-200 border-slate-700' : 'bg-emerald-100/80 hover:bg-emerald-200/80 text-emerald-900 border-emerald-300/80')">
                                <span x-show="copied" class="inline-flex items-center">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </span>
                                <span x-show="!copied" class="inline-flex items-center">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="14" height="14" x="8" y="8" rx="2" ry="2"></rect>
                                        <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path>
                                    </svg>
                                </span>
                                <span x-text="copied ? 'Tersalin!' : 'Salin Kode'"></span>
                            </button>
                        </div>

                        <p class="text-[11px] pt-0.5"
                           :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            Simpan kode tiket ini untuk melacak status persetujuan dari tim laboratorium UPA TIK melalui tombol <strong>Lacak Status Tiket</strong> di atas.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 sm:p-5 rounded-2xl border text-xs sm:text-sm flex items-center gap-3 shadow-md transition-all duration-300"
                 :class="darkMode 
                    ? 'bg-rose-950/40 border-rose-500/40 text-rose-300' 
                    : 'bg-rose-50 border-rose-200 text-rose-900'">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 border"
                     :class="darkMode ? 'bg-rose-500/20 border-rose-500/30 text-rose-400' : 'bg-rose-600 text-white border-rose-500 shadow-sm'">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <span class="font-medium flex-1">{{ session('error') }}</span>
            </div>
        @endif

        <!-- 1. HERO HEADER SECTION -->
        <div class="relative rounded-3xl overflow-hidden p-8 sm:p-12 border transition-all duration-300 shadow-2xl"
             :class="darkMode ? 'bg-gradient-to-br from-slate-900/90 via-slate-900/50 to-slate-950 border-slate-800' : 'bg-gradient-to-br from-white via-cyan-50/50 to-indigo-50/40 border-slate-200'">
            
            <!-- Ambient Glow -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider border shadow-sm"
                         :class="darkMode ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20' : 'bg-cyan-50 text-cyan-700 border-cyan-200'">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        <span>Fasilitas & Ruang Laboratorium TIK</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight font-heading leading-tight"
                        :class="darkMode ? 'text-white' : 'text-slate-900'">
                        Jadwal & Peminjaman <span class="bg-gradient-to-r from-cyan-400 to-indigo-500 bg-clip-text text-transparent">Laboratorium Komputer</span>
                    </h1>

                    <p class="text-sm sm:text-base leading-relaxed max-w-2xl font-normal"
                       :class="darkMode ? 'text-slate-300' : 'text-slate-600'">
                        Transparansi ketersediaan ruangan komputer, jaringan, dan pusat ujian CAT UPA TIK Universitas Tanjungpura. Cek slot waktu kosong dan ajukan reservasi online resmi dengan mudah.
                    </p>

                    <!-- CTA Actions -->
                    <div class="flex flex-wrap items-center gap-3 pt-3">
                        @if($isBookingEnabled)
                            <button type="button" 
                                    @click="openBookingModal()"
                                    class="px-6 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-xs sm:text-sm shadow-xl shadow-cyan-500/25 transition-all hover:scale-105 flex items-center gap-2.5 uppercase tracking-wider cursor-pointer">
                                <i data-lucide="plus-circle" class="w-4 h-4 text-slate-950"></i>
                                <span>Ajukan Peminjaman Lab</span>
                            </button>
                        @endif

                        <button type="button" 
                                @click="trackModalOpen = true"
                                class="px-5 py-3 rounded-2xl border font-bold text-xs sm:text-sm transition-all hover:scale-105 flex items-center gap-2 cursor-pointer shadow-md"
                                :class="darkMode ? 'bg-slate-800/80 border-slate-700 text-slate-200 hover:bg-slate-700' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'">
                            <i data-lucide="search" class="w-4 h-4 text-cyan-500"></i>
                            <span>Cek Status Tiket</span>
                        </button>

                        <a href="#sop-alur" 
                            class="px-4 py-3 rounded-2xl font-semibold text-xs transition-colors flex items-center gap-1.5"
                            :class="darkMode ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900'">
                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                            <span>Lihat SOP & Syarat</span>
                        </a>
                    </div>
                </div>

                <!-- Stats Quick Cards -->
                <div class="lg:col-span-4 grid grid-cols-2 gap-3.5">
                    <div class="p-4 rounded-2xl border transition-all"
                         :class="darkMode ? 'bg-slate-950/60 border-slate-800/80' : 'bg-white/80 border-slate-200 shadow-sm'">
                        <div class="flex items-center gap-2 text-cyan-500 mb-2">
                            <i data-lucide="monitor" class="w-4 h-4"></i>
                            <span class="text-[11px] font-bold uppercase tracking-wider">Laboratorium</span>
                        </div>
                        <p class="text-2xl font-black font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">{{ $totalLabs }}</p>
                        <p class="text-[11px]" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">Ruang Komputer Aktif</p>
                    </div>

                    <div class="p-4 rounded-2xl border transition-all"
                         :class="darkMode ? 'bg-slate-950/60 border-slate-800/80' : 'bg-white/80 border-slate-200 shadow-sm'">
                        <div class="flex items-center gap-2 text-indigo-500 mb-2">
                            <i data-lucide="cpu" class="w-4 h-4"></i>
                            <span class="text-[11px] font-bold uppercase tracking-wider">Kapasitas</span>
                        </div>
                        <p class="text-2xl font-black font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">{{ $totalCapacity }}</p>
                        <p class="text-[11px]" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">Unit Workstation PC</p>
                    </div>

                    <div class="p-4 rounded-2xl border transition-all"
                         :class="darkMode ? 'bg-slate-950/60 border-slate-800/80' : 'bg-white/80 border-slate-200 shadow-sm'">
                        <div class="flex items-center gap-2 text-emerald-500 mb-2">
                            <i data-lucide="calendar-check" class="w-4 h-4"></i>
                            <span class="text-[11px] font-bold uppercase tracking-wider">Bulan Ini</span>
                        </div>
                        <p class="text-2xl font-black font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">{{ $approvedBookingsCount }}</p>
                        <p class="text-[11px]" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">Sesi Terkonfirmasi</p>
                    </div>

                    <div class="p-4 rounded-2xl border transition-all"
                         :class="darkMode ? 'bg-slate-950/60 border-slate-800/80' : 'bg-white/80 border-slate-200 shadow-sm'">
                        <div class="flex items-center gap-2 text-amber-500 mb-2">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                            <span class="text-[11px] font-bold uppercase tracking-wider">SOP Resmi</span>
                        </div>
                        <p class="text-xs font-bold font-heading mt-1" :class="darkMode ? 'text-white' : 'text-slate-900'">Bebas Biaya</p>
                        <p class="text-[10px]" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">Khusus Civitas Untan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. LAB HIGHLIGHT SHOWCASE CARDS (CLEAN & MINIMAL) -->
        <div class="grid grid-cols-1 {{ $laboratories->count() == 2 ? 'md:grid-cols-2' : 'md:grid-cols-3' }} gap-6">
            @foreach($laboratories as $lab)
                <div class="p-6 rounded-3xl border transition-all duration-300 hover:scale-[1.01] flex flex-col justify-between group"
                     :class="darkMode ? 'bg-slate-900/60 border-slate-800 hover:border-cyan-500/40 shadow-xl' : 'bg-white border-slate-200 hover:border-cyan-500/40 shadow-sm hover:shadow-md'">
                    
                    <div class="space-y-4">
                        <!-- Top Row: Icon & Capacity -->
                        <div class="flex items-start justify-between">
                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center border transition-colors"
                                 :class="darkMode ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20 group-hover:bg-cyan-500/20' : 'bg-cyan-50 text-cyan-700 border-cyan-200 group-hover:bg-cyan-100'">
                                <i data-lucide="monitor" class="w-5 h-5"></i>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider border shadow-sm"
                                  :class="darkMode ? 'bg-indigo-500/10 text-indigo-300 border-indigo-500/30' : 'bg-indigo-50 text-indigo-700 border-indigo-200'">
                                {{ $lab->capacity }} PC
                            </span>
                        </div>

                        <!-- Lab Title & Location -->
                        <div>
                            <h3 class="text-base font-bold font-heading leading-snug line-clamp-1" :class="darkMode ? 'text-white' : 'text-slate-900'" title="{{ $lab->name }}">
                                {{ $lab->name }}
                            </h3>
                            <p class="text-xs font-medium flex items-center gap-1.5 mt-1.5" :class="darkMode ? 'text-cyan-400' : 'text-cyan-600'">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 shrink-0"></i>
                                <span>{{ $lab->room_number ?? 'Gedung UPA TIK' }}</span>
                            </p>
                        </div>

                        <!-- Brief Overview Box -->
                        <div class="p-3.5 rounded-2xl border text-xs space-y-1.5"
                             :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-slate-400' : 'bg-slate-50 border-slate-200 text-slate-600'">
                            @if($lab->pic_name)
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-slate-500">PIC / Pengelola:</span>
                                    <span class="font-bold" :class="darkMode ? 'text-slate-200' : 'text-slate-800'">{{ $lab->pic_name }}</span>
                                </div>
                            @endif
                            @if(!empty($lab->facilities) && is_array($lab->facilities))
                                <div class="flex items-center justify-between text-[11px] pt-1 border-t"
                                     :class="darkMode ? 'border-slate-800/80' : 'border-slate-200/80'">
                                    <span class="text-slate-500">Fasilitas Ruang:</span>
                                    <span class="font-semibold text-emerald-500 flex items-center gap-1">
                                        <i data-lucide="check-circle" class="w-3 h-3"></i>
                                        <span>{{ count($lab->facilities) }} Fasilitas Lengkap</span>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Bottom Action Buttons: Detail & Pinjam -->
                    <div class="pt-4 border-t mt-5 flex items-center justify-between gap-2"
                         :class="darkMode ? 'border-slate-800' : 'border-slate-100'">
                        <button type="button" 
                                @click="openLabDetail({{ json_encode($lab) }})"
                                class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer border {{ !$isBookingEnabled ? 'w-full justify-center' : '' }}"
                                :class="darkMode ? 'bg-slate-800/80 hover:bg-slate-800 text-slate-200 border-slate-700 hover:text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border-slate-200'">
                            <i data-lucide="info" class="w-3.5 h-3.5 text-cyan-400"></i>
                            <span>Lihat Detail</span>
                        </button>

                        @if($isBookingEnabled)
                            <button type="button" 
                                    @click="openBookingModal('', '{{ $lab->id }}')"
                                    class="px-4 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-xs transition-all flex items-center gap-1.5 cursor-pointer shadow-md shadow-cyan-500/20">
                                <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
                                <span>Pilih Lab</span>
                            </button>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

        <!-- 3. MAIN SCHEDULE CONTROLS & FILTER BAR -->
        <div class="p-6 rounded-3xl border transition-all duration-300 space-y-6"
             :class="darkMode ? 'bg-slate-900/80 border-slate-800 shadow-xl' : 'bg-white border-slate-200 shadow-sm'">
            
            <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                
                <!-- Month Navigator -->
                <div class="flex items-center gap-3 w-full lg:w-auto justify-between lg:justify-start">
                    <a href="{{ route('lab-schedule.index', ['month' => $prevMonth, 'lab' => $currentLab ? $currentLab->id : 'all', 'view' => $viewMode]) }}"
                       class="p-2.5 rounded-xl border transition-all hover:scale-105"
                       :class="darkMode ? 'bg-slate-800 border-slate-700 text-slate-300 hover:bg-slate-700' : 'bg-slate-100 border-slate-200 text-slate-700 hover:bg-slate-200'"
                       title="Bulan Sebelumnya">
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </a>

                    <div class="text-center px-4">
                        <h2 class="text-lg sm:text-xl font-black font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">
                            {{ $currentDate->translatedFormat('F Y') }}
                        </h2>
                        <span class="text-[11px] font-semibold text-cyan-500 block">
                            {{ $currentLab ? $currentLab->name : 'Seluruh Laboratorium' }}
                        </span>
                    </div>

                    <a href="{{ route('lab-schedule.index', ['month' => $nextMonth, 'lab' => $currentLab ? $currentLab->id : 'all', 'view' => $viewMode]) }}"
                       class="p-2.5 rounded-xl border transition-all hover:scale-105"
                       :class="darkMode ? 'bg-slate-800 border-slate-700 text-slate-300 hover:bg-slate-700' : 'bg-slate-100 border-slate-200 text-slate-700 hover:bg-slate-200'"
                       title="Bulan Berikutnya">
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </a>
                </div>

                <!-- Laboratory Filter Dropdown & View Mode Switcher -->
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto justify-end">
                    
                    <!-- Lab Filter -->
                    <form method="GET" action="{{ route('lab-schedule.index') }}" class="flex items-center gap-2">
                        <input type="hidden" name="month" value="{{ $currentDate->format('Y-m') }}">
                        <input type="hidden" name="view" :value="viewMode">
                        <select name="lab" onchange="this.form.submit()" 
                                class="px-3.5 py-2.5 rounded-xl border text-xs font-bold transition-all focus:outline-none focus:ring-2 focus:ring-cyan-500 cursor-pointer"
                                :class="darkMode ? 'bg-slate-950 border-slate-700 text-white' : 'bg-slate-50 border-slate-300 text-slate-800'">
                            <option value="all">Semua Laboratorium</option>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab->id }}" {{ $currentLab && $currentLab->id === $lab->id ? 'selected' : '' }}>
                                    {{ $lab->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <!-- VIEW MODE TOGGLE SWITCHER (CALENDAR vs CARD) -->
                    <div class="inline-flex p-1 rounded-2xl border"
                         :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-100 border-slate-200'">
                        <button type="button" 
                                @click="viewMode = 'calendar'"
                                class="px-4 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-2 cursor-pointer"
                                :class="viewMode === 'calendar' ? 'bg-gradient-to-r from-cyan-500 to-indigo-600 text-slate-950 shadow-md font-black' : (darkMode ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900')">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Tampilan Kalender</span>
                            <span class="sm:hidden">Kalender</span>
                        </button>

                        <button type="button" 
                                @click="viewMode = 'card'"
                                class="px-4 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-2 cursor-pointer"
                                :class="viewMode === 'card' ? 'bg-gradient-to-r from-cyan-500 to-indigo-600 text-slate-950 shadow-md font-black' : (darkMode ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900')">
                            <i data-lucide="layout-grid" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Tampilan Kartu (Agenda)</span>
                            <span class="sm:hidden">Kartu</span>
                        </button>
                    </div>

                </div>

            </div>

            <!-- Legend Status Indicators -->
            <div class="flex flex-wrap items-center gap-4 pt-3 border-t text-xs"
                 :class="darkMode ? 'border-slate-800 text-slate-400' : 'border-slate-100 text-slate-600'">
                <span class="font-bold text-[11px] uppercase tracking-wider">Keterangan:</span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span>Tersedia (Slot Kosong)</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <span>Terjadwal / Terpakai (Approved)</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                    <span>Menunggu Verifikasi (Pending)</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                    <span>Hari Ini</span>
                </span>
            </div>

            <!-- ========================================== -->
            <!-- 4A. TAMPILAN KALENDER (CALENDAR GRID VIEW) -->
            <!-- ========================================== -->
            <div x-show="viewMode === 'calendar'" x-transition class="space-y-2">
                <!-- Day of Week Header -->
                <div class="grid grid-cols-7 gap-1 sm:gap-2 text-center text-xs font-black uppercase tracking-wider py-2"
                     :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                    <div>Sen</div>
                    <div>Sel</div>
                    <div>Rab</div>
                    <div>Kam</div>
                    <div>Jum</div>
                    <div class="text-rose-400">Sab</div>
                    <div class="text-rose-400">Min</div>
                </div>

                <!-- Calendar 7xN Grid -->
                <div class="grid grid-cols-7 gap-1 sm:gap-2">
                    @foreach($calendarDays as $day)
                        @php
                            $isCurrentMonth = $day['is_current_month'];
                            $isToday = $day['is_today'];
                            $hasBookings = $day['bookings']->count() > 0;
                            $approvedBookings = $day['bookings']->where('status', 'approved');
                            $pendingBookings = $day['bookings']->where('status', 'pending');

                            $todayDateStr = Carbon\Carbon::now()->year < 2026 ? '2026-10-22' : date('Y-m-d');
                            $isPastDay = $day['date_string'] < $todayDateStr;
                            $hasCurrentLabApproved = $currentLab ? ($approvedBookings->where('laboratory_id', $currentLab->id)->count() > 0) : false;
                            $totalActiveLabs = $totalLabs;
                            $bookedLabCount = $approvedBookings->pluck('laboratory_id')->unique()->count();
                            $allLabsApproved = $bookedLabCount >= $totalActiveLabs;
                            $freeLabsCount = max(0, $totalActiveLabs - $bookedLabCount);
                        @endphp
                        
                        <div @click="openDayDetail({{ json_encode([
                                'date_formatted' => $day['date']->translatedFormat('l, d F Y'),
                                'date_string' => $day['date_string'],
                                'bookings' => $day['bookings'],
                                'approved_count' => $approvedBookings->count(),
                                'pending_count' => $pendingBookings->count(),
                                'is_past' => $isPastDay,
                                'current_lab_booked' => $hasCurrentLabApproved,
                                'current_lab_name' => $currentLab ? $currentLab->name : null,
                                'all_labs_booked' => $allLabsApproved,
                                'free_labs_count' => $freeLabsCount,
                                'total_labs' => $totalActiveLabs,
                            ]) }})"
                             class="min-h-[105px] sm:min-h-[135px] p-2 sm:p-2.5 rounded-2xl border transition-all duration-200 cursor-pointer flex flex-col justify-between group relative overflow-hidden {{ $isCurrentMonth ? '' : 'opacity-35' }} {{ $isToday ? 'ring-2 ring-cyan-400' : '' }}"
                             :class="darkMode ? 'bg-slate-950/70 border-slate-800 hover:border-cyan-500/60 hover:bg-slate-900' : 'bg-slate-50/80 border-slate-200 hover:border-cyan-500/60 hover:bg-white'">
                            
                            <!-- Header Cell (Day Number & Badges) -->
                            <div class="flex items-center justify-between">
                                <span class="text-xs sm:text-sm font-extrabold font-heading w-6 h-6 flex items-center justify-center rounded-lg {{ $isToday ? 'bg-cyan-500 text-slate-950 font-black' : '' }}"
                                      :class="{{ $isToday ? 'false' : 'true' }} ? (darkMode ? 'text-white' : 'text-slate-800') : ''">
                                    {{ $day['day_number'] }}
                                </span>

                                @if($hasBookings)
                                    <span class="w-2 h-2 rounded-full {{ $approvedBookings->count() > 0 ? 'bg-rose-500' : 'bg-amber-400' }}" title="{{ $day['bookings']->count() }} agenda"></span>
                                @else
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500/60"></span>
                                @endif
                            </div>

                            <!-- Middle: Event Chips -->
                            <div class="space-y-1 my-1 overflow-hidden">
                                @forelse($day['bookings']->take(2) as $b)
                                    <div class="px-1.5 py-0.5 rounded text-[10px] truncate font-medium border"
                                         :class="darkMode 
                                            ? '{{ $b->status === 'approved' ? ($b->is_internal_agenda ? ($b->borrower_type === 'maintenance' ? 'bg-rose-500/25 text-rose-300 border-rose-500/40' : 'bg-purple-500/20 text-purple-300 border-purple-500/35') : 'bg-rose-500/20 text-rose-300 border-rose-500/30') : 'bg-amber-500/20 text-amber-300 border-amber-500/30' }}' 
                                            : '{{ $b->status === 'approved' ? ($b->is_internal_agenda ? ($b->borrower_type === 'maintenance' ? 'bg-rose-100 text-rose-800 border-rose-300 font-bold' : 'bg-purple-50 text-purple-800 border-purple-200 font-bold') : 'bg-rose-50 text-rose-700 border-rose-200 font-bold') : 'bg-amber-50 text-amber-800 border-amber-200 font-bold' }}'"
                                         title="{{ $b->formatted_time_range }}: {{ $b->event_name }} ({{ $b->laboratory->name ?? 'Lab' }}) - {{ $b->borrower_type_label }}">
                                        <span class="font-bold">{{ substr($b->start_time, 0, 5) }}</span> {{ Str::limit($b->event_name, 14) }}
                                    </div>
                                @empty
                                    @if($isCurrentMonth && !$day['is_weekend'])
                                        <div class="text-[10px] font-medium hidden sm:block" :class="darkMode ? 'text-emerald-400/80' : 'text-emerald-700'">
                                            🟢 Slot Kosong
                                        </div>
                                    @endif
                                @endforelse

                                @if($day['bookings']->count() > 2)
                                    <span class="text-[9px] font-bold text-slate-400 block text-right">
                                        +{{ $day['bookings']->count() - 2 }} lainnya
                                    </span>
                                @endif
                            </div>

                            <!-- Cell Footer Indicator -->
                            <div class="text-[9px] text-slate-400 group-hover:text-cyan-400 transition-colors flex items-center justify-between">
                                <span class="hidden sm:inline">Detail</span>
                                <i data-lucide="arrow-right" class="w-2.5 h-2.5 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 4B. TAMPILAN KARTU / AGENDA (CARD VIEW)    -->
            <!-- ========================================== -->
            <div x-show="viewMode === 'card'" x-transition class="space-y-6">
                
                @php
                    $activeBookingsGrouped = $bookings->groupBy(function($item) {
                        return $item->booking_date->format('Y-m-d');
                    });
                @endphp

                @if($bookings->isEmpty())
                    <div class="text-center py-12 space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center mx-auto border border-cyan-500/20">
                            <i data-lucide="calendar-x" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-base font-bold font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">
                            Belum Ada Agenda Terjadwal di Bulan Ini
                        </h4>
                        <p class="text-xs text-slate-400 max-w-md mx-auto">
                            Seluruh slot laboratorium masih terbuka untuk permohonan peminjaman.
                        </p>
                        <button type="button" 
                                @click="openBookingModal()"
                                class="mt-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-slate-950 font-bold text-xs inline-flex items-center gap-2 cursor-pointer shadow-lg">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Ajukan Peminjaman Sekarang</span>
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($bookings as $bk)
                            <div class="p-5 rounded-2xl border transition-all duration-200 flex flex-col justify-between space-y-3"
                                 :class="darkMode ? 'bg-slate-950/70 border-slate-800 hover:border-slate-700' : 'bg-slate-50 border-slate-200 hover:border-slate-300 shadow-sm'">
                                
                                <div class="space-y-2.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                                <i data-lucide="monitor" class="w-3 h-3"></i>
                                                {{ $bk->laboratory->name ?? 'Laboratorium' }}
                                            </span>
                                            <span class="text-[11px] text-slate-400 font-semibold block mt-1">
                                                {{ $bk->formatted_date_range }}
                                                @if($bk->duration_days > 1)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500/10 text-amber-500 border border-amber-500/20 ml-1">
                                                        {{ $bk->duration_days }} Hari
                                                    </span>
                                                @endif
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-1.5 flex-wrap justify-end">
                                            @if($bk->is_internal_agenda)
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $bk->agenda_badge_class }}">
                                                    {{ $bk->borrower_type_label }}
                                                </span>
                                            @endif
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $bk->status_badge_class }}">
                                                {{ $bk->status_label }}
                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-sm font-bold font-heading leading-snug" :class="darkMode ? 'text-white' : 'text-slate-900'">
                                            {{ $bk->event_name }}
                                        </h4>
                                        <p class="text-xs mt-0.5" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                                            Oleh: <strong :class="darkMode ? 'text-slate-200' : 'text-slate-800'">{{ $bk->borrower_name }}</strong> ({{ $bk->institution_name }})
                                        </p>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-3 text-xs pt-1" :class="darkMode ? 'text-slate-300' : 'text-slate-600'">
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="clock" class="w-3.5 h-3.5" :class="darkMode ? 'text-cyan-400' : 'text-cyan-600'"></i>
                                            <span>{{ $bk->formatted_time_range }}</span>
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="users" class="w-3.5 h-3.5" :class="darkMode ? 'text-indigo-400' : 'text-indigo-600'"></i>
                                            <span>{{ $bk->participant_count }} Orang</span>
                                        </span>
                                    </div>

                                    @if($bk->event_description)
                                        <p class="text-[11px] italic line-clamp-2" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                                            "{{ $bk->event_description }}"
                                        </p>
                                    @endif
                                </div>

                                <div class="pt-3 border-t text-[11px] flex items-center justify-between"
                                     :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                                    <span class="font-mono text-slate-400">{{ $bk->booking_code }}</span>
                                    <button type="button" 
                                            @click="openBookingModal('', '{{ $bk->laboratory_id }}')"
                                            class="font-bold flex items-center gap-1 cursor-pointer transition-colors"
                                            :class="darkMode ? 'text-cyan-400 hover:text-cyan-300' : 'text-cyan-600 hover:text-cyan-700'">
                                        <span>Pinjam di Hari Lain</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

        </div>

        <!-- 5. SOP & ALUR PEMINJAMAN LAB SECTION -->
        <div id="sop-alur" class="p-8 sm:p-10 rounded-3xl border transition-all duration-300 space-y-8"
             :class="darkMode ? 'bg-slate-900/40 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
            
            <div class="max-w-2xl">
                <span class="text-xs font-black uppercase tracking-wider text-cyan-500 block mb-1">Standard Operating Procedure</span>
                <h3 class="text-2xl font-black font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">
                    Alur & Persyaratan Peminjaman Lab
                </h3>
                <p class="text-xs sm:text-sm mt-1" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                    Berikut panduan singkat langkah demi langkah bagi civitas akademika UNTAN yang membutuhkan fasilitas laboratorium TIK.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Step 1 -->
                <div class="p-5 rounded-2xl border space-y-3 transition-colors"
                     :class="darkMode ? 'bg-slate-950/60 border-slate-800' : 'bg-slate-50/80 border-slate-200 shadow-sm'">
                    <span class="w-8 h-8 rounded-xl font-black text-sm flex items-center justify-center border"
                          :class="darkMode ? 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30' : 'bg-cyan-100 text-cyan-800 border-cyan-300'">1</span>
                    <h4 class="text-sm font-bold font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">Cek Ketersediaan Slot</h4>
                    <p class="text-xs leading-relaxed" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                        Periksa kalender jadwal pada halaman ini untuk memastikan lab dan jam yang Anda inginkan tidak bentrok.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-5 rounded-2xl border space-y-3 transition-colors"
                     :class="darkMode ? 'bg-slate-950/60 border-slate-800' : 'bg-slate-50/80 border-slate-200 shadow-sm'">
                    <span class="w-8 h-8 rounded-xl font-black text-sm flex items-center justify-center border"
                          :class="darkMode ? 'bg-indigo-500/20 text-indigo-400 border-indigo-500/30' : 'bg-indigo-100 text-indigo-800 border-indigo-300'">2</span>
                    <h4 class="text-sm font-bold font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">Isi Formulir & Surat</h4>
                    <p class="text-xs leading-relaxed" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                        Klik tombol <strong>Ajukan Peminjaman</strong> dan lampirkan surat pengantar resmi dari pimpinan fakultas/prodi/lembaga.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-5 rounded-2xl border space-y-3 transition-colors"
                     :class="darkMode ? 'bg-slate-950/60 border-slate-800' : 'bg-slate-50/80 border-slate-200 shadow-sm'">
                    <span class="w-8 h-8 rounded-xl font-black text-sm flex items-center justify-center border"
                          :class="darkMode ? 'bg-amber-500/20 text-amber-300 border-amber-500/30' : 'bg-amber-100 text-amber-800 border-amber-300'">3</span>
                    <h4 class="text-sm font-bold font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">Verifikasi Tim TIK</h4>
                    <p class="text-xs leading-relaxed" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                        Tim Helpdesk & Laboran UPA TIK akan memvalidasi jadwal. Anda akan menerima notifikasi status via Email / WhatsApp.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="p-5 rounded-2xl border space-y-3 transition-colors"
                     :class="darkMode ? 'bg-slate-950/60 border-slate-800' : 'bg-slate-50/80 border-slate-200 shadow-sm'">
                    <span class="w-8 h-8 rounded-xl font-black text-sm flex items-center justify-center border"
                          :class="darkMode ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-emerald-100 text-emerald-800 border-emerald-300'">4</span>
                    <h4 class="text-sm font-bold font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">Pelaksanaan Kegiatan</h4>
                    <p class="text-xs leading-relaxed" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                        Hadir di lokasi 15 menit sebelum kegiatan dimulai dengan membawa salinan surat dan didampingi teknisi lab bertugas.
                    </p>
                </div>
            </div>

        </div>

    </div>

    <!-- ============================================================== -->
    <!-- MODAL 1: FORM PENGAJUAN PEMINJAMAN LAB                          -->
    <!-- ============================================================== -->
    <div x-show="bookingModalOpen" 
         x-transition.opacity
         x-effect="document.body.style.overflow = bookingModalOpen ? 'hidden' : ''"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         style="display: none;">
        
        <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-2xl shadow-2xl flex flex-col max-h-[92vh] overflow-hidden"
             @click.outside="bookingModalOpen = false">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-800 p-6 pb-4 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center border border-cyan-500/30">
                        <i data-lucide="calendar-plus" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white font-heading">Formulir Permohonan Peminjaman Lab</h3>
                        <p class="text-xs text-slate-400">Lengkapi identitas dan detail kegiatan permohonan Anda</p>
                    </div>
                </div>
                <button type="button" @click="bookingModalOpen = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 cursor-pointer">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Modal Body (Scrollable Form) -->
            <form method="POST" action="{{ route('lab-schedule.store') }}" enctype="multipart/form-data" class="overflow-y-auto flex-1 p-6 space-y-5 text-xs text-slate-300">
                @csrf

                <!-- Laboratory Multi-Select Checkbox Cards -->
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                            Pilih Ruang Laboratorium Komputer <span class="text-rose-400">*</span>
                        </label>
                        <button type="button" 
                                @click="toggleAllLabs()"
                                class="text-[11px] font-bold text-cyan-400 hover:text-cyan-300 transition-colors flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/30 cursor-pointer">
                            <i data-lucide="check-check" class="w-3.5 h-3.5" x-show="!isAllLabsSelected()"></i>
                            <i data-lucide="x" class="w-3.5 h-3.5" x-show="isAllLabsSelected()"></i>
                            <span x-text="isAllLabsSelected() ? 'Batal Semua' : 'Pilih Semua Lab'"></span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($laboratories as $lab)
                            <label class="relative flex items-start gap-3 p-3.5 rounded-xl border transition-all cursor-pointer select-none"
                                   :class="selectedLabIds.includes('{{ $lab->id }}') 
                                        ? 'bg-gradient-to-br from-cyan-950/40 to-slate-900 border-cyan-500/60 shadow-md shadow-cyan-500/10 ring-1 ring-cyan-500/30' 
                                        : 'bg-slate-800/60 border-slate-700 hover:border-slate-600 hover:bg-slate-800/90'">
                                <input type="checkbox" 
                                       name="laboratory_ids[]" 
                                       value="{{ $lab->id }}"
                                       class="mt-0.5 rounded border-slate-600 text-cyan-500 focus:ring-cyan-500 focus:ring-offset-slate-900 cursor-pointer w-4 h-4 bg-slate-700"
                                       :checked="selectedLabIds.includes('{{ $lab->id }}')"
                                       @change="toggleLab('{{ $lab->id }}')">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="font-bold text-white text-xs block leading-snug">{{ $lab->name }}</span>
                                        <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-cyan-500/15 text-cyan-300 border border-cyan-500/30 shrink-0 font-semibold">
                                            {{ $lab->capacity }} PC
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-slate-400 block truncate mt-0.5">{{ $lab->room_number ?? 'Gedung TIK' }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Dynamic Summary & Warning -->
                    <div class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-slate-800/60 border border-slate-700/80 text-[11px]">
                        <div class="flex items-center gap-1.5 text-slate-300">
                            <i data-lucide="info" class="w-3.5 h-3.5 text-cyan-400 shrink-0"></i>
                            <span>
                                Terpilih: <strong class="text-white font-bold" x-text="selectedLabIds.length"></strong> Lab
                                <span class="text-slate-500">•</span>
                                Total Kapasitas: <strong class="text-cyan-400 font-bold" x-text="getTotalCapacity() + ' PC'"></strong>
                            </span>
                        </div>
                        <template x-if="isAllLabsSelected()">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                Seluruh Lab
                            </span>
                        </template>
                    </div>

                    <!-- Client-side Validation Reminder -->
                    <div x-show="selectedLabIds.length === 0" class="text-[11px] text-rose-400 font-semibold flex items-center gap-1 mt-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                        <span>Wajib mencentang minimal 1 laboratorium.</span>
                    </div>
                </div>

                <!-- 2 Columns: Nama Pemohon & Kategori -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Nama Lengkap Pemohon <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="borrower_name" required placeholder="Contoh: Dr. Ir. Budi / Dimas Arya"
                               class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Kategori Pemohon <span class="text-rose-400">*</span>
                        </label>
                        <select name="borrower_type" required
                                class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-all">
                            <option value="mahasiswa" class="bg-slate-900 text-white">Mahasiswa (BEM / UKM / HMD)</option>
                            <option value="dosen" class="bg-slate-900 text-white">Dosen / Peneliti</option>
                            <option value="tendik" class="bg-slate-900 text-white">Tenaga Kependidikan</option>
                            <option value="unit_fakultas" class="bg-slate-900 text-white">Pimpinan Unit / Fakultas / Prodi</option>
                            <option value="instansi_luar" class="bg-slate-900 text-white">Instansi Luar / Lembaga Eksternal</option>
                        </select>
                    </div>
                </div>

                <!-- 2 Columns: Asal Fakultas / Lembaga & NIM / NIP -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Asal Fakultas / Prodi / Instansi <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="institution_name" required placeholder="Contoh: Fakultas Teknik / BEM Untan"
                               class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            NIM / NIP / NIDN (Opsional)
                        </label>
                        <input type="text" name="borrower_id_number" placeholder="Nomor Induk Pemohon"
                               class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-all">
                    </div>
                </div>

                <!-- 2 Columns: Email & WhatsApp -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Email Aktif <span class="text-rose-400">*</span>
                        </label>
                        <input type="email" name="email" required placeholder="alamat@untan.ac.id"
                               class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-all">
                        <span class="text-[10px] text-slate-400 block mt-1.5">Bukti tiket akan dikirimkan ke email ini.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Nomor WhatsApp <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" name="phone" required placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-all">
                    </div>
                </div>

                <!-- Nama Acara & Keperluan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Nama Acara / Kegiatan <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="event_name" required placeholder="Contoh: Workshop Pengenalan AI & Cloud Computing"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-all">
                </div>

                <!-- Rentang Tanggal Pelaksanaan: Tanggal Mulai & Tanggal Selesai -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Tanggal Mulai <span class="text-rose-400">*</span>
                        </label>
                        <input type="date" name="booking_date" x-model="prefillDate" required min="{{ date('Y-m-d') }}"
                               @change="if(!endDate || endDate < prefillDate) endDate = prefillDate"
                               style="color-scheme: dark;"
                               class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Tanggal Selesai <span class="text-rose-400">*</span>
                        </label>
                        <input type="date" name="end_date" x-model="endDate" required :min="prefillDate || '{{ date('Y-m-d') }}'"
                               style="color-scheme: dark;"
                               class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-all">
                        <span class="text-[10px] text-slate-400 block mt-1.5"
                              x-text="prefillDate && endDate && prefillDate !== endDate ? ('Durasi: ' + getDaysDuration() + ' hari') : 'Pilih tanggal sama untuk peminjaman 1 hari.'"></span>
                    </div>
                </div>

                <!-- Rentang Waktu: Jam Mulai & Jam Selesai -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Jam Mulai <span class="text-rose-400">*</span>
                        </label>
                        <input type="time" name="start_time" required value="08:00"
                               style="color-scheme: dark;"
                               class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Jam Selesai <span class="text-rose-400">*</span>
                        </label>
                        <input type="time" name="end_time" required value="12:00"
                               style="color-scheme: dark;"
                               class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-all">
                        <span class="text-[10px] text-slate-400 block mt-1.5">Waktu operasional berlaku per hari selama durasi peminjaman.</span>
                    </div>
                </div>

                <!-- Estimasi Peserta & Upload Surat -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Estimasi Peserta (Orang) <span class="text-rose-400">*</span>
                        </label>
                        <input type="number" name="participant_count" required min="1" 
                               :max="Math.max(100, getTotalCapacity() + 30)" 
                               value="30"
                               class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-all">
                        <span class="text-[10px] text-slate-400 block mt-1.5">
                            Daya tampung lab terpilih: <strong class="text-cyan-400 font-bold" x-text="getTotalCapacity() > 0 ? (getTotalCapacity() + ' PC') : '-'"></strong>
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Surat Permohonan / Pengantar
                        </label>
                        <input type="file" name="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                               class="w-full px-4 py-2 rounded-xl bg-slate-800/80 border border-slate-700 text-slate-300 text-xs focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-cyan-500/20 file:text-cyan-300">
                        <span class="text-[10px] text-slate-400 block mt-1.5">PDF/DOC/Gambar, Maksimal 5MB.</span>
                    </div>
                </div>

                <!-- Kebutuhan Tambahan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Deskripsi / Kebutuhan Tambahan (Opsional)
                    </label>
                    <textarea name="event_description" rows="2.5" placeholder="Sebutkan jika membutuhkan instalasi software khusus, sound system, atau mic wireless."
                              class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm placeholder-slate-500 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500/30 transition-all"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                    <button type="button" @click="bookingModalOpen = false" 
                            class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" 
                            :disabled="selectedLabIds.length === 0"
                            :class="selectedLabIds.length === 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'"
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-xs shadow-lg shadow-cyan-500/25 flex items-center gap-2 transition-all">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Kirim Permohonan Peminjaman</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 2: CEK STATUS TIKET RESERVASI LAB                        -->
    <!-- ============================================================== -->
    <div x-show="trackModalOpen" 
         x-transition.opacity
         x-effect="document.body.style.overflow = trackModalOpen ? 'hidden' : ''"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         style="display: none;">
        
        <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden p-6 space-y-5"
             @click.outside="trackModalOpen = false">
            
            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center border border-cyan-500/30">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white font-heading">Lacak Status Permohonan</h3>
                        <p class="text-xs text-slate-400">Masukkan kode tiket reservasi peminjaman lab Anda</p>
                    </div>
                </div>
                <button type="button" @click="trackModalOpen = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 cursor-pointer">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Input Box -->
            <div class="space-y-3">
                <div class="flex gap-2">
                    <input type="text" x-model="ticketCode" @keyup.enter="checkTicketStatus()"
                           placeholder="Contoh: LAB-202610-0001"
                           class="flex-1 px-4 py-3 rounded-xl bg-slate-800/80 border border-slate-700 text-white font-mono text-sm uppercase placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500/30">
                    <button type="button" @click="checkTicketStatus()" :disabled="trackingLoading"
                            class="px-5 py-3 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black text-xs flex items-center gap-2 cursor-pointer disabled:opacity-50">
                        <span x-show="!trackingLoading">Cek</span>
                        <span x-show="trackingLoading">Memuat...</span>
                    </button>
                </div>

                <!-- Error Message -->
                <div x-show="trackingError" class="p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs" x-text="trackingError"></div>

                <!-- Result Box -->
                <template x-if="trackingResult">
                    <div class="p-4 rounded-2xl bg-slate-800/60 border border-slate-700 space-y-3 text-xs">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                            <span class="font-mono text-cyan-400 font-bold" x-text="trackingResult.code"></span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase" :class="trackingResult.status_class" x-text="trackingResult.status_label"></span>
                        </div>

                        <div class="space-y-1.5 text-slate-300">
                            <div>
                                <span class="text-slate-500 block text-[10px] uppercase">Kegiatan:</span>
                                <span class="font-bold text-white text-sm" x-text="trackingResult.event_name"></span>
                            </div>
                            <div>
                                <span class="text-slate-500 block text-[10px] uppercase">Ruangan:</span>
                                <span class="font-bold text-cyan-400" x-text="trackingResult.lab_name"></span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <div>
                                    <span class="text-slate-500 block text-[10px] uppercase">Jadwal:</span>
                                    <span x-text="trackingResult.date"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[10px] uppercase">Waktu:</span>
                                    <span x-text="trackingResult.time"></span>
                                </div>
                            </div>
                            <div class="pt-1">
                                <span class="text-slate-500 block text-[10px] uppercase">Pemohon:</span>
                                <span x-text="trackingResult.borrower_name"></span> (<span x-text="trackingResult.institution"></span>)
                            </div>
                            <template x-if="trackingResult.admin_notes">
                                <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 mt-2">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block mb-0.5">Catatan Petugas Lab:</span>
                                    <span class="text-slate-200 italic" x-text="trackingResult.admin_notes"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <div class="text-right border-t border-slate-800 pt-3">
                <button type="button" @click="trackModalOpen = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-xl cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 3: DETAIL AGENDA HARIAN                                  -->
    <!-- ============================================================== -->
    <div x-show="detailDayModalOpen" 
         x-transition.opacity
         x-effect="document.body.style.overflow = detailDayModalOpen ? 'hidden' : ''; if (detailDayModalOpen && window.lucide) $nextTick(() => lucide.createIcons())"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         style="display: none;">
        
        <div class="border rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden p-6 space-y-4 transition-colors"
             :class="darkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200'"
             @click.outside="detailDayModalOpen = false">
            
            <div class="flex items-center justify-between border-b pb-3"
                 :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                <div>
                    <h3 class="text-base font-bold font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'" x-text="selectedDayData?.date_formatted"></h3>
                    <p class="text-[11px] font-semibold" :class="darkMode ? 'text-cyan-400' : 'text-cyan-600'">Jadwal Penggunaan Ruang Laboratorium Komputer</p>
                </div>
                <button type="button" @click="detailDayModalOpen = false" 
                        class="p-1 rounded-lg cursor-pointer transition-colors"
                        :class="darkMode ? 'text-slate-400 hover:text-white hover:bg-slate-800' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100'">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- List of Bookings on this Day -->
            <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                <template x-if="!selectedDayData?.bookings || selectedDayData.bookings.length === 0">
                    <div class="p-6 text-center rounded-2xl border space-y-2"
                         :class="darkMode ? 'bg-slate-950/60 border-slate-800' : 'bg-slate-50 border-slate-200'">
                        <i data-lucide="check-circle" class="w-8 h-8 text-emerald-500 mx-auto"></i>
                        <h4 class="text-xs font-bold" :class="darkMode ? 'text-white' : 'text-slate-900'">Tidak Ada Agenda Terjadwal</h4>
                        <p class="text-[11px]" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">Seluruh ruangan laboratorium masih kosong dan tersedia untuk dipinjam pada hari ini.</p>
                    </div>
                </template>

                <template x-for="item in selectedDayData?.bookings" :key="item.id">
                    <div class="p-3.5 rounded-xl border space-y-1.5 text-xs transition-colors"
                         :class="darkMode ? 'bg-slate-950 border-slate-800' : 'bg-slate-50 border-slate-200 shadow-sm'">
                        <div class="flex items-center justify-between">
                            <span class="font-bold" :class="darkMode ? 'text-cyan-400' : 'text-cyan-600'" x-text="item.laboratory ? item.laboratory.name : 'Lab'"></span>
                            <div class="flex items-center gap-1.5">
                                <template x-if="item.borrower_type === 'agenda_universitas'">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold"
                                          :class="darkMode ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-purple-100 text-purple-700'">Agenda Kampus</span>
                                </template>
                                <template x-if="item.borrower_type === 'praktikum'">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold"
                                          :class="darkMode ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30' : 'bg-blue-100 text-blue-700'">Praktikum</span>
                                </template>
                                <template x-if="item.borrower_type === 'workshop_internal'">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold"
                                          :class="darkMode ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'bg-cyan-100 text-cyan-700'">Pelatihan</span>
                                </template>
                                <template x-if="item.borrower_type === 'maintenance'">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold"
                                          :class="darkMode ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-rose-100 text-rose-700'">Maintenance</span>
                                </template>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase"
                                      :class="item.status === 'approved' ? (darkMode ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-emerald-100 text-emerald-800 border border-emerald-200') : (darkMode ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-amber-100 text-amber-800 border border-amber-200')"
                                      x-text="item.status === 'approved' ? 'Terjadwal' : 'Pending'"></span>
                            </div>
                        </div>
                        <p class="font-bold text-xs" :class="darkMode ? 'text-white' : 'text-slate-900'" x-text="item.event_name"></p>
                        <div class="flex items-center justify-between text-[11px]" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <span x-text="item.start_time.substring(0, 5) + ' - ' + item.end_time.substring(0, 5) + ' WIB'"></span>
                            <span x-text="item.borrower_name"></span>
                        </div>
                    </div>
                </template>
            </div>

            <!-- FOOTER WITH DYNAMIC AVAILABILITY & SMART CTA -->
            <div class="border-t pt-3" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                <!-- KONDISI 1: Tanggal Sudah Berlalu -->
                <template x-if="selectedDayData?.is_past">
                    <div class="flex items-center justify-between gap-3 w-full">
                        <div class="flex items-center gap-2 text-xs" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <i data-lucide="info" class="w-4 h-4 text-slate-400 shrink-0"></i>
                            <span>Tanggal ini telah berlalu (tidak dapat dipinjam).</span>
                        </div>
                        <button type="button" @click="detailDayModalOpen = false" 
                                class="px-4 py-2 font-bold text-xs rounded-xl cursor-pointer transition-colors"
                                :class="darkMode ? 'bg-slate-800 hover:bg-slate-700 text-slate-300' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'">
                            Tutup
                        </button>
                    </div>
                </template>

                <!-- KONDISI 2: Sedang Memfilter 1 Lab Spesifik & Lab Tersebut Sudah Terisi Disetujui -->
                <template x-if="!selectedDayData?.is_past && selectedDayData?.current_lab_booked">
                    <div class="space-y-3 w-full">
                        <div class="p-3 rounded-2xl flex items-start gap-2.5 text-xs border"
                             :class="darkMode ? 'bg-rose-500/10 border-rose-500/20 text-rose-300' : 'bg-rose-50 border-rose-200 text-rose-800'">
                            <i data-lucide="ban" class="w-4 h-4 shrink-0 text-rose-500 mt-0.5"></i>
                            <div class="space-y-0.5">
                                <p class="font-bold">Jadwal Penuh (Tidak Dapat Dipinjam)</p>
                                <p class="text-[11px] opacity-90 leading-relaxed">
                                    <span class="font-semibold" x-text="selectedDayData?.current_lab_name || 'Laboratorium ini'"></span> telah terisi agenda resmi pada tanggal ini. Peminjaman ruangan tidak tersedia.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <a href="{{ route('lab-schedule.index', ['month' => $currentDate->format('Y-m')]) }}" 
                               class="text-xs font-bold text-cyan-600 dark:text-cyan-400 hover:underline flex items-center gap-1">
                                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                                <span>Cek Lab Lain di Tanggal Ini</span>
                            </a>
                            <button type="button" @click="detailDayModalOpen = false" 
                                    class="px-4 py-2 font-bold text-xs rounded-xl cursor-pointer transition-colors"
                                    :class="darkMode ? 'bg-slate-800 hover:bg-slate-700 text-slate-300' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'">
                                Tutup
                            </button>
                        </div>
                    </div>
                </template>

                <!-- KONDISI 3: Mode Semua Lab & SELURUH Lab Sudah Terisi -->
                <template x-if="!selectedDayData?.is_past && !selectedDayData?.current_lab_booked && selectedDayData?.all_labs_booked">
                    <div class="space-y-3 w-full">
                        <div class="p-3 rounded-2xl flex items-start gap-2.5 text-xs border"
                             :class="darkMode ? 'bg-rose-500/10 border-rose-500/20 text-rose-300' : 'bg-rose-50 border-rose-200 text-rose-800'">
                            <i data-lucide="ban" class="w-4 h-4 shrink-0 text-rose-500 mt-0.5"></i>
                            <div class="space-y-0.5">
                                <p class="font-bold">Semua Laboratorium Penuh</p>
                                <p class="text-[11px] opacity-90 leading-relaxed">
                                    Seluruh ruangan laboratorium komputer UPA TIK telah terisi agenda kegiatan pada tanggal ini. Peminjaman tidak tersedia. Silakan pilih tanggal lain.
                                </p>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" @click="detailDayModalOpen = false" 
                                    class="px-4 py-2 font-bold text-xs rounded-xl cursor-pointer transition-colors"
                                    :class="darkMode ? 'bg-slate-800 hover:bg-slate-700 text-slate-300' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'">
                                Tutup
                            </button>
                        </div>
                    </div>
                </template>

                <!-- KONDISI 4: Mode Semua Lab & SEBAGIAN Lab Terisi, Masih Ada Lab Kosong -->
                <template x-if="!selectedDayData?.is_past && !selectedDayData?.current_lab_booked && !selectedDayData?.all_labs_booked && selectedDayData?.approved_count > 0">
                    <div class="space-y-3 w-full">
                        <div class="p-2.5 rounded-xl flex items-center gap-2 text-[11px] border"
                             :class="darkMode ? 'bg-amber-500/10 border-amber-500/20 text-amber-300' : 'bg-amber-50 border-amber-200 text-amber-800'">
                            <i data-lucide="info" class="w-4 h-4 shrink-0 text-amber-500"></i>
                            <span>Sebagian lab telah terisi. Masih ada <strong class="underline" x-text="selectedDayData?.free_labs_count + ' lab'"></strong> yang masih kosong.</span>
                        </div>
                        <div class="flex items-center {{ $isBookingEnabled ? 'justify-between' : 'justify-end' }}">
                            @if($isBookingEnabled)
                                <button type="button" 
                                        @click="detailDayModalOpen = false; openBookingModal(selectedDayData?.date_string)"
                                        class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-xs rounded-xl flex items-center gap-1.5 cursor-pointer shadow-lg shadow-cyan-500/20">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                    <span x-text="'Pinjam Lab Lain yang Tersedia (' + selectedDayData?.free_labs_count + ' Kosong)'"></span>
                                </button>
                            @endif

                            <button type="button" @click="detailDayModalOpen = false" 
                                    class="px-4 py-2 font-bold text-xs rounded-xl cursor-pointer transition-colors"
                                    :class="darkMode ? 'bg-slate-800 hover:bg-slate-700 text-slate-300' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'">
                                Tutup
                            </button>
                        </div>
                    </div>
                </template>

                <!-- KONDISI 5: Seluruh Ruangan Masih Kosong (Tersedia Penuh) -->
                <template x-if="!selectedDayData?.is_past && !selectedDayData?.current_lab_booked && !selectedDayData?.all_labs_booked && (!selectedDayData?.approved_count || selectedDayData?.approved_count === 0)">
                    <div class="flex items-center {{ $isBookingEnabled ? 'justify-between' : 'justify-end' }} w-full">
                        @if($isBookingEnabled)
                            <button type="button" 
                                    @click="detailDayModalOpen = false; openBookingModal(selectedDayData?.date_string)"
                                    class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-xs rounded-xl flex items-center gap-1.5 cursor-pointer shadow-lg shadow-cyan-500/20">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Pinjam di Tanggal Ini</span>
                            </button>
                        @endif

                        <button type="button" @click="detailDayModalOpen = false" 
                                class="px-4 py-2 font-bold text-xs rounded-xl cursor-pointer transition-colors"
                                :class="darkMode ? 'bg-slate-800 hover:bg-slate-700 text-slate-300' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'">
                            Tutup
                        </button>
                    </div>
                </template>
            </div>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 4: DETAIL LENGKAP FASILITAS & SPESIFIKASI LAB             -->
    <!-- ============================================================== -->
    <div x-show="labDetailModalOpen" 
         x-transition.opacity
         x-effect="document.body.style.overflow = labDetailModalOpen ? 'hidden' : ''; if (labDetailModalOpen && window.lucide) $nextTick(() => lucide.createIcons())"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         style="display: none;">
        
        <div class="border rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden p-6 space-y-5 transition-colors"
             :class="darkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200'"
             @click.outside="labDetailModalOpen = false">
            
            <!-- Modal Header -->
            <div class="flex items-start justify-between border-b pb-4"
                 :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center border shrink-0"
                         :class="darkMode ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20' : 'bg-cyan-50 text-cyan-700 border-cyan-200'">
                        <i data-lucide="monitor" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'" x-text="selectedLabDetail?.name"></h3>
                        <p class="text-xs font-semibold flex items-center gap-1 mt-0.5" :class="darkMode ? 'text-cyan-400' : 'text-cyan-600'">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                            <span x-text="selectedLabDetail?.room_number || 'Gedung UPA TIK'"></span>
                        </p>
                    </div>
                </div>
                <button type="button" @click="labDetailModalOpen = false" 
                        class="p-1.5 rounded-lg cursor-pointer transition-colors"
                        :class="darkMode ? 'text-slate-400 hover:text-white hover:bg-slate-800' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100'">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Modal Content (Scrollable) -->
            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1 text-xs">
                
                <!-- Kapasitas & PIC Box -->
                <div class="grid grid-cols-2 gap-3 p-3.5 rounded-2xl border"
                     :class="darkMode ? 'bg-slate-950/60 border-slate-800' : 'bg-slate-50 border-slate-200'">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider block text-slate-500">Kapasitas Workstation:</span>
                        <span class="text-sm font-extrabold text-cyan-500 dark:text-cyan-400" x-text="(selectedLabDetail?.capacity || 0) + ' Unit PC'"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider block text-slate-500">Penanggung Jawab (PIC):</span>
                        <span class="text-xs font-bold" :class="darkMode ? 'text-white' : 'text-slate-900'" x-text="selectedLabDetail?.pic_name || 'Petugas Lab TIK'"></span>
                        <span class="text-[10px] text-slate-400 block font-mono" x-text="selectedLabDetail?.pic_contact || '-'"></span>
                    </div>
                </div>

                <!-- Spesifikasi Hardware PC -->
                <template x-if="selectedLabDetail?.pc_specs">
                    <div class="space-y-1.5">
                        <h4 class="text-xs font-bold uppercase tracking-wider flex items-center gap-1.5"
                            :class="darkMode ? 'text-white' : 'text-slate-900'">
                            <i data-lucide="cpu" class="w-4 h-4 text-cyan-400"></i>
                            <span>Spesifikasi Hardware & Komputer</span>
                        </h4>
                        <div class="p-3.5 rounded-2xl border leading-relaxed text-xs"
                             :class="darkMode ? 'bg-slate-950 border-slate-800 text-slate-300' : 'bg-slate-50 border-slate-200 text-slate-700'"
                             x-text="selectedLabDetail.pc_specs">
                        </div>
                    </div>
                </template>

                <!-- Daftar Fasilitas Penunjang -->
                <template x-if="selectedLabDetail?.facilities && selectedLabDetail.facilities.length > 0">
                    <div class="space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider flex items-center gap-1.5"
                            :class="darkMode ? 'text-white' : 'text-slate-900'">
                            <i data-lucide="check-square" class="w-4 h-4 text-emerald-400"></i>
                            <span>Fasilitas Ruangan</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <template x-for="(fac, idx) in selectedLabDetail.facilities" :key="idx">
                                <div class="flex items-center gap-2 p-2.5 rounded-xl border"
                                     :class="darkMode ? 'bg-slate-950/70 border-slate-800 text-slate-300' : 'bg-slate-50 border-slate-200 text-slate-700'">
                                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-500 shrink-0"></i>
                                    <span class="text-[11px] font-semibold" x-text="fac"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

            </div>

            <!-- Modal Footer -->
            <div class="border-t pt-4 flex items-center justify-between"
                 :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                <button type="button" @click="labDetailModalOpen = false" 
                        class="px-4 py-2 font-bold text-xs rounded-xl cursor-pointer transition-colors"
                        :class="darkMode ? 'bg-slate-800 hover:bg-slate-700 text-slate-300' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'">
                    Tutup
                </button>

                @if($isBookingEnabled)
                    <button type="button" 
                            @click="labDetailModalOpen = false; openBookingModal('', selectedLabDetail?.id)"
                            class="px-5 py-2.5 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-xs rounded-xl flex items-center gap-1.5 cursor-pointer shadow-lg shadow-cyan-500/20">
                        <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
                        <span>Ajukan Peminjaman Lab Ini</span>
                    </button>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection
