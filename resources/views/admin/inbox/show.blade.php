@extends('layouts.admin')

@section('title', 'Detail Pesan Inbox')
@section('header_title', 'Detail Pesan Masuk')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.inbox.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Inbox</span>
        </a>

        <form action="{{ route('admin.inbox.status', $lead->id) }}" method="POST" class="flex items-center gap-2">
            @csrf
            @method('PATCH')
            <span class="text-xs text-slate-400 font-medium">Ubah Status:</span>
            <select name="status" onchange="this.form.submit()" class="px-3 py-1 bg-slate-800 border border-slate-700 rounded-lg text-xs text-white focus:outline-none">
                <option value="unread" {{ $lead->status === 'unread' ? 'selected' : '' }}>Unread</option>
                <option value="read" {{ $lead->status === 'read' ? 'selected' : '' }}>Read</option>
                <option value="replied" {{ $lead->status === 'replied' ? 'selected' : '' }}>Replied</option>
            </select>
        </form>
    </div>

    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-6 shadow-lg">
        <div class="flex items-start justify-between border-b border-slate-800 pb-4">
            <div>
                <h3 class="text-lg font-bold text-white">{{ $lead->subject ?? 'Tanpa Subjek' }}</h3>
                <p class="text-xs text-slate-400 mt-1">Diterima pada {{ $lead->created_at->format('d F Y, H:i') }} ({{ $lead->created_at->diffForHumans() }})</p>
            </div>
            <div>
                @if($lead->status === 'unread')
                    <span class="px-3 py-1 text-xs font-semibold bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-full">Unread</span>
                @elseif($lead->status === 'read')
                    <span class="px-3 py-1 text-xs font-semibold bg-sky-500/20 text-sky-400 border border-sky-500/30 rounded-full">Read</span>
                @else
                    <span class="px-3 py-1 text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full">Replied</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-slate-800/50 rounded-xl border border-slate-700/60 text-sm">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Pengirim</span>
                <span class="font-bold text-white block mt-0.5">{{ $lead->name }}</span>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Email</span>
                <a href="mailto:{{ $lead->email }}" class="text-indigo-400 hover:underline block mt-0.5">{{ $lead->email }}</a>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Telepon</span>
                <span class="text-slate-200 block mt-0.5">{{ $lead->phone ?? '-' }}</span>
            </div>
        </div>

        <div class="space-y-2">
            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Isi Pesan</h4>
            <div class="p-4 bg-slate-950 rounded-xl border border-slate-800 text-sm text-slate-200 leading-relaxed whitespace-pre-line">
                {{ $lead->message }}
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-slate-800">
            <a href="mailto:{{ $lead->email }}?subject=Re: {{ $lead->subject }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-1.5">
                <i data-lucide="reply" class="w-4 h-4"></i>
                <span>Balas via Email</span>
            </a>

            <form action="{{ route('admin.inbox.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini secara permanen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-rose-600/20 hover:bg-rose-600/30 text-rose-400 border border-rose-500/30 font-semibold text-xs rounded-xl transition-all flex items-center gap-1.5">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <span>Hapus Pesan</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
