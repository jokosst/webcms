@extends('layouts.admin')

@section('title', 'Kategori')
@section('header_title', 'Kelola Kategori Konten')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah Kategori Baru -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg h-fit">
        <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-5 h-5 text-indigo-400"></i>
            <span>Tambah Kategori Baru</span>
        </h3>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nama Kategori <span class="text-rose-400">*</span></label>
                <input type="text" name="name" required class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Aplikasi Web">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Tipe Kategori <span class="text-rose-400">*</span></label>
                <select name="type" required class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
                    <option value="article">Artikel / Berita</option>
                    <option value="service">Layanan / Produk</option>
                    <option value="portfolio">Portofolio & Proyek</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Deskripsi Kategori</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Penjelasan kategori..."></textarea>
            </div>

            <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Simpan Kategori</span>
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="lg:col-span-2 bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
        <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
            <i data-lucide="folder" class="w-5 h-5 text-indigo-400"></i>
            <span>Daftar Kategori Konten</span>
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/60 border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3">Nama Kategori</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-4 py-3.5 font-bold text-white">{{ $cat->name }}</td>
                            <td class="px-4 py-3.5">
                                @if($cat->type === 'article')
                                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full">Artikel</span>
                                @elseif($cat->type === 'service')
                                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 rounded-full">Layanan</span>
                                @else
                                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-violet-500/20 text-violet-400 border border-violet-500/30 rounded-full">Portofolio</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-xs font-mono text-slate-400">{{ $cat->slug }}</td>
                            <td class="px-4 py-3.5 text-right">
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-500">Belum ada kategori. Isi form di samping untuk membuat kategori baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
