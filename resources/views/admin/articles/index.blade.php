@extends('layouts.admin')

@section('title', 'Berita / Artikel')
@section('header_title', 'Kelola Berita & Artikel')

@section('content')
<div class="space-y-6">
    <!-- Header Top Row: Title Left, Add Button Right -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-white">Daftar Berita & Artikel</h2>
            <p class="text-xs text-slate-400 mt-0.5">Kelola publikasi artikel blog dan berita perusahaan</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-1.5 shrink-0 self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tulis Artikel Baru</span>
        </a>
    </div>

    <!-- Filter Bar: Search Input & Per Page Selector in One Row -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-4 shadow-lg">
        <form action="{{ route('admin.articles.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Left Side: Search Input -->
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul atau konten artikel..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none">
            </div>

            <!-- Right Side: Per Page Selector & Filter Button -->
            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                <span class="text-xs text-slate-400 font-medium whitespace-nowrap">Tampilkan per halaman:</span>
                <select name="per_page" onchange="this.form.submit()" class="px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-white focus:outline-none cursor-pointer">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 baris</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 baris</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 baris</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 baris</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl border border-slate-700 transition-colors">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Articles Table -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/60 border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5 w-24">Gambar</th>
                        <th class="px-4 py-3.5">Artikel</th>
                        <th class="px-4 py-3.5">Kategori</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5">Komentar</th>
                        <th class="px-4 py-3.5">Views</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($articles as $a)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-4 py-4">
                                @if($a->featured_image)
                                    <img src="{{ asset('storage/' . $a->featured_image) }}" alt="{{ $a->title }}" class="w-16 h-12 object-cover rounded-lg border border-slate-700">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-500">
                                        <i data-lucide="newspaper" class="w-6 h-6"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4 space-y-1">
                                <a href="{{ route('admin.articles.show', $a->id) }}" class="font-bold text-white text-base max-w-md line-clamp-1 hover:text-indigo-400 transition-colors">
                                    {{ $a->title }}
                                </a>
                                <p class="text-xs text-slate-400 line-clamp-1 max-w-md">{{ $a->excerpt ?? '-' }}</p>
                                <span class="text-[11px] text-slate-500 block">📅 Terbit: {{ $a->published_at ? $a->published_at->format('d M Y, H:i') : 'Draft (Belum terbit)' }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-xs font-semibold text-slate-300 whitespace-nowrap">
                                    {{ $a->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @if($a->status === 'published')
                                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full">Published</span>
                                @else
                                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-full">Draft</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.articles.show', $a->id) }}" class="px-2.5 py-1 text-xs font-semibold bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 border border-indigo-500/20 rounded-lg inline-flex items-center gap-1 transition-colors">
                                    <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                                    <span>{{ number_format($a->all_comments_count) }} Komen</span>
                                </a>
                            </td>
                            <td class="px-4 py-4 text-xs font-mono text-slate-400">{{ number_format($a->views_count) }}</td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.articles.show', $a->id) }}" class="p-2 text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/10 rounded-lg transition-colors" title="View Detail & Balas Komentar">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.articles.edit', $a->id) }}" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-lg transition-colors" title="Edit Artikel">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors" title="Hapus Artikel">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">Tidak ada artikel ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
