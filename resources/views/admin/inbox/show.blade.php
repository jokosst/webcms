@extends('layouts.admin')

@section('title', 'Detail Pesan Inbox')
@section('header_title', 'Detail Pesan Masuk')

@push('styles')
<style>
    /* Dark Slate Theme Overrides for CKEditor 5 */
    .inbox-ckeditor-wrapper,
    .ck.ck-reset_all {
        --ck-color-base-background: #1e293b;
        --ck-color-base-foreground: #1e293b;
        --ck-color-base-border: #334155;
        --ck-color-base-focus: #6366f1;
        --ck-color-base-text: #f1f5f9;
        --ck-color-base-active: #4f46e5;
        --ck-color-base-active-focus: #6366f1;
        --ck-color-toolbar-background: #0f172a;
        --ck-color-toolbar-border: #334155;
        --ck-color-button-default-background: transparent;
        --ck-color-button-default-hover-background: #1e293b;
        --ck-color-button-default-active-background: #334155;
        --ck-color-button-on-background: rgba(99, 102, 241, 0.25);
        --ck-color-button-on-hover-background: rgba(99, 102, 241, 0.38);
        --ck-color-button-on-color: #818cf8;
        --ck-color-text: #e2e8f0;
        --ck-color-dropdown-panel-background: #0f172a;
        --ck-color-dropdown-panel-border: #334155;
        --ck-color-list-background: #0f172a;
        --ck-color-list-button-hover-background: #1e293b;
        --ck-color-list-button-on-background: #4f46e5;
        --ck-color-list-button-on-text: #ffffff;
        --ck-color-panel-background: #0f172a;
        --ck-color-panel-border: #334155;
        --ck-color-input-background: #1e293b;
        --ck-color-input-border: #475569;
        --ck-color-input-text: #f8fafc;
        --ck-color-labeled-field-label-background: #0f172a;
        --ck-color-tooltip-background: #020617;
        --ck-color-tooltip-text: #f8fafc;
        --ck-border-radius: 0.75rem;
    }

    .inbox-ckeditor-wrapper .ck.ck-toolbar {
        border-top-left-radius: 0.75rem !important;
        border-top-right-radius: 0.75rem !important;
        border-color: #334155 !important;
        padding: 0.35rem 0.5rem !important;
    }

    .inbox-ckeditor-wrapper .ck.ck-editor__main > .ck-editor__editable {
        background-color: rgba(30, 41, 59, 0.85) !important;
        color: #f1f5f9 !important;
        border-color: #334155 !important;
        border-bottom-left-radius: 0.75rem !important;
        border-bottom-right-radius: 0.75rem !important;
        min-height: 200px;
        max-height: 420px;
        padding: 0.65rem 1rem !important;
        font-size: 0.8125rem;
        line-height: 1.65;
    }

    .inbox-ckeditor-wrapper .ck.ck-editor__main > .ck-editor__editable.ck-focused {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.35) !important;
    }

    .inbox-ckeditor-wrapper .ck.ck-editor__editable .ck-placeholder::before {
        color: #94a3b8 !important;
        font-style: normal;
    }

    /* Restore rich text typography stripped by Tailwind reset */
    .inbox-ckeditor-wrapper .ck-content p,
    .inbox-rich-content p,
    .inbox-preview-rich-content p {
        margin-bottom: 0.65rem;
    }
    .inbox-ckeditor-wrapper .ck-content p:last-child,
    .inbox-rich-content p:last-child,
    .inbox-preview-rich-content p:last-child {
        margin-bottom: 0;
    }
    .inbox-ckeditor-wrapper .ck-content h2,
    .inbox-rich-content h2,
    .inbox-preview-rich-content h2 {
        font-size: 1.15rem;
        font-weight: 700;
        margin: 0.75rem 0 0.45rem;
    }
    .inbox-ckeditor-wrapper .ck-content h3,
    .inbox-rich-content h3,
    .inbox-preview-rich-content h3 {
        font-size: 1.02rem;
        font-weight: 700;
        margin: 0.65rem 0 0.4rem;
    }
    .inbox-ckeditor-wrapper .ck-content ul,
    .inbox-rich-content ul,
    .inbox-preview-rich-content ul {
        list-style-type: disc;
        padding-left: 1.35rem;
        margin: 0.5rem 0 0.75rem;
    }
    .inbox-ckeditor-wrapper .ck-content ol,
    .inbox-rich-content ol,
    .inbox-preview-rich-content ol {
        list-style-type: decimal;
        padding-left: 1.35rem;
        margin: 0.5rem 0 0.75rem;
    }
    .inbox-ckeditor-wrapper .ck-content li,
    .inbox-rich-content li,
    .inbox-preview-rich-content li {
        margin-bottom: 0.25rem;
    }
    .inbox-ckeditor-wrapper .ck-content blockquote,
    .inbox-rich-content blockquote {
        border-left: 3px solid #6366f1;
        padding-left: 0.85rem;
        margin: 0.6rem 0;
        color: #94a3b8;
        font-style: italic;
    }
    .inbox-preview-rich-content blockquote {
        border-left: 3px solid #06b6d4;
        padding: 0.35rem 0.75rem;
        margin: 0.6rem 0;
        background: #f1f5f9;
        color: #475569;
    }
    .inbox-ckeditor-wrapper .ck-content a,
    .inbox-rich-content a {
        color: #38bdf8;
        text-decoration: underline;
    }
    .inbox-preview-rich-content a {
        color: #0284c7;
        text-decoration: underline;
    }
    .inbox-ckeditor-wrapper .ck-content table,
    .inbox-rich-content table,
    .inbox-preview-rich-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 0.65rem 0;
    }
    .inbox-ckeditor-wrapper .ck-content th,
    .inbox-ckeditor-wrapper .ck-content td,
    .inbox-rich-content th,
    .inbox-rich-content td {
        border: 1px solid #334155;
        padding: 0.4rem 0.6rem;
    }
    .inbox-preview-rich-content th,
    .inbox-preview-rich-content td {
        border: 1px solid #cbd5e1;
        padding: 0.4rem 0.6rem;
    }
    .inbox-ckeditor-wrapper .ck-content figure.image,
    .inbox-rich-content figure.image,
    .inbox-preview-rich-content figure.image {
        margin: 0.75rem 0;
        text-align: center;
    }
    .inbox-ckeditor-wrapper .ck-content img,
    .inbox-rich-content img,
    .inbox-preview-rich-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        display: inline-block;
    }
    .inbox-ckeditor-wrapper .ck-content figcaption,
    .inbox-rich-content figcaption,
    .inbox-preview-rich-content figcaption {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 0.35rem;
    }
    .inbox-rich-content,
    .inbox-preview-rich-content {
        overflow-wrap: anywhere;
        word-break: break-word;
    }
    .inbox-preview-modal-card {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        max-height: 90vh;
        max-height: 90dvh;
    }
    .inbox-preview-scroll-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        overscroll-behavior: contain;
        -webkit-overflow-scrolling: touch;
    }
