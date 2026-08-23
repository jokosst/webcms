@extends('layouts.public')

@section('title', $article->title . ' - ' . ($profile->company_name ?? 'Portal Resmi'))
@section('meta_description', $article->getExcerptClean(160))

@section('content')
    <!-- MAIN ARTICLE CONTENT -->
    <div class="py-10 lg:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- BREADCRUMB -->
            <nav class="flex items-center gap-2 text-xs font-semibold mb-8 overflow-x-auto pb-2"
                 :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                <a href="{{ route('home') }}" class="hover:text-cyan-500 transition-colors">{{ __('Beranda') }}</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400 flex-shrink-0"></i>
                <a href="{{ route('articles.index') }}" class="hover:text-cyan-500 transition-colors">{{ __('Berita TIK') }}</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400 flex-shrink-0"></i>
                <span class="truncate max-w-xs sm:max-w-md font-bold" :class="darkMode ? 'text-cyan-400' : 'text-slate-900'">{{ $article->title }}</span>
            </nav>

            <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- ARTICLE MAIN COLUMN -->
                <article class="lg:col-span-8 rounded-3xl border shadow-sm p-6 sm:p-10 space-y-8"
                         :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                    
                    <!-- Article Meta & Title Header -->
                    <div class="space-y-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="px-3.5 py-1.5 rounded-full bg-cyan-500/10 text-cyan-500 border border-cyan-500/20 text-xs font-extrabold tracking-wide uppercase">
                                {{ $article->category->name ?? 'Informasi' }}
                            </span>
                            <span class="text-xs font-semibold flex items-center gap-1.5" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                                <i data-lucide="calendar" class="w-4 h-4 text-cyan-500"></i>
                                {{ $article->published_at ? date('d F Y', strtotime($article->published_at)) : date('d F Y') }}
                            </span>
                            <span class="text-xs font-semibold flex items-center gap-1.5" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                                <i data-lucide="eye" class="w-4 h-4 text-slate-400"></i>
                                {{ number_format($article->views_count ?? 1) }}x {{ __('Jumlah Pembaca') }}
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-4xl font-black leading-tight font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">
                            {{ $article->title }}
                        </h1>

                        <!-- Author & Share Bar -->
                        <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t text-xs" :class="darkMode ? 'border-slate-800' : 'border-slate-100'">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-cyan-500 text-slate-950 flex items-center justify-center font-bold text-sm shadow-md">
                                    <i data-lucide="user" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span class="font-bold block" :class="darkMode ? 'text-white' : 'text-slate-900'">{{ $article->user->name ?? 'Admin' }}</span>
                                    <span class="text-slate-400 block">Tim Publikasi TIK</span>
                                </div>
                            </div>

                            <!-- Share Buttons -->
                            <div class="flex items-center gap-2">
                                <span class="text-slate-400 font-bold mr-1">{{ __('Bagikan Artikel Ini') }}:</span>
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' - ' . url()->current()) }}" target="_blank" class="p-2 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 transition-colors" title="Bagikan ke WhatsApp">
                                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="p-2 rounded-xl bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 transition-colors" title="Bagikan ke Facebook">
                                    <i data-lucide="share-2" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="rounded-2xl overflow-hidden aspect-[16/9] bg-slate-900 relative shadow-md">
                        @if($article->featured_image || $article->image_path)
                            <img src="{{ asset('storage/' . ($article->featured_image ?? $article->image_path)) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-950 p-8 flex flex-col justify-between text-white">
                                <span class="text-xs text-cyan-400 font-extrabold uppercase">{{ $article->category->name ?? 'Berita' }}</span>
                                <div class="space-y-2">
                                    <i data-lucide="newspaper" class="w-12 h-12 text-cyan-500 opacity-80"></i>
                                    <h3 class="text-lg font-bold text-slate-200">{{ $article->title }}</h3>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Article Excerpt Lead -->
                    @if($article->excerpt)
                        <div class="p-5 rounded-2xl bg-cyan-500/10 border-l-4 border-cyan-500 text-cyan-300 text-sm font-semibold leading-relaxed italic">
                            "{{ $article->excerpt }}"
                        </div>
                    @endif

                    <!-- Article Body Content -->
                    <style>
                        .article-body-content h1 { font-size: 1.75rem; font-weight: 800; margin-top: 1.75rem; margin-bottom: 0.75rem; line-height: 1.3; }
                        .article-body-content h2 { font-size: 1.45rem; font-weight: 800; margin-top: 1.5rem; margin-bottom: 0.75rem; line-height: 1.3; }
                        .article-body-content h3 { font-size: 1.25rem; font-weight: 700; margin-top: 1.25rem; margin-bottom: 0.5rem; }
                        .article-body-content h4 { font-size: 1.1rem; font-weight: 700; margin-top: 1rem; margin-bottom: 0.5rem; }
                        .article-body-content p { margin-bottom: 1.25rem; line-height: 1.8; }
                        .article-body-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.25rem; }
                        .article-body-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1.25rem; }
                        .article-body-content li { margin-bottom: 0.35rem; }
                        .article-body-content table { width: 100%; border-collapse: collapse; margin-top: 1rem; margin-bottom: 1.5rem; }
                        .article-body-content table th, 
                        .article-body-content table td { border: 1px solid #cbd5e1; padding: 0.65rem 0.85rem; text-align: left; }
                        .dark .article-body-content table th, 
                        .dark .article-body-content table td { border-color: #334155; }
                        .article-body-content table th { background-color: rgba(148, 163, 184, 0.15); font-weight: 700; }
                        .article-body-content img { max-width: 100%; height: auto; border-radius: 0.75rem; margin-top: 1rem; margin-bottom: 1rem; }
                        .article-body-content blockquote { border-left: 4px solid #06b6d4; padding-left: 1rem; font-style: italic; margin-top: 1rem; margin-bottom: 1.25rem; opacity: 0.9; }
                        .article-body-content a { color: #0891b2; text-decoration: underline; font-weight: 600; }
                        .dark .article-body-content a { color: #22d3ee; }
                    </style>
                    <div class="prose max-w-none text-sm sm:text-base leading-relaxed pt-4 border-t article-body-content" :class="darkMode ? 'border-slate-800 text-slate-300' : 'border-slate-100 text-slate-700'">
                        {!! $article->content !!}
                    </div>

                    <!-- COMMENTS SECTION -->
                    @if($article->allow_comments ?? true)
                    <section id="comments" class="pt-10 border-t-2 space-y-8" :class="darkMode ? 'border-slate-800' : 'border-slate-100'" x-data="{ activeReplyForm: null }">
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center font-bold">
                                    <i data-lucide="message-square" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-extrabold font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">{{ __('Komentar') }}</h2>
                                    <span class="text-xs text-slate-400 font-semibold">{{ number_format($article->comments->count()) }} {{ __('Komentar') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Notification -->
                        @if(session('comment_success'))
                            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs sm:text-sm font-extrabold flex items-center gap-3">
                                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400 flex-shrink-0"></i>
                                <span>{{ session('comment_success') }}</span>
                            </div>
                        @endif

                        <!-- Comments List -->
                        <div class="space-y-6">
                            @forelse($article->comments as $comment)
                                <div class="p-5 sm:p-6 rounded-2xl border space-y-4" :class="darkMode ? 'bg-slate-950/60 border-slate-800' : 'bg-slate-50 border-slate-200'">
                                    <!-- Parent Comment Header -->
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-900 text-cyan-400 flex items-center justify-center font-extrabold text-sm uppercase shadow-sm">
                                                {{ substr($comment->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-extrabold text-sm" :class="darkMode ? 'text-white' : 'text-slate-900'">{{ $comment->name }}</span>
                                                    @if($comment->is_admin_reply)
                                                        <span class="px-2 py-0.5 rounded-full bg-cyan-500 text-slate-950 text-[10px] font-black uppercase">Admin</span>
                                                    @endif
                                                </div>
                                                <span class="text-[11px] text-slate-400 font-semibold block mt-0.5">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>

                                        <button @click="activeReplyForm = (activeReplyForm === {{ $comment->id }} ? null : {{ $comment->id }})" 
                                                class="px-3 py-1.5 rounded-xl text-cyan-400 border border-slate-700 text-xs font-bold transition-all flex items-center gap-1.5"
                                                :class="darkMode ? 'bg-slate-900 hover:bg-slate-800' : 'bg-white hover:bg-slate-100'">
                                            <i data-lucide="reply" class="w-3.5 h-3.5"></i>
                                            <span>Balas</span>
                                        </button>
                                    </div>

                                    <!-- Comment Text -->
                                    <p class="text-xs sm:text-sm leading-relaxed font-normal pl-1" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">
                                        {{ $comment->comment }}
                                    </p>

                                    <!-- Inline Reply Form -->
                                    <div x-show="activeReplyForm === {{ $comment->id }}" x-collapse class="pt-3 border-t border-slate-700">
                                        <form action="{{ route('articles.comment.submit', $article->id) }}" method="POST" class="p-4 rounded-xl border space-y-3" :class="darkMode ? 'bg-slate-900 border-cyan-500/30' : 'bg-white border-cyan-200'">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            
                                            <span class="text-xs font-bold text-cyan-400 block">Balas Komentar {{ $comment->name }}:</span>
                                            
                                            <div class="grid sm:grid-cols-2 gap-3">
                                                <div>
                                                    <input type="text" name="name" required placeholder="{{ __('Nama') }} *" class="w-full px-3.5 py-2 rounded-lg border text-xs focus:border-cyan-500 focus:outline-none transition-all" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                                                </div>
                                                <div>
                                                    <input type="email" name="email" required placeholder="{{ __('Email') }} *" class="w-full px-3.5 py-2 rounded-lg border text-xs focus:border-cyan-500 focus:outline-none transition-all" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                                                </div>
                                            </div>
                                            <div>
                                                <textarea name="comment" rows="2" required placeholder="{{ __('Komentar Anda') }}..." class="w-full px-3.5 py-2 rounded-lg border text-xs focus:border-cyan-500 focus:outline-none transition-all" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'"></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="activeReplyForm = null" class="px-3 py-1.5 rounded-lg bg-slate-800 text-slate-300 text-xs font-bold">Batal</button>
                                                <button type="submit" class="px-4 py-1.5 rounded-lg bg-cyan-500 text-slate-950 text-xs font-extrabold shadow-md transition-all">Kirim Balasan</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Nested Replies List -->
                                    @if($comment->replies && $comment->replies->count() > 0)
                                        <div class="ml-4 sm:ml-8 pt-3 border-l-2 border-cyan-500/30 pl-4 space-y-4">
                                            @foreach($comment->replies as $reply)
                                                <div class="p-4 rounded-xl border space-y-2" :class="darkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200'">
                                                    <div class="flex items-center justify-between gap-2">
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-bold text-xs" :class="darkMode ? 'text-white' : 'text-slate-900'">{{ $reply->name }}</span>
                                                            @if($reply->is_admin_reply)
                                                                <span class="px-2 py-0.5 rounded-full bg-cyan-500 text-slate-950 text-[9px] font-black uppercase">Balasan Admin</span>
                                                            @else
                                                                <span class="px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 text-[9px] font-bold">Pengunjung</span>
                                                            @endif
                                                        </div>
                                                        <span class="text-[10px] text-slate-400 font-semibold">
                                                            {{ $reply->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                    <p class="text-xs leading-relaxed font-normal" :class="darkMode ? 'text-slate-300' : 'text-slate-600'">
                                                        {{ $reply->comment }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="p-8 text-center rounded-2xl text-xs sm:text-sm italic" :class="darkMode ? 'bg-slate-950/60 text-slate-400' : 'bg-slate-100 text-slate-500'">
                                    {{ __('Belum ada komentar. Jadilah yang pertama berkomentar!') }}
                                </div>
                            @endforelse
                        </div>

                        <!-- Main New Comment Form -->
                        <div class="p-6 sm:p-8 rounded-3xl bg-slate-950 text-white space-y-4 shadow-xl border border-slate-800">
                            <h3 class="text-lg font-extrabold text-white font-heading flex items-center gap-2">
                                <i data-lucide="edit-3" class="w-5 h-5 text-cyan-400"></i>
                                <span>{{ __('Tulis Komentar') }}</span>
                            </h3>

                            <form action="{{ route('articles.comment.submit', $article->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-300 mb-1.5">{{ __('Nama Lengkap') }} *</label>
                                        <input type="text" name="name" required placeholder="{{ __('Nama') }}..." class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs focus:border-cyan-500 focus:outline-none transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-300 mb-1.5">{{ __('Alamat Email') }} *</label>
                                        <input type="email" name="email" required placeholder="nama@email.com" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs focus:border-cyan-500 focus:outline-none transition-all">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-300 mb-1.5">{{ __('Komentar Anda') }} *</label>
                                    <textarea name="comment" rows="4" required placeholder="{{ __('Tuliskan komentar atau masukan Anda secara santun...') }}" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs focus:border-cyan-500 focus:outline-none transition-all"></textarea>
                                </div>

                                <div class="flex items-center justify-between pt-2">
                                    <span class="text-[11px] text-slate-400">Komentar akan langsung dipublikasikan.</span>
                                    <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg flex items-center gap-2">
                                        <span>{{ __('Kirim Komentar') }}</span>
                                        <i data-lucide="send" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                    </section>
                    @endif

                </article>

                <!-- SIDEBAR COLUMN -->
                <aside class="lg:col-span-4 space-y-6">
                    
                    <!-- Recent Articles Widget -->
                    <div class="p-6 rounded-3xl border shadow-sm space-y-4" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                        <h3 class="text-base font-extrabold font-heading border-b pb-3 flex items-center gap-2" :class="darkMode ? 'border-slate-800' : 'border-slate-100'">
                            <i data-lucide="newspaper" class="w-5 h-5 text-cyan-500"></i>
                            <span>{{ __('Artikel Terkait') }}</span>
                        </h3>

                        <div class="space-y-4">
                            @forelse($recentArticles as $recent)
                                <a href="{{ route('articles.show', $recent->slug) }}" class="flex items-start gap-3 group">
                                    <div class="w-16 h-16 rounded-xl bg-slate-900 overflow-hidden flex-shrink-0 relative">
                                        @if($recent->featured_image || $recent->image_path)
                                            <img src="{{ asset('storage/' . ($recent->featured_image ?? $recent->image_path)) }}" alt="{{ $recent->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                        @else
                                            <div class="w-full h-full bg-slate-800 text-cyan-400 flex items-center justify-center">
                                                <i data-lucide="newspaper" class="w-5 h-5"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-[10px] text-slate-400 font-semibold block">
                                            {{ $recent->published_at ? date('d M Y', strtotime($recent->published_at)) : date('d M Y') }}
                                        </span>
                                        <h4 class="text-xs font-bold group-hover:text-cyan-500 transition-colors line-clamp-2 leading-snug" :class="darkMode ? 'text-white' : 'text-slate-900'">
                                            {{ $recent->title }}
                                        </h4>
                                    </div>
                                </a>
                            @empty
                                <p class="text-xs text-slate-400 italic">Belum ada berita lainnya.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Emergency Contact Card Widget -->
                    <div class="p-6 rounded-3xl bg-slate-950 text-white space-y-4 border border-slate-800 shadow-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-cyan-500 text-slate-950 flex items-center justify-center font-bold">
                                <i data-lucide="phone-call" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-white font-heading">Kontak Layanan TIK</h4>
                                <span class="text-xs text-slate-400 block">IT Support Helpdesk</span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Butuh informasi atau bantuan layanan infrastruktur TIK lebih cepat? Hubungi kontak resmi kami.
                        </p>
                        @php
                            $rawPhone = $profile->whatsapp ?? $profile->phone ?? '';
                            $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                            $waUrl = !empty($cleanPhone) 
                                ? 'https://wa.me/' . $cleanPhone . '?text=' . urlencode('Halo Admin Unit TIK, saya ingin bertanya mengenai artikel: ' . $article->title)
                                : route('home') . '#helpdesk';
                        @endphp

                        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="w-full py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-extrabold text-xs shadow-md transition-all flex items-center justify-center gap-2">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            <span>Hubungi via WhatsApp</span>
                        </a>
                    </div>

                </aside>
            </div>

        </div>
    </div>
@endsection
