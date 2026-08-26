<!DOCTYPE html>
<html lang="id" class="scroll-smooth" :class="darkMode ? 'dark' : ''" x-data="{ darkMode: localStorage.getItem('theme') ? localStorage.getItem('theme') === 'dark' : false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', ($profile->company_name ?? 'Unit TIK') . ' - Pusat Pengelolaan Sistem Informasi, Jaringan & Server')</title>
    <meta name="description" content="@yield('meta_description', 'Unit Teknologi Informasi dan Komunikasi (TIK) - Pengelolaan Sistem Aplikasi Web, Infrastruktur Jaringan, Ruang Lab Komputer, dan Server Multi-Cloud.')">

    <!-- Favicon Icon for Browser Tab -->
    @if(!empty($profile->favicon_path))
        <link rel="icon" href="{{ asset('storage/' . $profile->favicon_path) }}">
        <link rel="shortcut icon" href="{{ asset('storage/' . $profile->favicon_path) }}">
    @elseif(!empty($profile->logo_path))
        <link rel="icon" href="{{ asset('storage/' . $profile->logo_path) }}">
        <link rel="shortcut icon" href="{{ asset('storage/' . $profile->logo_path) }}">
    @endif

    <!-- Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=Red+Hat+Display:wght@700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        tech: {
                            50: '#ecfeff',
                            100: '#cffafe',
                            400: '#22d3ee',
                            500: '#06b6d4',
                            600: '#0891b2',
                            900: '#164e63',
                            950: '#083344',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .cyber-grid-dark {
            background-image: linear-gradient(to right, rgba(6, 182, 212, 0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(6, 182, 212, 0.05) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .cyber-grid-light {
            background-image: linear-gradient(to right, rgba(6, 182, 212, 0.08) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(6, 182, 212, 0.08) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .glow-cyan {
            box-shadow: 0 0 35px -5px rgba(6, 182, 212, 0.3);
        }
        .glow-indigo {
            box-shadow: 0 0 35px -5px rgba(99, 102, 241, 0.3);
        }
        .glass-card-dark {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card-dark:hover {
            border-color: rgba(6, 182, 212, 0.4);
            box-shadow: 0 10px 30px -10px rgba(6, 182, 212, 0.2);
        }
        .glass-card-light {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05);
        }
        .glass-card-light:hover {
            border-color: rgba(6, 182, 212, 0.5);
            box-shadow: 0 15px 35px -10px rgba(6, 182, 212, 0.15);
        }
        .prose p { margin-bottom: 1.25rem; line-height: 1.8; }
        .prose h2 { font-size: 1.5rem; font-weight: 800; margin-top: 2rem; margin-bottom: 1rem; color: #0f172a; }
        .prose h3 { font-size: 1.25rem; font-weight: 700; margin-top: 1.5rem; margin-bottom: 0.75rem; color: #1e293b; }
        .prose ul, .prose ol { margin-left: 1.5rem; margin-bottom: 1.25rem; }
        .prose ul { list-style-type: disc; }
        .prose ol { list-style-type: decimal; }

        /* Google Translate Seamless Integration Styles */
        .goog-te-banner-frame.skiptranslate,
        .goog-te-banner-frame,
        iframe.goog-te-banner-frame {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
        }
        body {
            top: 0px !important;
            position: static !important;
        }
        .goog-tooltip, .goog-tooltip:hover {
            display: none !important;
        }
        .goog-text-highlight {
            background-color: transparent !important;
            box-shadow: none !important;
        }
        #google_translate_element, .skiptranslate, .goog-te-spinner-pos {
            display: none !important;
        }
        .VIpgJd-Z44fyf-O2wb-gfv28a {
            display: none !important;
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased selection:bg-cyan-500 selection:text-slate-950 min-h-screen flex flex-col justify-between transition-colors duration-300"
      :class="darkMode ? 'bg-slate-950 text-slate-100 cyber-grid-dark' : 'bg-slate-50 text-slate-800 cyber-grid-light'"
      x-data="{ mobileMenuOpen: false }">

    <!-- Hidden Google Translate Element -->
    <div id="google_translate_element" style="display:none !important;"></div>

    <!-- HEADER LAYOUT -->
    @include('layouts.header')

    <!-- MAIN CONTENT -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER LAYOUT -->
    @include('layouts.footer')

    <!-- SMART IT HELPDESK CHATBOT WIDGET -->
    @if(($settings['chatbot_enabled'] ?? '1') === '1')
        @include('layouts.chatbot')
    @endif

    @stack('scripts')

    <!-- Google Translate Auto Translation Script -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'id',
                includedLanguages: 'id,en',
                autoDisplay: false
            }, 'google_translate_element');

            // Auto-trigger translation if active locale is 'en'
            var activeLocale = "{{ app()->getLocale() }}";
            if (activeLocale === 'en') {
                var attempts = 0;
                var checkCombo = setInterval(function() {
                    attempts++;
                    var select = document.querySelector('.goog-te-combo');
                    if (select) {
                        select.value = 'en';
                        select.dispatchEvent(new Event('change'));
                        clearInterval(checkCombo);
                    }
                    if (attempts > 25) {
                        clearInterval(checkCombo);
                    }
                }, 200);
            }
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>
