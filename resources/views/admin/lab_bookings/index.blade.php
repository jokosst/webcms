@extends('layouts.admin')

@section('title', 'Permohonan Peminjaman Lab')
@section('header_title', 'Kelola Permohonan & Jadwal Peminjaman Lab')

@section('content')
<div class="space-y-6" x-data="{ statusModalOpen: false, serviceEnabled: '{{ $labBookingEnabled ? '1' : '0' }}', closedMsg: @js($labBookingClosedMessage) }">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <i data-lucide="calendar-check" class="w-5 h-5 text-indigo-400"></i>
                <span>Daftar Permohonan & Reservasi Ruang Lab</span>
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Verifikasi pengajuan peminjaman laboratorium dari mahasiswa, dosen, fakultas, dan instansi luar.</p>
        </div>
        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('admin.laboratories.index') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700 flex items-center gap-1.5 transition-colors">
                <i data-lucide="monitor" class="w-4 h-4 text-slate-400"></i>
                <span>Data Lab</span>
            </a>
            <a href="{{ route('lab-schedule.index') }}" target="_blank" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700 flex items-center gap-1.5 transition-all">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Kalender Publik</span>
            </a>
            <a href="{{ route('admin.lab-bookings.create') }}" class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-xs rounded-xl shadow-lg shadow-cyan-500/20 flex items-center gap-1.5 transition-all cursor-pointer">
                <i data-lucide="calendar-plus" class="w-4 h-4 text-slate-950"></i>
                <span>Agenda Lab</span>
            </a>
        </div>
    </div>

    <!-- SERVICE STATUS BANNER & QUICK TOGGLE -->
    <div class="p-4 sm:p-4.5 rounded-2xl border transition-all duration-300 flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 shadow-lg {{ $labBookingEnabled ? 'bg-emerald-950/20 border-emerald-500/30 text-emerald-300' : 'bg-rose-950/25 border-rose-500/40 text-rose-300' }}">
        <div class="flex items-start sm:items-center gap-3">
            <span class="w-3 h-3 rounded-full shrink-0 mt-0.5 sm:mt-0 {{ $labBookingEnabled ? 'bg-emerald-400 animate-pulse shadow-lg shadow-emerald-400/50' : 'bg-rose-500 ring-4 ring-rose-500/20' }}"></span>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-extrabold text-xs uppercase tracking-wider {{ $labBookingEnabled ? 'text-emerald-400' : 'text-rose-400' }}">
                        Layanan Peminjaman Online: {{ $labBookingEnabled ? 'AKTIF (DIBUKA)' : 'NONAKTIF (DITUTUP SEMENTARA)' }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $labBookingEnabled ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-rose-500/10 text-rose-300 border-rose-500/30' }}">
                        {{ $labBookingEnabled ? 'Menerima Pengajuan Publik' : 'Pengajuan Publik Ditutup' }}
                    </span>
                </div>
                <p class="text-[11px] text-slate-300 mt-0.5 leading-relaxed">
                    @if($labBookingEnabled)
                        Civitas akademika dapat mengajukan permohonan reservasi laboratorium secara online melalui kalender publik.
                    @else
                        <span class="italic font-medium text-rose-200/90">"{{ $labBookingClosedMessage }}"</span>
                    @endif
                </p>
            </div>
        </div>
        <button type="button" 
                @click="statusModalOpen = true" 
                class="px-3.5 py-2 rounded-xl text-xs font-bold shrink-0 border transition-all flex items-center gap-1.5 cursor-pointer shadow-sm {{ $labBookingEnabled ? 'bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-200 border-emerald-500/40' : 'bg-rose-600 hover:bg-rose-500 text-white border-rose-500 shadow-rose-600/30' }}">
            <i data-lucide="{{ $labBookingEnabled ? 'sliders' : 'unlock' }}" class="w-3.5 h-3.5"></i>
            <span>{{ $labBookingEnabled ? 'Atur Status Layanan' : 'Buka Layanan Kembali' }}</span>
        </button>
    </div>

    <!-- Filter Status Tabs -->
    <div class="overflow-x-auto pb-1">
        <div class="flex items-center gap-2 min-w-max border-b border-slate-800 pb-3 text-xs">
            <a href="{{ route('admin.lab-bookings.index', ['status' => 'all', 'lab_id' => $labId, 'search' => $search, 'per_page' => $perPage]) }}"
               class="px-3.5 py-2 rounded-xl font-bold transition-all flex items-center gap-2 {{ $status === 'all' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800 hover:bg-slate-800' }}">
                <span>Semua</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ $status === 'all' ? 'bg-indigo-800 text-white' : 'bg-slate-800 text-slate-400' }}">{{ $counts['all'] }}</span>
            </a>

            <a href="{{ route('admin.lab-bookings.index', ['status' => 'pending', 'lab_id' => $labId, 'search' => $search, 'per_page' => $perPage]) }}"
               class="px-3.5 py-2 rounded-xl font-bold transition-all flex items-center gap-2 {{ $status === 'pending' ? 'bg-amber-500 text-slate-950 font-black shadow-lg shadow-amber-500/30' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800 hover:bg-slate-800' }}">
                <span>Menunggu Konfirmasi</span>
                @if($counts['pending'] > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $status === 'pending' ? 'bg-amber-400 text-slate-950 animate-pulse' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30' }}">{{ $counts['pending'] }}</span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-mono bg-slate-800 text-slate-400">0</span>
                @endif
            </a>

            <a href="{{ route('admin.lab-bookings.index', ['status' => 'approved', 'lab_id' => $labId, 'search' => $search, 'per_page' => $perPage]) }}"
               class="px-3.5 py-2 rounded-xl font-bold transition-all flex items-center gap-2 {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800 hover:bg-slate-800' }}">
                <span>Disetujui</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ $status === 'approved' ? 'bg-emerald-800 text-white' : 'bg-slate-800 text-slate-400' }}">{{ $counts['approved'] }}</span>
            </a>

            <a href="{{ route('admin.lab-bookings.index', ['status' => 'rejected', 'lab_id' => $labId, 'search' => $search, 'per_page' => $perPage]) }}"
               class="px-3.5 py-2 rounded-xl font-bold transition-all flex items-center gap-2 {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800 hover:bg-slate-800' }}">
                <span>Ditolak</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ $status === 'rejected' ? 'bg-rose-800 text-white' : 'bg-slate-800 text-slate-400' }}">{{ $counts['rejected'] }}</span>
            </a>

            <a href="{{ route('admin.lab-bookings.index', ['status' => 'completed', 'lab_id' => $labId, 'search' => $search, 'per_page' => $perPage]) }}"
               class="px-3.5 py-2 rounded-xl font-bold transition-all flex items-center gap-2 {{ $status === 'completed' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800 hover:bg-slate-800' }}">
                <span>Selesai</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ $status === 'completed' ? 'bg-blue-800 text-white' : 'bg-slate-800 text-slate-400' }}">{{ $counts['completed'] }}</span>
            </a>
        </div>
    </div>

    <!-- Search & Laboratory Filter Bar (Structured & Responsive) -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-4 shadow-lg">
        <form method="GET" action="{{ route('admin.lab-bookings.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="hidden" name="per_page" value="{{ $perPage }}">
            
            <!-- Left Side: Search Input -->
            <div class="relative flex-1 min-w-[240px]">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Cari nomor tiket, nama pemohon, fakultas, atau acara..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white placeholder-slate-500 text-xs focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all">
            </div>

            <!-- Middle: Laboratory Dropdown Filter -->
            <div class="w-full md:w-64 shrink-0">
                <select name="lab_id" onchange="this.form.submit()" 
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 cursor-pointer transition-all">
                    <option value="" class="bg-slate-900 text-white">Semua Laboratorium</option>
                    @foreach($laboratories as $lab)
                        <option value="{{ $lab->id }}" {{ $labId == $lab->id ? 'selected' : '' }} class="bg-slate-900 text-white">
                            {{ $lab->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Right Side: Filter & Reset Actions -->
            <div class="flex items-center gap-2 shrink-0 justify-end">
                <button type="submit" 
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/30 transition-all flex items-center justify-center gap-1.5 cursor-pointer shrink-0">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Filter</span>
                </button>

                @if($search || $labId)
                    <a href="{{ route('admin.lab-bookings.index', ['status' => $status, 'per_page' => $perPage]) }}" 
                       class="px-3.5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-semibold text-xs rounded-xl border border-slate-700 transition-colors flex items-center gap-1 shrink-0"
                       title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="text-slate-400 uppercase bg-slate-800/60 border-b border-slate-800 text-[11px] tracking-wider font-bold">
                    <tr>
                        <th class="px-5 py-3.5 w-36">No. Tiket</th>
                        <th class="px-5 py-3.5 w-48">Laboratorium</th>
                        <th class="px-5 py-3.5 w-44">Jadwal</th>
                        <th class="px-5 py-3.5">Kegiatan & Pemohon</th>
                        <th class="px-5 py-3.5 text-center w-28">Peserta</th>
                        <th class="px-5 py-3.5 text-center w-36">Status</th>
                        <th class="px-5 py-3.5 text-right w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70 font-medium">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.lab-bookings.show', $b->id) }}" class="font-mono font-bold text-cyan-400 hover:underline block text-xs">
                                    {{ $b->booking_code }}
                                </a>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-bold text-white block">{{ $b->laboratory->name ?? '-' }}</span>
                                @if($b->is_multi_lab)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-400 bg-amber-500/10 border border-amber-500/25 px-1.5 py-0.5 rounded mt-0.5">
                                        <i data-lucide="layers" class="w-3 h-3"></i>
                                        <span>Multi-Lab ({{ $b->group_laboratories->count() }} Ruang)</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-semibold text-white">{{ $b->formatted_date_range }}</div>
                                <div class="text-[11px] text-cyan-400 font-mono mt-0.5">{{ $b->formatted_time_range }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-white text-xs line-clamp-1 max-w-sm" title="{{ $b->event_name }}">{{ $b->event_name }}</span>
                                    @if($b->is_internal_agenda)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold shrink-0 {{ $b->agenda_badge_class }}">
                                            {{ $b->borrower_type_label }}
                                        </span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-400 truncate max-w-sm mt-0.5">{{ $b->borrower_name }} • {{ $b->institution_name }}</div>
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300 font-bold text-[11px]">
                                    <i data-lucide="users" class="w-3 h-3 text-indigo-400"></i>
                                    <span>{{ $b->participant_count }} Org</span>
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $b->status_badge_class }}">
                                    {{ $b->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.lab-bookings.show', $b->id) }}" 
                                       class="px-3 py-1.5 rounded-lg bg-indigo-600/20 text-indigo-400 hover:bg-indigo-600 hover:text-white font-bold text-xs transition-all flex items-center gap-1 shadow-sm"
                                       title="Periksa & Verifikasi">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <span>Detail</span>
                                    </a>

                                    <form action="{{ route('admin.lab-bookings.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan reservasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors cursor-pointer" title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center text-slate-500 space-y-2">
                                <i data-lucide="calendar-x" class="w-10 h-10 mx-auto text-slate-600"></i>
                                <p class="text-sm font-semibold text-slate-400">Tidak ada permohonan peminjaman lab dengan filter yang dipilih.</p>
                                <p class="text-xs text-slate-500">Coba ubah status tab filter atau reset pencarian di atas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Summary Footer (Always Visible) -->
        <div class="px-5 py-3.5 border-t border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs text-slate-400 bg-slate-900/60">
            <!-- Left Side: Summary & Items Per Page -->
            <div class="flex flex-wrap items-center gap-3">
                <span>
                    Menampilkan 
                    <strong class="text-white font-semibold">{{ $bookings->firstItem() ?? 0 }}</strong>
                    sampai 
                    <strong class="text-white font-semibold">{{ $bookings->lastItem() ?? 0 }}</strong>
                    dari 
                    <strong class="text-white font-semibold">{{ $bookings->total() }}</strong>
                    data
                </span>
                
                <div class="flex items-center gap-1.5 pl-3 border-l border-slate-800">
                    <span class="text-slate-500">Tampilkan:</span>
                    <select onchange="window.location.href = this.value" 
                            class="px-2.5 py-1 rounded-lg bg-slate-800/80 border border-slate-700 text-slate-200 text-xs focus:outline-none focus:border-indigo-500 cursor-pointer">
                        @foreach([10, 20, 50, 100] as $size)
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}" {{ $perPage == $size ? 'selected' : '' }} class="bg-slate-900 text-white">
                                {{ $size }} per hal
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Right Side: Page Controls -->
            <div class="flex items-center gap-1.5 self-center sm:self-auto">
                {{-- Previous Page Link --}}
                @if ($bookings->onFirstPage())
                    <span class="px-2.5 py-1.5 rounded-xl bg-slate-950/60 border border-slate-800/80 text-slate-600 cursor-not-allowed text-xs font-semibold flex items-center gap-1">
                        <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $bookings->previousPageUrl() }}" 
                       class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white text-xs font-semibold flex items-center gap-1 transition-colors">
                        <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @php
                    $currentPage = $bookings->currentPage();
                    $lastPage = max(1, $bookings->lastPage());
                    $start = max(1, $currentPage - 1);
                    $end = min($lastPage, $currentPage + 1);
                @endphp

                @if($start > 1)
                    <a href="{{ $bookings->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white font-bold text-xs transition-colors">1</a>
                    @if($start > 2)
                        <span class="px-1 text-slate-600">...</span>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $currentPage)
                        <span class="w-8 h-8 flex items-center justify-center rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-md shadow-indigo-600/30">{{ $page }}</span>
                    @else
                        <a href="{{ $bookings->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white font-bold text-xs transition-colors">{{ $page }}</a>
                    @endif
                @endfor

                @if($end < $lastPage)
                    @if($end < $lastPage - 1)
                        <span class="px-1 text-slate-600">...</span>
                    @endif
                    <a href="{{ $bookings->url($lastPage) }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white font-bold text-xs transition-colors">{{ $lastPage }}</a>
                @endif

                {{-- Next Page Link --}}
                @if ($bookings->hasMorePages())
                    <a href="{{ $bookings->nextPageUrl() }}" 
                       class="px-2.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white text-xs font-semibold flex items-center gap-1 transition-colors">
                        <span class="hidden sm:inline">Selanjutnya</span>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                @else
                    <span class="px-2.5 py-1.5 rounded-xl bg-slate-950/60 border border-slate-800/80 text-slate-600 cursor-not-allowed text-xs font-semibold flex items-center gap-1">
                        <span class="hidden sm:inline">Selanjutnya</span>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- MODAL PENGATURAN STATUS LAYANAN -->
    <div x-show="statusModalOpen" 
         x-transition.opacity
         x-effect="document.body.style.overflow = statusModalOpen ? 'hidden' : ''; if (statusModalOpen && window.lucide) $nextTick(() => lucide.createIcons())"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         style="display: none;">
        
        <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden"
             @click.outside="statusModalOpen = false">
            
            <div class="flex items-center justify-between border-b border-slate-800 p-5 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center border border-cyan-500/30">
                        <i data-lucide="power" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white font-heading">Pengaturan Layanan Peminjaman Lab</h3>
                        <p class="text-[11px] text-slate-400">Aktifkan atau tutup sementara layanan reservasi online</p>
                    </div>
                </div>
                <button type="button" @click="statusModalOpen = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.lab-bookings.toggle-service') }}" class="p-6 space-y-4 text-xs text-slate-300">
                @csrf

                <!-- Radio Choice: BUKA vs TUTUP -->
                <div>
                    <label class="block font-bold text-white mb-2 uppercase tracking-wider text-[11px]">
                        Status Layanan Reservasi Online <span class="text-rose-400">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="p-3.5 rounded-xl border cursor-pointer transition-all flex items-start gap-2.5"
                               :class="serviceEnabled === '1' ? 'bg-emerald-500/15 border-emerald-500 text-white shadow-md' : 'bg-slate-800/60 border-slate-700 text-slate-400 hover:border-slate-600'">
                            <input type="radio" name="is_enabled" value="1" x-model="serviceEnabled" class="mt-0.5 text-emerald-600 focus:ring-0">
                            <div>
                                <span class="font-bold text-xs block text-emerald-400">AKTIF (DIBUKA)</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Pengunjung dapat mengajukan reservasi secara online</span>
                            </div>
                        </label>

                        <label class="p-3.5 rounded-xl border cursor-pointer transition-all flex items-start gap-2.5"
                               :class="serviceEnabled === '0' ? 'bg-rose-500/15 border-rose-500 text-white shadow-md' : 'bg-slate-800/60 border-slate-700 text-slate-400 hover:border-slate-600'">
                            <input type="radio" name="is_enabled" value="0" x-model="serviceEnabled" class="mt-0.5 text-rose-600 focus:ring-0">
                            <div>
                                <span class="font-bold text-xs block text-rose-400">TUTUP SEMENTARA</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Pengajuan publik ditolak dan banner tutup dimunculkan</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Pesan Penutupan Kustom -->
                <div x-show="serviceEnabled === '0'" x-transition class="space-y-1.5">
                    <label class="block font-bold text-white uppercase tracking-wider text-[11px]">
                        Pesan Pengumuman Penutupan <span class="text-slate-400 font-normal">(Ditampilkan ke Publik)</span>
                    </label>
                    <textarea name="closed_message" rows="3" x-model="closedMsg"
                              placeholder="Tuliskan alasan penutupan sementara..."
                              class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-xs focus:outline-none focus:border-cyan-500"></textarea>
                    <p class="text-[10px] text-slate-400">
                        Pesan ini akan tampil di bagian atas halaman kalender publik dan banner pengumuman.
                    </p>
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-end gap-2.5">
                    <button type="button" @click="statusModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-md">
                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
