@extends('layouts.admin')

@section('title', 'Kelola FAQ & Basis Pengetahuan Chatbot')
@section('header_title', 'Kelola FAQ & Basis Pengetahuan Chatbot')

@section('content')
<div class="space-y-6" 
     x-data="{ 
        showModal: false, 
        showImportModal: false,
        showExportDropdown: false,
        editMode: false, 
        formUrl: '{{ route('admin.faqs.store') }}', 
        faqId: null, 
        question: '', 
        answer: '', 
        sort_order: 1, 
        is_active: true,
        importMode: 'append_skip',
        targetStatus: 'chatbot_only'
     }">
    
    <!-- 1. Top Analytics Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Total Knowledge Base -->
        <div class="bg-slate-900/90 rounded-2xl border border-slate-800 p-5 relative overflow-hidden shadow-lg group hover:border-cyan-500/40 transition-all">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition-colors"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Basis Pengetahuan</p>
                    <h3 class="text-2xl font-black text-white mt-1">{{ number_format($totalCount) }}</h3>
                    <p class="text-[11px] text-cyan-400 mt-1 flex items-center gap-1">
                        <i data-lucide="database" class="w-3.5 h-3.5"></i>
                        <span>Seluruh FAQ di sistem</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center border border-cyan-500/30 shadow-lg shadow-cyan-500/10">
                    <i data-lucide="help-circle" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Tampil di Web Publik -->
        <div class="bg-slate-900/90 rounded-2xl border border-slate-800 p-5 relative overflow-hidden shadow-lg group hover:border-emerald-500/40 transition-all">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-colors"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tampil di Web Publik</p>
                    <h3 class="text-2xl font-black text-emerald-400 mt-1">{{ number_format($publicCount) }}</h3>
                    <p class="text-[11px] text-emerald-400 mt-1 flex items-center gap-1">
                        <i data-lucide="globe" class="w-3.5 h-3.5"></i>
                        <span>Muncul di halaman web</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center border border-emerald-500/30 shadow-lg shadow-emerald-500/10">
                    <i data-lucide="eye" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Khusus Data Dukung Chatbot (Hidden) -->
        <div class="bg-slate-900/90 rounded-2xl border border-slate-800 p-5 relative overflow-hidden shadow-lg group hover:border-indigo-500/40 transition-all">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-colors"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Khusus Chatbot & AI</p>
                    <h3 class="text-2xl font-black text-indigo-400 mt-1">{{ number_format($chatbotOnlyCount) }}</h3>
                    <p class="text-[11px] text-indigo-400 mt-1 flex items-center gap-1">
                        <i data-lucide="bot" class="w-3.5 h-3.5"></i>
                        <span>Hidden dari web publik</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center border border-indigo-500/30 shadow-lg shadow-indigo-500/10">
                    <i data-lucide="cpu" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Filter Bar & Action Toolbar -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-4 shadow-xl space-y-3">
        <div class="flex flex-col xl:flex-row items-stretch xl:items-center justify-between gap-3">
            
            <!-- Left Side: Search & Status Filter -->
            <form action="{{ route('admin.faqs.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 flex-1">
                <!-- Search Input: Widened with Centered Icon -->
                <div class="relative flex-1 min-w-[280px] sm:min-w-[340px] max-w-lg">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari pertanyaan / jawaban FAQ..." 
                           class="w-full pl-10 pr-4 py-2 bg-slate-800/90 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none placeholder-slate-400 transition-all">
                </div>

                <!-- Status Filter -->
                <select name="status" 
                        class="px-3 py-2 bg-slate-800/90 border border-slate-700 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="">Semua Status FAQ</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>🟢 Tampil di Web & Chatbot</option>
                    <option value="chatbot_only" {{ request('status') === 'chatbot_only' ? 'selected' : '' }}>🤖 Khusus Chatbot (Hidden)</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition-colors flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Filter</span>
                </button>

                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.faqs.index') }}" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white rounded-xl border border-slate-700 transition-colors flex items-center justify-center" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                @endif
            </form>

            <!-- Right Side: Action Buttons (Import, Export, Add) -->
            <div class="flex items-center gap-2 shrink-0 justify-end pt-2 xl:pt-0 border-t xl:border-t-0 border-slate-800">
                <!-- Import Button -->
                <button type="button" 
                        @click="showImportModal = true" 
                        class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white border border-slate-700 font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="upload" class="w-3.5 h-3.5 text-indigo-400"></i>
                    <span>Impor FAQ</span>
                </button>

                <!-- Export Dropdown -->
                <div class="relative" @click.outside="showExportDropdown = false">
                    <button type="button" 
                            @click="showExportDropdown = !showExportDropdown" 
                            class="px-3.5 py-2 bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-300 hover:text-emerald-200 border border-emerald-500/30 font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                        <i data-lucide="download" class="w-3.5 h-3.5 text-emerald-400"></i>
                        <span>Ekspor</span>
                        <i data-lucide="chevron-down" class="w-3 h-3 text-emerald-400"></i>
                    </button>

                    <div x-show="showExportDropdown" 
                         x-transition.origin.top.right 
                         class="absolute right-0 mt-2 w-48 bg-slate-900 border border-slate-800 rounded-xl shadow-2xl z-20 py-1.5 text-xs" 
                         style="display: none;">
                        <a href="{{ route('admin.faqs.export.csv') }}" class="flex items-center gap-2 px-3.5 py-2 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                            <i data-lucide="file-spreadsheet" class="w-4 h-4 text-emerald-400"></i>
                            <span>Ekspor Excel / CSV</span>
                        </a>
                        <a href="{{ route('admin.faqs.export.json') }}" class="flex items-center gap-2 px-3.5 py-2 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                            <i data-lucide="code" class="w-4 h-4 text-amber-400"></i>
                            <span>Ekspor File JSON</span>
                        </a>
                    </div>
                </div>

                <!-- Add FAQ Button (Original Blue / Indigo) -->
                <button @click="
                    editMode = false;
                    formUrl = '{{ route('admin.faqs.store') }}';
                    question = '';
                    answer = '';
                    sort_order = {{ $faqs->count() + 1 }};
                    is_active = true;
                    showModal = true;
                " class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Tambah FAQ</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 3. FAQ List Table -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 uppercase font-extrabold text-slate-400 border-b border-slate-800 text-[11px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5 w-16 text-center">Urutan</th>
                        <th class="px-4 py-3.5">Pertanyaan FAQ</th>
                        <th class="px-4 py-3.5">Jawaban Resmi</th>
                        <th class="px-4 py-3.5 w-44 text-center">Status Tayang</th>
                        <th class="px-4 py-3.5 w-28 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-slate-800/40 transition-colors group">
                            <!-- Sort Order -->
                            <td class="px-4 py-4 text-center font-mono font-bold text-slate-500">
                                {{ $faq->sort_order }}
                            </td>

                            <!-- Question -->
                            <td class="px-4 py-4 font-bold text-white max-w-xs group-hover:text-cyan-400 transition-colors">
                                {{ $faq->question }}
                            </td>

                            <!-- Answer -->
                            <td class="px-4 py-4 text-slate-400 max-w-md leading-relaxed text-[11px]">
                                {{ Str::limit(strip_tags($faq->answer), 130) }}
                            </td>

                            <!-- Status with 1-Click Toggle -->
                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                <form action="{{ route('admin.faqs.toggle-active', $faq->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    @if($faq->is_active)
                                        <button type="submit" 
                                                class="px-2.5 py-1 rounded-full bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-bold text-[10px] transition-all flex items-center gap-1.5 mx-auto cursor-pointer"
                                                title="Klik untuk ubah status ke Khusus Chatbot">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                            <span>Web & Chatbot</span>
                                        </button>
                                    @else
                                        <button type="submit" 
                                                class="px-2.5 py-1 rounded-full bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 font-bold text-[10px] transition-all flex items-center gap-1.5 mx-auto cursor-pointer"
                                                title="Klik untuk tampilkan di Web Publik">
                                            <i data-lucide="bot" class="w-3 h-3 text-indigo-400"></i>
                                            <span>Khusus Chatbot</span>
                                        </button>
                                    @endif
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button @click="
                                        editMode = true;
                                        formUrl = '{{ route('admin.faqs.update', $faq->id) }}';
                                        faqId = {{ $faq->id }};
                                        question = {{ json_encode($faq->question) }};
                                        answer = {{ json_encode($faq->answer) }};
                                        sort_order = {{ $faq->sort_order }};
                                        is_active = {{ $faq->is_active ? 'true' : 'false' }};
                                        showModal = true;
                                    " class="p-2 bg-slate-800 hover:bg-indigo-600/20 text-slate-300 hover:text-indigo-400 rounded-lg border border-slate-700 hover:border-indigo-500/30 transition-colors cursor-pointer" title="Edit FAQ">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                    </button>

                                    <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertanyaan FAQ ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-slate-800 hover:bg-rose-600/20 text-slate-400 hover:text-rose-400 rounded-lg border border-slate-700 hover:border-rose-500/30 transition-colors cursor-pointer" title="Hapus FAQ">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-600 flex items-center justify-center mx-auto mb-3">
                                    <i data-lucide="inbox" class="w-6 h-6"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-400">Belum ada data FAQ yang cocok dengan filter.</p>
                                <p class="text-xs text-slate-600 mt-1">Gunakan tombol "Tambah FAQ" atau "Impor FAQ" di atas untuk menambahkan data.</p>
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
                @if($faqs->total() > 0)
                    Menampilkan <span class="font-bold text-white">{{ $faqs->firstItem() }}</span> sampai <span class="font-bold text-white">{{ $faqs->lastItem() }}</span> dari <span class="font-bold text-cyan-400">{{ $faqs->total() }}</span> total FAQ
                @else
                    Menampilkan <span class="font-bold text-white">0</span> data FAQ
                @endif
            </div>

            <!-- Right Controls: Per-Page Selector & Page Buttons -->
            <div class="flex items-center gap-3">
                <!-- Per-Page Selector -->
                <div class="flex items-center gap-1.5 text-xs text-slate-400">
                    <span class="hidden md:inline">Tampilkan:</span>
                    <select onchange="location = this.value;" class="px-2.5 py-1.5 bg-slate-900 border border-slate-700/80 rounded-lg text-white text-xs focus:border-indigo-500 focus:outline-none cursor-pointer">
                        @foreach([10, 25, 50, 100] as $size)
                            <option value="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}" {{ $perPage == $size ? 'selected' : '' }}>
                                {{ $size }} baris
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Page Navigation Pills -->
                <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-1">
                    @if($faqs->hasPages())
                        {{-- Previous Page Link --}}
                        @if($faqs->onFirstPage())
                            <span class="px-2.5 py-1.5 rounded-lg bg-slate-900/50 text-slate-600 border border-slate-800 text-xs font-semibold cursor-not-allowed">
                                &laquo; Prev
                            </span>
                        @else
                            <a href="{{ $faqs->previousPageUrl() }}" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 text-xs font-semibold transition-colors">
                                &laquo; Prev
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach($faqs->getUrlRange(max(1, $faqs->currentPage() - 2), min($faqs->lastPage(), $faqs->currentPage() + 2)) as $page => $url)
                            @if($page == $faqs->currentPage())
                                <span class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 border border-indigo-500">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 hover:border-slate-700 text-xs font-semibold transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if($faqs->hasMorePages())
                            <a href="{{ $faqs->nextPageUrl() }}" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 text-xs font-semibold transition-colors">
                                Next &raquo;
                            </a>
                        @else
                            <span class="px-2.5 py-1.5 rounded-lg bg-slate-900/50 text-slate-600 border border-slate-800 text-xs font-semibold cursor-not-allowed">
                                Next &raquo;
                            </span>
                        @endif
                    @else
                        <span class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 border border-indigo-500">
                            1
                        </span>
                    @endif
                </nav>
            </div>
        </div>
    </div>

    <!-- 4. MODAL: TAMBAH / EDIT FAQ -->
    <div x-show="showModal" 
         x-transition.opacity 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" 
         style="display: none;">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-xl overflow-hidden shadow-2xl space-y-4 p-6"
             @click.outside="showModal = false">
            
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center border border-indigo-500/30">
                        <i data-lucide="help-circle" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white" x-text="editMode ? 'Edit Pertanyaan FAQ' : 'Tambah Pertanyaan FAQ Baru'"></h3>
                        <p class="text-[11px] text-slate-400">Pertanyaan ini akan dijadikan referensi resmi untuk Web & AI Chatbot.</p>
                    </div>
                </div>
                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form :action="formUrl" method="POST" class="space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Pertanyaan (Question) *</label>
                    <input type="text" 
                           name="question" 
                           x-model="question" 
                           required 
                           class="w-full px-4 py-2.5 bg-slate-800/90 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none"
                           placeholder="Contoh: Bagaimana cara mengaktifkan akun SSO Untan?">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Jawaban Resmi (Answer) *</label>
                    <textarea name="answer" 
                              rows="5" 
                              x-model="answer" 
                              required 
                              class="w-full px-4 py-2.5 bg-slate-800/90 border border-slate-700 rounded-xl text-white text-xs leading-relaxed focus:border-indigo-500 focus:outline-none"
                              placeholder="Tuliskan solusi praktis dan panduan langkah demi langkah yang jelas..."></textarea>
                </div>

                <!-- Status & Urutan Tampil Section -->
                <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800 space-y-3">
                    <!-- Checkbox Tampilkan di Halaman Web Profil -->
                    <div class="flex items-start gap-2.5">
                        <input type="checkbox" 
                               name="is_active" 
                               id="faq_is_active" 
                               value="1" 
                               x-model="is_active" 
                               class="mt-0.5 rounded border-slate-700 text-indigo-600 focus:ring-indigo-500 bg-slate-800 cursor-pointer">
                        <label for="faq_is_active" class="cursor-pointer">
                            <span class="text-xs text-slate-200 font-bold block">Tampilkan di Halaman Web Profil</span>
                            <span class="text-[11px] text-slate-400 block mt-0.5">Jika dicentang, FAQ akan muncul di website publik dan Anda dapat mengatur nomor urutan posisinya.</span>
                        </label>
                    </div>

                    <!-- Input Urutan Tampil (Hanya muncul jika is_active tercentang) -->
                    <div x-show="is_active" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="pt-2 border-t border-slate-800/80">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                            <span>Urutan Tampil di Web Profil *</span>
                            <span class="text-[10px] text-slate-500 font-normal lowercase">(nomor urutan dari atas ke bawah)</span>
                        </label>
                        <input type="number" 
                               name="sort_order" 
                               x-model="sort_order" 
                               min="1" 
                               class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none placeholder-slate-500"
                               placeholder="Contoh: 1, 2, 3...">
                    </div>

                    <!-- Info Khusus Chatbot jika tidak tercentang -->
                    <div x-show="!is_active" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="p-2.5 rounded-lg bg-indigo-950/40 border border-indigo-500/30 flex items-center gap-2 text-indigo-300">
                        <i data-lucide="bot" class="w-4 h-4 text-indigo-400 shrink-0"></i>
                        <span class="text-[11px]">Mode: <strong>Khusus Data Dukung Chatbot & AI</strong>. Urutan data akan diatur otomatis oleh sistem dan tidak tampil di web profil publik.</span>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-800">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-1.5 cursor-pointer">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span x-text="editMode ? 'Simpan Perubahan' : 'Simpan FAQ'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. MODAL: IMPOR DATA FAQ (SMART FUZZY PARSER) -->
    <div x-show="showImportModal" 
         x-transition.opacity 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" 
         style="display: none;">
        <div class="bg-slate-900 border border-indigo-500/40 rounded-2xl w-full max-w-xl overflow-hidden shadow-2xl space-y-4 p-6"
             @click.outside="showImportModal = false">
            
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center border border-indigo-500/30">
                        <i data-lucide="upload-cloud" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">Impor Basis Pengetahuan FAQ</h3>
                        <p class="text-[11px] text-slate-400">Mendukung format file <strong>JSON</strong> dan <strong>Excel / CSV</strong> dengan deteksi kolom otomatis.</p>
                    </div>
                </div>
                <button type="button" @click="showImportModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form action="{{ route('admin.faqs.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf

                <!-- File Input -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Pilih File (.json, .csv, .txt) *</label>
                    <div class="border-2 border-dashed border-slate-700 hover:border-indigo-500/50 rounded-xl p-4 bg-slate-950/40 text-center transition-colors">
                        <input type="file" 
                               name="faq_file" 
                               required 
                               accept=".json,.csv,.txt" 
                               class="w-full text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer">
                        <p class="text-[10px] text-slate-500 mt-2">Nama kolom tidak harus kaku, parser cerdas akan otomatis mendeteksi kolom pertanyaan dan jawaban.</p>
                    </div>
                </div>

                <!-- Pilihan Target Tayang (Chatbot Only vs Web) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Target Penempatan Data *</label>
                    <div class="space-y-2">
                        <label class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-800/80 border border-slate-700 cursor-pointer hover:border-indigo-500/40 transition-colors">
                            <input type="radio" name="target_status" value="chatbot_only" x-model="targetStatus" class="mt-0.5 text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <span class="font-bold text-white block">🤖 Khusus Data Dukung Chatbot & AI (Rekomendasi)</span>
                                <span class="text-[11px] text-slate-400">Data ribuan FAQ/SOP akan dipelajari oleh Chatbot, tetapi <strong>TIDAK MUNCUL</strong> di halaman web profil.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-800/80 border border-slate-700 cursor-pointer hover:border-indigo-500/40 transition-colors">
                            <input type="radio" name="target_status" value="public_web" x-model="targetStatus" class="mt-0.5 text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <span class="font-bold text-white block">🌐 Tampilkan di Web Profil Publik & Chatbot</span>
                                <span class="text-[11px] text-slate-400">FAQ akan tampil di daftar tanya jawab website publik dan juga dijawab oleh Chatbot.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-800/80 border border-slate-700 cursor-pointer hover:border-indigo-500/40 transition-colors">
                            <input type="radio" name="target_status" value="from_file" x-model="targetStatus" class="mt-0.5 text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <span class="font-bold text-white block">📄 Sesuai Nilai di File</span>
                                <span class="text-[11px] text-slate-400">Mengikuti kolom <code>is_active</code> yang ditentukan di dalam file yang diunggah.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Strategi Impor -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Metode Impor *</label>
                    <select name="import_mode" x-model="importMode" class="w-full px-3.5 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none cursor-pointer">
                        <option value="append_skip">Tambahkan Data Baru (Lewati jika Pertanyaan Sudah Ada)</option>
                        <option value="append_update">Perbarui Jawaban (Jika Pertanyaan Sudah Ada di Database)</option>
                        <option value="replace_all">Ganti Seluruh Data (Kosongkan FAQ Lama & Masukkan Data Baru)</option>
                    </select>
                </div>

                <!-- Download Templates -->
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-950/60 border border-slate-800 text-[11px]">
                    <span class="text-slate-400">Belum punya format contoh?</span>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.faqs.template', 'json') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold flex items-center gap-1">
                            <i data-lucide="file-code" class="w-3 h-3"></i>
                            <span>Template JSON</span>
                        </a>
                        <span class="text-slate-600">•</span>
                        <a href="{{ route('admin.faqs.template', 'csv') }}" class="text-emerald-400 hover:text-emerald-300 font-semibold flex items-center gap-1">
                            <i data-lucide="file-spreadsheet" class="w-3 h-3"></i>
                            <span>Template CSV</span>
                        </a>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                    <button type="button" @click="showImportModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-1.5 cursor-pointer">
                        <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                        <span>Mulai Proses Impor</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
