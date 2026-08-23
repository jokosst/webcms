<!DOCTYPE html>
<html lang="id" class="scroll-smooth bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['seo_default_title'] ?? $profile->company_name . ' - Official Web Profile' }}</title>
    <meta name="description" content="{{ $settings['seo_default_description'] ?? $profile->tagline }}">
    
    @if($profile->favicon_path)
        <link rel="icon" href="{{ asset('storage/' . $profile->favicon_path) }}" type="image/png">
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js & Lucide Icons -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950 text-slate-100 selection:bg-indigo-500 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- Header Navigation Bar -->
    <header class="fixed top-0 inset-x-0 z-50 bg-slate-950/80 backdrop-blur-md border-b border-slate-800/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="#" class="flex items-center gap-3 group">
                @if($profile->logo_path)
                    <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $profile->company_name }}" class="h-9 w-auto object-contain">
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-violet-600 to-pink-500 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform">
                        {{ strtoupper(substr($profile->company_name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <span class="text-base font-extrabold text-white tracking-tight block">{{ $profile->company_name }}</span>
                    @if($profile->tagline)
                        <span class="text-[11px] text-slate-400 font-medium block -mt-0.5 truncate max-w-xs">{{ $profile->tagline }}</span>
                    @endif
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden lg:flex items-center gap-8 text-sm font-medium text-slate-300">
                <a href="#home" class="hover:text-indigo-400 transition-colors">Beranda</a>
                <a href="#about" class="hover:text-indigo-400 transition-colors">Tentang Kami</a>
                <a href="#services" class="hover:text-indigo-400 transition-colors">Layanan</a>
                <a href="#portfolio" class="hover:text-indigo-400 transition-colors">Portofolio</a>
                <a href="#articles" class="hover:text-indigo-400 transition-colors">Artikel</a>
                <a href="#team" class="hover:text-indigo-400 transition-colors">Tim</a>
                <a href="#contact" class="hover:text-indigo-400 transition-colors">Kontak</a>
            </nav>

            <!-- Admin Button -->
            <div class="hidden sm:flex items-center gap-4">
                <a href="{{ route('admin.login') }}" class="px-4 py-2 text-xs font-semibold text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl flex items-center gap-2 transition-all hover:scale-105 shadow-md">
                    <i data-lucide="shield-check" class="w-4 h-4 text-indigo-400"></i>
                    <span>Portal Admin</span>
                </a>
            </div>

            <!-- Mobile Menu Trigger -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-slate-400 hover:text-white">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" x-transition class="lg:hidden bg-slate-900 border-b border-slate-800 px-4 py-6 space-y-4">
            <a href="#home" @click="mobileMenuOpen = false" class="block text-sm font-medium text-slate-300 hover:text-white">Beranda</a>
            <a href="#about" @click="mobileMenuOpen = false" class="block text-sm font-medium text-slate-300 hover:text-white">Tentang Kami</a>
            <a href="#services" @click="mobileMenuOpen = false" class="block text-sm font-medium text-slate-300 hover:text-white">Layanan</a>
            <a href="#portfolio" @click="mobileMenuOpen = false" class="block text-sm font-medium text-slate-300 hover:text-white">Portofolio</a>
            <a href="#articles" @click="mobileMenuOpen = false" class="block text-sm font-medium text-slate-300 hover:text-white">Artikel</a>
            <a href="#team" @click="mobileMenuOpen = false" class="block text-sm font-medium text-slate-300 hover:text-white">Tim</a>
            <a href="#contact" @click="mobileMenuOpen = false" class="block text-sm font-medium text-slate-300 hover:text-white">Kontak</a>
            <a href="{{ route('admin.login') }}" class="block text-center py-2.5 text-xs font-semibold text-white bg-indigo-600 rounded-xl">Portal Admin CMS</a>
        </div>
    </header>

    <!-- SECTION 1: HERO SECTION -->
    <section id="home" class="relative pt-32 pb-20 md:pt-44 md:pb-32 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/30 via-slate-950 to-slate-950 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @php $mainBanner = $heroBanners->first(); @endphp
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                        <span>{{ $profile->company_name }}</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                        {{ $mainBanner->title ?? 'Solusi Digital Terbaik untuk Bisnis Anda' }}
                    </h1>

                    <p class="text-base sm:text-lg text-slate-400 max-w-2xl mx-auto lg:mx-0 font-normal leading-relaxed">
                        {{ $mainBanner->subtitle ?? $profile->tagline ?? 'Kami membantu mengembangkan sistem web, aplikasi mobile, dan transformasi IT terintegrasi.' }}
                    </p>

                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="{{ $mainBanner->button_link ?? '#contact' }}" class="px-6 py-3.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold text-sm rounded-xl shadow-xl shadow-indigo-600/30 transition-all hover:scale-105 flex items-center gap-2">
                            <span>{{ $mainBanner->button_text ?? 'Hubungi Kami' }}</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                        <a href="#services" class="px-6 py-3.5 bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white font-semibold text-sm rounded-xl transition-all">
                            Lihat Layanan
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 relative">
                    <div class="relative rounded-2xl overflow-hidden border border-slate-800 bg-slate-900/80 p-4 shadow-2xl shadow-indigo-950/40">
                        @if($mainBanner && $mainBanner->image_path)
                            <img src="{{ asset('storage/' . $mainBanner->image_path) }}" alt="Hero Banner" class="w-full h-80 object-cover rounded-xl">
                        @else
                            <div class="w-full h-80 rounded-xl bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-900 flex items-center justify-center border border-indigo-500/20">
                                <div class="text-center p-6 space-y-3">
                                    <div class="w-16 h-16 rounded-2xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center mx-auto border border-indigo-500/30">
                                        <i data-lucide="rocket" class="w-8 h-8"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-300">Transformasi Teknologi Digital</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: TENTANG KAMI -->
    <section id="about" class="py-20 bg-slate-900/40 border-y border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-3 max-w-3xl mx-auto">
                <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Tentang Kami</h2>
                <h3 class="text-3xl font-extrabold text-white">Mengenal Lebih Dekat Perusahaan Kami</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
                <!-- About Description Card -->
                <div class="bg-slate-900 rounded-2xl border border-slate-800 p-8 space-y-4 flex flex-col justify-between shadow-lg">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-600/10 text-indigo-400 flex items-center justify-center border border-indigo-500/20">
                            <i data-lucide="building-2" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white">Profil & Sejarah Singkat</h4>
                        <p class="text-sm text-slate-300 leading-relaxed">
                            {{ $profile->about_us ?? 'Kami adalah perusahaan penyedia layanan IT komprehensif yang berfokus pada pengembangan perangkat lunak dan infrastruktur digital berkualitas tinggi.' }}
                        </p>
                        @if($profile->history)
                            <p class="text-xs text-slate-400 leading-relaxed pt-2 border-t border-slate-800">
                                {{ $profile->history }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Vision & Mission Cards -->
                <div class="space-y-6">
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-3 shadow-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-base font-bold text-white">Visi Perusahaan</h4>
                        </div>
                        <p class="text-sm text-slate-300 leading-relaxed">
                            {{ $profile->vision ?? 'Menjadi mitra teknologi terpercaya pilihan utama di Asia Tenggara.' }}
                        </p>
                    </div>

                    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-3 shadow-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-violet-500/10 text-violet-400 flex items-center justify-center border border-violet-500/20">
                                <i data-lucide="target" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-base font-bold text-white">Misi Perusahaan</h4>
                        </div>
                        <p class="text-sm text-slate-300 leading-relaxed">
                            {{ $profile->mission ?? 'Memberikan solusi perangkat lunak dan infrastruktur digital berkualitas tinggi dengan standar internasional.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: LAYANAN / PRODUK -->
    <section id="services" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-3 max-w-3xl mx-auto">
                <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Layanan & Produk</h2>
                <h3 class="text-3xl font-extrabold text-white">Solusi Terbaik yang Kami Tawarkan</h3>
                <p class="text-sm text-slate-400">Pilih dari katalog layanan profesional kami untuk akselerasi pertumbuhan bisnis Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($services as $svc)
                    <div class="bg-slate-900 hover:bg-slate-850 rounded-2xl border border-slate-800 p-6 space-y-4 transition-all hover:-translate-y-1 shadow-lg group">
                        <div class="w-12 h-12 rounded-xl bg-indigo-600/10 text-indigo-400 flex items-center justify-center border border-indigo-500/20 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <i data-lucide="{{ $svc->icon ?? 'layers' }}" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-lg font-bold text-white group-hover:text-indigo-300 transition-colors">{{ $svc->title }}</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">{{ $svc->short_description ?? 'Layanan profesional dengan standar kualitas tinggi.' }}</p>
                        @if($svc->category)
                            <span class="inline-block px-2.5 py-0.5 text-xs font-semibold bg-slate-800 text-slate-300 rounded-full border border-slate-700">
                                {{ $svc->category->name }}
                            </span>
                        @endif
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-slate-500">Belum ada layanan yang ditambahkan.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 4: PORTOFOLIO & PROYEK -->
    <section id="portfolio" class="py-20 bg-slate-900/40 border-y border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-3 max-w-3xl mx-auto">
                <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Portofolio Proyek</h2>
                <h3 class="text-3xl font-extrabold text-white">Hasil Kerja & Karya Terbaik Kami</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($portfolios as $pf)
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg space-y-4 group">
                        <div class="relative h-48 bg-slate-800 overflow-hidden">
                            @if($pf->thumbnail_path)
                                <img src="{{ asset('storage/' . $pf->thumbnail_path) }}" alt="{{ $pf->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-slate-900 to-indigo-950 text-slate-500">
                                    <i data-lucide="folder-kanban" class="w-12 h-12"></i>
                                </div>
                            @endif
                            @if($pf->is_featured)
                                <span class="absolute top-3 right-3 px-2.5 py-0.5 text-[10px] font-extrabold bg-amber-500 text-slate-950 rounded-full shadow">Featured</span>
                            @endif
                        </div>
                        <div class="p-6 pt-0 space-y-2">
                            <span class="text-xs font-semibold text-indigo-400 uppercase tracking-wider block">{{ $pf->category->name ?? 'Proyek Enterprise' }}</span>
                            <h4 class="text-base font-bold text-white group-hover:text-indigo-300 transition-colors line-clamp-1">{{ $pf->title }}</h4>
                            <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed">{{ $pf->short_description ?? 'Proyek kustom dikembangkan dengan standar tinggi.' }}</p>
                            @if($pf->client_name)
                                <p class="text-xs text-slate-500 pt-2 border-t border-slate-800">Klien: <span class="text-slate-300 font-medium">{{ $pf->client_name }}</span></p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-slate-500">Belum ada portofolio proyek.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 5: BERITA & ARTIKEL -->
    <section id="articles" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-3 max-w-3xl mx-auto">
                <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Blog & Berita</h2>
                <h3 class="text-3xl font-extrabold text-white">Wawasan Terbaru Seputar Teknologi</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($articles as $art)
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg space-y-4 group">
                        <div class="relative h-44 bg-slate-800 overflow-hidden">
                            @if($art->featured_image)
                                <img src="{{ asset('storage/' . $art->featured_image) }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-slate-900 to-indigo-950 text-slate-500">
                                    <i data-lucide="newspaper" class="w-10 h-10"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-6 pt-0 space-y-2">
                            <span class="text-xs font-semibold text-slate-400 block">{{ $art->published_at ? $art->published_at->format('d M Y') : $art->created_at->format('d M Y') }}</span>
                            <h4 class="text-base font-bold text-white group-hover:text-indigo-300 transition-colors line-clamp-2">{{ $art->title }}</h4>
                            <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed">{{ $art->getExcerptClean(120) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-slate-500">Belum ada artikel dipublikasikan.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 6: TIM & MANAJEMEN -->
    <section id="team" class="py-20 bg-slate-900/40 border-y border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-3 max-w-3xl mx-auto">
                <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Tim Kami</h2>
                <h3 class="text-3xl font-extrabold text-white">Para Profesional di Balik Layar</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($team as $member)
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 text-center space-y-3 shadow-lg">
                        <div class="w-20 h-20 rounded-full bg-slate-800 border-2 border-indigo-500/30 mx-auto overflow-hidden flex items-center justify-center text-indigo-400 font-bold text-xl">
                            @if($member->photo_path)
                                <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-white">{{ $member->name }}</h4>
                            <p class="text-xs font-medium text-indigo-400">{{ $member->position }}</p>
                        </div>
                        <p class="text-xs text-slate-400 line-clamp-2">{{ $member->bio ?? '' }}</p>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-8 text-slate-500">Belum ada data tim.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 7: TESTIMONI KLIEN -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-3 max-w-3xl mx-auto">
                <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Testimoni</h2>
                <h3 class="text-3xl font-extrabold text-white">Apa Kata Klien Kami?</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($testimonials as $t)
                    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
                        <div class="flex items-center gap-1 text-amber-400">
                            @for($i = 0; $i < $t->rating; $i++)
                                <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                            @endfor
                        </div>
                        <p class="text-sm text-slate-300 italic leading-relaxed">"{{ $t->review_content }}"</p>
                        <div class="flex items-center gap-3 pt-2 border-t border-slate-800">
                            <div class="w-10 h-10 rounded-full bg-indigo-600/20 text-indigo-400 flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($t->client_name, 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-white">{{ $t->client_name }}</h5>
                                <p class="text-xs text-slate-400">{{ $t->client_position }} {{ $t->client_company ? '- ' . $t->client_company : '' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-8 text-slate-500">Belum ada testimoni.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- SECTION 8: KONTAK & FORM PESAN -->
    <section id="contact" class="py-20 bg-slate-900/60 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-3 max-w-3xl mx-auto">
                <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Hubungi Kami</h2>
                <h3 class="text-3xl font-extrabold text-white">Mulai Konsultasi & Kerjasama Sekarang</h3>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Contact Info Column -->
                <div class="lg:col-span-5 bg-slate-900 rounded-2xl border border-slate-800 p-8 space-y-6 shadow-lg">
                    <h4 class="text-xl font-bold text-white">Informasi Kontak</h4>

                    <div class="space-y-4 text-sm text-slate-300">
                        @if($profile->address)
                            <div class="flex items-start gap-3">
                                <i data-lucide="map-pin" class="w-5 h-5 text-indigo-400 shrink-0 mt-0.5"></i>
                                <span>{{ $profile->address }}</span>
                            </div>
                        @endif

                        @if($profile->email)
                            <div class="flex items-center gap-3">
                                <i data-lucide="mail" class="w-5 h-5 text-indigo-400 shrink-0"></i>
                                <a href="mailto:{{ $profile->email }}" class="hover:text-indigo-400 transition-colors">{{ $profile->email }}</a>
                            </div>
                        @endif

                        @if($profile->phone)
                            <div class="flex items-center gap-3">
                                <i data-lucide="phone" class="w-5 h-5 text-indigo-400 shrink-0"></i>
                                <span>{{ $profile->phone }}</span>
                            </div>
                        @endif

                        @if($profile->whatsapp)
                            <div class="flex items-center gap-3">
                                <i data-lucide="message-circle" class="w-5 h-5 text-emerald-400 shrink-0"></i>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profile->whatsapp) }}" target="_blank" class="hover:text-emerald-400 transition-colors">WhatsApp: {{ $profile->whatsapp }}</a>
                            </div>
                        @endif
                    </div>

                    @if($profile->google_maps_embed)
                        <div class="pt-4 border-t border-slate-800 rounded-xl overflow-hidden">
                            {!! $profile->google_maps_embed !!}
                        </div>
                    @endif
                </div>

                <!-- Interactive Contact Form -->
                <div class="lg:col-span-7 bg-slate-900 rounded-2xl border border-slate-800 p-8 space-y-6 shadow-lg">
                    <h4 class="text-xl font-bold text-white">Kirim Pesan Kepada Kami</h4>

                    @if(session('contact_success'))
                        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-semibold flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            <span>{{ session('contact_success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-400">*</span></label>
                                <input type="text" name="name" required class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Nama Anda">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Alamat Email <span class="text-rose-400">*</span></label>
                                <input type="email" name="email" required class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="email@domain.com">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nomor Telepon / WA</label>
                                <input type="text" name="phone" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="08123456789">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Subjek Pesan</label>
                                <input type="text" name="subject" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Tanya Layanan Web">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Pesan Anda <span class="text-rose-400">*</span></label>
                            <textarea name="message" rows="4" required class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Tuliskan kebutuhan bisnis atau pertanyaan Anda..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2">
                            <span>Kirim Pesan Sekarang</span>
                            <i data-lucide="send" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-950 border-t border-slate-800 py-12 text-sm text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <p class="text-white font-bold text-base">{{ $profile->company_name }}</p>
                <p class="text-xs text-slate-500">© {{ date('Y') }} {{ $profile->company_name }}. All rights reserved.</p>
            </div>

            <div class="flex items-center gap-6">
                <a href="#home" class="hover:text-white transition-colors text-xs">Beranda</a>
                <a href="#about" class="hover:text-white transition-colors text-xs">Profil</a>
                <a href="#services" class="hover:text-white transition-colors text-xs">Layanan</a>
                <a href="{{ route('admin.login') }}" class="text-xs font-semibold text-indigo-400 hover:underline">Admin CMS Portal</a>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
