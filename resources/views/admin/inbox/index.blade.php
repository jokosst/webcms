@extends('layouts.admin')

@section('title', 'Inbox Pesan')
@section('header_title', 'Inbox Pesan Masuk (Leads)')

@section('content')
<div class="space-y-6">
    
    <!-- Top Row: Page Title Left, Export Action Buttons Right -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-white font-heading">Inbox Pesan & Tiket Helpdesk</h2>
            <p class="text-xs text-slate-400 mt-0.5">Kelola pesan dan permintaan bantuan teknis yang dikirim lewat formulir web.</p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <!-- Export Excel Button -->
            <a href="{{ route('admin.inbox.export.excel', request()->all()) }}" 
               class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                <span>Export Excel</span>
            </a>

            <!-- Export PDF Button -->
            <a href="{{ route('admin.inbox.export.pdf', request()->all()) }}" target="_blank" 
               class="px-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-rose-600/20 transition-all flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span>Export PDF</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar Bar -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-4 shadow-lg">
        <form action="{{ route('admin.inbox.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Left Side: Search Input -->
            <div class="relative w-full md:w-96 shrink-0">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, email, telepon, subjek, pesan..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none placeholder-slate-500">
            </div>

            <!-- Right Side: Status Filter & Action Buttons -->
            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                <select name="status" class="px-3.5 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="">Semua Status Pesan</option>
                    <option value="unread" {{ $status === 'unread' ? 'selected' : '' }}>Belum Dibaca (Unread)</option>
                    <option value="read" {{ $status === 'read' ? 'selected' : '' }}>Sudah Dibaca (Read)</option>
                    <option value="replied" {{ $status === 'replied' ? 'selected' : '' }}>Sudah Dibalas (Replied)</option>
                </select>

                <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl border border-indigo-500 transition-colors shadow-md flex items-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Filter</span>
                </button>

                @if($search || $status)
                    <a href="{{ route('admin.inbox.index') }}" class="px-3.5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white text-xs font-semibold rounded-xl border border-slate-700 transition-colors flex items-center justify-center" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Table Container -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/60 border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5 w-12 text-center">No</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">No. Tiket</th>
                        <th class="px-4 py-3.5">Pengirim & Kontak</th>
                        <th class="px-4 py-3.5">Kategori / Subjek</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5">Tanggal</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($leads as $index => $l)
                        <tr class="hover:bg-slate-800/40 transition-colors {{ $l->status === 'unread' ? 'bg-indigo-950/25' : '' }}">
                            <td class="px-4 py-4 text-center font-bold text-slate-500 text-xs">
                                {{ $leads->firstItem() + $index }}
                            </td>
                            <!-- No. Tiket Column -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.inbox.show', $l->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 font-mono text-xs font-bold transition-colors group/tkt" title="Klik untuk lihat detail tiket">
                                    <i data-lucide="ticket" class="w-3.5 h-3.5 text-indigo-400 group-hover/tkt:scale-110 transition-transform"></i>
                                    <span>TKT-{{ str_pad($l->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </a>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-white font-bold flex items-center gap-2">
                                    <span>{{ $l->name }}</span>
                                    @if($l->status === 'unread')
                                        <span class="w-2 h-2 rounded-full bg-rose-500 inline-block animate-pulse" title="Pesan Baru"></span>
                                    @endif
                                </p>
                                <p class="text-xs text-slate-400 font-normal mt-0.5">
                                    {{ $l->email }}
                                    @if($l->phone) <span class="text-slate-600 mx-1">|</span> {{ $l->phone }} @endif
                                </p>
                            </td>
                            <td class="px-4 py-4 max-w-xs">
                                <p class="font-bold text-slate-200 truncate">{{ $l->subject ?? 'Pesan Umum' }}</p>
                                <p class="text-xs text-slate-400 truncate mt-0.5">{{ Str::limit($l->message, 45) }}</p>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($l->status === 'unread')
                                    <span class="px-2.5 py-1 text-xs font-extrabold bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-full inline-flex items-center gap-1.5">
                                        <i data-lucide="mail-warning" class="w-3.5 h-3.5"></i> Unread
                                    </span>
                                @elseif($l->status === 'read')
                                    <span class="px-2.5 py-1 text-xs font-extrabold bg-sky-500/20 text-sky-400 border border-sky-500/30 rounded-full inline-flex items-center gap-1.5">
                                        <i data-lucide="mail-open" class="w-3.5 h-3.5"></i> Read
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-extrabold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full inline-flex items-center gap-1.5">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Replied
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-400 whitespace-nowrap">
                                {{ $l->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.inbox.show', $l->id) }}" class="p-2 text-slate-400 hover:text-cyan-400 hover:bg-cyan-500/10 rounded-lg transition-colors" title="Baca Pesan & Detail">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.inbox.destroy', $l->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors" title="Hapus Pesan">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-500 space-y-2">
                                <i data-lucide="inbox" class="w-10 h-10 mx-auto text-slate-600 opacity-50"></i>
                                <p class="text-sm font-semibold text-slate-400">Tidak ada pesan masuk yang ditemukan.</p>
                                @if($search || $status)
                                    <a href="{{ route('admin.inbox.index') }}" class="text-xs text-cyan-400 hover:underline">Reset pencarian dan filter</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Pagination Bar -->
        <div class="p-4 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <p>
                Menampilkan <span class="font-bold text-white">{{ $leads->firstItem() ?? 0 }}</span> 
                sampai <span class="font-bold text-white">{{ $leads->lastItem() ?? 0 }}</span> 
                dari <span class="font-bold text-white">{{ $leads->total() }}</span> total pesan.
            </p>
            @if($leads->hasPages())
                <div>
                    {{ $leads->links() }}
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
