@extends('layouts.admin')

@section('title', 'Hero Banners')
@section('header_title', 'Kelola Hero Banners Slider')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-400">Daftar banner slider yang tampil pada bagian paling atas halaman utama web profil.</p>
        <a href="{{ route('admin.hero-banners.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-1.5">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Banner Baru</span>
        </a>
    </div>

    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/60 border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5 w-16">Urutan</th>
                        <th class="px-4 py-3.5">Banner</th>
                        <th class="px-4 py-3.5">Tombol CTA</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($banners as $b)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-4 py-4 font-bold text-center text-indigo-400">{{ $b->sort_order }}</td>
                            <td class="px-4 py-4 space-y-1">
                                <p class="font-bold text-white text-base">{{ $b->title }}</p>
                                <p class="text-xs text-slate-400">{{ $b->subtitle ?? '-' }}</p>
                                @if($b->image_path)
                                    <div class="pt-1">
                                        <img src="{{ asset('storage/' . $b->image_path) }}" alt="Banner" class="h-12 w-32 object-cover rounded-lg border border-slate-700">
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($b->button_text)
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-slate-800 text-slate-300 rounded-lg border border-slate-700">
                                        {{ $b->button_text }} ({{ $b->button_link }})
                                    </span>
                                @else
                                    <span class="text-xs text-slate-500">Tanpa Tombol</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($b->is_active)
                                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full">Aktif</span>
                                @else
                                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-slate-800 text-slate-400 border border-slate-700 rounded-full">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.hero-banners.edit', $b->id) }}" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-lg transition-colors">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.hero-banners.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus banner ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada Hero Banner. Klik tombol di atas untuk membuat banner baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
