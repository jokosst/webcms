<!-- TOP ANNOUNCEMENT & SOCIAL MEDIA HEADER BAR -->
@php
    $social = $profile->social_media ?? [];
    $fb = $social['facebook'] ?? $social['facebook_url'] ?? $settings['social_facebook'] ?? $settings['facebook'] ?? null;
    $ig = $social['instagram'] ?? $social['instagram_url'] ?? $settings['social_instagram'] ?? $settings['instagram'] ?? null;
    $tw = $social['twitter'] ?? $social['x'] ?? $social['twitter_url'] ?? $settings['social_twitter'] ?? $settings['twitter'] ?? null;
    $yt = $social['youtube'] ?? $social['youtube_url'] ?? $settings['social_youtube'] ?? $settings['youtube'] ?? null;
    $isChatbotActiveForHeader = ($settings['chatbot_enabled'] ?? '1') === '1'
        && ($settings['header_helpdesk_action'] ?? 'chatbot') === 'chatbot';
@endphp

<div class="transition-colors duration-300 text-xs py-2 px-4 sm:px-8 flex items-center justify-between gap-4 z-50 border-b"
     :class="darkMode ? 'bg-slate-900/90 border-slate-800/80 text-slate-300' : 'bg-slate-900 border-slate-800 text-slate-200'">
    
    <!-- Left Side: Dynamic Announcement Ticker from Database -->
    <div class="flex items-center gap-2.5 overflow-hidden">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-cyan-500/20 text-cyan-300 font-extrabold border border-cyan-500/30 text-[10px] uppercase tracking-wider shrink-0">
            <i data-lucide="megaphone" class="w-3 h-3 text-cyan-400 animate-bounce"></i>
            PENGUMUMAN
        </span>
        <span class="text-slate-200 font-semibold truncate text-[11px]">
            {{ $profile->announcement_ticker ?? $settings['announcement'] ?? 'Selamat Datang di Portal Resmi UPA TIK UNTAN - Pusat Pengelolaan Sistem Informasi, Jaringan & Cloud' }}
        </span>
    </div>

    <!-- Right Side: Language Switcher & Social Media Links -->
    <div class="flex items-center gap-3.5 text-slate-300 shrink-0">
        
        <!-- LANGUAGE SWITCHER (ID / EN) IN TOP BAR -->
        <div class="flex items-center rounded-lg p-0.5 border text-[11px] font-bold transition-all bg-slate-800/90 border-slate-700">
            <a href="{{ route('lang.switch', 'id') }}" 
               onclick="if(document.querySelector('.goog-te-combo')){ var s=document.querySelector('.goog-te-combo'); s.value='id'; s.dispatchEvent(new Event('change')); }"
               class="px-2 py-0.5 rounded transition-all flex items-center gap-1 {{ app()->getLocale() == 'id' ? 'bg-cyan-500 text-slate-950 font-black shadow-sm' : 'text-slate-300 hover:text-white' }}"
               title="Bahasa Indonesia">
                <span>🇮🇩 ID</span>
            </a>
            <a href="{{ route('lang.switch', 'en') }}" 
               onclick="if(document.querySelector('.goog-te-combo')){ var s=document.querySelector('.goog-te-combo'); s.value='en'; s.dispatchEvent(new Event('change')); }"
               class="px-2 py-0.5 rounded transition-all flex items-center gap-1 {{ app()->getLocale() == 'en' ? 'bg-cyan-500 text-slate-950 font-black shadow-sm' : 'text-slate-300 hover:text-white' }}"
               title="English">
                <span>🇬🇧 EN</span>
            </a>
        </div>

        <span class="h-3.5 w-px bg-slate-700 hidden sm:inline-block"></span>

        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider hidden sm:inline">Ikuti Kami:</span>
        
        <!-- Facebook -->
        <a href="{{ $fb ?? 'https://facebook.com' }}" target="_blank" rel="noopener noreferrer" class="hover:text-cyan-400 transition-colors p-1" title="Facebook">
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </a>

        <!-- Instagram -->
        <a href="{{ $ig ?? 'https://instagram.com' }}" target="_blank" rel="noopener noreferrer" class="hover:text-cyan-400 transition-colors p-1" title="Instagram">
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
        </a>

        <!-- X (Twitter) -->
        <a href="{{ $tw ?? 'https://x.com' }}" target="_blank" rel="noopener noreferrer" class="hover:text-cyan-400 transition-colors p-1" title="X (Twitter)">
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>

        <!-- YouTube -->
        <a href="{{ $yt ?? 'https://youtube.com' }}" target="_blank" rel="noopener noreferrer" class="hover:text-cyan-400 transition-colors p-1" title="YouTube">
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
        </a>
    </div>
</div>

