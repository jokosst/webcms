<!DOCTYPE html>
<html lang="id" class="scroll-smooth bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['seo_default_title'] ?? $profile->company_name . ' - Web Profil Resmi Desa' }}</title>
    <meta name="description" content="{{ $settings['seo_default_description'] ?? $profile->tagline ?? 'Portal Resmi Pelayanan Informasi dan Transparansi Desa' }}">
    
    @if($profile->favicon_path)
        <link rel="icon" href="{{ asset('storage/' . $profile->favicon_path) }}" type="image/png">
    @endif

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN Fallback for instant rendering -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js & Lucide Icons -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            overflow-x: hidden;
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 selection:bg-red-600 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- TOP ANNOUNCEMENT TICKER -->
    <div class="bg-red-900 text-white text-xs py-2.5 px-4 sm:px-6 lg:px-8 border-b border-red-800">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 flex-shrink-0 font-bold bg-red-950 px-3 py-1 rounded-full text-[11px] tracking-wide uppercase text-amber-300">
                <i data-lucide="megaphone" class="w-3.5 h-3.5"></i>
                <span>Pengumuman</span>
            </div>
            <div class="overflow-hidden whitespace-nowrap w-full">
                <p class="animate-marquee inline-block text-red-100 font-medium">
                    {{ $profile->announcement_ticker ?? ('Selamat Datang di Portal Resmi ' . $profile->company_name . '. Jam Operasional Pelayanan Balai Desa: Senin - Jumat (08:00 - 15:30 WIB). Untuk Layanan Darurat Hubungi Hotline Desa di ' . ($settings['contact_whatsapp'] ?? '(021) 8899-1234') . '.') }}
                </p>
            </div>
            @if(isset($settings['contact_whatsapp']))
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['contact_whatsapp']) }}" target="_blank" class="hidden md:flex items-center gap-1.5 font-bold text-amber-300 hover:text-amber-200 transition-colors flex-shrink-0 text-[11px]">
                    <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                    <span>WA Pelayanan</span>
                </a>
            @endif
        </div>
    </div>

    <!-- HEADER NAVIGATION BAR -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            <!-- Brand Logo & Identity -->
            <a href="#" class="flex items-center gap-3 group flex-shrink-0">
                @if($profile->logo_path)
                    <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $profile->company_name }}" class="h-10 w-auto object-contain">
                @else
                    <div class="w-11 h-11 rounded-xl bg-red-600 flex items-center justify-center text-white font-extrabold text-xl shadow-md group-hover:scale-105 transition-transform">
                        <i data-lucide="building-2" class="w-6 h-6"></i>
                    </div>
                @endif
                <div>
                    <span class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight block group-hover:text-red-600 transition-colors leading-tight">
                        {{ $profile->company_name }}
                    </span>
                    <span class="text-xs text-red-600 font-semibold block mt-0.5 truncate max-w-[180px] sm:max-w-xs">
                        {{ $profile->tagline ?? 'Portal Resmi Pemerintah Desa' }}
                    </span>
                </div>
            </a>

            <!-- Desktop Navigation Menu -->
            <nav class="hidden lg:flex items-center gap-6 text-sm font-semibold text-slate-600">
                <a href="#beranda" class="hover:text-red-600 transition-colors py-2">Beranda</a>
                <a href="#profil" class="hover:text-red-600 transition-colors py-2">Profil & Visi Misi</a>
                <a href="#layanan" class="hover:text-red-600 transition-colors py-2">Layanan Publik</a>
                <a href="#perangkat" class="hover:text-red-600 transition-colors py-2">Perangkat Desa</a>
                <a href="#berita" class="hover:text-red-600 transition-colors py-2">Kabar Desa</a>
                <a href="#faq" class="hover:text-red-600 transition-colors py-2">FAQ</a>
            </nav>

            <!-- CTA Action Button (RED) -->
            <div class="hidden sm:flex items-center gap-3 flex-shrink-0">
                <a href="#pengaduan" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-red-600 hover:bg-red-700 text-white shadow-md shadow-red-600/20 transition-all">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Aspirasi Warga</span>
                </a>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none" aria-label="Toggle menu">
                <i x-show="!mobileMenuOpen" data-lucide="menu" class="w-6 h-6"></i>
                <i x-show="mobileMenuOpen" data-lucide="x" class="w-6 h-6" style="display: none;"></i>
            </button>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-transition class="lg:hidden border-b border-slate-200 bg-white px-4 pt-3 pb-6 space-y-2 shadow-lg" style="display: none;">
            <a href="#beranda" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-red-50 hover:text-red-600">Beranda</a>
            <a href="#profil" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-red-50 hover:text-red-600">Profil & Visi Misi</a>
            <a href="#layanan" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-red-50 hover:text-red-600">Layanan Publik</a>
            <a href="#perangkat" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-red-50 hover:text-red-600">Perangkat Desa</a>
            <a href="#berita" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-red-50 hover:text-red-600">Kabar Desa</a>
            <a href="#faq" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-red-50 hover:text-red-600">FAQ Warga</a>
            
            <div class="pt-3 border-t border-slate-200">
                <a href="#pengaduan" @click="mobileMenuOpen = false" class="w-full block text-center py-3 rounded-xl font-bold bg-red-600 text-white shadow-md">
                    Kirim Aspirasi & Pengaduan
                </a>
            </div>
        </div>
    </header>

    <main>
        <!-- HERO SECTION SLIDER (FULL BACKGROUND SLIDER FROM HERO BANNERS ADMIN) -->
        <section id="beranda" class="relative min-h-[80vh] lg:min-h-[85vh] flex items-center bg-slate-950 overflow-hidden py-16 sm:py-20 text-white"
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
            
            <!-- BACKGROUND IMAGE SLIDES WITH OVERLAY -->
            <div class="absolute inset-0 z-0">
                @if($heroBanners->count() > 0)
                    @foreach($heroBanners as $index => $banner)
                        <div x-show="activeSlide === {{ $index }}"
                             x-transition:enter="transition ease-out duration-700"
                             x-transition:enter-start="opacity-0 scale-105"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-500"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute inset-0"
                             style="{{ $index !== 0 ? 'display: none;' : '' }}">
                            
                            @if($banner->image_path)
                                <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-red-950 via-slate-900 to-red-900"></div>
                            @endif
                            <!-- Balanced Gradient Overlay (Slightly Darker for Text Clarity) -->
                            <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/45 to-black/25"></div>
                        </div>
                    @endforeach
                @else
                    <div class="absolute inset-0">
                        <div class="w-full h-full bg-gradient-to-br from-red-950 via-slate-900 to-red-900"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/45 to-black/25"></div>
                    </div>
                @endif
            </div>

            <!-- FOREGROUND CONTENT LAYER -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                
                @if($heroBanners->count() > 0)
                    @foreach($heroBanners as $index => $banner)
                        <div x-show="activeSlide === {{ $index }}"
                             x-transition:enter="transition ease-out duration-500 delay-100"
                             x-transition:enter-start="opacity-0 translate-y-6"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-4"
                             class="max-w-3xl space-y-6 text-center sm:text-left"
                             style="{{ $index !== 0 ? 'display: none;' : '' }}">
                            
                            <div>
                                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-600/90 text-white text-xs font-bold border border-red-500/40 shadow-lg backdrop-blur-md">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-amber-300"></i>
                                    <span>{{ $banner->badge_text ?? 'Portal Transparansi & Pelayanan Digital Desa' }}</span>
                                </div>
                            </div>

                            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                                {{ $banner->title }}
                            </h1>

                            @if($banner->subtitle)
                                <p class="text-base sm:text-xl text-slate-200 leading-relaxed font-normal max-w-2xl">
                                    {{ $banner->subtitle }}
                                </p>
                            @endif

                            <!-- CTA Buttons -->
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 pt-2">
                                @if($banner->button_text)
                                    <a href="{{ $banner->button_link ?? '#layanan' }}" class="px-7 py-4 rounded-xl bg-red-600 hover:bg-red-700 text-white font-extrabold text-sm shadow-xl shadow-red-600/30 transition-all hover:scale-105 flex items-center gap-2.5">
                                        <i data-lucide="file-text" class="w-5 h-5"></i>
                                        <span>{{ $banner->button_text }}</span>
                                    </a>
                                @else
                                    <a href="#layanan" class="px-7 py-4 rounded-xl bg-red-600 hover:bg-red-700 text-white font-extrabold text-sm shadow-xl shadow-red-600/30 transition-all hover:scale-105 flex items-center gap-2.5">
                                        <i data-lucide="file-text" class="w-5 h-5"></i>
                                        <span>Layanan Surat Online</span>
                                    </a>
                                @endif
                                
                                <a href="#berita" class="px-7 py-4 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 hover:bg-white/20 text-white font-bold text-sm shadow-md transition-all hover:scale-105 flex items-center gap-2.5">
                                    <i data-lucide="compass" class="w-5 h-5 text-amber-300"></i>
                                    <span>Jelajahi Berita Desa</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Default Fallback Slide -->
                    <div class="max-w-3xl space-y-6 text-center sm:text-left">
                        <div>
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-600/90 text-white text-xs font-bold border border-red-500/40 shadow-lg backdrop-blur-md">
                                <i data-lucide="shield-check" class="w-4 h-4 text-amber-300"></i>
                                <span>Portal Transparansi & Pelayanan Digital Desa</span>
                            </div>
                        </div>

                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                            Selamat Datang di Portal Resmi <br class="hidden sm:inline">
                            <span class="text-red-500">
                                {{ $profile->company_name }}
                            </span>
                        </h1>

                        <p class="text-base sm:text-xl text-slate-200 leading-relaxed font-normal max-w-2xl">
                            {{ $profile->tagline ?? 'Mewujudkan Desa Mandiri, Sejahtera, Transparan, dan Berdaya Saing Melalui Pelayanan Publik Digital yang Mudah dan Cepat.' }}
                        </p>

                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 pt-2">
                            <a href="#layanan" class="px-7 py-4 rounded-xl bg-red-600 hover:bg-red-700 text-white font-extrabold text-sm shadow-xl shadow-red-600/30 transition-all hover:scale-105 flex items-center gap-2.5">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                <span>Layanan Surat Online</span>
                            </a>
                            <a href="#potensi" class="px-7 py-4 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 hover:bg-white/20 text-white font-bold text-sm shadow-md transition-all hover:scale-105 flex items-center gap-2.5">
                                <i data-lucide="compass" class="w-5 h-5 text-amber-300"></i>
                                <span>Jelajahi Berita Desa</span>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Slider Controls & Indicators -->
                @if($heroBanners->count() > 1)
                    <div class="flex items-center justify-between mt-10">
                        <div class="flex items-center gap-2">
                            <button @click="prev()" class="p-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 hover:bg-white/20 text-white shadow-md transition-all" aria-label="Previous Slide">
                                <i data-lucide="chevron-left" class="w-5 h-5"></i>
                            </button>
                            <button @click="next()" class="p-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 hover:bg-white/20 text-white shadow-md transition-all" aria-label="Next Slide">
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </button>
                        </div>

                        <div class="flex items-center gap-2">
                            @foreach($heroBanners as $index => $banner)
                                <button @click="activeSlide = {{ $index }}" 
                                        class="h-2.5 rounded-full transition-all duration-300"
                                        :class="activeSlide === {{ $index }} ? 'bg-red-500 w-8' : 'bg-white/40 hover:bg-white/70 w-2.5'"
                                        aria-label="Go to slide {{ $index + 1 }}">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </section>

        <!-- STATISTIK DEMOGRAFI WIDGET -->
        <section class="py-12 bg-white border-y border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center hover:bg-white hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-xl bg-red-100 text-red-700 mx-auto flex items-center justify-center mb-3">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                        <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block tracking-tight">{{ $profile->stat_population ?? '3,450+' }}</span>
                        <span class="text-xs font-semibold text-slate-500 mt-1 block">Jumlah Penduduk</span>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center hover:bg-white hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-700 mx-auto flex items-center justify-center mb-3">
                            <i data-lucide="map" class="w-6 h-6"></i>
                        </div>
                        <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block tracking-tight">{{ $profile->stat_area ?? '12.5 km²' }}</span>
                        <span class="text-xs font-semibold text-slate-500 mt-1 block">Luas Wilayah</span>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center hover:bg-white hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 mx-auto flex items-center justify-center mb-3">
                            <i data-lucide="home" class="w-6 h-6"></i>
                        </div>
                        <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block tracking-tight">{{ $profile->stat_subdistricts ?? '6 Dusun' }}</span>
                        <span class="text-xs font-semibold text-slate-500 mt-1 block">Wilayah RT / RW</span>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 text-center hover:bg-white hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-700 mx-auto flex items-center justify-center mb-3">
                            <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                        </div>
                        <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 block tracking-tight">{{ $profile->stat_umkm ?? '48+' }}</span>
                        <span class="text-xs font-semibold text-slate-500 mt-1 block">UMKM Binaan Desa</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- PROFIL DESA & VISI MISI -->
        <section id="profil" class="py-16 sm:py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-3xl mx-auto mb-12 space-y-2">
                    <span class="text-xs font-bold tracking-widest text-red-600 uppercase">Mengenal Desa Kami</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Profil & Visi Misi Desa</h2>
                    <p class="text-xs sm:text-sm text-slate-600">Komitmen pimpinan dan aparatur desa dalam membangun desa.</p>
                </div>

                <div class="grid lg:grid-cols-12 gap-8 items-stretch">
                    <!-- Kades Card -->
                    <div class="lg:col-span-5 p-6 sm:p-8 rounded-2xl bg-gradient-to-br from-red-800 via-rose-900 to-red-950 text-white flex flex-col justify-between shadow-lg">
                        <div class="space-y-5">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-xl bg-white/10 border border-red-400/30 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                    @if($profile->leader_photo_path)
                                        <img src="{{ asset('storage/' . $profile->leader_photo_path) }}" class="w-full h-full object-cover">
                                    @elseif($team->where('position', 'Kepala Desa')->first()?->photo_path)
                                        <img src="{{ asset('storage/' . $team->where('position', 'Kepala Desa')->first()->photo_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="user" class="w-8 h-8 text-red-200"></i>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-white leading-tight">
                                        {{ $profile->leader_name ?? ($team->where('position', 'Kepala Desa')->first()?->name ?? 'Kepala Desa') }}
                                    </h3>
                                    <span class="text-xs text-red-200 font-medium block mt-0.5">
                                        {{ $profile->leader_position ?? ('Kepala Desa ' . $profile->company_name) }}
                                    </span>
                                </div>
                            </div>

                            <blockquote class="text-xs sm:text-sm text-red-100 italic leading-relaxed border-l-2 border-amber-400 pl-4 py-1">
                                "{{ $profile->about_us ?? 'Kami bertekad memberikan pelayanan terbaik, tercepat, dan bebas dari korupsi. Pembangunan desa ini adalah hasil kerja keras bersama seluruh warga.' }}"
                            </blockquote>
                        </div>

                        <div class="pt-4 mt-6 border-t border-red-700/50 flex items-center justify-between text-xs text-red-200">
                            <span>{{ $profile->leader_term ?? 'Masa Jabatan 2021 - 2027' }}</span>
                            <div class="flex items-center gap-2">
                                @if($profile->logo_path)
                                    <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $profile->company_name }}" class="h-6 w-auto object-contain brightness-0 invert opacity-90">
                                @else
                                    <span class="text-amber-300 font-bold flex items-center gap-1.5">
                                        <i data-lucide="building-2" class="w-4 h-4"></i> {{ $profile->company_name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Visi & Misi Cards -->
                    <div class="lg:col-span-7 space-y-5">
                        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-2">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-red-100 text-red-700 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900">Visi Desa</h3>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal pt-1">
                                {{ $profile->vision ?? 'Terwujudnya Desa yang Maju, Mandiri, Sejahtera, Berkarakter Budaya, dan Transparan dalam Tata Kelola Pemerintahan berbasis Teknologi Informasi.' }}
                            </p>
                        </div>

                        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="target" class="w-5 h-5"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900">Misi Utama</h3>
                            </div>

                            @if($profile->mission)
                                <p class="text-xs sm:text-sm text-slate-600 whitespace-pre-line leading-relaxed pt-1">{{ $profile->mission }}</p>
                            @else
                                <ul class="space-y-2.5 text-xs sm:text-sm text-slate-600 pt-1">
                                    <li class="flex items-start gap-2.5">
                                        <i data-lucide="check-circle-2" class="w-4 h-4 text-red-600 mt-0.5 flex-shrink-0"></i>
                                        <span>Meningkatkan kualitas pelayanan publik secara digital dan responsif.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <i data-lucide="check-circle-2" class="w-4 h-4 text-red-600 mt-0.5 flex-shrink-0"></i>
                                        <span>Pembangunan infrastruktur desa yang merata di seluruh dusun.</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <i data-lucide="check-circle-2" class="w-4 h-4 text-red-600 mt-0.5 flex-shrink-0"></i>
                                        <span>Pemberdayaan ekonomi warga melalui UMKM dan BUMDes unggulan.</span>
                                    </li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- LAYANAN PUBLIK & ADMINISTRASI -->
        <section id="layanan" class="py-16 sm:py-20 bg-white border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-3xl mx-auto mb-12 space-y-2">
                    <span class="text-xs font-bold tracking-widest text-red-600 uppercase">Pelayanan Warga</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Layanan Publik & Surat Online</h2>
                    <p class="text-xs sm:text-sm text-slate-600">Pengurusan dokumen kependudukan dan surat keterangan kini makin cepat dan transparan.</p>
                </div>

                @if($services->count() > 0)
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($services as $service)
                            <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-lg hover:border-red-300 transition-all flex flex-col justify-between group">
                                <div class="space-y-3">
                                    <div class="w-12 h-12 rounded-xl bg-red-100 text-red-700 flex items-center justify-center group-hover:scale-105 transition-transform">
                                        <i data-lucide="file-check" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase text-red-600 tracking-wider">
                                            {{ $service->category->name ?? 'Administrasi' }}
                                        </span>
                                        <h3 class="text-base font-bold text-slate-900 group-hover:text-red-600 transition-colors mt-0.5">
                                            {{ $service->title }}
                                        </h3>
                                    </div>
                                    <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">
                                        {{ $service->description }}
                                    </p>
                                </div>

                                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium"></span>
                                    <a href="#" class="font-bold text-red-600 hover:text-red-700 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                        Selengkapnya <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between group">
                            <div class="space-y-3">
                                <div class="w-12 h-12 rounded-xl bg-red-100 text-red-700 flex items-center justify-center">
                                    <i data-lucide="id-card" class="w-6 h-6"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 group-hover:text-red-600 transition-colors">Surat Keterangan Usaha (SKU)</h3>
                                <p class="text-xs text-slate-600 leading-relaxed">Pengurusan persyaratan usaha bagi UMKM lokal untuk mengajukan bantuan / perizinan perbankan.</p>
                            </div>
                            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-500">Proses: 1 Hari Kerja</span>
                                <a href="#pengaduan" class="font-bold text-red-600 flex items-center gap-1">Mulai <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
                            </div>
                        </div>

                        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between group">
                            <div class="space-y-3">
                                <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center">
                                    <i data-lucide="home" class="w-6 h-6"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 group-hover:text-red-600 transition-colors">Surat Keterangan Domisili</h3>
                                <p class="text-xs text-slate-600 leading-relaxed">Surat resmi pernyataan domisili tempat tinggal bagi penduduk tetap maupun pendatang.</p>
                            </div>
                            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-500">Proses: Langsung Jadi</span>
                                <a href="#pengaduan" class="font-bold text-red-600 flex items-center gap-1">Mulai <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
                            </div>
                        </div>

                        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between group">
                            <div class="space-y-3">
                                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                                    <i data-lucide="heart-handshake" class="w-6 h-6"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 group-hover:text-red-600 transition-colors">Pengantar KTP & KK</h3>
                                <p class="text-xs text-slate-600 leading-relaxed">Layanan rekomendasi pengantar perubahan data KK dan pencetakan fisik E-KTP baru.</p>
                            </div>
                            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-500">Proses: Gratis 100%</span>
                                <a href="#pengaduan" class="font-bold text-red-600 flex items-center gap-1">Mulai <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </section>

        

       

        <!-- STRUKTUR PERANGKAT DESA -->
        <section id="perangkat" class="py-16 sm:py-20 bg-slate-50 border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-3xl mx-auto mb-12 space-y-2">
                    <span class="text-xs font-bold tracking-widest text-red-600 uppercase">Aparatur Pemerintahan</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Struktur Perangkat Desa</h2>
                    <p class="text-xs sm:text-sm text-slate-600">Aparatur desa yang siap melayani kebutuhan administrasi dan kemasyarakatan warga.</p>
                </div>

                @if($team->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($team as $member)
                            <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm text-center space-y-3 group hover:border-red-300 transition-all">
                                <div class="w-20 h-20 rounded-xl mx-auto bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                                    @if($member->photo_path)
                                        <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="user" class="w-8 h-8 text-slate-400"></i>
                                    @endif
                                </div>

                                <div>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-red-600 transition-colors leading-snug">
                                        {{ $member->name }}
                                    </h3>
                                    <span class="text-xs text-red-700 font-semibold block mt-0.5">
                                        {{ $member->position }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
                        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-2">
                            <div class="w-14 h-14 rounded-full bg-red-100 mx-auto flex items-center justify-center text-red-700 font-extrabold text-sm">KDT</div>
                            <h4 class="text-sm font-bold text-slate-900">Kepala Desa</h4>
                            <span class="text-[11px] text-slate-500 block">Penanggung Jawab Wilayah</span>
                        </div>
                        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-2">
                            <div class="w-14 h-14 rounded-full bg-rose-100 mx-auto flex items-center justify-center text-rose-700 font-extrabold text-sm">SEK</div>
                            <h4 class="text-sm font-bold text-slate-900">Sekretaris Desa</h4>
                            <span class="text-[11px] text-slate-500 block">Koordinator Administrasi</span>
                        </div>
                        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-2">
                            <div class="w-14 h-14 rounded-full bg-amber-100 mx-auto flex items-center justify-center text-amber-700 font-extrabold text-sm">KAU</div>
                            <h4 class="text-sm font-bold text-slate-900">Kaur Keuangan</h4>
                            <span class="text-[11px] text-slate-500 block">Pengelola Anggaran Desa</span>
                        </div>
                        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-2">
                            <div class="w-14 h-14 rounded-full bg-indigo-100 mx-auto flex items-center justify-center text-indigo-700 font-extrabold text-sm">KAS</div>
                            <h4 class="text-sm font-bold text-slate-900">Kasi Pelayanan</h4>
                            <span class="text-[11px] text-slate-500 block">Petugas Layanan Warga</span>
                        </div>
                    </div>
                @endif

            </div>
        </section>

         <!-- KABAR & BERITA DESA -->
        <section id="berita" class="py-16 sm:py-20 bg-white border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                    <div class="space-y-2 max-w-2xl text-center md:text-left">
                        <span class="text-xs font-bold tracking-widest text-red-600 uppercase">Informasi Publik</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Kabar & Pengumuman Terbaru</h2>
                        <p class="text-xs sm:text-sm text-slate-600">Berita kegiatan warga, transparansi anggaran, dan agenda resmi pemerintahan desa.</p>
                    </div>
                    <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-extrabold text-xs shadow-lg shadow-red-600/30 transition-all hover:scale-105 self-center md:self-auto">
                        <span>Cek Berita Lainnya</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                @if($articles->count() > 0)
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($articles as $article)
                            <article class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between group hover:shadow-lg transition-all">
                                <a href="{{ route('articles.show', $article->slug) }}" class="block flex-grow">
                                    <div class="aspect-video relative overflow-hidden bg-slate-100">
                                        @if($article->featured_image || $article->image_path)
                                            <img src="{{ asset('storage/' . ($article->featured_image ?? $article->image_path)) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full bg-slate-800 p-6 flex flex-col justify-between text-white">
                                                <span class="text-xs text-red-400 font-bold uppercase tracking-wider">{{ $article->category->name ?? 'Berita Desa' }}</span>
                                                <div class="flex items-center gap-2 text-slate-400">
                                                    <i data-lucide="newspaper" class="w-8 h-8 text-red-500 opacity-80"></i>
                                                    <span class="text-xs font-semibold">Upload Gambar di Admin -> Berita</span>
                                                </div>
                                            </div>
                                        @endif
                                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-red-600 text-[10px] font-bold text-white shadow-sm">
                                            {{ $article->category->name ?? 'Pengumuman' }}
                                        </span>
                                    </div>

                                    <div class="p-5 space-y-2">
                                        <span class="text-[10px] text-slate-400 block font-semibold">
                                            {{ $article->published_at ? date('d M Y', strtotime($article->published_at)) : date('d M Y') }}
                                        </span>
                                        <h3 class="text-base font-bold text-slate-900 group-hover:text-red-600 transition-colors line-clamp-2 leading-snug">
                                            {{ $article->title }}
                                        </h3>
                                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                            {{ $article->getExcerptClean(120) }}
                                        </p>
                                    </div>
                                </a>

                                <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between text-xs bg-slate-50/50">
                                    <span class="text-slate-500">Oleh: {{ $article->user->name ?? 'Admin Desa' }}</span>
                                    <a href="{{ route('articles.show', $article->slug) }}" class="font-bold text-red-600 hover:text-red-700 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                        Baca Selengkapnya <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="text-center pt-10">
                        <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2.5 px-8 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs shadow-lg transition-all hover:scale-105">
                            <i data-lucide="newspaper" class="w-4 h-4 text-red-400"></i>
                            <span>Lihat Semua Berita Desa & Arsip</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 text-red-400"></i>
                        </a>
                    </div>
                @else
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-2">
                            <span class="text-xs font-bold text-red-600">12 Agustus 2026</span>
                            <h3 class="text-base font-bold text-slate-900 leading-snug">Musyawarah Perencanaan Pembangunan Desa (Musrenbangdes)</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">Undangan terbuka bagi seluruh ketua RT/RW dan tokoh masyarakat untuk menghadiri penyusunan RKPDes.</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-2">
                            <span class="text-xs font-bold text-rose-600">08 Agustus 2026</span>
                            <h3 class="text-base font-bold text-slate-900 leading-snug">Jadwal Posyandu Lansia & Balita Serentak</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">Kegiatan pemeriksaan kesehatan gratis dan pemberian makanan tambahan di Poskesdes tiap dusun.</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-2">
                            <span class="text-xs font-bold text-amber-600">01 Agustus 2026</span>
                            <h3 class="text-base font-bold text-slate-900 leading-snug">Penyaluran Bantuan Langsung Tunai (BLT) Dana Desa</h3>
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
                    <span class="text-xs font-bold tracking-widest text-red-600 uppercase">Tanya Jawab</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Pertanyaan Umum Warga (FAQ)</h2>
                    <p class="text-xs sm:text-sm text-slate-600">Jawaban cepat seputar pengurusan layanan dan informasi balai desa.</p>
                </div>

                <div class="space-y-4" x-data="{ activeFaq: {{ $faqs->first()?->id ?? 1 }} }">
                    @forelse($faqs as $index => $faq)
                        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
                            <button @click="activeFaq = (activeFaq === {{ $faq->id }} ? null : {{ $faq->id }})" class="w-full p-5 sm:p-6 text-left font-bold text-slate-900 text-sm sm:text-base flex items-center justify-between gap-4">
                                <span>{{ $faq->question }}</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform" :class="{'rotate-180': activeFaq === {{ $faq->id }}}"></i>
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

        <!-- FORM ASPIRASI & PENGADUAN WARGA -->
        <section id="pengaduan" class="py-16 sm:py-20 bg-slate-900 text-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                    
                    <!-- Left Info -->
                    <div class="lg:col-span-5 space-y-6">
                        <div class="space-y-2">
                            <span class="text-xs font-bold tracking-widest text-red-400 uppercase">Layanan Aspirasi</span>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-white">Kirim Pesan & Pengaduan Warga</h2>
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                                Punya masukan, saran pembangunan, atau butuh bantuan informasi pelayanan? Sampaikan pesan Anda secara tertulis di sini.
                            </p>
                        </div>

                        <!-- Alert Success -->
                        @if(session('contact_success'))
                            <div class="p-4 rounded-xl bg-red-500/20 border border-red-500/40 text-red-300 text-xs sm:text-sm flex items-start gap-3">
                                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <strong class="font-bold block">Pesan Terkirim!</strong>
                                    {{ session('contact_success') }}
                                </div>
                            </div>
                        @endif

                        <div class="space-y-3">
                            <div class="p-4 rounded-xl bg-slate-800 border border-slate-700 flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-lg bg-red-500/20 text-red-400 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                                </div>
                                <div class="text-xs">
                                    <span class="text-slate-400 block">Kantor Balai Desa</span>
                                    <strong class="text-white text-xs sm:text-sm block mt-0.5">{{ $profile->address ?? 'Jl. Raya Desa No. 01, Kecamatan Nusantara' }}</strong>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-3">
                                <div class="p-4 rounded-xl bg-slate-800 border border-slate-700 flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-rose-500/20 text-rose-400 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="mail" class="w-4 h-4"></i>
                                    </div>
                                    <div class="text-xs min-w-0">
                                        <span class="text-slate-400 block">Email Resmi</span>
                                        <strong class="text-white truncate block mt-0.5">{{ $profile->email ?? 'desa@nusantara.go.id' }}</strong>
                                    </div>
                                </div>

                                <div class="p-4 rounded-xl bg-slate-800 border border-slate-700 flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="phone" class="w-4 h-4"></i>
                                    </div>
                                    <div class="text-xs">
                                        <span class="text-slate-400 block">Telepon Kantor</span>
                                        <strong class="text-white block mt-0.5">{{ $profile->phone ?? '(021) 8899-1234' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Form Card -->
                    <div class="lg:col-span-7">
                        <div class="p-6 sm:p-8 rounded-2xl bg-slate-800 border border-slate-700 shadow-xl">
                            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4 sm:space-y-5">
                                @csrf

                                <div class="grid sm:grid-cols-2 gap-4 sm:gap-5">
                                    <div class="space-y-1.5">
                                        <label for="name" class="text-xs font-bold text-slate-300 block">Nama Lengkap Warga <span class="text-red-400">*</span></label>
                                        <input type="text" id="name" name="name" required placeholder="Contoh: Ahmad Subagyo" class="w-full px-4 py-2.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs sm:text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="email" class="text-xs font-bold text-slate-300 block">Alamat Email / Kontak <span class="text-red-400">*</span></label>
                                        <input type="email" id="email" name="email" required placeholder="nama@email.com" class="w-full px-4 py-2.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs sm:text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                                    </div>
                                </div>

                                <div class="grid sm:grid-cols-2 gap-4 sm:gap-5">
                                    <div class="space-y-1.5">
                                        <label for="phone" class="text-xs font-bold text-slate-300 block">Nomor HP / WhatsApp</label>
                                        <input type="text" id="phone" name="phone" placeholder="0812xxxxxxxx" class="w-full px-4 py-2.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs sm:text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="subject" class="text-xs font-bold text-slate-300 block">Topik / Perihal Pengaduan</label>
                                        <input type="text" id="subject" name="subject" placeholder="Contoh: Permohonan Surat / Usul Paving" class="w-full px-4 py-2.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs sm:text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label for="message" class="text-xs font-bold text-slate-300 block">Isi Pesan / Aspirasi <span class="text-red-400">*</span></label>
                                    <textarea id="message" name="message" rows="4" required placeholder="Tuliskan rincian aspirasi atau kebutuhan surat Anda secara jelas..." class="w-full px-4 py-2.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs sm:text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"></textarea>
                                </div>

                                <!-- RED SUBMIT BUTTON -->
                                <button type="submit" class="w-full py-3.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-extrabold text-xs sm:text-sm shadow-md shadow-red-600/20 transition-all flex items-center justify-center gap-2">
                                    <i data-lucide="send" class="w-4 h-4"></i>
                                    <span>Kirimkan Aspirasi / Pengaduan Saya</span>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-950 text-white border-t border-slate-800 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-red-600 text-white flex items-center justify-center font-bold">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-sm font-extrabold text-white block">{{ $profile->company_name }}</span>
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
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
