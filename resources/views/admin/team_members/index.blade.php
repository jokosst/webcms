@extends('layouts.admin')

@section('title', 'Tim & Manajemen')
@section('header_title', 'Kelola Tim & Manajemen')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-400">Daftar profil jajaran manajemen dan tim utama perusahaan.</p>
        <a href="{{ route('admin.team-members.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-1.5">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Anggota Tim</span>
        </a>
    </div>

    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/60 border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5 w-16">Urutan</th>
                        <th class="px-4 py-3.5 w-20">Foto</th>
                        <th class="px-4 py-3.5">Nama & Jabatan</th>
                        <th class="px-4 py-3.5">Bio Singkat</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($team as $m)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-4 py-4 font-bold text-center text-indigo-400">{{ $m->sort_order }}</td>
                            <td class="px-4 py-4">
                                @if($m->photo_path)
                                    <img src="{{ asset('storage/' . $m->photo_path) }}" alt="{{ $m->name }}" class="w-10 h-10 object-cover rounded-full border border-slate-700">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-indigo-400">
                                        {{ strtoupper(substr($m->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4 space-y-1">
                                <p class="font-bold text-white text-base">{{ $m->name }}</p>
                                <p class="text-xs text-indigo-400 font-medium">{{ $m->position }}</p>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-400 max-w-sm truncate">{{ $m->bio ?? '-' }}</td>
                            <td class="px-4 py-4">
                                @if($m->is_active)
                                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full">Aktif</span>
                                @else
                                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-slate-800 text-slate-400 border border-slate-700 rounded-full">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.team-members.edit', $m->id) }}" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-lg transition-colors">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.team-members.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus anggota tim ini?')">
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
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada anggota tim.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