<!-- MAIN HEADER NAVBAR WITH THEME TOGGLER -->
<header class="sticky top-0 z-40 backdrop-blur-xl border-b transition-colors duration-300"
        :class="darkMode ? 'bg-slate-950/80 border-slate-800/80' : 'bg-white/90 border-slate-200 shadow-sm'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            <!-- Logo & Brand Title -->
            <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-cyan-500 to-indigo-600 p-0.5 shadow-lg shadow-cyan-500/20 group-hover:scale-105 transition-transform">
                    <div class="w-full h-full rounded-[14px] flex items-center justify-center transition-colors"
                         :class="darkMode ? 'bg-slate-950' : 'bg-white'">
                        @if($profile->logo_path)
                            <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $profile->company_name }}" class="w-7 h-7 object-contain">
                        @else
                            <i data-lucide="cpu" class="w-6 h-6 text-cyan-500"></i>
                        @endif
                    </div>
                </div>
                <div>
                    <span class="text-lg font-black tracking-tight font-heading group-hover:text-cyan-500 transition-colors block leading-tight"
                          :class="darkMode ? 'text-white' : 'text-slate-900'">
                        {{ $profile->company_name ?? 'UNIT TIK' }}
                    </span>
                    <span class="text-[11px] font-bold text-cyan-500 uppercase tracking-wider block">
                        {{ $profile->tagline ?? 'Pusat Teknologi & Infrastruktur' }}
                    </span>
                </div>
            </a>

            <!-- Desktop Navigation Menu (Concise & Spacious) -->
            <nav class="hidden lg:flex items-center gap-7 text-xs font-bold transition-colors"
                 :class="darkMode ? 'text-slate-300' : 'text-slate-700'">
                <a href="{{ route('home') }}#beranda" class="hover:text-cyan-500 transition-colors py-2">{{ __('Beranda') }}</a>
                <a href="{{ route('home') }}#visi-misi-sambutan" class="hover:text-cyan-500 transition-colors py-2">{{ __('Profil') }}</a>
                <a href="{{ route('home') }}#layanan" class="hover:text-cyan-500 transition-colors py-2">{{ __('Layanan') }}</a>
                <a href="{{ route('lab-schedule.index') }}" class="hover:text-cyan-500 transition-colors py-2 flex items-center gap-1.5 {{ request()->routeIs('lab-schedule.*') ? 'text-cyan-400 font-extrabold' : '' }}">
                    <span>{{ __('Jadwal Lab') }}</span>
                </a>
                <a href="{{ route('articles.index') }}" class="hover:text-cyan-500 transition-colors py-2">{{ __('Berita') }}</a>
                <a href="{{ route('home') }}#portofolio" class="hover:text-cyan-500 transition-colors py-2">{{ __('Portofolio') }}</a>
                <a href="{{ route('home') }}#tim-ahli" class="hover:text-cyan-500 transition-colors py-2">{{ __('Tim') }}</a>
                <a href="{{ route('home') }}#faq" class="hover:text-cyan-500 transition-colors py-2 whitespace-nowrap">{{ __('FAQ & Tiket') }}</a>
            </nav>

            <!-- Desktop Theme Toggle & Helpdesk CTA (Visible only on LG screens alongside desktop nav) -->
            <div class="hidden lg:flex items-center gap-3">
                
                <!-- THEME TOGGLE BUTTON (ICON ONLY) -->
                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')" 
                        class="p-2.5 rounded-xl border transition-all flex items-center justify-center text-xs font-extrabold focus:outline-none"
                        :class="darkMode ? 'bg-slate-900 border-slate-800 text-amber-400 hover:bg-slate-800' : 'bg-slate-100 border-slate-200 text-slate-700 hover:bg-slate-200'"
                        title="Beralih Mode Tampilan (Dark / Light)">
                    <!-- Sun Icon (Active in Dark Mode -> Switch to Light) -->
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                        <circle cx="12" cy="12" r="4" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41" />
                    </svg>

                    <!-- Moon Icon (Active in Light Mode -> Switch to Dark) -->
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 12.79A9 9 0 1111.21 3 7 7 0 0021.75 12.79z" />
                    </svg>
                </button>

                <!-- HELPDESK CTA BUTTON -->
                @if($isChatbotActiveForHeader)
                    <button type="button" 
                            onclick="window.openItChatbot()"
                            @click="window.openItChatbot()" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-black bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 shadow-lg shadow-cyan-500/25 transition-all hover:scale-105 uppercase tracking-wider cursor-pointer">
                        <i data-lucide="headphones" class="w-4 h-4 text-slate-950"></i>
                        <span>IT Helpdesk</span>
                    </button>
                @else
                    <a href="{{ route('home') }}#helpdesk" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-black bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-slate-950 shadow-lg shadow-cyan-500/25 transition-all hover:scale-105 uppercase tracking-wider">
                        <i data-lucide="headphones" class="w-4 h-4 text-slate-950"></i>
                        <span>IT Helpdesk</span>
                    </a>
                @endif
            </div>

            <!-- Mobile & Tablet Controls (Visible only below LG screen width < 1024px) -->
            <div class="flex items-center gap-2.5 lg:hidden">
                <!-- Theme Toggle Button (Mobile/Tablet) -->
                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')" 
                        class="p-2.5 rounded-xl border transition-all flex items-center justify-center"
                        :class="darkMode ? 'bg-slate-900 border-slate-800 text-amber-400' : 'bg-slate-100 border-slate-200 text-slate-700'"
                        title="Beralih Mode Tampilan">
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                        <circle cx="12" cy="12" r="4" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41" />
                    </svg>
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 12.79A9 9 0 1111.21 3 7 7 0 0021.75 12.79z" />
                    </svg>
                </button>

                <!-- Hamburger Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="p-2.5 rounded-xl border focus:outline-none transition-colors"
                        :class="darkMode ? 'text-slate-400 hover:text-white hover:bg-slate-900 border-slate-800' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 border-slate-200'" 
                        aria-label="Toggle menu">
                    <i x-show="!mobileMenuOpen" data-lucide="menu" class="w-6 h-6"></i>
                    <i x-show="mobileMenuOpen" data-lucide="x" class="w-6 h-6" style="display: none;"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="mobileMenuOpen" x-transition class="lg:hidden border-t px-4 pt-3 pb-6 space-y-2 shadow-2xl transition-colors duration-300"
         :class="darkMode ? 'border-slate-800 bg-slate-950/95 backdrop-blur-xl' : 'border-slate-200 bg-white/95 backdrop-blur-xl'"
         style="display: none;">
        <a href="{{ route('home') }}#beranda" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-xl text-sm font-bold" :class="darkMode ? 'text-slate-200 hover:bg-slate-900 hover:text-cyan-400' : 'text-slate-700 hover:bg-slate-100 hover:text-cyan-600'">{{ __('Beranda') }}</a>
        <a href="{{ route('home') }}#visi-misi-sambutan" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-xl text-sm font-bold" :class="darkMode ? 'text-slate-200 hover:bg-slate-900 hover:text-cyan-400' : 'text-slate-700 hover:bg-slate-100 hover:text-cyan-600'">{{ __('Profil') }}</a>
        <a href="{{ route('home') }}#layanan" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-xl text-sm font-bold" :class="darkMode ? 'text-slate-200 hover:bg-slate-900 hover:text-cyan-400' : 'text-slate-700 hover:bg-slate-100 hover:text-cyan-600'">{{ __('Layanan') }}</a>
        <a href="{{ route('lab-schedule.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-xl text-sm font-bold" :class="darkMode ? 'text-slate-200 hover:bg-slate-900 hover:text-cyan-400' : 'text-slate-700 hover:bg-slate-100 hover:text-cyan-600'">
            <span>{{ __('Jadwal Peminjaman Lab') }}</span>
        </a>
        <a href="{{ route('articles.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-xl text-sm font-bold" :class="darkMode ? 'text-slate-200 hover:bg-slate-900 hover:text-cyan-400' : 'text-slate-700 hover:bg-slate-100 hover:text-cyan-600'">{{ __('Berita') }}</a>
        <a href="{{ route('home') }}#portofolio" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-xl text-sm font-bold" :class="darkMode ? 'text-slate-200 hover:bg-slate-900 hover:text-cyan-400' : 'text-slate-700 hover:bg-slate-100 hover:text-cyan-600'">{{ __('Portofolio') }}</a>
        <a href="{{ route('home') }}#tim-ahli" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-xl text-sm font-bold" :class="darkMode ? 'text-slate-200 hover:bg-slate-900 hover:text-cyan-400' : 'text-slate-700 hover:bg-slate-100 hover:text-cyan-600'">{{ __('Tim') }}</a>
        <a href="{{ route('home') }}#faq" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-xl text-sm font-bold" :class="darkMode ? 'text-slate-200 hover:bg-slate-900 hover:text-cyan-400' : 'text-slate-700 hover:bg-slate-100 hover:text-cyan-600'">{{ __('FAQ & Tiket') }}</a>
        
        @if($isChatbotActiveForHeader)
            <button type="button" 
                    onclick="window.openItChatbot()"
                    @click="mobileMenuOpen = false; window.openItChatbot()" 
                    class="w-full px-3 py-2.5 rounded-xl text-sm font-extrabold bg-cyan-500 text-slate-950 text-center mt-2 flex items-center justify-center gap-2 shadow-lg shadow-cyan-500/20 cursor-pointer">
                <i data-lucide="headphones" class="w-4 h-4"></i>
                <span>Buka Chatbot IT Helpdesk</span>
            </button>
        @else
            <a href="{{ route('home') }}#helpdesk" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-xl text-sm font-extrabold bg-cyan-500 text-slate-950 text-center mt-2">IT Helpdesk Support</a>
        @endif
    </div>
</header>
