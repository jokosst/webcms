@extends('layouts.admin')

@section('title', 'Inbox Pesan')
@section('header_title', 'Inbox Pesan Masuk (Leads)')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-400">Pesan dari calon konsumen/klien yang dikirim melalui form kontak web profil.</p>
    </div>

    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/60 border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5">Pengirim</th>
                        <th class="px-4 py-3.5">Subjek</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5">Tanggal</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($leads as $l)
                        <tr class="hover:bg-slate-800/40 transition-colors {{ $l->status === 'unread' ? 'bg-indigo-950/20 font-semibold' : '' }}">
                            <td class="px-4 py-4">
                                <p class="text-white">{{ $l->name }}</p>
                                <p class="text-xs text-slate-400 font-normal">{{ $l->email }} | {{ $l->phone ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-4 text-slate-300 truncate max-w-xs">{{ $l->subject ?? '-' }}</td>
                            <td class="px-4 py-4">
                                @if($l->status === 'unread')
                                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-full">Unread</span>
                                @elseif($l->status === 'read')
                                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-sky-500/20 text-sky-400 border border-sky-500/30 rounded-full">Read</span>
                                @else
                                    <span class="px-2.5 py-0.5 text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full">Replied</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-400">{{ $l->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.inbox.show', $l->id) }}" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-lg transition-colors" title="Baca Pesan">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.inbox.destroy', $l->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
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
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada pesan masuk di inbox.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leads->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $leads->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
