@extends('layouts.admin')

@section('header_title', 'Riwayat & Analitik Percakapan Chatbot')

@section('content')
<div class="space-y-6" 
     x-data="{
        detailModal: false,
        faqModal: false,
        clearModal: false,
        selectedLog: null,
        faqQuestion: '',
        faqAnswer: '',
        openDetail(logId) {
            fetch('{{ url('dalam/chatbot-logs') }}/' + logId, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                this.selectedLog = data;
                this.detailModal = true;
                this.$nextTick(() => lucide.createIcons());
            });
        },
        openCreateFaq(queryText) {
            this.faqQuestion = queryText;
            this.faqAnswer = '';
            this.faqModal = true;
        }
     }">

    <!-- 1. Top Analytics Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Obrolan -->
        <div class="bg-slate-900/90 rounded-2xl border border-slate-800 p-5 relative overflow-hidden shadow-lg group hover:border-cyan-500/40 transition-all">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition-colors"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pertanyaan</p>
                    <h3 class="text-2xl font-black text-white mt-1">{{ number_format($totalChats) }}</h3>
                    <p class="text-[11px] text-cyan-400 mt-1 flex items-center gap-1">
                        <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                        <span>Seluruh riwayat chat</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center border border-cyan-500/30 shadow-lg shadow-cyan-500/10">
                    <i data-lucide="headphones" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Tingkat Keberhasilan Jawaban -->
        <div class="bg-slate-900/90 rounded-2xl border border-slate-800 p-5 relative overflow-hidden shadow-lg group hover:border-emerald-500/40 transition-all">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-colors"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tingkat Terjawab</p>
                    <h3 class="text-2xl font-black text-white mt-1">{{ $answeredRate }}%</h3>
                    <p class="text-[11px] text-emerald-400 mt-1 flex items-center gap-1">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                        <span>{{ number_format($aiCount + $faqCount + $smallTalkCount + $ticketTrackingCount) }} terjawab sukses</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center border border-emerald-500/30 shadow-lg shadow-emerald-500/10">
                    <i data-lucide="sparkles" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Respon Google Gemini AI -->
        <div class="bg-slate-900/90 rounded-2xl border border-slate-800 p-5 relative overflow-hidden shadow-lg group hover:border-indigo-500/40 transition-all">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-colors"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Dijawab Gemini AI</p>
                    <h3 class="text-2xl font-black text-white mt-1">{{ number_format($aiCount) }}</h3>
                    <p class="text-[11px] text-indigo-400 mt-1 flex items-center gap-1">
                        <i data-lucide="cpu" class="w-3.5 h-3.5"></i>
                        <span>{{ number_format($faqCount) }} dari FAQ database</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center border border-indigo-500/30 shadow-lg shadow-indigo-500/10">
                    <i data-lucide="bot" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Pertanyaan Belum Terjawab (Peluang FAQ Baru) -->
        <div class="bg-slate-900/90 rounded-2xl border border-slate-800 p-5 relative overflow-hidden shadow-lg group hover:border-amber-500/40 transition-all">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-colors"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Perlu Tambah FAQ</p>
                    <h3 class="text-2xl font-black text-white mt-1">{{ number_format($unansweredCount) }}</h3>
                    <p class="text-[11px] text-amber-400 mt-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                        <span>Pertanyaan belum ada data</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center border border-amber-500/30 shadow-lg shadow-amber-500/10">
                    <i data-lucide="help-circle" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Filter Bar & Actions (Top: Filter Row, Bottom: Action Toolbar) -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-4 shadow-xl space-y-3">
        <!-- Row 1: Filter Inputs in 1 Line -->
        <form action="{{ route('admin.chatbot-logs.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
            <!-- Search Input: Wider and Centered Left Icon -->
            <div class="relative flex-1 min-w-[240px] max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4 text-cyan-400"></i>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari pertanyaan, balasan, atau IP..." 
                       class="w-full pl-10 pr-4 py-2 bg-slate-800/90 border border-slate-700 rounded-xl text-white text-xs focus:border-cyan-500 focus:outline-none placeholder-slate-400 transition-all">
            </div>

            <!-- Dropdown: Tipe Respon -->
            <select name="response_type" 
                    class="px-3 py-2 bg-slate-800/90 border border-slate-700 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-cyan-500 cursor-pointer">
                <option value="all">Semua Tipe Respon</option>
                <option value="ai_gemini" {{ request('response_type') === 'ai_gemini' ? 'selected' : '' }}>🤖 Google Gemini AI</option>
                <option value="faq_database" {{ request('response_type') === 'faq_database' ? 'selected' : '' }}>📚 FAQ Database</option>
                <option value="small_talk" {{ request('response_type') === 'small_talk' ? 'selected' : '' }}>💬 Percakapan / Sapaan</option>
                <option value="ticket_tracking" {{ request('response_type') === 'ticket_tracking' ? 'selected' : '' }}>🎫 Pelacakan Tiket</option>
                <option value="fallback_unanswered" {{ request('response_type') === 'fallback_unanswered' ? 'selected' : '' }}>⚠️ Tidak Terjawab</option>
            </select>

            <!-- Dropdown: Status Respon -->
            <select name="status" 
                    class="px-3 py-2 bg-slate-800/90 border border-slate-700 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-cyan-500 cursor-pointer">
                <option value="">Semua Status</option>
                <option value="answered" {{ request('status') === 'answered' ? 'selected' : '' }}>🟢 Terjawab</option>
                <option value="unanswered" {{ request('status') === 'unanswered' ? 'selected' : '' }}>🔴 Belum Ada FAQ</option>
            </select>

            <!-- Date Picker -->
            <input type="date" 
                   name="date" 
                   value="{{ request('date') }}" 
                   class="px-3 py-2 bg-slate-800/90 border border-slate-700 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-cyan-500">

            <!-- Submit & Reset Buttons -->
            <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                <span>Terapkan</span>
            </button>

            @if(request()->hasAny(['search', 'response_type', 'status', 'date']))
                <a href="{{ route('admin.chatbot-logs.index') }}" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white rounded-xl border border-slate-700 transition-colors flex items-center justify-center" title="Reset Filter">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                </a>
            @endif
        </form>

        <!-- Row 2: Bottom Toolbar with Export & Purge Actions -->
        <div class="flex items-center justify-between pt-3 border-t border-slate-800/80">
            <div class="text-xs text-slate-400 flex items-center gap-2">
                <i data-lucide="activity" class="w-3.5 h-3.5 text-cyan-400"></i>
                <span>Ditemukan <strong>{{ number_format($logs->total()) }}</strong> data riwayat obrolan</span>
            </div>

            <div class="flex items-center gap-2">
                <!-- Export CSV Button -->
                <a href="{{ route('admin.chatbot-logs.export.csv', request()->query()) }}" class="px-3.5 py-2 bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-300 hover:text-emerald-200 border border-emerald-500/30 font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="download" class="w-3.5 h-3.5 text-emerald-400"></i>
                    <span>Ekspor CSV / Excel</span>
                </a>

                <!-- Purge Logs Button -->
                <button type="button" @click="clearModal = true" class="px-3 py-2 bg-slate-800 hover:bg-rose-600/20 text-slate-400 hover:text-rose-400 border border-slate-700 hover:border-rose-500/30 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer" title="Bersihkan Riwayat Obrolan">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    <span>Bersihkan Riwayat</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 3. Logs Table Card -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/60 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                        <th class="py-3.5 px-4 w-12 text-center">#</th>
                        <th class="py-3.5 px-4">Pertanyaan Pengunjung</th>
                        <th class="py-3.5 px-4">Sumber Jawaban</th>
                        <th class="py-3.5 px-4">Status Respon</th>
                        <th class="py-3.5 px-4">Waktu</th>
                        <th class="py-3.5 px-4 w-36 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-xs">
                    @forelse($logs as $index => $log)
                        <tr class="hover:bg-slate-800/40 transition-colors group">
                            <!-- Index -->
                            <td class="py-4 px-4 text-center font-mono text-slate-500">
                                {{ $logs->firstItem() + $index }}
                            </td>

                            <!-- User Query & Snippet -->
                            <td class="py-4 px-4 max-w-md">
                                <div class="font-bold text-white text-sm mb-1 group-hover:text-cyan-400 transition-colors flex items-start gap-1.5">
                                    <span class="text-cyan-400 font-mono">“</span>
                                    <span>{{ $log->user_query }}</span>
                                    <span class="text-cyan-400 font-mono">”</span>
                                </div>
                                <div class="text-slate-400 text-[11px] line-clamp-1 flex items-center gap-1">
                                    <i data-lucide="corner-down-right" class="w-3 h-3 text-slate-500 shrink-0"></i>
                                    <span>{{ Str::limit(strip_tags($log->bot_reply), 85) }}</span>
                                </div>
                            </td>

                            <!-- Response Type Badge -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider inline-block {{ $log->response_type_badge }}">
                                    {{ $log->response_type_label }}
                                </span>
                                @if($log->faq)
                                    <div class="text-[10px] text-cyan-400 mt-1 truncate max-w-[180px]" title="{{ $log->faq->question }}">
                                        🔗 {{ $log->faq->question }}
                                    </div>
                                @endif
                            </td>

                            <!-- Answered Status -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($log->is_answered)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        <span>Terjawab</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                        <span>Tidak Ditemukan</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Timestamp & Client Info (WIB / Asia/Jakarta) -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="text-slate-200 font-medium">
                                    {{ $log->created_at ? $log->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}
                                </div>
                                <div class="text-[10px] text-slate-500 mt-0.5 flex items-center gap-1">
                                    <i data-lucide="globe" class="w-3 h-3 text-slate-600"></i>
                                    <span>{{ $log->ip_address ?? '127.0.0.1' }}</span>
                                    <span>•</span>
                                    <span>{{ $log->created_at ? $log->created_at->timezone('Asia/Jakarta')->diffForHumans() : '' }}</span>
                                </div>
                            </td>

                            <!-- Action Buttons -->
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- View Detail -->
                                    <button type="button" 
                                            @click="openDetail({{ $log->id }})" 
                                            class="p-2 bg-slate-800 hover:bg-cyan-600/20 text-slate-300 hover:text-cyan-400 rounded-lg border border-slate-700 hover:border-cyan-500/30 transition-all cursor-pointer"
                                            title="Lihat Detail Obrolan">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    </button>

                                    <!-- 1-Click Convert to FAQ (Terutama jika Unanswered) -->
                                    <button type="button" 
                                            @click="openCreateFaq('{{ addslashes($log->user_query) }}')" 
                                            class="p-2 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 hover:text-white rounded-lg border border-indigo-500/30 transition-all cursor-pointer"
                                            title="Jadikan Jawaban FAQ Baru">
                                        <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                                    </button>

                                    <!-- Delete Log -->
                                    <form action="{{ route('admin.chatbot-logs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan log ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-slate-800 hover:bg-rose-600/20 text-slate-400 hover:text-rose-400 rounded-lg border border-slate-700 hover:border-rose-500/30 transition-all cursor-pointer" title="Hapus Log">
                                            <i data-lucide="trash" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-600 flex items-center justify-center mx-auto mb-3">
                                    <i data-lucide="inbox" class="w-6 h-6"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-400">Belum ada riwayat percakapan chatbot yang cocok dengan filter.</p>
                                <p class="text-xs text-slate-600 mt-1">Gunakan tombol reset filter untuk melihat seluruh obrolan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 4. Ultra-Premium Custom Dark Glassmorphism Pagination -->
        <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/60 flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Left Info -->
            <div class="text-xs text-slate-400">
                @if($logs->total() > 0)
                    Menampilkan <span class="font-bold text-white">{{ $logs->firstItem() }}</span> sampai <span class="font-bold text-white">{{ $logs->lastItem() }}</span> dari <span class="font-bold text-cyan-400">{{ $logs->total() }}</span> total riwayat obrolan
                @else
                    Menampilkan <span class="font-bold text-white">0</span> riwayat obrolan
                @endif
            </div>

            <!-- Right Controls: Per-Page Selector & Page Buttons -->
            <div class="flex items-center gap-3">
                <!-- Per-Page Selector -->
                <div class="flex items-center gap-1.5 text-xs text-slate-400">
                    <span class="hidden md:inline">Tampilkan:</span>
                    <select onchange="location = this.value;" class="px-2.5 py-1.5 bg-slate-900 border border-slate-700/80 rounded-lg text-white text-xs focus:border-cyan-500 focus:outline-none cursor-pointer">
                        @foreach([10, 25, 50, 100] as $size)
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}" {{ $perPage == $size ? 'selected' : '' }}>
                                {{ $size }} baris
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Page Navigation Pills -->
                <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-1">
                    @if($logs->hasPages())
                        {{-- Previous Page Link --}}
                        @if($logs->onFirstPage())
                            <span class="px-2.5 py-1.5 rounded-lg bg-slate-900/50 text-slate-600 border border-slate-800 text-xs font-semibold cursor-not-allowed">
                                &laquo; Prev
                            </span>
                        @else
                            <a href="{{ $logs->previousPageUrl() }}" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 text-xs font-semibold transition-colors">
                                &laquo; Prev
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach($logs->getUrlRange(max(1, $logs->currentPage() - 2), min($logs->lastPage(), $logs->currentPage() + 2)) as $page => $url)
                            @if($page == $logs->currentPage())
                                <span class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-bold text-xs shadow-lg shadow-cyan-500/25 border border-cyan-400/40">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 hover:border-slate-700 text-xs font-semibold transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if($logs->hasMorePages())
                            <a href="{{ $logs->nextPageUrl() }}" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 text-xs font-semibold transition-colors">
                                Next &raquo;
                            </a>
                        @else
                            <span class="px-2.5 py-1.5 rounded-lg bg-slate-900/50 text-slate-600 border border-slate-800 text-xs font-semibold cursor-not-allowed">
                                Next &raquo;
                            </span>
                        @endif
                    @else
                        <span class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-bold text-xs shadow-lg shadow-cyan-500/25 border border-cyan-400/40">
                            1
                        </span>
                    @endif
                </nav>
            </div>
        </div>
    </div>

    <!-- 5. MODAL: DETAIL OBROLAN CHATBOT -->
    <div x-show="detailModal" 
         x-transition.opacity 
         x-effect="document.body.style.overflow = detailModal ? 'hidden' : ''"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" 
         style="display: none;">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-2xl shadow-2xl flex flex-col"
             style="max-height: 90vh;"
             @click.outside="detailModal = false">
            
            <!-- Modal Header (Fixed) -->
            <div class="flex items-center justify-between border-b border-slate-800 p-5 flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center border border-cyan-500/30">
                        <i data-lucide="message-square" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">Detail Riwayat Obrolan Chatbot</h3>
                        <p class="text-[11px] text-slate-400" x-text="selectedLog?.created_at"></p>
                    </div>
                </div>
                <button type="button" @click="detailModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div style="overflow-y: auto; flex: 1 1 auto; padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem;">
                <!-- User Question Bubble -->
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">👤 Pertanyaan Pengunjung:</span>
                    <div class="p-3.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white text-sm font-medium leading-relaxed"
                         x-text="selectedLog?.user_query"></div>
                </div>

                <!-- Bot Reply Bubble -->
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-cyan-400 block mb-1">🤖 Balasan Asisten Chatbot:</span>
                    <div class="p-3.5 rounded-xl bg-cyan-950/30 border border-cyan-500/30 text-slate-200 text-xs leading-relaxed whitespace-pre-line font-mono"
                         x-text="selectedLog?.bot_reply"></div>
                </div>

                <!-- Metadata Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-3 rounded-xl bg-slate-950/60 border border-slate-800 text-[11px]">
                    <div>
                        <span class="text-slate-500 block">Tipe Respon:</span>
                        <span class="font-bold text-white" x-text="selectedLog?.response_type_label"></span>
                    </div>
                    <div>
                        <span class="text-slate-500 block">Alamat IP:</span>
                        <span class="font-mono text-slate-300" x-text="selectedLog?.ip_address"></span>
                    </div>
                    <div>
                        <span class="text-slate-500 block">Status:</span>
                        <span class="font-bold" :class="selectedLog?.is_answered ? 'text-emerald-400' : 'text-rose-400'" x-text="selectedLog?.is_answered ? '● Terjawab' : '○ Tidak Ditemukan'"></span>
                    </div>
                </div>
            </div>

            <!-- Modal Footer (Fixed) -->
            <div class="flex justify-between items-center p-5 border-t border-slate-800 flex-shrink-0">
                <button type="button" 
                        @click="detailModal = false; openCreateFaq(selectedLog?.user_query)" 
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                    <span>Jadikan FAQ Baru</span>
                </button>

                <button type="button" @click="detailModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- 6. MODAL: 1-CLICK TAMBAH JADI FAQ BARU -->
    <div x-show="faqModal" 
         x-transition.opacity 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" 
         style="display: none;">
        <div class="bg-slate-900 border border-indigo-500/30 rounded-2xl w-full max-w-xl overflow-hidden shadow-2xl space-y-4 p-6"
             @click.outside="faqModal = false">
            
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center border border-indigo-500/30">
                        <i data-lucide="help-circle" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">Tambahkan ke FAQ Resmi Instansi</h3>
                        <p class="text-[11px] text-slate-400">Pertanyaan ini akan otomatis dikenali dan dijawab oleh Chatbot & AI.</p>
                    </div>
                </div>
                <button type="button" @click="faqModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form action="{{ route('admin.faqs.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Pertanyaan (Question) *</label>
                    <input type="text" 
                           name="question" 
                           x-model="faqQuestion" 
                           required 
                           class="w-full px-4 py-2.5 bg-slate-800/90 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none"
                           placeholder="Contoh: Bagaimana cara reset password SSO Untan?">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Jawaban Resmi (Answer) *</label>
                    <textarea name="answer" 
                              rows="4" 
                              x-model="faqAnswer" 
                              required 
                              class="w-full px-4 py-2.5 bg-slate-800/90 border border-slate-700 rounded-xl text-white text-xs leading-relaxed focus:border-indigo-500 focus:outline-none"
                              placeholder="Tuliskan panduan langkah demi langkah jawaban yang jelas..."></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="faq_is_active" value="1" checked class="rounded border-slate-700 text-indigo-600 focus:ring-indigo-500 bg-slate-800">
                    <label for="faq_is_active" class="text-xs text-slate-300">Langsung Aktifkan di Web & Chatbot</label>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-800">
                    <button type="button" @click="faqModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-1.5 cursor-pointer">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan FAQ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 7. MODAL: BERSIHKAN LOG -->
    <div x-show="clearModal" 
         x-transition.opacity 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" 
         style="display: none;">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md overflow-hidden shadow-2xl p-6 space-y-4"
             @click.outside="clearModal = false">
            
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center border border-rose-500/30">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">Bersihkan Riwayat Obrolan</h3>
                    <p class="text-xs text-slate-400">Pilih rentang data log yang ingin dihapus.</p>
                </div>
            </div>

            <form action="{{ route('admin.chatbot-logs.clear') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Hapus Riwayat:</label>
                    <select name="days" class="w-full px-3.5 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:border-rose-500 focus:outline-none">
                        <option value="30">Lebih lama dari 30 hari yang lalu</option>
                        <option value="60">Lebih lama dari 60 hari yang lalu</option>
                        <option value="90">Lebih lama dari 90 hari yang lalu</option>
                        <option value="0">Hapus SELURUH riwayat obrolan (Kosongkan tabel)</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="clearModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-rose-600/30 transition-all flex items-center gap-1.5 cursor-pointer">
                        <i data-lucide="trash" class="w-3.5 h-3.5"></i>
                        <span>Proses Pembersihan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
