@extends('layouts.admin')

@section('title', 'Detail Pesan Inbox')
@section('header_title', 'Detail Pesan Masuk')

@section('content')
<div class="max-w-3xl space-y-6" x-data="{ 
    showPreviewModal: false, 
    replySubject: 'Re: {{ addslashes($lead->subject ?? 'Tanggapan Pesan Helpdesk') }}', 
    replyMessage: '{{ addslashes(old('reply_message', '')) }}' 
}">
    
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

    <!-- RIWAYAT BALASAN TERKIRIM (TERSIMPAN DI DATABASE) -->
    @if($lead->reply_message)
    <div class="bg-slate-900 rounded-2xl border border-emerald-500/30 p-6 space-y-4 shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400"></i>
                <h3 class="text-base font-bold text-white">Riwayat Balasan Terkirim</h3>
            </div>
            <span class="text-xs text-emerald-400 font-medium">
                Dibalas pada {{ $lead->replied_at ? $lead->replied_at->format('d F Y, H:i') : $lead->updated_at->format('d F Y, H:i') }} WIB
            </span>
        </div>

        <div class="space-y-3 text-sm">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Subjek Balasan:</span>
                <span class="font-bold text-emerald-300 block mt-0.5">{{ $lead->reply_subject ?? 'Re: ' . ($lead->subject ?? 'Tanggapan Pesan Helpdesk') }}</span>
            </div>

            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Isi Pesan Balasan:</span>
                <div class="p-4 bg-slate-950/80 rounded-xl border border-slate-800 text-slate-200 text-xs leading-relaxed whitespace-pre-line mt-1">
{{ $lead->reply_message }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- FORM BALAS EMAIL LANGSUNG -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-5 shadow-lg">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <div class="flex items-center gap-2">
                <i data-lucide="send" class="w-5 h-5 text-indigo-400"></i>
                <h3 class="text-base font-bold text-white">Balas Email Langsung</h3>
            </div>
            <button type="button" @click="showPreviewModal = true" class="text-xs font-semibold text-cyan-400 hover:text-cyan-300 hover:underline flex items-center gap-1">
                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                <span>Preview Template HTML</span>
            </button>
        </div>

        <form id="reply-email-form" action="{{ route('admin.inbox.reply', $lead->id) }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Tujuan Email</label>
                <input type="email" value="{{ $lead->email }}" readonly disabled 
                       class="w-full px-4 py-2.5 bg-slate-800/40 border border-slate-800 rounded-xl text-slate-400 text-xs cursor-not-allowed">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Subjek Email Balasan <span class="text-rose-400">*</span></label>
                <input type="text" name="subject" x-model="replySubject" required 
                       class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Pesan Balasan <span class="text-rose-400">*</span></label>
                <textarea name="reply_message" x-model="replyMessage" rows="5" required placeholder="Tuliskan pesan balasan resmi untuk {{ $lead->name }} di sini..." 
                          class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none leading-relaxed"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-800/80 space-y-4">
                <div class="p-3 bg-slate-950/60 rounded-xl border border-slate-800 flex items-center gap-2.5 text-[11px] text-slate-400">
                    <i data-lucide="info" class="w-4 h-4 text-cyan-400 shrink-0"></i>
                    <span>Pilih salah satu metode pembalasan di bawah. Balas via Gmail Web akan otomatis membuka akun <strong class="text-cyan-400">{{ env('GMAIL_USER_EMAIL', 'tik@untan.ac.id') }}</strong>.</span>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                    <button type="button" @click="showPreviewModal = true" class="w-full sm:w-auto px-4 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-xl border border-slate-700 transition-all flex items-center justify-center gap-2 uppercase tracking-wider">
                        <i data-lucide="eye" class="w-4 h-4 text-slate-400"></i>
                        <span>Preview HTML</span>
                    </button>

                    <button type="submit" formaction="{{ route('admin.inbox.reply-gmail-web', $lead->id) }}" formtarget="_blank" class="w-full sm:w-auto px-5 py-3 bg-slate-800 hover:bg-slate-700 text-cyan-400 font-bold text-xs rounded-xl border border-slate-700 transition-all flex items-center justify-center gap-2 uppercase tracking-wider">
                        <i data-lucide="external-link" class="w-4 h-4 text-cyan-400"></i>
                        <span>Balas via Gmail Web</span>
                    </button>

                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/20 transition-all flex items-center justify-center gap-2 uppercase tracking-wider">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Kirim Langsung</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- MODAL PREVIEW TEMPLATE HTML EMAIL -->
    <div x-show="showPreviewModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto"
         style="display: none;">
        
        <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-2xl w-full p-6 space-y-6 shadow-2xl overflow-hidden my-8" @click.away="showPreviewModal = false">
            
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2 text-cyan-400 font-bold text-sm">
                    <i data-lucide="layout-template" class="w-4 h-4"></i>
                    <span>Preview Template Email Balasan Resmi (HTML)</span>
                </div>
                <button type="button" @click="showPreviewModal = false" class="text-slate-400 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- HTML Email Card Mockup (Sesuai Desain Resmi) -->
            <div class="bg-slate-100 text-slate-800 rounded-2xl overflow-hidden border border-slate-300 font-sans shadow-md">
                <!-- Header Dark Card -->
                <div class="bg-slate-900 text-white p-6 border-b-2 border-cyan-500">
                    <h2 class="text-xl font-black text-cyan-400 tracking-wide uppercase">UPA. TIK</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Universitas Tanjungpura</p>
                </div>

                <!-- Body Content -->
                <div class="p-6 space-y-5 bg-white">
                    <p class="text-sm font-semibold text-slate-900">Halo <strong class="text-indigo-600">{{ $lead->name }}</strong>,</p>
                    
                    <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <span x-text="replyMessage || 'Tuliskan pesan balasan resmi Anda di kolom form untuk melihat tampilan teks balasan di sini...'"></span>
                    </div>

                    <!-- Quoted Message Box -->
                    <div class="p-4 bg-slate-100 rounded-xl border-l-4 border-cyan-500 text-xs space-y-1 text-slate-600">
                        <p class="font-bold text-slate-800">Pesan Asal Anda:</p>
                        <p><strong>Subjek:</strong> {{ $lead->subject ?? '-' }}</p>
                        <p><strong>Tanggal:</strong> {{ $lead->created_at->format('d M Y, H:i') }} WIB</p>
                        <p class="italic mt-1.5 text-slate-700">"{{ $lead->message }}"</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-slate-50 p-4 text-center border-t border-slate-200 text-xs text-slate-500">
                    <p>© {{ date('Y') }} UPA. TIK. All rights reserved.</p>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                <button type="button" @click="showPreviewModal = false" class="w-full sm:w-auto px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700 transition-colors">
                    ← Kembali Edit
                </button>

                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <button type="submit" form="reply-email-form" formaction="{{ route('admin.inbox.reply-gmail-web', $lead->id) }}" formtarget="_blank" class="px-5 py-2.5 bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-cyan-600/20 transition-all flex items-center gap-2 uppercase tracking-wider">
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                        <span>Buka & Kirim via Gmail Web</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
