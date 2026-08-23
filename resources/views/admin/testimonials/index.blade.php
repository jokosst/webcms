@extends('layouts.admin')

@section('title', 'Testimoni & Ulasan')
@section('header_title', 'Kelola Testimoni & Ulasan Klien')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-400">Daftar ulasan dan testimoni kepuasan klien.</p>
        <a href="{{ route('admin.testimonials.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-1.5">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Testimoni Baru</span>
        </a>
    </div>

    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/60 border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5 w-20">Avatar</th>
                        <th class="px-4 py-3.5">Klien</th>
                        <th class="px-4 py-3.5">Perusahaan & Jabatan</th>
                        <th class="px-4 py-3.5">Rating</th>
                        <th class="px-4 py-3.5">Ulasan</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($testimonials as $t)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-4 py-4">
                                @if($t->avatar_path)
                                    <img src="{{ asset('storage/' . $t->avatar_path) }}" alt="{{ $t->client_name }}" class="w-10 h-10 object-cover rounded-full border border-slate-700">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-indigo-400">
                                        {{ strtoupper(substr($t->client_name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4 font-bold text-white">{{ $t->client_name }}</td>
                            <td class="px-4 py-4 text-slate-300">
                                {{ $t->client_company ?? '-' }}
                                <span class="block text-xs text-slate-400 font-normal">{{ $t->client_position ?? '' }}</span>
                            </td>
                            <td class="px-4 py-4 text-amber-400 font-bold flex items-center gap-1">
                                <span>{{ $t->rating }}</span>
                                <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-300 max-w-sm truncate">{{ $t->review_content }}</td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.testimonials.edit', $t->id) }}" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-lg transition-colors">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.testimonials.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus testimoni ini?')">
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
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada data testimoni.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
