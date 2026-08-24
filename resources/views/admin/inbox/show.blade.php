@extends('layouts.admin')

@section('title', 'Detail Pesan Inbox')
@section('header_title', 'Detail Pesan Masuk')

@section('content')
<div class="max-w-3xl space-y-6">
    
    <!-- Top Bar: Navigation Left, Status Selector Right -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.inbox.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white flex items-center gap-1.5 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Inbox</span>
        </a>

        <form action="{{ route('admin.inbox.status', $lead->id) }}" method="POST" class="flex items-center gap-2">
            @csrf
            @method('PATCH')
            <span class="text-xs text-slate-400 font-medium">Ubah Status:</span>
            <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:border-indigo-500 cursor-pointer">
                <option value="unread" {{ $lead->status === 'unread' ? 'selected' : '' }}>Unread</option>
                <option value="read" {{ $lead->status === 'read' ? 'selected' : '' }}>Read</option>
                <option value="replied" {{ $lead->status === 'replied' ? 'selected' : '' }}>Replied</option>
            </select>
        </form>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold flex items-center gap-2">
            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-semibold space-y-1">
            <div class="flex items-center gap-2 font-bold">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-400 shrink-0"></i>
                <span>Gagal Mengirim Email!</span>
            </div>
            <p class="text-[11px] text-rose-300/80 leading-relaxed">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Card Message Detail -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-6 shadow-lg">
        
        <!-- Header Message Info -->
        <div class="flex items-start justify-between border-b border-slate-800 pb-4">
            <div>
                <h3 class="text-lg font-bold text-white">{{ $lead->subject ?? 'Tanpa Subjek' }}</h3>
                <p class="text-xs text-slate-400 mt-1">Diterima pada {{ $lead->created_at->format('d F Y, H:i') }} ({{ $lead->created_at->diffForHumans() }})</p>
            </div>
            <div>
                @if($lead->status === 'unread')
                    <span class="px-3 py-1 text-xs font-semibold bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-full inline-flex items-center gap-1">
                        <i data-lucide="mail-warning" class="w-3 h-3"></i> Unread
                    </span>
                @elseif($lead->status === 'read')
                    <span class="px-3 py-1 text-xs font-semibold bg-sky-500/20 text-sky-400 border border-sky-500/30 rounded-full inline-flex items-center gap-1">
                        <i data-lucide="mail-open" class="w-3 h-3"></i> Read
                    </span>
                @else
                    <span class="px-3 py-1 text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full inline-flex items-center gap-1">
                        <i data-lucide="check-circle-2" class="w-3 h-3"></i> Replied
                    </span>
                @endif
            </div>
        </div>

        <!-- Sender Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-slate-800/50 rounded-xl border border-slate-700/60 text-sm">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Pengirim</span>
                <span class="font-bold text-white block mt-0.5">{{ $lead->name }}</span>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Email</span>
                <a href="mailto:{{ $lead->email }}" class="text-cyan-400 hover:underline block mt-0.5 font-medium">{{ $lead->email }}</a>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Telepon / WA</span>
                <span class="text-slate-200 block mt-0.5 font-medium">{{ $lead->phone ?? '-' }}</span>
            </div>
        </div>

        <!-- Original Message Content -->
        <div class="space-y-2">
            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Isi Pesan Masuk</h4>
            <div class="p-4 bg-slate-950 rounded-xl border border-slate-800 text-sm text-slate-200 leading-relaxed whitespace-pre-line">
                {{ $lead->message }}
            </div>
        </div>

        <!-- Delete Action Row -->
        <div class="flex justify-end pt-2 border-t border-slate-800">
            <form action="{{ route('admin.inbox.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini secara permanen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3.5 py-1.5 bg-rose-600/20 hover:bg-rose-600/30 text-rose-400 border border-rose-500/30 font-semibold text-xs rounded-xl transition-all flex items-center gap-1.5">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    <span>Hapus Pesan</span>
                </button>
            </form>
        </div>
    </div>

    <!-- FORM BALAS EMAIL LANGSUNG FROM DASHBOARD ADMIN -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-5 shadow-lg">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <div class="flex items-center gap-2">
                <i data-lucide="send" class="w-5 h-5 text-indigo-400"></i>
                <h3 class="text-base font-bold text-white">Balas Email Langsung (via SMTP Gmail)</h3>
            </div>
            <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $lead->email }}&su=Re: {{ urlencode($lead->subject ?? 'Pesan Helpdesk') }}" 
               target="_blank" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 hover:underline flex items-center gap-1">
                <span>Buka di Gmail Web</span>
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <form action="{{ route('admin.inbox.reply', $lead->id) }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Tujuan Email</label>
                <input type="email" value="{{ $lead->email }}" readonly disabled 
                       class="w-full px-4 py-2.5 bg-slate-800/40 border border-slate-800 rounded-xl text-slate-400 text-xs cursor-not-allowed">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Subjek Email Balasan <span class="text-rose-400">*</span></label>
                <input type="text" name="subject" value="{{ old('subject', 'Re: ' . ($lead->subject ?? 'Tanggapan Pesan Helpdesk')) }}" required 
                       class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Pesan Balasan <span class="text-rose-400">*</span></label>
                <textarea name="reply_message" rows="5" required placeholder="Tuliskan pesan balasan resmi untuk {{ $lead->name }} di sini..." 
                          class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none leading-relaxed">{{ old('reply_message') }}</textarea>
            </div>

            <div class="pt-3 border-t border-slate-800/80 space-y-4">
                <div class="p-3 bg-slate-950/60 rounded-xl border border-slate-800 flex items-center gap-2.5 text-[11px] text-slate-400">
                    <i data-lucide="info" class="w-4 h-4 text-cyan-400 shrink-0"></i>
                    <span>Email akan dikirim menggunakan konfigurasi SMTP Gmail yang terpasang di file <code class="text-cyan-400 font-mono px-1 py-0.5 bg-slate-800 rounded">.env</code>.</span>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/20 transition-all flex items-center justify-center gap-2 uppercase tracking-wider">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Kirim Email Balasan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