</style>
@endpush

@section('content')
<script>
    window.inboxReplyPanel = function () {
        return {
            showPreviewModal: false,
            replySubject: @json('Re: ' . ($lead->subject ?? 'Tanggapan Pesan Helpdesk')),
            replyMessage: @json(old('reply_message', '')),
            replyEditorEmpty: false,
            showAiPanel: false,
            aiMode: 'auto_draft',
            aiTone: 'formal',
            aiInstruction: '',
            aiLoading: false,
            aiError: '',
            aiDraftHtml: '',
            aiModelUsed: '',
            aiDraftEndpoint: @json(\Illuminate\Support\Facades\Route::has('admin.inbox.ai-draft') ? route('admin.inbox.ai-draft', $lead->id) : url('/dalam/inbox/' . $lead->id . '/ai-draft')),
            csrfToken: @json(csrf_token()),
            getPreviewHtml() {
                const raw = (this.replyMessage || '').trim();
                const plain = raw.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ').trim();
                const hasImage = /<img\b/i.test(raw);
                if (!plain && !hasImage) {
                    return '<span class="text-slate-400 italic">Tuliskan pesan balasan resmi Anda di kolom form untuk melihat tampilan teks balasan di sini...</span>';
                }
                return raw.replace(/<script[\s\S]*?>[\s\S]*?<\/script>/gi, '');
            },
            async generateAiDraft(mode) {
                if (this.aiLoading) {
                    return;
                }
                this.aiMode = mode || 'auto_draft';
                this.aiLoading = true;
                this.aiError = '';

                const currentHtml = window.inboxReplyEditor
                    ? window.inboxReplyEditor.getData()
                    : (this.replyMessage || '');

                try {
                    const res = await fetch(this.aiDraftEndpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify({
                            mode: this.aiMode,
                            tone: this.aiTone,
                            instruction: this.aiInstruction,
                            current_html: currentHtml
                        })
                    });

                    const data = await res.json().catch(() => ({}));
                    if (!res.ok || !data.success || !data.draft_html) {
                        this.aiError = data.message || 'Gagal membuat alternatif draf dari Gemini AI.';
                        return;
                    }

                    this.aiDraftHtml = data.draft_html;
                    this.aiModelUsed = data.model || '';
                } catch (err) {
                    this.aiError = 'Terjadi gangguan koneksi saat menghubungi layanan AI.';
                } finally {
                    this.aiLoading = false;
                }
            },
            applyAiDraft() {
                if (!this.aiDraftHtml) {
                    return;
                }
                if (window.inboxReplyEditor) {
                    window.inboxReplyEditor.setData(this.aiDraftHtml);
                } else {
                    const textarea = document.getElementById('reply_message_editor');
                    if (textarea) {
                        textarea.value = this.aiDraftHtml;
                    }
                    this.replyMessage = this.aiDraftHtml;
                }
                this.replyEditorEmpty = false;
            }
        };
    };
