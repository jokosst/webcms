@extends('layouts.admin')

@section('title', 'Komentar Artikel')
@section('header_title', 'Kelola & Balas Komentar Artikel')

@section('content')
<div class="space-y-6 max-w-5xl">
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-400">Kelola dan berikan balasan resmi admin/operator terhadap komentar pembaca pada artikel.</p>
    </div>

    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-6 shadow-lg">
        <div class="space-y-6">
            @forelse($comments as $c)
                <div class="p-5 rounded-xl bg-slate-800/60 border border-slate-800 space-y-4" x-data="{ replying: false }">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-800 pb-3">
                        <div>
                            <span class="text-xs text-slate-400 font-medium">Artikel:</span>
                            <a href="{{ route('admin.articles.show', $c->article->id ?? 1) }}" class="font-bold text-sm text-indigo-400 hover:underline block sm:inline ml-1">
                                {{ $c->article->title ?? 'Artikel' }}
                            </a>
                        </div>
                        <span class="text-xs text-slate-500">{{ $c->created_at->format('d M Y, H:i') }} ({{ $c->created_at->diffForHumans() }})</span>
                    </div>

                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center font-bold text-indigo-400 text-sm shrink-0">
                                {{ strtoupper(substr($c->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white flex items-center gap-2">
                                    <span>{{ $c->name }}</span>
                                    @if($c->is_admin_reply)
                                        <span class="px-2 py-0.5 text-[10px] font-extrabold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 rounded-full">Admin</span>
                                    @endif
                                </h4>
                                <span class="text-xs text-slate-400">{{ $c->email }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button @click="replying = !replying" class="px-3 py-1.5 bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-400 text-xs font-semibold rounded-lg border border-indigo-500/30 transition-colors flex items-center gap-1.5">
                                <i data-lucide="reply" class="w-3.5 h-3.5"></i>
                                <span>Balas Komentar</span>
                            </button>
                            <form action="{{ route('admin.comments.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors" title="Hapus Komentar">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <p class="text-sm text-slate-200 leading-relaxed bg-slate-950 p-3.5 rounded-xl border border-slate-800">
                        "{{ $c->comment }}"
                    </p>

                    <!-- Form Balasan Admin -->
                    <div x-show="replying" x-transition class="pt-2">
                        <form action="{{ route('admin.comments.reply', $c->id) }}" method="POST" class="space-y-3 p-4 bg-slate-950 rounded-xl border border-indigo-500/30">
                            @csrf
                            <label class="block text-xs font-semibold text-indigo-400">Ketikkan Balasan Resmi Admin / Operator:</label>
                            <textarea name="comment" rows="3" required class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none" placeholder="Tuliskan jawaban atau tanggapan resmi..."></textarea>
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="replying = false" class="px-3.5 py-1.5 bg-slate-800 text-slate-400 hover:text-white text-xs font-semibold rounded-lg">Batal</button>
                                <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-lg shadow-lg shadow-indigo-600/30 flex items-center gap-1.5">
                                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                                    <span>Kirim Balasan</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Balasan-balasan Terkait -->
                    @if($c->replies->count() > 0)
                        <div class="pl-6 space-y-3 pt-3 border-t border-slate-800/80">
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Balasan Terkait ({{ $c->replies->count() }}):</span>
                            @foreach($c->replies as $reply)
                                <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800 space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-xs text-white">{{ $reply->name }}</span>
                                            @if($reply->is_admin_reply)
                                                <span class="px-2 py-0.5 text-[9px] font-extrabold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 rounded-full">Admin / Operator</span>
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
                <div class="text-center py-12 text-slate-500">Belum ada komentar artikel dari pembaca.</div>
            @endforelse
        </div>

        @if($comments->hasPages())
            <div class="pt-4 border-t border-slate-800">
                {{ $comments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
