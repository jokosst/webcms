@extends('layouts.public')

@section('title', 'Kabar & Berita - ' . ($profile->company_name ?? 'Portal Resmi'))
@section('meta_description', 'Arsip Berita, Pengumuman, dan Kegiatan ' . ($profile->company_name ?? ''))

@section('content')
    <!-- HERO SEARCH SECTION -->
    <section class="bg-slate-900 text-white py-12 sm:py-16 relative overflow-hidden border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-6 text-center max-w-3xl">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-cyan-500/20 text-cyan-300 text-xs font-bold shadow-md border border-cyan-500/30">
                <i data-lucide="newspaper" class="w-4 h-4 text-cyan-400"></i>
                <span>{{ __('Informasi Publik') }} {{ $profile->company_name ?? '' }}</span>
            </span>

            <h1 class="text-3xl sm:text-5xl font-black text-white font-heading leading-tight">
                {{ __('Kabar & Berita Terlengkap') }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                {{ __('Pusat pengumuman resmi, berita pembangunan, serta liputan kegiatan.') }}
            </p>

            <!-- Search Form -->
            <form action="{{ route('articles.index') }}" method="GET" class="pt-4 max-w-2xl mx-auto">
                <div class="relative flex items-center">
                    <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-4"></i>
                    <input type="text" 
                           name="q" 
                           value="{{ $search }}" 
                           placeholder="{{ __('Cari judul berita, kata kunci, atau kegiatan...') }}" 
                           class="w-full pl-12 pr-32 py-4 rounded-2xl bg-slate-800/90 border-2 border-slate-700 text-white placeholder-slate-400 text-xs sm:text-sm focus:outline-none focus:border-cyan-500 focus:bg-slate-800 transition-all shadow-xl">
                    <button type="submit" class="absolute right-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 text-xs font-extrabold transition-all shadow-md">
                        {{ __('Cari Berita') }}
                    </button>
                </div>
            </form>

            <!-- Category Filter Pills -->
            @if($categories->count() > 0)
                <div class="flex flex-wrap items-center justify-center gap-2 pt-2">
                    <a href="{{ route('articles.index') }}" 
                       class="px-4 py-2 rounded-xl text-xs font-bold transition-all border {{ !$categoryId ? 'bg-cyan-500 text-slate-950 border-cyan-400 shadow-md font-black' : 'bg-slate-800/80 text-slate-300 border-slate-700 hover:bg-slate-700 hover:text-white' }}">
                        {{ __('Semua Kategori') }}
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('articles.index', ['category' => $cat->id, 'q' => $search]) }}" 
                           class="px-4 py-2 rounded-xl text-xs font-bold transition-all border {{ $categoryId == $cat->id ? 'bg-cyan-500 text-slate-950 border-cyan-400 shadow-md font-black' : 'bg-slate-800/80 text-slate-300 border-slate-700 hover:bg-slate-700 hover:text-white' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- MAIN ARTICLE GRID & PAGINATION -->
    <div class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- Active Search Header Notice -->
            @if($search || $categoryId)
                <div class="flex items-center justify-between p-4 rounded-2xl bg-white border border-slate-200 shadow-sm text-xs">
                    <div class="flex items-center gap-2">
                        <span class="text-slate-500">{{ __('Hasil pencarian untuk') }}:</span>
                        @if($search)
                            <span class="font-extrabold text-slate-900 bg-cyan-50 text-cyan-700 px-2.5 py-1 rounded-lg">"{{ $search }}"</span>
                        @endif
                        @if($categoryId)
                            @php $selectedCategory = $categories->firstWhere('id', $categoryId); @endphp
                            <span class="font-extrabold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg">{{ __('Kategori') }}: {{ $selectedCategory->name ?? 'Kategori' }}</span>
                        @endif
                    </div>
                    <a href="{{ route('articles.index') }}" class="font-extrabold text-cyan-600 hover:underline flex items-center gap-1">
                        <i data-lucide="x-circle" class="w-4 h-4"></i> {{ __('Reset Filter') }}
                    </a>
                </div>
            @endif

            <!-- Articles Grid -->
            @if($articles->count() > 0)
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($articles as $article)
                        <article class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between group hover:shadow-xl transition-all border-t-4 border-t-slate-800 hover:border-t-cyan-500"
                                 :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                            <a href="{{ route('articles.show', $article->slug) }}" class="block flex-grow">
                                <!-- Image Wrapper -->
                                <div class="aspect-[16/10] relative overflow-hidden bg-slate-100">
                                    @if($article->featured_image || $article->image_path)
                                        <img src="{{ asset('storage/' . ($article->featured_image ?? $article->image_path)) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-slate-800 via-slate-900 to-slate-950 p-6 flex flex-col justify-between text-white">
                                            <span class="text-xs text-cyan-400 font-bold uppercase tracking-wider">{{ $article->category->name ?? 'Berita TIK' }}</span>
                                            <div class="flex items-center gap-2 text-slate-400">
                                                <i data-lucide="newspaper" class="w-8 h-8 text-cyan-500 opacity-80"></i>
                                                <span class="text-xs font-semibold">Berita Resmi</span>
                                            </div>
                                        </div>
                                    @endif
                                    <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-cyan-500 text-slate-950 text-[10px] font-extrabold shadow-md uppercase">
                                        {{ $article->category->name ?? 'Berita TIK' }}
                                    </span>
                                </div>

                                <!-- Content Body -->
                                <div class="p-6 space-y-3">
                                    <div class="flex items-center justify-between text-[11px]" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                                        <span class="flex items-center gap-1.5">
                                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-cyan-500"></i>
                                            {{ $article->published_at ? date('d M Y', strtotime($article->published_at)) : date('d M Y') }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="eye" class="w-3.5 h-3.5 text-slate-400"></i>
                                            {{ number_format($article->views_count ?? 1) }}
                                        </span>
                                    </div>
                                    <h3 class="text-base font-extrabold group-hover:text-cyan-500 transition-colors leading-snug font-heading line-clamp-2"
                                        :class="darkMode ? 'text-white' : 'text-slate-900'">
                                        {{ $article->title }}
                                    </h3>
                                    <p class="text-xs line-clamp-3 leading-relaxed"
                                       :class="darkMode ? 'text-slate-300' : 'text-slate-600'">
                                        {{ $article->getExcerptClean(120) }}
                                    </p>
                                </div>
                            </a>

                            <!-- Card Footer -->
                            <div class="px-6 py-4 border-t flex items-center justify-between text-xs"
                                 :class="darkMode ? 'border-slate-800 bg-slate-950/50 text-slate-400' : 'border-slate-100 bg-slate-50/50 text-slate-500'">
                                <span class="font-medium">{{ __('Oleh') }}: {{ $article->user->name ?? 'Admin' }}</span>
                                <a href="{{ route('articles.show', $article->slug) }}" class="font-extrabold text-cyan-500 hover:text-cyan-400 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                    {{ __('Baca Selengkapnya') }} <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Custom Styled Pagination -->
                <div class="pt-6 border-t border-slate-200">
                    {{ $articles->links() }}
                </div>

            @else
                <!-- Empty Search Result State -->
                <div class="p-12 text-center rounded-3xl border space-y-4 max-w-xl mx-auto shadow-sm"
                     :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                    <div class="w-16 h-16 rounded-2xl bg-cyan-500/10 text-cyan-500 flex items-center justify-center mx-auto">
                        <i data-lucide="newspaper-slash" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-extrabold font-heading">{{ __('Berita Tidak Ditemukan') }}</h3>
                    <p class="text-xs sm:text-sm text-slate-400">
                        {{ __('Maaf, tidak ada publikasi berita yang cocok dengan kata kunci') }} <span class="font-bold text-cyan-400">"{{ $search }}"</span>.
                    </p>
                    <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-slate-950 font-extrabold text-xs shadow-md transition-all">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        <span>{{ __('Lihat Semua Berita') }}</span>
                    </a>
                </div>
            @endif

        </div>
    </div>
@endsection
