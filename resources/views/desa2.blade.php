<!DOCTYPE html>
<html lang="id" class="scroll-smooth bg-slate-100 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['seo_default_title'] ?? $profile->company_name . ' - Web Profil Resmi Desa (Desain 2)' }}</title>
    <meta name="description" content="{{ $settings['seo_default_description'] ?? $profile->tagline ?? 'Portal Resmi Pelayanan Informasi dan Transparansi Desa' }}">
    
    @if($profile->favicon_path)
        <link rel="icon" href="{{ asset('storage/' . $profile->favicon_path) }}" type="image/png">
    @endif

    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN Fallback -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js & Lucide Icons -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }
        h1, h2, h3, .font-heading {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        }

        /* DAYAK KALIMANTAN BARAT AUTHENTIC ETHNIC MOTIF PATTERN FROM USER IMAGE - FULL COVERAGE */
        .dayak-pattern-dark {
            position: relative;
        }
        .dayak-pattern-dark::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('{{ asset("images/motif-dayak.jpg") }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.18;
            filter: hue-rotate(330deg) saturate(2.5) contrast(1.2);
            mix-blend-mode: screen;
            pointer-events: none;
            z-index: 1;
        }

        .dayak-pattern-light {
            position: relative;
        }
        .dayak-pattern-light::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('{{ asset("images/motif-dayak.jpg") }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.08;
            filter: sepia(1) hue-rotate(320deg) saturate(2.5);
            pointer-events: none;
            z-index: 1;
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-900 selection:bg-red-600 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- TOP ANNOUNCEMENT TICKER (DARK SLATE & RED DAYAK BADGE) -->
    <div class="bg-slate-950 text-white text-xs py-2.5 px-4 sm:px-6 lg:px-8 border-b border-slate-800 relative z-20">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 flex-shrink-0 font-bold bg-red-600 px-3 py-1 rounded-full text-[11px] tracking-wide uppercase text-white shadow-md shadow-red-600/30">
                <i data-lucide="megaphone" class="w-3.5 h-3.5"></i>
                <span>Pengumuman Desa</span>
            </div>
            <div class="overflow-hidden whitespace-nowrap w-full">
                <p class="animate-marquee inline-block text-slate-200 font-medium">
                    {{ $profile->announcement_ticker ?? ('Selamat Datang di Portal Resmi ' . $profile->company_name . '. Jam Operasional Pelayanan Balai Desa: Senin - Jumat (08:00 - 15:30 WIB). Untuk Layanan Darurat Hubungi Hotline Desa di ' . ($settings['contact_whatsapp'] ?? '(021) 8899-1234') . '.') }}
                </p>
            </div>
            @if(isset($settings['contact_whatsapp']))
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['contact_whatsapp']) }}" target="_blank" class="hidden md:flex items-center gap-1.5 font-bold text-red-400 hover:text-red-300 transition-colors flex-shrink-0 text-[11px]">
                    <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                    <span>WA Pelayanan</span>
                </a>
            @endif
        </div>
    </div>

    <!-- HEADER NAVIGATION BAR (FLOATING DARK HEADER STYLE WITH DAYAK CORNER MOTIFS) -->
    <header class="sticky top-0 z-50 py-3 px-4 sm:px-6 lg:px-8 bg-slate-900/95 backdrop-blur-md border-b border-slate-800 shadow-xl">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <!-- Brand Logo & Identity -->
            <a href="#" class="flex items-center gap-3 group flex-shrink-0">
                @if($profile->logo_path)
                    <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $profile->company_name }}" class="h-10 w-auto object-contain">
                @else
                    <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center text-white font-extrabold text-xl shadow-lg shadow-red-600/30 group-hover:scale-105 transition-transform">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                    </div>
                @endif
                <div>
                    <span class="text-base sm:text-lg font-extrabold text-white tracking-tight block group-hover:text-red-400 transition-colors leading-tight font-heading">
                        {{ $profile->company_name }}
                    </span>
                    <span class="text-xs text-red-400 font-semibold block mt-0.5 truncate max-w-[180px] sm:max-w-xs">
                        {{ $profile->tagline ?? 'Portal Resmi Pemerintah Desa' }}
                    </span>
                </div>
            </a>

            <!-- Desktop Navigation Menu -->
            <nav class="hidden lg:flex items-center gap-6 text-sm font-semibold text-slate-300">
                <a href="#beranda" class="hover:text-red-400 transition-colors py-2">Beranda</a>
                <a href="#profil" class="hover:text-red-400 transition-colors py-2">Profil & Visi Misi</a>
                <a href="#layanan" class="hover:text-red-400 transition-colors py-2">Layanan Publik</a>
                <a href="#pengurus" class="hover:text-red-400 transition-colors py-2">Pengurus Desa</a>
                <a href="#berita" class="hover:text-red-400 transition-colors py-2">Kabar Desa</a>
                <a href="#faq" class="hover:text-red-400 transition-colors py-2">FAQ</a>
            </nav>

            <!-- CTA Action Button (RED) -->
            <div class="hidden sm:flex items-center gap-3 flex-shrink-0">
                <a href="#pengaduan" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold bg-red-600 hover:bg-red-700 text-white shadow-lg shadow-red-600/30 transition-all hover:scale-105">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Aspirasi Warga</span>
                </a>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-xl text-slate-300 hover:text-white hover:bg-slate-800 focus:outline-none" aria-label="Toggle menu">
                <i x-show="!mobileMenuOpen" data-lucide="menu" class="w-6 h-6"></i>
                <i x-show="mobileMenuOpen" data-lucide="x" class="w-6 h-6" style="display: none;"></i>
            </button>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-transition class="lg:hidden border-t border-slate-800 bg-slate-900 px-4 pt-3 pb-6 space-y-2 shadow-2xl mt-3 rounded-2xl text-slate-200" style="display: none;">
            <a href="#beranda" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-200 hover:bg-slate-800 hover:text-red-400">Beranda</a>
            <a href="#profil" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-200 hover:bg-slate-800 hover:text-red-400">Profil & Visi Misi</a>
            <a href="#layanan" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-200 hover:bg-slate-800 hover:text-red-400">Layanan Publik</a>
            <a href="#pengurus" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-200 hover:bg-slate-800 hover:text-red-400">Pengurus Desa</a>
            <a href="#berita" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-200 hover:bg-slate-800 hover:text-red-400">Kabar Desa</a>
            <a href="#faq" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-200 hover:bg-slate-800 hover:text-red-400">FAQ Warga</a>
            
            <div class="pt-3 border-t border-slate-800">
                <a href="#pengaduan" @click="mobileMenuOpen = false" class="w-full block text-center py-3 rounded-xl font-extrabold bg-red-600 text-white shadow-lg shadow-red-600/30">
                    Kirim Aspirasi & Pengaduan
                </a>
            </div>
        </div>
    </header>

    <!-- DAYAK TRIBAL ORNAMENT RIBBON STRIP -->
    <div class="h-1.5 w-full bg-gradient-to-r from-red-700 via-amber-500 to-red-700 shadow-md"></div>

    <main>
        <!-- HERO SECTION WITH DAYAK KALIMANTAN BARAT MOTIF OVERLAY & ASYMMETRIC SPLIT LAYOUT -->
        <section id="beranda" class="py-12 lg:py-16 bg-slate-900 dayak-pattern-dark text-white relative overflow-hidden"
            x-data="{ 
                activeSlide: 0, 
                slidesCount: {{ max(1, $heroBanners->count()) }}, 
                next() { this.activeSlide = (this.activeSlide + 1) % this.slidesCount }, 
                prev() { this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount }, 
                init() { 
                    if (this.slidesCount > 1) { 
                        setInterval(() => this.next(), 6000); 
                    } 
                } 
            }">
            
            <!-- Dayak Watermark Shield SVG Background Overlay -->
            <div class="absolute right-0 top-0 bottom-0 w-1/2 opacity-10 pointer-events-none hidden lg:block overflow-hidden">
                <svg viewBox="0 0 400 600" class="w-full h-full text-red-500" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M200 20 L380 200 L200 580 L20 200 Z" />
                    <path d="M200 60 L340 200 L200 540 L60 200 Z" />
                    <circle cx="200" cy="200" r="60" />
                    <circle cx="200" cy="200" r="30" />
                    <path d="M200 140 C140 140 140 260 200 260 C260 260 260 140 200 140 Z" />
                </svg>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                    
                    <!-- Left Column: Content Text & Red Buttons (CSS Grid Stacked to prevent layout overlap) -->
                    <div class="lg:col-span-7 grid grid-cols-1 grid-rows-1 items-center min-h-[320px]">
                        @if($heroBanners->count() > 0)
                            @foreach($heroBanners as $index => $banner)
                                <div x-show="activeSlide === {{ $index }}"
                                     x-transition:enter="transition-all duration-600 ease-[cubic-bezier(0.16,1,0.3,1)] delay-100"
                                     x-transition:enter-start="opacity-0 -translate-x-10"
                                     x-transition:enter-end="opacity-100 translate-x-0"
                                     x-transition:leave="transition-all duration-150 ease-in"
                                     x-transition:leave-start="opacity-100 translate-x-0"
                                     x-transition:leave-end="opacity-0 translate-x-10"
                                     class="col-start-1 row-start-1 space-y-6"
                                     style="{{ $index !== 0 ? 'display: none;' : '' }}">
                                    
                                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-600/30 text-red-300 text-xs font-extrabold border border-red-500/40 backdrop-blur-md">
                                        <i data-lucide="shield-check" class="w-4 h-4 text-red-500"></i>
                                        <span>{{ $banner->badge_text ?? 'Portal Transparansi & Pelayanan Digital Desa' }}</span>
                                    </div>

                                    <h1 class="text-3xl sm:text-5xl font-black text-white leading-tight font-heading">
                                        {{ $banner->title }}
                                    </h1>

                                    @if($banner->subtitle)
                                        <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-normal max-w-xl">
                                            {{ $banner->subtitle }}
                                        </p>
                                    @endif

                                    <!-- CTA Red Buttons -->
                                    <div class="flex flex-wrap items-center gap-4 pt-2">
                                        @if($banner->button_text)
                                            <a href="{{ $banner->button_link ?? '#layanan' }}" class="px-7 py-4 rounded-xl bg-red-600 hover:bg-red-700 text-white font-extrabold text-sm shadow-xl shadow-red-600/40 transition-all hover:scale-105 flex items-center gap-2.5">
                                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                                <span>{{ $banner->button_text }}</span>
                                            </a>
                                        @else
                                            <a href="#layanan" class="px-7 py-4 rounded-xl bg-red-600 hover:bg-red-700 text-white font-extrabold text-sm shadow-xl shadow-red-600/40 transition-all hover:scale-105 flex items-center gap-2.5">
                                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                                <span>Lihat Layanan Kami</span>
                                            </a>
                                        @endif
                                        
                                        <a href="#berita" class="px-7 py-4 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 font-bold text-sm shadow-md transition-all hover:scale-105 flex items-center gap-2.5">
                                            <i data-lucide="newspaper" class="w-5 h-5 text-red-400"></i>
                                            <span>Jelajahi Berita Desa</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Fallback Text -->
                            <div class="col-start-1 row-start-1 space-y-6">
                                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-600/30 text-red-300 text-xs font-extrabold border border-red-500/40 backdrop-blur-md">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-red-500"></i>
                                    <span>Desa {{ $profile->company_name }}</span>
                                </div>

                                <h1 class="text-3xl sm:text-5xl font-black text-white leading-tight font-heading">
                                    Selamat Datang
                                </h1>

                                <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-normal max-w-xl">
                                    Di Website {{ $profile->company_name }} Kecamatan Ngabang, Kabupaten Landak, Kalimantan Barat
                                </p>

                                <div class="flex flex-wrap items-center gap-4 pt-2">
                                    <a href="#layanan" class="px-7 py-4 rounded-xl bg-red-600 hover:bg-red-700 text-white font-extrabold text-sm shadow-xl shadow-red-600/40 transition-all hover:scale-105 flex items-center gap-2.5">
                                        <i data-lucide="file-text" class="w-5 h-5"></i>
                                        <span>Lihat Layanan Kami</span>
                                    </a>
                                    <a href="#berita" class="px-7 py-4 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 font-bold text-sm shadow-md transition-all hover:scale-105 flex items-center gap-2.5">
                                        <i data-lucide="newspaper" class="w-5 h-5 text-red-400"></i>
                                        <span>Jelajahi Berita Desa</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Featured Image Showcase Frame -->
                    <div class="lg:col-span-5 relative">
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-slate-800 bg-slate-950 aspect-[4/3] sm:aspect-video lg:aspect-[4/3] group">
                            @if($heroBanners->count() > 0)
                                @foreach($heroBanners as $index => $banner)
                                    <div x-show="activeSlide === {{ $index }}"
                                         x-transition:enter="transition-all duration-700 ease-[cubic-bezier(0.16,1,0.3,1)] delay-75"
                                         x-transition:enter-start="opacity-0 -translate-x-8 scale-105"
                                         x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                                         x-transition:leave="transition-all duration-200 ease-in"
                                         x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                                         x-transition:leave-end="opacity-0 translate-x-8 scale-95"
                                         class="absolute inset-0"
                                         style="{{ $index !== 0 ? 'display: none;' : '' }}">
                                        @if($banner->image_path)
                                            <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-red-900 to-slate-900 flex items-center justify-center text-white/50">
                                                <i data-lucide="image" class="w-16 h-16"></i>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                                    </div>
                                @endforeach
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-red-900 to-slate-900 flex items-center justify-center text-white/50">
                                    <i data-lucide="building-2" class="w-16 h-16 text-red-400"></i>
                                </div>
                            @endif

                            <!-- Floating Badge Bottom -->
                            <div class="absolute bottom-4 left-4 right-4 p-4 rounded-2xl bg-slate-900/90 backdrop-blur-md border border-slate-700/60 flex items-center justify-between text-xs text-white">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-red-500 animate-ping"></div>
                                    <span class="font-bold">Galeri Foto & Video</span>
                                </div>
                                <span class="text-red-400 font-extrabold">Dokumentasi</span>
                            </div>
                        </div>

                        <!-- Slider Controls Below Image -->
                        @if($heroBanners->count() > 1)
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center gap-2">
                                    <button @click="prev()" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 transition-all">
                                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                    </button>
                                    <button @click="next()" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 transition-all">
                                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    @foreach($heroBanners as $index => $banner)
                                        <button @click="activeSlide = {{ $index }}" 
                                                class="h-2 rounded-full transition-all duration-300"
                                                :class="activeSlide === {{ $index }} ? 'bg-red-600 w-6' : 'bg-slate-700 w-2'"
                                                aria-label="Go to slide {{ $index + 1 }}">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </section>

        <!-- STATISTIK DEMOGRAFI WIDGET (RED ACCENT CARD STRIP) -->
        <section class="py-10 bg-white border-y border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    
                    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:border-red-500 hover:shadow-xl transition-all flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-red-600 text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-red-600/30">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-black text-slate-900 block font-heading">{{ $profile->stat_population ?? '3,450+' }}</span>
                            <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider block">Jumlah Penduduk</span>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:border-red-500 hover:shadow-xl transition-all flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-rose-600 text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-rose-600/30">
                            <i data-lucide="map" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-black text-slate-900 block font-heading">{{ $profile->stat_area ?? '12.5 km²' }}</span>
                            <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider block">Luas Wilayah</span>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:border-red-500 hover:shadow-xl transition-all flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-amber-500/30">
                            <i data-lucide="home" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-black text-slate-900 block font-heading">{{ $profile->stat_subdistricts ?? '6 Dusun' }}</span>
                            <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider block">Wilayah RT / RW</span>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:border-red-500 hover:shadow-xl transition-all flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-900 text-red-500 flex items-center justify-center flex-shrink-0 shadow-md">
                            <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-black text-slate-900 block font-heading">{{ $profile->stat_umkm ?? '48+' }}</span>
                            <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider block">UMKM Binaan Desa</span>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- PROFIL DESA & VISI MISI -->
        <section id="profil" class="py-16 sm:py-20 bg-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-3xl mx-auto mb-12 space-y-2">
                    <span class="text-xs font-extrabold tracking-widest text-red-600 uppercase">Mengenal Desa Kami</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading">Profil & Visi Misi Desa</h2>
                    <p class="text-xs sm:text-sm text-slate-600">Komitmen pimpinan dan aparatur desa dalam membangun desa.</p>
                </div>

                <div class="grid lg:grid-cols-12 gap-8 items-stretch">
                    <!-- Kades Card (DARK SLATE & RED BORDER ACCENT) -->
                    <div class="lg:col-span-5 p-6 sm:p-8 rounded-3xl bg-slate-900 text-white flex flex-col justify-between shadow-2xl border-2 border-red-600/60 relative overflow-hidden">
                        <div class="space-y-6 relative z-10">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-slate-800 border-2 border-red-500 overflow-hidden flex-shrink-0 flex items-center justify-center shadow-md">
                                    @if($profile->leader_photo_path)
                                        <img src="{{ asset('storage/' . $profile->leader_photo_path) }}" class="w-full h-full object-cover">
                                    @elseif($team->where('position', 'Kepala Desa')->first()?->photo_path)
                                        <img src="{{ asset('storage/' . $team->where('position', 'Kepala Desa')->first()->photo_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="user" class="w-8 h-8 text-red-400"></i>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-extrabold text-white leading-tight font-heading">
                                        {{ $profile->leader_name ?? ($team->where('position', 'Kepala Desa')->first()?->name ?? 'Kepala Desa') }}
                                    </h3>
                                    <span class="text-xs text-red-400 font-bold block mt-0.5">
                                        {{ $profile->leader_position ?? ('Kepala Desa ' . $profile->company_name) }}
                                    </span>
                                </div>
                            </div>

                            <blockquote class="text-xs sm:text-sm text-slate-200 italic leading-relaxed border-l-4 border-red-600 pl-4 py-1.5 bg-slate-950/60 rounded-r-xl">
                                "{{ $profile->about_us ?? 'Kami bertekad memberikan pelayanan terbaik, tercepat, dan bebas dari korupsi. Pembangunan desa ini adalah hasil kerja keras bersama seluruh warga.' }}"
                            </blockquote>
                        </div>

                        <div class="pt-4 mt-6 border-t border-slate-800 flex items-center justify-between text-xs text-slate-300 relative z-10">
                            <span class="font-semibold">{{ $profile->leader_term ?? 'Masa Jabatan 2021 - 2027' }}</span>
                            <div class="flex items-center gap-2">
                                @if($profile->logo_path)
                                    <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $profile->company_name }}" class="h-6 w-auto object-contain brightness-0 invert opacity-90">
                                @else
                                    <span class="text-red-400 font-bold flex items-center gap-1.5">
                                        <i data-lucide="building-2" class="w-4 h-4"></i> {{ $profile->company_name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Visi & Misi Cards -->
                    <div class="lg:col-span-7 space-y-5">
                        <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-2 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 font-bold">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 font-heading">Visi Desa</h3>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal pt-1">
                                {{ $profile->vision ?? 'Terwujudnya Desa yang Maju, Mandiri, Sejahtera, Berkarakter Budaya, dan Transparan dalam Tata Kelola Pemerintahan berbasis Teknologi Informasi.' }}
                            </p>
                        </div>

                        <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 font-bold">
                                    <i data-lucide="target" class="w-5 h-5"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 font-heading">Misi Utama Desa</h3>
                            </div>
                            <div class="text-xs sm:text-sm text-slate-600 leading-relaxed space-y-2">
                                @if($profile->mission)
                                    {!! nl2br(e($profile->mission)) !!}
                                @else
                                    <ul class="space-y-2 list-disc list-inside text-slate-600">
                                        <li>Meningkatkan kualitas pelayanan publik berbasis teknologi yang ramah dan cepat.</li>
                                        <li>Mewujudkan transparansi pengelolaan Anggaran Pendapatan dan Belanja Desa (APBDes).</li>
                                        <li>Mendorong pemberdayaan ekonomi warga melalui BUMDes dan UMKM lokal.</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

       

        <!-- LAYANAN PUBLIK & SURAT ONLINE -->
        <section id="layanan" class="py-16 sm:py-20 bg-white border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-3xl mx-auto mb-12 space-y-2">
                    <span class="text-xs font-extrabold tracking-widest text-red-600 uppercase">Pelayanan Kependudukan</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading">Layanan Kependudukan & Administrasi</h2>
                    <p class="text-xs sm:text-sm text-slate-600">Proses pengurusan surat pengantar dan administrasi desa secara transparan dan mudah.</p>
                </div>

                @if($services->count() > 0)
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($services as $service)
                            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 shadow-sm hover:border-red-500 hover:bg-white hover:shadow-xl transition-all group flex flex-col justify-between border-t-4 border-t-red-600">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-colors">
                                            <i data-lucide="{{ $service->icon_class ?? 'file-text' }}" class="w-6 h-6"></i>
                                        </div>
                                        <span class="text-[11px] font-extrabold text-red-700 px-3 py-1 rounded-full bg-red-100 uppercase tracking-wide">
                                            {{ $service->category->name ?? 'Layanan Publik' }}
                                        </span>
                                    </div>

                                    <div>
                                        <h3 class="text-base font-extrabold text-slate-900 group-hover:text-red-600 transition-colors leading-snug font-heading">
                                            {{ $service->title }}
                                        </h3>
                                        <p class="text-xs text-slate-600 mt-2 leading-relaxed line-clamp-3">
                                            {{ $service->description }}
                                        </p>
                                    </div>
                                </div>

                                <div class="pt-4 mt-4 border-t border-slate-200/60 flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium"></span>
                                    <a href="#pengaduan" class="font-extrabold text-red-600 hover:text-red-700 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                        Ajukan Layanan <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 shadow-sm hover:bg-white hover:border-red-500 transition-all border-t-4 border-t-red-600 space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center">
                                <i data-lucide="file-text" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 font-heading">Surat Keterangan Usaha (SKU)</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">Pengantar surat keterangan domisili usaha untuk keperluan perbankan dan izin UMKM.</p>
                        </div>

                        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 shadow-sm hover:bg-white hover:border-red-500 transition-all border-t-4 border-t-red-600 space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center">
                                <i data-lucide="user-check" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 font-heading">Surat Keterangan Domisili</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">Surat keterangan tempat tinggal resmi warga pendatang atau warga tempatan.</p>
                        </div>

                        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 shadow-sm hover:bg-white hover:border-red-500 transition-all border-t-4 border-t-red-600 space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center">
                                <i data-lucide="heart-handshake" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 font-heading">Surat Keterangan Tidak Mampu (SKTM)</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">Persyaratan pengajuan beasiswa pendidikan, bantuan kesehatan, dan beasiswa kurang mampu.</p>
                        </div>
                    </div>
                @endif

            </div>
        </section>

         <!-- STRUKTUR APARATUR & PENGURUS DESA SECTION -->
        <section id="pengurus" class="py-16 sm:py-20 text-white relative overflow-hidden bg-slate-950">
            <!-- Background Image with Dark Overlay -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/kampung-budaya-ngabang.jpg') }}');"></div>
            <div class="absolute inset-0 bg-slate-950/85 backdrop-blur-[3px]"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                
                <div class="text-center max-w-3xl mx-auto mb-14 space-y-4">
                    <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-red-600 text-white text-xs font-black border-2 border-red-500 shadow-xl shadow-red-600/40 uppercase tracking-wide">
                        <i data-lucide="users" class="w-4 h-4 text-white"></i>
                        <span>Aparatur Pemerintahan Desa</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-white font-heading tracking-tight drop-shadow-[0_4px_8px_rgba(0,0,0,1)]">
                        Struktur Pengurus & Aparatur Desa
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-100 font-extrabold max-w-xl mx-auto leading-relaxed bg-slate-950 border-2 border-slate-800 p-4 rounded-2xl shadow-2xl">
                        Jajaran pimpinan dan aparatur desa yang siap memberikan pelayanan publik terbaik, transparan, dan responsif kepada seluruh warga masyarakat.
                    </p>
                </div>

                @if($team->count() > 0)
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                        @foreach($team as $member)
                            <div class="group relative rounded-3xl bg-slate-950 border border-slate-800 p-6 flex flex-col items-center text-center shadow-xl hover:border-red-500/80 transition-all duration-300 hover:-translate-y-1.5 overflow-hidden">
                                
                                <!-- Decorative Red Glow Accent -->
                                <div class="absolute -top-12 -right-12 w-24 h-24 bg-red-600/10 rounded-full blur-2xl group-hover:bg-red-600/30 transition-all"></div>

                                <!-- Photo Frame with Red Border -->
                                <div class="relative w-28 h-28 rounded-2xl overflow-hidden border-2 border-red-500/60 shadow-lg shadow-red-500/10 mb-4 group-hover:scale-105 transition-transform duration-300 bg-slate-900 flex items-center justify-center">
                                    @if($member->photo_path)
                                        <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-800 flex items-center justify-center text-red-400 font-extrabold text-2xl uppercase">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Member Info -->
                                <div class="space-y-1.5 w-full">
                                    <h3 class="text-base font-extrabold text-white font-heading group-hover:text-red-400 transition-colors leading-snug">
                                        {{ $member->name }}
                                    </h3>
                                    <span class="inline-block px-3 py-1 rounded-full bg-red-600/20 text-red-400 text-[11px] font-extrabold uppercase border border-red-500/30 tracking-wider">
                                        {{ $member->position }}
                                    </span>
                                    @if($member->bio)
                                        <p class="text-[11px] text-slate-400 pt-2 leading-relaxed line-clamp-2">
                                            {{ $member->bio }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Social / Contact Links -->
                                @if($member->phone || $member->email)
                                    <div class="pt-4 mt-4 border-t border-slate-800/80 w-full flex items-center justify-center gap-3 text-xs text-slate-400">
                                        @if($member->phone)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $member->phone) }}" target="_blank" class="p-2 rounded-xl bg-slate-900 hover:bg-red-600 hover:text-white transition-colors border border-slate-800" title="Hubungi WhatsApp">
                                                <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                            </a>
                                        @endif
                                        @if($member->email)
                                            <a href="mailto:{{ $member->email }}" class="p-2 rounded-xl bg-slate-900 hover:bg-red-600 hover:text-white transition-colors border border-slate-800" title="Kirim Email">
                                                <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-8 rounded-3xl bg-slate-950/60 border border-slate-800 text-slate-400 text-xs italic">
                        Data pengurus desa belum diinputkan di Admin Panel.
                    </div>
                @endif

            </div>
        </section>

        <!-- KABAR & BERITA DESA -->
        <section id="berita" class="py-16 sm:py-20 bg-slate-100 border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                    <div class="space-y-2 max-w-2xl">
                        <span class="text-xs font-extrabold tracking-widest text-red-600 uppercase">Informasi & Publikasi</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading">Kabar & Berita Desa Terkini</h2>
                        <p class="text-xs sm:text-sm text-slate-600">Berita pembangunan, kegiatan kemasyarakatan, dan pengumuman resmi balai desa.</p>
                    </div>
                    <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition-all hover:scale-105 self-start md:self-auto">
                        <span>Cek Berita Lainnya</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                @if($articles->count() > 0)
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($articles as $article)
                            <article class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between group hover:shadow-xl transition-all">
                                <a href="{{ route('articles.show', $article->slug) }}" class="block flex-grow">
                                    <div class="aspect-[16/10] relative overflow-hidden bg-slate-100">
                                        @if($article->featured_image || $article->image_path)
                                            <img src="{{ asset('storage/' . ($article->featured_image ?? $article->image_path)) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-slate-800 via-slate-900 to-slate-950 p-6 flex flex-col justify-between text-white">
                                                <span class="text-xs text-red-400 font-bold uppercase tracking-wider">{{ $article->category->name ?? 'Berita Desa' }}</span>
                                                <div class="flex items-center gap-2 text-slate-400">
                                                    <i data-lucide="newspaper" class="w-8 h-8 text-red-500 opacity-80"></i>
                                                    <span class="text-xs font-semibold">Upload Gambar di Admin -> Berita</span>
                                                </div>
                                            </div>
                                        @endif
                                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-red-600 text-white text-[10px] font-extrabold shadow-md">
                                            {{ $article->category->name ?? 'Berita Desa' }}
                                        </span>
                                    </div>

                                    <div class="p-6 space-y-3">
                                        <div class="flex items-center gap-2 text-[11px] text-slate-500 font-medium">
                                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-red-600"></i>
                                            <span>{{ $article->published_at ? date('d M Y', strtotime($article->published_at)) : date('d M Y') }}</span>
                                        </div>
                                        <h3 class="text-base font-extrabold text-slate-900 group-hover:text-red-600 transition-colors leading-snug font-heading">
                                            {{ $article->title }}
                                        </h3>
                                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                            {{ $article->getExcerptClean(120) }}
                                        </p>
                                    </div>
                                </a>

                                <div class="px-6 py-3 border-t border-slate-100 flex items-center justify-between text-xs bg-slate-50/50">
                                    <span class="text-slate-500 font-medium">Oleh: {{ $article->user->name ?? 'Admin Desa' }}</span>
                                    <a href="{{ route('articles.show', $article->slug) }}" class="font-extrabold text-red-600 hover:text-red-700 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                        Baca Selengkapnya <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="text-center pt-10">
                        <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2.5 px-8 py-3.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs shadow-xl transition-all hover:scale-105 border border-slate-700">
                            <i data-lucide="newspaper" class="w-4 h-4 text-red-400"></i>
                            <span>Lihat Semua Berita Desa & Arsip</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 text-red-400"></i>
                        </a>
                    </div>
                @else
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3">
                            <span class="text-xs font-bold text-red-600">05 Agustus 2026</span>
                            <h3 class="text-base font-bold text-slate-900 leading-snug font-heading">Kerja Bakti Gotong Royong Pembersihan Lingkungan Dusun</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">Warga antusias membersihkan saluran irigasi desa menyongsong musim tanam.</p>
                        </div>
                        <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3">
                            <span class="text-xs font-bold text-red-600">03 Agustus 2026</span>
                            <h3 class="text-base font-bold text-slate-900 leading-snug font-heading">Pelatihan Digitalisasi Produk UMKM Olahan Pangan Desa</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">Pendampingan pembuatan kemasan modern dan pemasaran online bagi pelaku UMKM desa.</p>
                        </div>
                        <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-3">
                            <span class="text-xs font-bold text-red-600">01 Agustus 2026</span>
                            <h3 class="text-base font-bold text-slate-900 leading-snug font-heading">Penyaluran Bantuan Langsung Tunai (BLT) Dana Desa</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">Laporan pertanggungjawaban penerima manfaat BLT tahap III berjalan tertib dan lancar.</p>
                        </div>
                    </div>
                @endif

            </div>
        </section>

        <!-- FAQ WARGA -->
        <section id="faq" class="py-16 sm:py-20 bg-white border-t border-slate-200">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center space-y-2 mb-12">
                    <span class="text-xs font-extrabold tracking-widest text-red-600 uppercase">Tanya Jawab</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading">Pertanyaan Umum Warga (FAQ)</h2>
                    <p class="text-xs sm:text-sm text-slate-600">Jawaban cepat seputar pengurusan layanan dan informasi balai desa.</p>
                </div>

                <div class="space-y-4" x-data="{ activeFaq: {{ $faqs->first()?->id ?? 1 }} }">
                    @forelse($faqs as $index => $faq)
                        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden transition-all" :class="{'border-l-4 border-l-red-600 shadow-md': activeFaq === {{ $faq->id }}}">
                            <button @click="activeFaq = (activeFaq === {{ $faq->id }} ? null : {{ $faq->id }})" class="w-full p-5 sm:p-6 text-left font-bold text-slate-900 text-sm sm:text-base flex items-center justify-between gap-4">
                                <span>{{ $faq->question }}</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform" :class="{'rotate-180 text-red-600': activeFaq === {{ $faq->id }}}"></i>
                            </button>
                            <div x-show="activeFaq === {{ $faq->id }}" x-collapse class="px-5 sm:px-6 pb-6 text-xs sm:text-sm text-slate-600 border-t border-slate-100 pt-3 leading-relaxed">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center bg-slate-50 border border-slate-200 rounded-2xl text-slate-500 text-sm italic">
                            Belum ada pertanyaan FAQ yang dipublikasikan.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- FORM ASPIRASI & PENGADUAN WARGA (DARK SLATE BACKDROP) -->
        <section id="pengaduan" class="py-16 sm:py-20 bg-slate-950 text-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                    
                    <!-- Left Information -->
                    <div class="lg:col-span-5 space-y-6">
                        <div>
                            <span class="text-xs font-extrabold tracking-widest text-red-500 uppercase">Layanan Informasi</span>
                            <h2 class="text-2xl sm:text-4xl font-black text-white mt-1 font-heading">Aspirasi & Pengaduan Warga</h2>
                            <p class="text-xs sm:text-sm text-slate-300 mt-3 leading-relaxed font-normal">
                                Sampaikan saran, kritik, atau pengaduan secara langsung untuk perbaikan layanan pemerintahan desa kami.
                            </p>
                        </div>

                        <!-- Contact Badges -->
                        <div class="space-y-4 pt-2">
                            <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-900 border border-slate-800">
                                <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center flex-shrink-0 font-bold shadow-md shadow-red-600/30">
                                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-400 font-semibold block">Alamat Balai Desa</span>
                                    <p class="text-xs sm:text-sm font-bold text-white mt-0.5 leading-snug">
                                        {{ $profile->address ?? 'Jl. Raya Desa No. 01 Balai Desa Utama' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-900 border border-slate-800">
                                <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center flex-shrink-0 font-bold shadow-md shadow-red-600/30">
                                    <i data-lucide="phone-call" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-400 font-semibold block">Kontak Telepon / WA</span>
                                    <p class="text-xs sm:text-sm font-bold text-white mt-0.5">
                                        {{ $settings['contact_whatsapp'] ?? $profile->phone ?? '(021) 8899-1234' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Form Container -->
                    <div class="lg:col-span-7">
                        <div class="p-6 sm:p-8 rounded-3xl bg-white text-slate-900 shadow-2xl border border-slate-200">
                            <h3 class="text-lg font-bold text-slate-900 mb-1 font-heading">Kirimkan Aspirasi / Pengaduan Saya</h3>
                            <p class="text-xs text-slate-500 mb-6">Pesan Anda akan terkirim secara aman dan rahasia ke sekretariat desa.</p>

                            @if(session('contact_success'))
                                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
                                    <span>{{ session('contact_success') }}</span>
                                </div>
                            @endif

                            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap *</label>
                                        <input type="text" name="name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-xs focus:border-red-600 focus:outline-none" placeholder="Masukkan nama Anda">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Telepon / WA *</label>
                                        <input type="text" name="phone" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-xs focus:border-red-600 focus:outline-none" placeholder="0812xxxx">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email *</label>
                                        <input type="email" name="email" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-xs focus:border-red-600 focus:outline-none" placeholder="email@contoh.com">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Topik Subjek</label>
                                        <input type="text" name="subject" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-xs focus:border-red-600 focus:outline-none" placeholder="Pengaduan / Aspirasi / Pertanyaan">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Isi Aspirasi / Pengaduan *</label>
                                    <textarea name="message" rows="4" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-xs focus:border-red-600 focus:outline-none" placeholder="Tuliskan aspirasi atau rincian pengaduan Anda..."></textarea>
                                </div>

                                <button type="submit" class="w-full py-4 rounded-xl bg-red-600 hover:bg-red-700 text-white font-extrabold text-xs shadow-xl shadow-red-600/30 transition-all flex items-center justify-center gap-2">
                                    <i data-lucide="send" class="w-4 h-4"></i>
                                    <span>Kirimkan Aspirasi Saya</span>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-950 text-slate-400 text-xs py-10 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b border-slate-900 pb-6">
                <div class="flex items-center gap-3">
                    @if($profile->logo_path)
                        <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $profile->company_name }}" class="h-8 w-auto object-contain">
                    @else
                        <div class="w-9 h-9 rounded-xl bg-red-600 flex items-center justify-center text-white font-extrabold text-base">
                            <i data-lucide="building-2" class="w-5 h-5"></i>
                        </div>
                    @endif
                    <div>
                        <span class="text-sm font-extrabold text-white block font-heading">{{ $profile->company_name }}</span>
                        <span class="text-xs text-slate-400 block mt-0.5">© {{ date('Y') }} Create by ZONA IT PONTIANAK</span>
                    </div>
                </div>

                <div class="flex items-center gap-6 text-xs font-semibold text-slate-400">
                    <a href="#beranda" class="hover:text-red-400 transition-colors">Beranda</a>
                    <a href="#layanan" class="hover:text-red-400 transition-colors">Layanan</a>
                    <a href="#pengaduan" class="hover:text-red-400 transition-colors">Pengaduan</a>
                </div>
            </div>

            
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