</script>
<div class="max-w-3xl space-y-6"
     x-data="inboxReplyPanel()"
     @inbox-reply-changed.window="replyMessage = $event.detail; replyEditorEmpty = false">
    
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
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-indigo-500/15 text-indigo-300 border border-indigo-500/30 font-mono text-xs font-bold">
                        <i data-lucide="ticket" class="w-3 h-3 text-indigo-400"></i>
                        <span>#TKT-{{ str_pad($lead->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </span>
                </div>
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
                @php
                    $savedReply = (string) $lead->reply_message;
                    $hasHtmlReply = $savedReply !== strip_tags($savedReply);
                    if ($hasHtmlReply) {
                        $safeHistoryHtml = strip_tags($savedReply, '<p><br><strong><b><em><i><u><s><ul><ol><li><blockquote><a><h1><h2><h3><h4><figure><figcaption><img><table><thead><tbody><tr><th><td><hr>');
                        $safeHistoryHtml = preg_replace('/\s+on[a-z]+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $safeHistoryHtml) ?? $safeHistoryHtml;
                    } else {
                        $safeHistoryHtml = nl2br(e($savedReply));
                    }
                @endphp
                <div class="p-4 bg-slate-950/80 rounded-xl border border-slate-800 text-slate-200 text-xs leading-relaxed mt-1 inbox-rich-content">
                    {!! $safeHistoryHtml !!}
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

            <div class="space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <label for="reply_message_editor" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Pesan Balasan <span class="text-rose-400">*</span></label>
                    <div class="flex items-center gap-2">
                        <button type="button"
                                @click="showAiPanel = !showAiPanel"
                                class="px-3 py-1.5 rounded-lg text-[11px] font-bold transition-all flex items-center gap-1.5 border"
                                :class="showAiPanel ? 'bg-indigo-600/20 border-indigo-500/50 text-indigo-300' : 'bg-slate-800/80 hover:bg-slate-800 border-slate-700 text-cyan-300'">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                            <span>Asisten Penulisan AI</span>
                        </button>
                    </div>
                </div>

                <!-- Panel Opsional Asisten AI (Gemini + FAQ Grounded) -->
                <div x-show="showAiPanel"
                     x-transition
                     style="display: none;"
                     class="p-4 rounded-2xl bg-slate-950/70 border border-indigo-500/30 space-y-3">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2 text-xs font-bold text-indigo-300">
                            <i data-lucide="bot" class="w-4 h-4 text-cyan-400"></i>
                            <span>Alternatif Draf &amp; Perapian Format AI (Gemini + FAQ)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[11px] text-slate-400">Gaya:</span>
                            <select x-model="aiTone" class="px-2.5 py-1 bg-slate-900 border border-slate-700 rounded-lg text-[11px] text-slate-200 focus:outline-none focus:border-indigo-500">
                                <option value="formal">Resmi Institusi</option>
                                <option value="friendly">Ramah &amp; Solutif</option>
                                <option value="concise">Singkat &amp; Padat</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <input type="text"
                               x-model="aiInstruction"
                               placeholder="Catatan opsional untuk AI (misal: Password sudah direset ke Untan#2026, minta ganti via sso.untan.ac.id)..."
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button"
                                @click="generateAiDraft('auto_draft')"
                                :disabled="aiLoading"
                                class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white text-[11px] font-bold rounded-lg transition-colors flex items-center gap-1.5">
                            <i data-lucide="wand-2" class="w-3.5 h-3.5"></i>
                            <span>Susun Jawaban (Tiket + FAQ)</span>
                        </button>
                        <button type="button"
                                @click="generateAiDraft('polish')"
                                :disabled="aiLoading"
                                class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 disabled:opacity-50 text-cyan-300 border border-slate-700 text-[11px] font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                            <span>Rapikan Isi Editor</span>
                        </button>
                        <button type="button"
                                @click="generateAiDraft('steps')"
                                :disabled="aiLoading"
                                class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 disabled:opacity-50 text-slate-200 border border-slate-700 text-[11px] font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                            <i data-lucide="list-ordered" class="w-3.5 h-3.5"></i>
                            <span>Buat Poin Langkah</span>
                        </button>
                        <button type="button"
                                @click="generateAiDraft('shorten')"
                                :disabled="aiLoading"
                                class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 disabled:opacity-50 text-slate-200 border border-slate-700 text-[11px] font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                            <i data-lucide="minimize-2" class="w-3.5 h-3.5"></i>
                            <span>Persingkat</span>
                        </button>
                    </div>

                    <div x-show="aiLoading" style="display: none;" class="flex items-center gap-2 text-xs text-cyan-300 py-1">
                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        <span>AI sedang menyusun alternatif balasan...</span>
                    </div>

                    <div x-show="aiError" style="display: none;" class="p-2.5 rounded-xl bg-rose-950/50 border border-rose-500/40 text-xs text-rose-300 flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                        <span x-text="aiError"></span>
                    </div>

                    <div x-show="aiDraftHtml && !aiLoading" style="display: none;" class="space-y-2.5 pt-1">
                        <div class="flex items-center justify-between text-[11px] text-emerald-300 font-semibold">
                            <span>Alternatif Draf dari AI (Belum masuk ke email sebelum Anda terapkan):</span>
                            <span x-show="aiModelUsed" class="text-slate-400 font-normal" x-text="'Model: ' + aiModelUsed"></span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-700 text-xs text-slate-200 leading-relaxed inbox-rich-content"
                             style="max-height: 240px; overflow-y: auto;"
                             x-html="aiDraftHtml"></div>
                        <div class="flex flex-wrap items-center justify-end gap-2">
                            <button type="button"
                                    @click="aiDraftHtml = ''"
                                    class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-semibold rounded-lg border border-slate-700 transition-colors">
                                Tutup Draf
                            </button>
                            <button type="button"
                                    @click="generateAiDraft(aiMode)"
                                    :disabled="aiLoading"
                                    class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-cyan-300 text-[11px] font-semibold rounded-lg border border-slate-700 transition-colors flex items-center gap-1">
                                <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                <span>Susun Ulang</span>
                            </button>
                            <button type="button"
                                    @click="applyAiDraft()"
                                    class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-[11px] font-bold rounded-lg transition-colors flex items-center gap-1.5">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                <span>Terapkan ke Editor</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="inbox-ckeditor-wrapper">
                    <textarea id="reply_message_editor"
                              name="reply_message"
                              rows="6"
                              placeholder="Tuliskan pesan balasan resmi untuk {{ $lead->name }} di sini..."
                              class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none leading-relaxed">{{ old('reply_message', '') }}</textarea>
                </div>
                <p x-show="replyEditorEmpty" style="display: none;" class="text-xs text-rose-400 font-semibold flex items-center gap-1.5 pt-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                    <span>Isi pesan balasan tidak boleh kosong sebelum mengirim email langsung.</span>
                </p>
                @error('reply_message')
                    <p class="text-xs text-rose-400 font-semibold flex items-center gap-1.5 pt-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div class="pt-3 border-t border-slate-800/80 space-y-4">
                <div class="p-3 bg-slate-950/60 rounded-xl border border-slate-800 flex items-center gap-2.5 text-[11px] text-slate-400">
                    <i data-lucide="info" class="w-4 h-4 text-cyan-400 shrink-0"></i>
                    <span>Pilih salah satu metode pembalasan di bawah. Balas via Gmail Web akan otomatis membuka akun <strong class="text-cyan-400">{{ config('services.gmail.user_email') ?: env('GMAIL_USER_EMAIL', 'tik@untan.ac.id') }}</strong>.</span>
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
         @keydown.escape.window="showPreviewModal = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         style="display: none;">
        
        <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-2xl w-full p-6 shadow-2xl overflow-hidden inbox-preview-modal-card" @click.away="showPreviewModal = false">
            
            <div class="shrink-0 flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2 text-cyan-400 font-bold text-sm">
                    <i data-lucide="layout-template" class="w-4 h-4"></i>
                    <span>Preview Template Email Balasan Resmi (HTML)</span>
                </div>
                <button type="button" @click="showPreviewModal = false" class="text-slate-400 hover:text-white transition-colors" aria-label="Tutup preview">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Scrollable HTML Email Card Mockup -->
            <div class="inbox-preview-scroll-body rounded-2xl">
                <div class="bg-slate-100 text-slate-800 rounded-2xl overflow-hidden border border-slate-300 font-sans shadow-md">
                    <!-- Header Dark Card -->
                    <div class="bg-slate-900 text-white p-6 border-b-2 border-cyan-500">
                        <h2 class="text-xl font-black text-cyan-400 tracking-wide uppercase">UPA. TIK</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Universitas Tanjungpura</p>
                    </div>

                    <!-- Body Content -->
                    <div class="p-6 space-y-5 bg-white">
                        <p class="text-sm font-semibold text-slate-900">Halo <strong class="text-indigo-600">{{ $lead->name }}</strong>,</p>
                        
                        <div class="text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-200 inbox-preview-rich-content"
                             x-html="getPreviewHtml()">
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
            </div>

            <!-- Modal Action Buttons -->
            <div class="shrink-0 flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-slate-800">
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

@push('scripts')
<!-- CKEditor 5 Classic CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    class InboxImageUploadAdapter {
        constructor(loader, uploadUrl, csrfToken) {
            this.loader = loader;
            this.uploadUrl = uploadUrl;
            this.csrfToken = csrfToken;
            this.controller = null;
        }

        upload() {
            return this.loader.file.then(file => new Promise((resolve, reject) => {
                this.controller = new AbortController();
                const formData = new FormData();
                formData.append('upload', file);

                fetch(this.uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData,
                    signal: this.controller.signal
                })
                .then(async res => {
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok || !data.url) {
                        const validationMsg = data.errors && data.errors.upload ? data.errors.upload[0] : null;
                        const msg = validationMsg || (data.error && data.error.message) || data.message || 'Gagal mengunggah gambar (maks. 5MB, JPG/PNG/GIF/WebP).';
                        return reject(msg);
                    }
                    resolve({ default: data.url });
                })
                .catch(err => {
                    if (err && err.name === 'AbortError') {
                        return reject();
                    }
                    reject('Terjadi kesalahan jaringan saat mengunggah gambar.');
                });
            }));
        }

        abort() {
            if (this.controller) {
                this.controller.abort();
            }
        }
    }

    function InboxImageUploadAdapterPlugin(editor) {
        const uploadUrl = '{{ \Illuminate\Support\Facades\Route::has('admin.inbox.upload-image') ? route('admin.inbox.upload-image') : url('/dalam/inbox/upload-image') }}';
        const csrfToken = '{{ csrf_token() }}';

        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new InboxImageUploadAdapter(loader, uploadUrl, csrfToken);
        };
    }

    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('reply_message_editor');
        const form = document.getElementById('reply-email-form');
        if (!textarea || typeof ClassicEditor === 'undefined') {
            return;
        }

        let editorInstance = null;

        ClassicEditor
            .create(textarea, {
                placeholder: textarea.getAttribute('placeholder') || 'Tuliskan pesan balasan resmi...',
                extraPlugins: [InboxImageUploadAdapterPlugin],
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', '|',
                    'bulletedList', 'numberedList', 'blockQuote', '|',
                    'uploadImage', 'insertTable', '|',
                    'undo', 'redo'
                ],
                image: {
                    toolbar: [
                        'imageTextAlternative', '|',
                        'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'
                    ]
                }
            })
            .then(editor => {
                editorInstance = editor;
                window.inboxReplyEditor = editor;

                editor.model.document.on('change:data', () => {
                    const html = editor.getData();
                    textarea.value = html;
                    window.dispatchEvent(new CustomEvent('inbox-reply-changed', { detail: html }));
                });
            })
            .catch(error => {
                console.error('Gagal menginisialisasi CKEditor:', error);
            });

        if (form) {
            form.addEventListener('submit', function (e) {
                if (!editorInstance) {
                    return;
                }

                const html = editorInstance.getData();
                textarea.value = html;

                // Validate non-empty message only for direct reply (not Gmail Web shortcut which allows fallback text)
                const submitter = e.submitter;
                const isGmailWebSubmit = submitter && submitter.hasAttribute('formaction');
                if (!isGmailWebSubmit) {
                    const plainText = html.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ').trim();
                    const hasImage = /<img\b/i.test(html);
                    if (!plainText && !hasImage) {
                        e.preventDefault();
                        const container = form.closest('[x-data]');
                        if (container && window.Alpine && Alpine.$data) {
                            Alpine.$data(container).replyEditorEmpty = true;
                        }
                        editorInstance.editing.view.focus();
                    }
                }
            });
        }
    });
</script>
@endpush
