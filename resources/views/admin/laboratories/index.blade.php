@extends('layouts.admin')

@section('title', 'Data Laboratorium Komputer')
@section('header_title', 'Master Data Laboratorium Komputer')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <i data-lucide="monitor" class="w-5 h-5 text-indigo-400"></i>
                <span>Master Data Laboratorium</span>
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Kelola ruangan laboratorium komputer, kapasitas PC, penanggung jawab (PIC), dan status aktif.</p>
        </div>
        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('lab-schedule.index') }}" target="_blank" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700 flex items-center gap-1.5 transition-colors">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Kalender Publik</span>
            </a>
            <a href="{{ route('admin.laboratories.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Lab</span>
            </a>
        </div>
    </div>

    <!-- Simplified Laboratorium Table (Fits Perfectly in 1 Screen) -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="text-slate-400 uppercase bg-slate-800/60 border-b border-slate-800 text-[11px] tracking-wider font-bold">
                    <tr>
                        <th class="px-4 py-3.5 w-12 text-center whitespace-nowrap">#</th>
                        <th class="px-4 py-3.5">Laboratorium & Lokasi</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Kapasitas</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">PIC / Pengelola</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Reservasi</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Status</th>
                        <th class="px-4 py-3.5 text-right w-24 whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70 font-medium">
                    @forelse($laboratories as $lab)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-4 py-3.5 text-center font-bold text-slate-400 font-mono whitespace-nowrap">
                                {{ $lab->order_index }}
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0 border border-indigo-500/20" title="{{ $lab->pc_specs ?? 'Spesifikasi PC' }}">
                                        <i data-lucide="monitor" class="w-4 h-4"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-white text-xs sm:text-sm leading-snug truncate">{{ $lab->name }}</p>
                                        <p class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                                            <i data-lucide="map-pin" class="w-3 h-3 text-slate-500 shrink-0"></i>
                                            <span>{{ $lab->room_number ?? 'Gedung TIK' }}</span>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                    {{ $lab->capacity }} PC
                                </span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($lab->pic_name)
                                    <p class="font-bold text-slate-200 text-xs">{{ $lab->pic_name }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">{{ $lab->pic_contact ?? '-' }}</p>
                                @else
                                    <span class="text-slate-500 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700 shadow-sm">
                                    <i data-lucide="calendar" class="w-3 h-3 text-cyan-400"></i>
                                    <span>{{ $lab->approved_bookings_count ?? 0 }} Disetujui</span>
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                @if($lab->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[11px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[11px] font-bold bg-slate-800 text-slate-400 border border-slate-700 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                        <span>Non-Aktif</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.laboratories.edit', $lab->id) }}" 
                                       class="p-1.5 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-lg transition-colors cursor-pointer" 
                                       title="Edit Lab">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.laboratories.destroy', $lab->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data laboratorium ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors cursor-pointer" title="Hapus Lab">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-500 space-y-2">
                                <i data-lucide="monitor-off" class="w-10 h-10 mx-auto text-slate-600"></i>
                                <p class="text-sm font-semibold text-slate-400">Belum ada data laboratorium yang didaftarkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
