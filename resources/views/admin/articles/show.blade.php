@extends('layouts.admin')

@section('title', 'Detail Artikel - ' . $article->title)
@section('header_title', 'View & Kelola Komentar Artikel')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.articles.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Artikel</span>
        </a>

        <a href="{{ route('admin.articles.edit', $article->id) }}" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg flex items-center gap-1.5">
            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
            <span>Edit Artikel</span>
        </a>
    </div>

    <!-- Article Preview Box -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-6 shadow-lg">
        <div class="space-y-3 border-b border-slate-800 pb-5">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 text-xs font-semibold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 rounded-full">
                    {{ $article->category->name ?? 'Uncategorized' }}
                </span>
                <span class="text-xs text-slate-400 font-medium">• {{ $article->published_at ? $article->published_at->format('d M Y, H:i') : 'Draft' }}</span>
                <span class="text-xs text-slate-400 font-medium">• {{ number_format($article->views_count) }} Views</span>
            </div>

            <h1 class="text-2xl font-extrabold text-white leading-snug">{{ $article->title }}</h1>
            <p class="text-sm font-medium text-slate-400 italic">{{ $article->excerpt }}</p>
        </div>

        @if($article->featured_image)
            <div class="rounded-xl overflow-hidden border border-slate-800 max-h-80">
                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <style>
            .article-body-content h1 { font-size: 1.75rem; font-weight: 800; margin-top: 1.5rem; margin-bottom: 0.75rem; color: #ffffff; }
            .article-body-content h2 { font-size: 1.45rem; font-weight: 800; margin-top: 1.25rem; margin-bottom: 0.75rem; color: #ffffff; }
            .article-body-content h3 { font-size: 1.25rem; font-weight: 700; margin-top: 1rem; margin-bottom: 0.5rem; color: #ffffff; }
            .article-body-content h4 { font-size: 1.1rem; font-weight: 700; margin-top: 0.85rem; margin-bottom: 0.5rem; color: #ffffff; }
            .article-body-content p { margin-bottom: 1.25rem; line-height: 1.8; color: #e2e8f0; }
            .article-body-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.25rem; color: #e2e8f0; }
            .article-body-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1.25rem; color: #e2e8f0; }
            .article-body-content li { margin-bottom: 0.35rem; }
            .article-body-content table { width: 100%; border-collapse: collapse; margin-top: 1rem; margin-bottom: 1.5rem; }
            .article-body-content table th, 
            .article-body-content table td { border: 1px solid #334155; padding: 0.65rem 0.85rem; text-align: left; color: #e2e8f0; }
            .article-body-content table th { background-color: rgba(99, 102, 241, 0.15); font-weight: 700; color: #ffffff; }
            .article-body-content img { max-width: 100%; height: auto; border-radius: 0.75rem; margin-top: 1rem; margin-bottom: 1rem; }
            .article-body-content blockquote { border-left: 4px solid #6366f1; padding-left: 1rem; font-style: italic; margin-top: 1rem; margin-bottom: 1.25rem; color: #cbd5e1; }
            .article-body-content a { color: #818cf8; text-decoration: underline; font-weight: 600; }
        </style>
        <div class="prose prose-invert max-w-none text-slate-200 text-sm leading-relaxed border-b border-slate-800 pb-6 article-body-content">
            {!! $article->content !!}
        </div>
    </div>

    <!-- Article Comments & Reply Section -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-6 shadow-lg">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div class="flex items-center gap-2">
                <i data-lucide="message-square" class="w-5 h-5 text-indigo-400"></i>
                <h3 class="text-base font-bold text-white">Komentar Pembaca ({{ $article->comments->count() }})</h3>
            </div>
        </div>

        <div class="space-y-6">
            @forelse($article->comments as $comment)
                <div class="p-4 rounded-xl bg-slate-800/60 border border-slate-800 space-y-4" x-data="{ replying: false }">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center font-bold text-indigo-400 text-sm">
                                {{ strtoupper(substr($comment->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white flex items-center gap-2">
                                    <span>{{ $comment->name }}</span>
                                    @if($comment->is_admin_reply)
                                        <span class="px-2 py-0.5 text-[10px] font-extrabold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 rounded-full">Admin</span>
                                    @endif
                                </h4>
                                <span class="text-[11px] text-slate-400">{{ $comment->email }} • {{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button @click="replying = !replying" class="px-3 py-1 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-400 text-xs font-semibold rounded-lg border border-indigo-500/30 transition-colors flex items-center gap-1">
                                <i data-lucide="reply" class="w-3.5 h-3.5"></i>
                                <span>Balas</span>
                            </button>
                            <form action="{{ route('admin.articles.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 rounded-lg">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <p class="text-sm text-slate-200 leading-relaxed pl-12">{{ $comment->comment }}</p>

                    <!-- Admin Reply Form -->
                    <div x-show="replying" x-transition class="pl-12 pt-2 space-y-3">
                        <form action="{{ route('admin.articles.comments.reply', [$article->id, $comment->id]) }}" method="POST" class="space-y-3 p-3 bg-slate-900 rounded-xl border border-slate-800">
                            @csrf
                            <label class="block text-xs font-semibold text-indigo-400">Tulis Balasan Admin:</label>
                            <textarea name="comment" rows="2" required class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-xs focus:outline-none" placeholder="Balasan resmi admin..."></textarea>
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="replying = false" class="px-3 py-1.5 bg-slate-800 text-slate-400 hover:text-white text-xs font-semibold rounded-lg">Batal</button>
                                <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-lg shadow">Kirim Balasan</button>
                            </div>
                        </form>
                    </div>

                    <!-- Nested Replies -->
                    @if($comment->replies->count() > 0)
                        <div class="pl-12 space-y-3 pt-2 border-t border-slate-800/60">
                            @foreach($comment->replies as $reply)
                                <div class="p-3 rounded-lg bg-slate-900/80 border border-slate-800/80 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-xs text-white">{{ $reply->name }}</span>
                                            @if($reply->is_admin_reply)
                                                <span class="px-2 py-0.5 text-[9px] font-extrabold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 rounded-full">Admin</span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-slate-500">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-slate-300">{{ $reply->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-8 text-slate-500 text-sm">Belum ada komentar pada artikel ini.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
