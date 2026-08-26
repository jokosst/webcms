@extends('layouts.public')

@section('title', ($profile->company_name ?? 'Unit TIK') . ' - Pusat Pengelolaan Sistem Informasi, Jaringan & Server')
@section('meta_description', 'Unit Teknologi Informasi dan Komunikasi (TIK) - Pengelolaan Sistem Aplikasi Web, Infrastruktur Jaringan, Ruang Lab Komputer, dan Server Multi-Cloud.')

@section('content')

    <!-- HERO SECTION (DYNAMIC CAROUSEL & NOC TECH SHOWCASE) -->
    <section id="beranda" class="relative py-16 sm:py-24 overflow-hidden border-b transition-colors duration-300"
             :class="darkMode ? 'border-slate-800/80' : 'border-slate-200'"
             x-data="{ activeSlide: 0, totalSlides: {{ max(1, $heroBanners->count()) }} }">
        
        <!-- Dynamic Background Image Overlay from Hero Banners / Fallback Photo -->
        <div class="absolute inset-0 z-0">
            @if($heroBanners->count() > 0)
                @foreach($heroBanners as $index => $banner)
                    <div x-show="activeSlide === {{ $index }}"
                         x-transition:enter="transition ease-out duration-1000"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-700"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute inset-0 bg-cover bg-center brightness-[0.85] contrast-[1.15]"
                         style="background-image: url('{{ $banner->image_path ? asset('storage/' . $banner->image_path) : asset('images/hero-bg.jpg') }}'); display: none;">
                    </div>
                @endforeach
            @else
                <div class="absolute inset-0 bg-cover bg-center brightness-[0.85] contrast-[1.15]"
                     style="background-image: url('{{ $profile->hero_bg_path ? asset('storage/' . $profile->hero_bg_path) : asset('images/hero-bg.jpg') }}');">
                </div>
            @endif

            <!-- Dark Cyber Tech Backdrop Overlay -->
            <div class="absolute inset-0 backdrop-blur-[2px] transition-colors duration-300"
                 :class="darkMode ? 'bg-slate-950/85' : 'bg-slate-950/75'"></div>

            <!-- Cyber Grid Pattern Overlay -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#06b6d420_1px,transparent_1px),linear-gradient(to_bottom,#06b6d420_1px,transparent_1px)] bg-[size:32px_32px] opacity-40"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Column: Hero Text Content -->
                <div class="lg:col-span-7 space-y-8">
                    
                    @if($heroBanners->count() > 0)
                        <div class="space-y-6">
                            @foreach($heroBanners as $index => $banner)
                                <div x-show="activeSlide === {{ $index }}" 
                                     x-transition:enter="transition ease-out duration-500"
                                     x-transition:enter-start="opacity-0 translate-y-4"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="space-y-6"
                                     style="display: none;">
                                    
                                    <!-- Dynamic Badge Text -->
                                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full text-xs font-black border backdrop-blur-md shadow-lg shadow-cyan-500/10 bg-cyan-500/10 text-cyan-300 border-cyan-500/30">
                                        <i data-lucide="shield-check" class="w-4 h-4 text-cyan-500 animate-pulse"></i>
                                        <span>{{ $banner->badge_text ? __($banner->badge_text) : __('PUSAT OPERASIONAL TIK & DATA CENTER') }}</span>
                                    </div>

                                    <!-- Dynamic Title -->
                                    <h1 class="text-3xl sm:text-5xl font-extrabold leading-tight font-heading tracking-tight text-white">
                                        {{ $banner->title }}
                                    </h1>

                                    <!-- Dynamic Subtitle / Description -->
                                    <p class="text-base sm:text-lg leading-relaxed font-normal max-w-2xl text-slate-200">
                                        {{ $banner->subtitle }}
                                    </p>

                                    <!-- Dynamic CTA Buttons from Database -->
                                    <div class="flex flex-wrap items-center gap-4 pt-2">
                                        @if($banner->button_text)
                                            <a href="{{ $banner->button_link ?? '#pilar-kompetensi' }}" class="px-7 py-4 rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-sm shadow-xl shadow-cyan-500/30 transition-all hover:scale-105 flex items-center gap-2.5 uppercase tracking-wide">
                                                <i data-lucide="layers" class="w-5 h-5"></i>
                                                <span>{{ __($banner->button_text) }}</span>
                                            </a>
                                        @else
                                            <a href="#layanan" class="px-7 py-4 rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-sm shadow-xl shadow-cyan-500/30 transition-all hover:scale-105 flex items-center gap-2.5 uppercase tracking-wide">
                                                <i data-lucide="layers" class="w-5 h-5"></i>
                                                <span>{{ __('Jelajahi Layanan Kami') }}</span>
                                            </a>
                                        @endif
                                        <a href="#berita" class="px-7 py-4 rounded-2xl font-extrabold text-sm shadow-lg transition-all hover:scale-105 flex items-center gap-2.5 border bg-slate-900/90 hover:bg-slate-800 border-slate-700 text-white backdrop-blur-md">
                                            <i data-lucide="newspaper" class="w-5 h-5 text-cyan-400"></i>
                                            <span>{{ __('Kabar & Berita') }}</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Fallback Text -->
                        <div class="space-y-6">
                            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full text-xs font-black border backdrop-blur-md shadow-lg shadow-cyan-500/10 bg-cyan-500/10 text-cyan-300 border-cyan-500/30">
                                <i data-lucide="server" class="w-4 h-4 text-cyan-500 animate-pulse"></i>
                                <span>{{ __('Pusat Operasional Teknologi Informasi & Komunikasi') }}</span>
                            </div>

                            <h1 class="text-3xl sm:text-5xl font-extrabold leading-tight font-heading tracking-tight text-white">
                                {{ __('Transformasi Digital & Pengelolaan Infrastruktur TIK Terpadu') }}
                            </h1>
                            <p class="text-base sm:text-lg leading-relaxed font-normal max-w-2xl text-slate-200">
                                Unit profesional pengelola Sistem Aplikasi Web, Infrastruktur Jaringan, Ruang Komputer & Pelatihan Kompetensi, serta Server Multi-Cloud (AWS, Google Cloud, & Server House).
                            </p>
                            <div class="flex flex-wrap items-center gap-4 pt-2">
                                <a href="#layanan" class="px-7 py-4 rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-sm shadow-xl shadow-cyan-500/30 transition-all hover:scale-105 flex items-center gap-2.5 uppercase tracking-wide">
                                    <i data-lucide="layers" class="w-5 h-5"></i>
                                    <span>{{ __('Jelajahi Layanan Kami') }}</span>
                                </a>
                                <a href="#berita" class="px-7 py-4 rounded-2xl font-extrabold text-sm shadow-lg transition-all hover:scale-105 flex items-center gap-2.5 border bg-slate-900/90 hover:bg-slate-800 border-slate-700 text-white backdrop-blur-md">
                                    <i data-lucide="newspaper" class="w-5 h-5 text-cyan-400"></i>
                                    <span>{{ __('Kabar & Berita') }}</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Slide Controls if multiple hero banners exist -->
                    @if($heroBanners->count() > 1)
                        <div class="flex items-center gap-2 pt-4">
                            @foreach($heroBanners as $index => $b)
                                <button @click="activeSlide = {{ $index }}" class="h-2 rounded-full transition-all duration-300" :class="activeSlide === {{ $index }} ? 'w-8 bg-cyan-500' : 'w-2 bg-slate-400 hover:bg-slate-500'"></button>
                            @endforeach
                        </div>
                    @endif

                </div>

                <!-- Right Column: Integrated YouTube Video Player Showcase -->
                <div class="lg:col-span-5 relative">
                    <div class="relative rounded-3xl p-4 sm:p-6 transition-all duration-300 overflow-hidden border shadow-2xl bg-white/95 backdrop-blur-xl border-slate-200 text-slate-900">
                        
                        <!-- Header Status Bar -->
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                            <div class="flex items-center gap-3">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                </span>
                                <div>
                                    <h3 class="text-sm font-extrabold font-heading text-slate-900">
                                        Monitoring Center
                                    </h3>
                                    <span class="text-[10px] text-cyan-600 font-bold uppercase tracking-wider block">Live Stream Broadcast</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                ACTIVE HD
                            </span>
                        </div>

                        <!-- Embedded YouTube Video Showcase Frame (16:9 Aspect Ratio) -->
                        <div class="relative w-full aspect-video rounded-2xl overflow-hidden shadow-xl border border-slate-200 bg-slate-950">
                            <iframe class="w-full h-full object-cover" 
                                    src="https://www.youtube.com/embed/s5ujoeb0I_Q?autoplay=1&mute=1&loop=1&playlist=s5ujoeb0I_Q&controls=1&modestbranding=1" 
                                    title="Tinjauan Operasional TIK & Monitoring Server" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen>
                            </iframe>
                        </div>

                        <!-- Sub Card Info -->
                        <div class="mt-4 pt-3 flex items-center justify-between text-xs text-slate-600">
                            <span class="flex items-center gap-1.5 font-bold text-slate-800">
                                <i data-lucide="play-circle" class="w-4 h-4 text-cyan-600"></i>
                                Video Profil Operasional Unit TIK
                            </span>
                            <a href="https://youtu.be/s5ujoeb0I_Q" target="_blank" rel="noopener noreferrer" class="text-cyan-600 hover:text-cyan-700 font-bold flex items-center gap-1 text-[11px]">
                                Tonton di YouTube <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 2: VISI, MISI & KATA SAMBUTAN KEPALA UNIT TIK -->
    <section id="visi-misi-sambutan" class="py-20 relative border-b transition-colors duration-300 overflow-hidden"
             :class="darkMode ? 'bg-slate-900/60 border-slate-800/80' : 'bg-slate-100/70 border-slate-200'">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Section Header Tag -->
            <div class="text-center max-w-3xl mx-auto space-y-3 mb-16">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black border"
                     :class="darkMode ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30' : 'bg-cyan-50 text-cyan-700 border-cyan-200'">
                    <i data-lucide="award" class="w-4 h-4 text-cyan-500"></i>
                    <span>{{ __('Kepemimpinan & Arah Strategis') }}</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading tracking-tight"
                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                    {{ __('Kata Sambutan & Visi Misi Unit TIK') }}
                </h2>
                <p class="text-sm leading-relaxed" :class="darkMode ? 'text-slate-300' : 'text-slate-600'">
                    Komitmen kepemimpinan dalam mengawal transformasi digital, integritas infrastruktur jaringan, dan keandalan sistem aplikasi secara berkelanjutan.
                </p>
            </div>

            <div class="grid lg:grid-cols-12 gap-12 items-start">
                
                <!-- Left Column: Kata Sambutan Pimpinan / Kepala Unit -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="relative rounded-3xl p-6 sm:p-8 space-y-6 transition-all duration-300 overflow-hidden border"
                         :class="darkMode ? 'glass-card-dark glow-cyan border-slate-800' : 'glass-card-light shadow-xl border-slate-200'">
                        
                        <!-- Leader Photo & Details -->
                        <div class="flex items-center gap-4 border-b pb-6"
                             :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                            <div class="relative w-20 h-20 rounded-2xl overflow-hidden border-2 border-cyan-500/50 shadow-lg shrink-0">
                                @if($profile->leader_photo_path)
                                    <img src="{{ asset('storage/' . $profile->leader_photo_path) }}" alt="{{ $profile->leader_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-tr from-cyan-500 to-indigo-600 flex items-center justify-center text-white font-black text-2xl">
                                        {{ substr($profile->leader_name ?? 'K', 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 block w-fit mb-1">
                                    {{ $profile->leader_term ? __($profile->leader_term) : __('Kepala Unit TIK') }}
                                </span>
                                <h3 class="text-lg font-black font-heading leading-tight"
                                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                                    {{ $profile->leader_name ?? 'Kepala Unit TIK' }}
                                </h3>
                                <p class="text-xs font-semibold text-cyan-500">
                                    {{ $profile->leader_position ? __($profile->leader_position) : __('Penanggung Jawab Operasional TIK') }}
                                </p>
                            </div>
                        </div>

                        <!-- Leader Greeting Quote / About Us -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-cyan-500">
                                <i data-lucide="quote" class="w-6 h-6 rotate-180 opacity-60"></i>
                                <span class="text-xs font-black uppercase tracking-widest">{{ __('Kata Sambutan Pimpinan') }}</span>
                            </div>
                            <p class="text-xs sm:text-sm leading-relaxed italic"
                               :class="darkMode ? 'text-slate-300' : 'text-slate-700'">
                                "{{ $profile->about_us ?? 'Selamat datang di Portal Resmi Unit Teknologi Informasi dan Komunikasi (TIK). Kami berkomitmen memberikan layanan terbaik untuk mendukung transformasi digital, ketersediaan jaringan cepat, pengembangan sistem informasi terintegrasi, serta pengelolaan data center berstandar tinggi.' }}"
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Right Column: Visi & Misi Cards -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Visi Card -->
                    <div class="rounded-3xl p-6 sm:p-8 space-y-4 transition-all duration-300 border"
                         :class="darkMode ? 'glass-card-dark border-slate-800' : 'glass-card-light shadow-lg border-slate-200'">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center border border-cyan-500/30 shrink-0">
                                <i data-lucide="compass" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-cyan-500 uppercase tracking-widest block">{{ __('VISI UTAMA') }}</span>
                                <h3 class="text-xl font-black font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">
                                    {{ __('Visi Unit TIK') }}
                                </h3>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm leading-relaxed" :class="darkMode ? 'text-slate-300' : 'text-slate-600'">
                            {{ $profile->vision ?? 'Menjadi pusat keunggulan pengelolaan teknologi informasi, komunikasi, dan data center terpadu yang andal, aman, dan inovatif dalam mendukung pencapaian universitas unggul berstandar internasional.' }}
                        </p>
                    </div>

                    <!-- Misi Card -->
                    <div class="rounded-3xl p-6 sm:p-8 space-y-4 transition-all duration-300 border"
                         :class="darkMode ? 'glass-card-dark border-slate-800' : 'glass-card-light shadow-lg border-slate-200'">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center border border-indigo-500/30 shrink-0">
                                <i data-lucide="target" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest block">{{ __('MISI STRATEGIS') }}</span>
                                <h3 class="text-xl font-black font-heading" :class="darkMode ? 'text-white' : 'text-slate-900'">
                                    {{ __('Misi Operasional TIK') }}
                                </h3>
                            </div>
                        </div>
                        <div class="text-xs sm:text-sm leading-relaxed space-y-2" :class="darkMode ? 'text-slate-300' : 'text-slate-600'">
                            {!! nl2br(e($profile->mission ?? '1. Menyediakan layanan infrastruktur jaringan komunikasi data berkecepatan tinggi dan stabil.
2. Mengembangkan dan mengintegrasikan sistem aplikasi web serta portal layanan publik secara aman.
3. Mengelola ruang laboratorium komputer dan pelatihan kompetensi teknis secara profesional.
4. Menjamin ketersediaan server house on-premise dan integrasi multi-cloud (AWS & GCP) dengan keamanan tinggi.')) !!}
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 3: 4 PILAR UTAMA KAMPUS/TIK SHOWCASE -->
    <section id="layanan" class="py-20 relative border-b transition-colors duration-300"
             :class="darkMode ? 'bg-slate-950 border-slate-800/80' : 'bg-white border-slate-200'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-black border uppercase tracking-widest"
                      :class="darkMode ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30' : 'bg-cyan-50 text-cyan-700 border-cyan-200'">
                    {{ __('Pilar Layanan TIK Utama') }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading tracking-tight"
                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                    {{ __('Layanan Unggulan TIK') }}
                </h2>
                <p class="text-sm" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                    Solusi teknologi menyeluruh yang menopang fondasi transformasi digital instansi & perguruan tinggi.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($services as $index => $s)
                    @php
                        $colorPalettes = [
                            0 => ['badge' => 'text-cyan-500', 'bgDark' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30', 'bgLight' => 'bg-cyan-50 text-cyan-600 border-cyan-200', 'hoverBg' => 'group-hover:bg-cyan-500', 'hoverText' => 'group-hover:text-cyan-500', 'iconColor' => 'text-cyan-500'],
                            1 => ['badge' => 'text-indigo-500', 'bgDark' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/30', 'bgLight' => 'bg-indigo-50 text-indigo-600 border-indigo-200', 'hoverBg' => 'group-hover:bg-indigo-500', 'hoverText' => 'group-hover:text-indigo-500', 'iconColor' => 'text-indigo-500'],
                            2 => ['badge' => 'text-purple-500', 'bgDark' => 'bg-purple-500/10 text-purple-400 border-purple-500/30', 'bgLight' => 'bg-purple-50 text-purple-600 border-purple-200', 'hoverBg' => 'group-hover:bg-purple-500', 'hoverText' => 'group-hover:text-purple-500', 'iconColor' => 'text-purple-500'],
                            3 => ['badge' => 'text-emerald-500', 'bgDark' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30', 'bgLight' => 'bg-emerald-50 text-emerald-600 border-emerald-200', 'hoverBg' => 'group-hover:bg-emerald-500', 'hoverText' => 'group-hover:text-emerald-500', 'iconColor' => 'text-emerald-500'],
                        ];
                        $c = $colorPalettes[$index % 4];
                        $features = array_filter(array_map('trim', explode("\n", $s->content ?? '')));
                    @endphp
                    <div class="rounded-3xl p-7 space-y-5 flex flex-col justify-between group transition-all"
                         :class="darkMode ? 'glass-card-dark' : 'glass-card-light'">
                        <div class="space-y-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center border {{ $c['hoverBg'] }} group-hover:text-slate-950 transition-all duration-300 shadow-lg"
                                 :class="darkMode ? '{{ $c['bgDark'] }}' : '{{ $c['bgLight'] }}'">
                                <i data-lucide="{{ $s->icon ?: 'layers' }}" class="w-7 h-7"></i>
                            </div>
                            <span class="text-[10px] font-black {{ $c['badge'] }} uppercase tracking-widest block">PILAR {{ sprintf('%02d', $index + 1) }}</span>
                            <h3 class="text-xl font-extrabold font-heading {{ $c['hoverText'] }} transition-colors leading-snug"
                                :class="darkMode ? 'text-white' : 'text-slate-900'">
                                {{ $s->title }}
                            </h3>
                            <p class="text-xs leading-relaxed" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                                {{ $s->short_description }}
                            </p>
                        </div>
                        @if(count($features) > 0)
                            <ul class="text-xs space-y-2 pt-4 border-t"
                                :class="darkMode ? 'text-slate-300 border-slate-800/80' : 'text-slate-700 border-slate-200'">
                                @foreach($features as $feature)
                                    <li class="flex items-center gap-2">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 {{ $c['iconColor'] }}"></i>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

   

    <!-- SECTION 5: BERITA & ARTIKEL TERKINI (`$articles`) -->
    <section id="berita" class="py-20 relative border-b transition-colors duration-300"
             :class="darkMode ? 'bg-slate-900/60 border-slate-800/80' : 'bg-slate-50 border-slate-200'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header & See All Button -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                <div class="space-y-3 max-w-2xl">
                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-black border uppercase tracking-widest"
                          :class="darkMode ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30' : 'bg-cyan-50 text-cyan-700 border-cyan-200'">
                        {{ __('Berita TIK') }}
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold font-heading tracking-tight"
                        :class="darkMode ? 'text-white' : 'text-slate-900'">
                        {{ __('Artikel & Kabar TIK Terbaru') }}
                    </h2>
                    <p class="text-sm" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                        Ragam berita kegiatan, pembaruan sistem aplikasi, panduan teknologi, dan edukasi seputar infrastruktur TIK.
                    </p>
                </div>

                <a href="{{ route('articles.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-wider border transition-all hover:scale-105 shrink-0"
                   :class="darkMode ? 'bg-slate-900 hover:bg-slate-800 border-slate-700 text-cyan-400' : 'bg-white hover:bg-slate-100 border-slate-300 text-cyan-600 shadow-sm'">
                    <span>{{ __('Lihat Artikel Lainnya') }}</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <!-- Articles Grid -->
            @if($articles->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($articles as $article)
                        <article class="rounded-3xl overflow-hidden flex flex-col justify-between border transition-all duration-300 group hover:-translate-y-1.5"
                                 :class="darkMode ? 'bg-slate-950/80 border-slate-800/80 hover:border-cyan-500/50 shadow-xl shadow-slate-950/50' : 'bg-white border-slate-200 hover:border-cyan-500/50 shadow-lg'">
                            
                            <!-- Thumbnail Image -->
                            <div class="relative aspect-[16/10] overflow-hidden bg-slate-800">
                                @if($article->image_path)
                                    <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-tr from-cyan-500/20 to-indigo-600/20 flex items-center justify-center">
                                        <i data-lucide="newspaper" class="w-12 h-12 text-cyan-500/40"></i>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-500 text-slate-950 shadow-md">
                                        {{ $article->category->name ?? 'Berita TIK' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                                <div class="space-y-3">
                                    <div class="flex items-center gap-2 text-[11px]" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-cyan-500"></i>
                                        <span>{{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</span>
                                    </div>

                                    <h3 class="text-base font-extrabold font-heading line-clamp-2 leading-snug group-hover:text-cyan-500 transition-colors"
                                        :class="darkMode ? 'text-white' : 'text-slate-900'">
                                        <a href="{{ route('articles.show', $article->slug) }}">
                                            {{ $article->title }}
                                        </a>
                                    </h3>

                                    <p class="text-xs leading-relaxed line-clamp-3" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                                        {{ $article->getExcerptClean(120) }}
                                    </p>
                                </div>

                                <div class="pt-4 border-t" :class="darkMode ? 'border-slate-800/80' : 'border-slate-100'">
                                    <a href="{{ route('articles.show', $article->slug) }}" 
                                       class="inline-flex items-center gap-1.5 text-xs font-black text-cyan-500 hover:text-cyan-400 uppercase tracking-wider group/link">
                                        <span>{{ __('Baca Selengkapnya') }}</span>
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover/link:translate-x-1 transition-transform"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <!-- Fallback if no articles exist yet -->
                <div class="text-center py-12 rounded-3xl border" :class="darkMode ? 'bg-slate-950/80 border-slate-800' : 'bg-white border-slate-200'">
                    <i data-lucide="newspaper" class="w-12 h-12 mx-auto text-cyan-500 opacity-50 mb-3"></i>
                    <h3 class="text-base font-bold" :class="darkMode ? 'text-white' : 'text-slate-900'">Belum Ada Artikel Dipublikasikan</h3>
                    <p class="text-xs text-slate-400 mt-1">Artikel dan berita terbaru akan muncul di sini secara otomatis.</p>
                </div>
            @endif

            <!-- Bottom Button to View All Articles -->
            <div class="text-center pt-12">
                <a href="{{ route('articles.index') }}" 
                   class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-xl shadow-cyan-500/20 hover:scale-105 transition-all">
                    <i data-lucide="newspaper" class="w-4 h-4"></i>
                    <span>Lihat Semua Artikel & Berita TIK</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

        </div>
    </section>

    <!-- SECTION 6: PORTOFOLIO PROYEK TIK (`$portfolios`) -->
    <section id="portofolio" class="py-20 relative border-b transition-colors duration-300"
             :class="darkMode ? 'bg-slate-900/60 border-slate-800/80' : 'bg-slate-100/70 border-slate-200'"
             x-data="{ activePortfolioModal: null }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-black border uppercase tracking-widest"
                      :class="darkMode ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30' : 'bg-cyan-50 text-cyan-700 border-cyan-200'">
                    {{ __('Rekam Jejak Proyek') }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading tracking-tight"
                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                    {{ __('Portofolio & Implementasi TIK') }}
                </h2>
                <p class="text-sm" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                    {{ __('Daftar proyek aplikasi, penataan jaringan, dan infrastruktur cloud yang sukses diimplementasikan.') }}
                </p>
            </div>

            @if($portfolios->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($portfolios->sortBy('sort_order') as $portfolio)
                        <div class="rounded-3xl overflow-hidden group transition-all duration-300 flex flex-col justify-between border hover:-translate-y-1.5"
                             :class="darkMode ? 'bg-slate-950/80 border-slate-800/80 hover:border-cyan-500/50 shadow-xl' : 'bg-white border-slate-200 hover:border-cyan-500/50 shadow-lg'">
                            
                            <!-- Thumbnail or Fallback Icon Header -->
                            <div class="h-48 overflow-hidden relative bg-slate-900 flex items-center justify-center">
                                @if($portfolio->thumbnail_path || $portfolio->image_path)
                                    <img src="{{ asset('storage/' . ($portfolio->thumbnail_path ?? $portfolio->image_path)) }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t" :class="darkMode ? 'from-slate-950' : 'from-slate-900/60'"></div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-slate-900 via-slate-800 to-slate-950 p-6 flex flex-col justify-between text-white relative">
                                        <div class="flex items-center justify-between">
                                            <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center border border-cyan-500/30 shadow-lg">
                                                <i data-lucide="folder-git-2" class="w-6 h-6"></i>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                                                {{ __('AKTIF') }}
                                            </span>
                                        </div>
                                        <div>
                                            <i data-lucide="layers" class="w-16 h-16 text-cyan-500/10 absolute bottom-3 right-3 pointer-events-none"></i>
                                            <span class="text-xs text-slate-400 font-semibold block">{{ __('Pengguna') }}: {{ $portfolio->client_name ?? __('Internal Kampus') }}</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-500 text-slate-950 shadow-md">
                                        {{ $portfolio->category->name ?? __('Aplikasi Web') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Body Content -->
                            <div class="p-6 space-y-3 flex-1 flex flex-col justify-between">
                                <div class="space-y-2">
                                    <h3 class="text-lg font-extrabold font-heading group-hover:text-cyan-500 transition-colors leading-snug"
                                        :class="darkMode ? 'text-white' : 'text-slate-900'">
                                        {{ $portfolio->title }}
                                    </h3>
                                    <p class="text-xs leading-relaxed line-clamp-3"
                                       :class="darkMode ? 'text-slate-300' : 'text-slate-600'">
                                        {{ $portfolio->getExcerptClean(120) }}
                                    </p>
                                </div>

                                <!-- Card Action Footer -->
                                <div class="pt-4 border-t flex items-center justify-between text-xs"
                                     :class="darkMode ? 'border-slate-800/80 text-slate-400' : 'border-slate-100 text-slate-500'">
                                    <span class="truncate max-w-[140px]" title="{{ $portfolio->client_name }}">{{ __('Pengguna') }}: {{ $portfolio->client_name ?? __('Internal Kampus') }}</span>
                                    
                                    <button @click="activePortfolioModal = {{ json_encode([
                                        'title' => $portfolio->title,
                                        'category' => $portfolio->category->name ?? __('Sistem Informasi Kampus'),
                                        'client' => $portfolio->client_name ?? __('Internal Kampus'),
                                        'status' => $portfolio->is_active ? __('Aktif') : __('Tidak Aktif'),
                                        'is_active' => (bool) $portfolio->is_active,
                                        'description' => $portfolio->getExcerptClean(300),
                                        'content' => $portfolio->content,
                                        'url' => $portfolio->project_url,
                                        'image' => $portfolio->thumbnail_path ? asset('storage/' . $portfolio->thumbnail_path) : null
                                    ]) }}" 
                                    class="font-black text-cyan-500 hover:text-cyan-400 flex items-center gap-1 group/btn shrink-0">
                                        <span>{{ __('Detail Proyek') }}</span>
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover/btn:translate-x-1 transition-transform"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center p-12 rounded-3xl text-xs italic"
                     :class="darkMode ? 'glass-card-dark text-slate-400' : 'glass-card-light text-slate-500'">
                    {{ __('Belum ada portofolio proyek yang diinputkan di Admin Panel.') }}
                </div>
            @endif

        </div>

        <!-- PORTFOLIO DETAIL MODAL DIALOG -->
        <div x-show="activePortfolioModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
             style="display: none;">
            
            <div @click.away="activePortfolioModal = null" 
                 class="w-full max-w-2xl rounded-3xl overflow-hidden border shadow-2xl p-6 sm:p-8 space-y-6 relative transition-colors duration-300"
                 :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                
                <!-- Close Button -->
                <button @click="activePortfolioModal = null" class="absolute top-5 right-5 p-2 rounded-xl bg-slate-800/50 hover:bg-slate-800 text-slate-400 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <!-- Modal Header -->
                <div class="space-y-3 pr-8">
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-500/20 text-cyan-400 border border-cyan-500/30"
                          x-text="activePortfolioModal?.category"></span>
                    <h3 class="text-2xl font-black font-heading leading-snug" x-text="activePortfolioModal?.title"></h3>
                </div>

                <!-- Meta Details Grid -->
                <div class="grid grid-cols-2 gap-4 p-4 rounded-2xl border text-xs"
                     :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-slate-300' : 'bg-slate-50 border-slate-200 text-slate-700'">
                    <div>
                        <span class="text-slate-500 block text-[10px] uppercase tracking-wider font-bold">{{ __('Pengguna') }}:</span>
                        <span class="font-extrabold" x-text="activePortfolioModal?.client"></span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[10px] uppercase tracking-wider font-bold">{{ __('Status') }}:</span>
                        <span class="font-extrabold px-2.5 py-0.5 rounded-full text-[11px] inline-block mt-0.5"
                              :class="activePortfolioModal?.is_active ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30'"
                              x-text="activePortfolioModal?.status"></span>
                    </div>
                </div>

                <!-- Content & Description -->
                <div class="space-y-3 text-xs leading-relaxed max-h-60 overflow-y-auto pr-2" :class="darkMode ? 'text-slate-300' : 'text-slate-600'">
                    <p class="font-semibold text-cyan-400" x-text="activePortfolioModal?.description"></p>
                    <div class="space-y-2 leading-relaxed" x-html="activePortfolioModal?.content"></div>
                </div>

                <!-- Action Button -->
                <div class="pt-4 border-t flex items-center justify-between gap-4" :class="darkMode ? 'border-slate-800' : 'border-slate-200'">
                    <button @click="activePortfolioModal = null" class="px-5 py-2.5 rounded-xl border font-bold text-xs" :class="darkMode ? 'border-slate-700 text-slate-300 hover:bg-slate-800' : 'border-slate-300 text-slate-700 hover:bg-slate-100'">
                        {{ __('Tutup') }}
                    </button>
                    <template x-if="activePortfolioModal?.url">
                        <a :href="activePortfolioModal?.url" target="_blank" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-xs uppercase tracking-wider flex items-center gap-2 shadow-lg shadow-cyan-500/20">
                            <span>{{ __('Kunjungi Aplikasi') }}</span>
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                        </a>
                    </template>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 7: STRUKTUR TIM & AHLI TIK (`$team`) -->
    <section id="tim-ahli" class="py-20 relative border-b transition-colors duration-300 overflow-hidden"
             :class="darkMode ? 'bg-slate-950 border-slate-800/80' : 'bg-slate-900 border-slate-800 text-white'">
        
        <!-- Cyber Grid Overlay Background -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#06b6d415_1px,transparent_1px),linear-gradient(to_bottom,#06b6d415_1px,transparent_1px)] bg-[size:32px_32px] opacity-60 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-black border uppercase tracking-widest bg-cyan-500/10 text-cyan-400 border-cyan-500/30">
                    {{ __('SDM & Keahlian') }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading tracking-tight text-white">
                    {{ __('Tim Ahli & Personel TIK') }}
                </h2>
                <p class="text-sm text-slate-300">
                    {{ __('Tenaga profesional pengembang software, administrator jaringan, & teknisi server data center.') }}
                </p>
            </div>

            @if($team->count() > 0)
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($team as $member)
                        <div class="rounded-3xl p-6 text-center space-y-4 group transition-all duration-300 border bg-white border-slate-200 shadow-xl hover:border-cyan-500/50 hover:shadow-cyan-500/20 hover:-translate-y-1">
                            <div class="w-24 h-24 mx-auto rounded-2xl overflow-hidden border-2 border-cyan-500/40 shadow-md group-hover:scale-105 transition-transform duration-300">
                                @if($member->photo_path)
                                    <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-cyan-50 text-cyan-600 flex items-center justify-center font-bold text-2xl">
                                        <i data-lucide="user" class="w-10 h-10 text-cyan-600"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold font-heading text-slate-900 group-hover:text-cyan-600 transition-colors">
                                    {{ $member->name }}
                                </h3>
                                <span class="text-xs font-bold text-cyan-600 block mt-0.5">
                                    {{ __($member->position) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="grid sm:grid-cols-4 gap-6 text-center">
                    <div class="rounded-3xl p-6 space-y-3 border bg-white border-slate-200 shadow-lg">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-cyan-50 text-cyan-600 border border-cyan-100 flex items-center justify-center font-black text-xl">N</div>
                        <h3 class="text-sm font-extrabold text-slate-900">{{ __('Network Administrator') }}</h3>
                        <span class="text-[11px] text-cyan-600 font-bold block">{{ __('Infrastruktur & FO') }}</span>
                    </div>
                    <div class="rounded-3xl p-6 space-y-3 border bg-white border-slate-200 shadow-lg">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center font-black text-xl">S</div>
                        <h3 class="text-sm font-extrabold text-slate-900">{{ __('Senior Web Developer') }}</h3>
                        <span class="text-[11px] text-indigo-600 font-bold block">{{ __('SIAKAD & API System') }}</span>
                    </div>
                    <div class="rounded-3xl p-6 space-y-3 border bg-white border-slate-200 shadow-lg">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center font-black text-xl">C</div>
                        <h3 class="text-sm font-extrabold text-slate-900">{{ __('Cloud & SysAdmin') }}</h3>
                        <span class="text-[11px] text-purple-600 font-bold block">{{ __('AWS, GCP & Bare-Metal') }}</span>
                    </div>
                    <div class="rounded-3xl p-6 space-y-3 border bg-white border-slate-200 shadow-lg">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center font-black text-xl">H</div>
                        <h3 class="text-sm font-extrabold text-slate-900">{{ __('IT Helpdesk Officer') }}</h3>
                        <span class="text-[11px] text-emerald-600 font-bold block">{{ __('Technical Support') }}</span>
                    </div>
                </div>
            @endif

        </div>
    </section>

    <!-- SECTION 8: FAQ (FREQUENTLY ASKED QUESTIONS) -->
    <section id="faq" class="py-20 relative border-b transition-colors duration-300"
             :class="darkMode ? 'bg-slate-900/60 border-slate-800/80' : 'bg-slate-100/70 border-slate-200'">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-black border uppercase tracking-widest"
                      :class="darkMode ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30' : 'bg-cyan-50 text-cyan-700 border-cyan-200'">
                    {{ __('Pusat Informasi & FAQ') }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading tracking-tight"
                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                    {{ __('Pertanyaan Yang Sering Diajukan') }}
                </h2>
                <p class="text-sm" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                    {{ __('Jawaban resmi seputar prosedur permohonan layanan, akun SSO, dan dukungan teknis Unit TIK.') }}
                </p>
            </div>

            @if($faqs->count() > 0)
                <div class="space-y-4" x-data="{ openFaq: null }">
                    @foreach($faqs as $index => $faq)
                        <div class="rounded-2xl border overflow-hidden transition-all duration-300"
                             :class="darkMode ? 'glass-card-dark border-slate-800' : 'glass-card-light border-slate-200'">
                            <button @click="openFaq = (openFaq === {{ $index }} ? null : {{ $index }})" 
                                    class="w-full p-5 text-left font-bold flex justify-between items-center text-sm hover:text-cyan-500 transition-colors"
                                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                                <span>{{ $faq->question }}</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300" :class="openFaq === {{ $index }} ? 'rotate-180 text-cyan-500' : 'text-slate-400'"></i>
                            </button>
                            <div x-show="openFaq === {{ $index }}" x-transition 
                                 class="p-5 pt-0 text-xs leading-relaxed border-t"
                                 :class="darkMode ? 'text-slate-300 border-slate-800/50' : 'text-slate-600 border-slate-200'">
                                {{ $faq->answer }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="space-y-4" x-data="{ openFaq: 0 }">
                    <div class="rounded-2xl p-5 space-y-2" :class="darkMode ? 'glass-card-dark' : 'glass-card-light'">
                        <h4 class="text-sm font-extrabold" :class="darkMode ? 'text-white' : 'text-slate-900'">{{ __('Bagaimana cara mengajukan bantuan teknis / masalah jaringan?') }}</h4>
                        <p class="text-xs" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">{{ __('Anda dapat mengisi formulir IT Helpdesk di bawah ini atau menghubungi kontak resmi Unit TIK.') }}</p>
                    </div>
                    <div class="rounded-2xl p-5 space-y-2" :class="darkMode ? 'glass-card-dark' : 'glass-card-light'">
                        <h4 class="text-sm font-extrabold" :class="darkMode ? 'text-white' : 'text-slate-900'">{{ __('Apakah Ruang Lab Komputer bisa dipinjam untuk kegiatan pelatihan?') }}</h4>
                        <p class="text-xs" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">{{ __('Ya, Ruang Komputer dapat digunaan untuk workshop, sertifikasi, dan pelatihan dengan mengajukan surat izin penggunaan lab.') }}</p>
                    </div>
                </div>
            @endif

        </div>
    </section>

    <!-- SECTION 9: IT HELPDESK & SUPPORT TICKET FORM / LIVE ASSISTANT -->
    <section id="helpdesk" class="py-20 relative transition-colors duration-300"
             :class="darkMode ? 'bg-slate-950' : 'bg-white'">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(($settings['chatbot_enabled'] ?? '1') === '1')
                <!-- KONDISI 1: JIKA CHATBOT AKTIF -> TAMPILKAN INTERACTIVE LIVE ASSISTANT BANNER & SEMBUNYIKAN FORM MANUAL -->
                <div class="rounded-3xl p-8 sm:p-12 border text-center space-y-6 transition-all duration-300 relative overflow-hidden"
                     :class="darkMode ? 'glass-card-dark glow-cyan border-cyan-500/30' : 'glass-card-light shadow-2xl border-cyan-200'">
                    
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-gradient-to-tr from-cyan-500 to-indigo-600 flex items-center justify-center text-white mx-auto shadow-xl shadow-cyan-500/30 border border-cyan-400/30">
                        <i data-lucide="headphones" class="w-8 h-8 sm:w-10 sm:h-10 animate-bounce"></i>
                    </div>

                    <div class="space-y-3 max-w-2xl mx-auto">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black border uppercase tracking-wider"
                             :class="darkMode ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30' : 'bg-cyan-50 text-cyan-700 border-cyan-200'">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            <span>{{ __('Asisten Virtual IT Helpdesk') }}</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-extrabold font-heading tracking-tight"
                            :class="darkMode ? 'text-white' : 'text-slate-900'">
                            {{ __('Layanan IT Helpdesk & Konsultasi Cepat') }}
                        </h2>
                        <p class="text-xs sm:text-sm leading-relaxed" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                            {{ __('Dapatkan panduan solusi otomatis seputar akun SSO, jaringan Wi-Fi, sistem aplikasi, atau buat tiket pengaduan teknis secara langsung melalui Asisten Cerdas kami.') }}
                        </p>
                    </div>

                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <button type="button" 
                                onclick="window.openItChatbot()" 
                                @click="window.openItChatbot()" 
                                class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-sm shadow-xl shadow-cyan-500/30 transition-all transform hover:scale-105 uppercase tracking-wider flex items-center justify-center gap-2.5 cursor-pointer">
                            <i data-lucide="message-square" class="w-5 h-5 text-slate-950"></i>
                            <span>{{ __('Buka Chat Asisten IT Helpdesk') }}</span>
                        </button>
                    </div>

                    <div class="pt-4 flex items-center justify-center gap-6 text-[11px] font-semibold text-slate-500 border-t border-slate-800/40">
                        <span class="flex items-center gap-1.5"><i data-lucide="zap" class="w-3.5 h-3.5 text-amber-400"></i> Respon Cepat 24/7</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="shield-check" class="w-3.5 h-3.5 text-cyan-400"></i> Basis Data FAQ Resmi</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="ticket" class="w-3.5 h-3.5 text-emerald-400"></i> Tiket Langsung ke Tim Teknis</span>
                    </div>
                </div>

            @else
                <!-- KONDISI 2: JIKA CHATBOT NONAKTIF -> TAMPILKAN FORMULIR MANUAL LENGKAP -->
                <div class="rounded-3xl p-8 sm:p-12 border space-y-8 transition-all duration-300"
                     :class="darkMode ? 'glass-card-dark glow-cyan border-slate-800' : 'glass-card-light shadow-xl border-slate-200'">
                    
                    <div class="text-center space-y-3">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black border uppercase tracking-wider"
                             :class="darkMode ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30' : 'bg-cyan-50 text-cyan-700 border-cyan-200'">
                            <i data-lucide="headphones" class="w-4 h-4"></i>
                            <span>{{ __('Formulir IT Support & Ticketing') }}</span>
                        </div>
                        <h2 class="text-3xl font-extrabold font-heading tracking-tight"
                            :class="darkMode ? 'text-white' : 'text-slate-900'">
                            {{ __('Hubungi IT Helpdesk & Tim Operasional') }}
                        </h2>
                        <p class="text-xs sm:text-sm" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                            {{ __('Kirimkan permintaan bantuan teknis, perbaikan jaringan, atau konsultasi server secara langsung.') }}
                        </p>
                    </div>

                    @if(session('success'))
                        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 text-xs font-bold text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('captcha_error') || $errors->has('captcha_input'))
                        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-bold text-center">
                            {{ session('captcha_error') ?? $errors->first('captcha_input') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">{{ __('Nama Lengkap / Instansi') }}</label>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso (Staf IT)" 
                                       class="w-full px-4 py-3 rounded-xl text-xs focus:outline-none focus:border-cyan-500 transition-colors border"
                                       :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">{{ __('Email Resmi') }}</label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@perusahaan.com" 
                                       class="w-full px-4 py-3 rounded-xl text-xs focus:outline-none focus:border-cyan-500 transition-colors border"
                                       :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">{{ __('Nomor Telepon / WhatsApp') }}</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="081234567890" 
                                       class="w-full px-4 py-3 rounded-xl text-xs focus:outline-none focus:border-cyan-500 transition-colors border"
                                       :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">{{ __('Kategori Permintaan') }}</label>
                                <select name="subject" class="w-full px-4 py-3 rounded-xl text-xs focus:outline-none focus:border-cyan-500 transition-colors border"
                                        :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                                    <option value="Perbaikan Sistem Aplikasi Web" {{ old('subject') == 'Perbaikan Sistem Aplikasi Web' ? 'selected' : '' }}>{{ __('Perbaikan Sistem Aplikasi Web') }}</option>
                                    <option value="Gangguan Jaringan & Wifi" {{ old('subject') == 'Gangguan Jaringan & Wifi' ? 'selected' : '' }}>{{ __('Gangguan Jaringan & Wifi') }}</option>
                                    <option value="Penggunaan Ruang Komputer Lab" {{ old('subject') == 'Penggunaan Ruang Komputer Lab' ? 'selected' : '' }}>{{ __('Penggunaan Ruang Komputer Lab') }}</option>
                                    <option value="Permintaan Server AWS/GCP/Private Cloud" {{ old('subject') == 'Permintaan Server AWS/GCP/Private Cloud' ? 'selected' : '' }}>{{ __('Permintaan Server AWS/GCP/Private Cloud') }}</option>
                                    <option value="Konsultasi IT Umum" {{ old('subject') == 'Konsultasi IT Umum' ? 'selected' : '' }}>{{ __('Konsultasi IT Umum') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">{{ __('Rincian Pesan / Kendala Teknis') }}</label>
                            <textarea name="message" rows="4" required placeholder="Jelaskan secara rinci kendala atau kebutuhan Anda..." 
                                      class="w-full px-4 py-3 rounded-xl text-xs focus:outline-none focus:border-cyan-500 transition-colors border"
                                      :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">{{ old('message') }}</textarea>
                        </div>

                        <!-- CAPTCHA VERIFICATION (ANTI-BOT) -->
                        <div class="space-y-1.5 pt-2">
                            <label class="text-xs font-bold flex items-center gap-1.5" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">
                                <i data-lucide="shield-check" class="w-4 h-4 text-cyan-400"></i>
                                <span>{{ __('Verifikasi Keamanan (Anti-Bot)') }}</span>
                                <span class="text-rose-400">*</span>
                            </label>
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                <div class="px-4 py-3 rounded-xl border font-mono font-extrabold text-sm tracking-wider select-none shrink-0 flex items-center gap-2"
                                     :class="darkMode ? 'bg-slate-900/90 border-cyan-500/30 text-cyan-400' : 'bg-cyan-50 border-cyan-200 text-cyan-700'">
                                    <i data-lucide="calculator" class="w-4 h-4 text-cyan-500"></i>
                                    <span>{{ $captchaQuestion ?? '5 + 3' }} = ?</span>
                                </div>
                                <input type="number" name="captcha_input" required placeholder="{{ __('Masukkan hasil penjumlahan angka di samping...') }}" 
                                       class="w-full px-4 py-3 rounded-xl text-xs focus:outline-none focus:border-cyan-500 transition-colors border"
                                       :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 font-black text-sm shadow-xl shadow-cyan-500/25 transition-all hover:scale-[1.01] uppercase tracking-wider flex items-center justify-center gap-2">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            <span>{{ __('Kirim Tiket Helpdesk') }}</span>
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </section>

@endsection
