<!DOCTYPE html>
<html lang="id" class="min-h-screen bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - CMS Web Profil</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/admin-favicon.svg') }}">
    <link rel="shortcut icon" type="image/svg+xml" href="{{ asset('images/admin-favicon.svg') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js & FontAwesome/Lucide Icons -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Quill Rich Text Editor -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen font-sans antialiased bg-slate-950 text-slate-100 selection:bg-indigo-500 selection:text-white" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col md:flex-row bg-slate-950">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between shrink-0">
            <div>
                <!-- Brand Header -->
                <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800 bg-slate-900/50 backdrop-blur">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 font-bold text-lg text-white">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        </div>
                        <span>Admin <span class="text-indigo-400">CMS</span></span>
                    </a>
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-slate-400 hover:text-white">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1 text-sm font-medium" :class="{ 'block': sidebarOpen, 'hidden md:block': !sidebarOpen }">
                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Utama</p>
                    
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <i data-lucide="layout-grid" class="w-4 h-4"></i>
                        <span>Dashboard</span>
                    </a>

                    @if(Auth::check() && in_array(Auth::user()->role, ['superadmin', 'editor']))
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Artikel & Komentar</p>

                        <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.articles.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i data-lucide="newspaper" class="w-4 h-4 text-cyan-400"></i>
                            <span>Berita / Artikel</span>
                        </a>

                        <a href="{{ route('admin.comments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.comments.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i data-lucide="message-square" class="w-4 h-4 text-cyan-400"></i>
                            <span>Komentar Artikel</span>
                        </a>
                    @endif

                    @if(Auth::check() && Auth::user()->role === 'superadmin')
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Konten Perusahaan</p>

                        <a href="{{ route('admin.company-profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.company-profile.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                            <span>Profil</span>
                        </a>

                        <a href="{{ route('admin.hero-banners.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.hero-banners.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i data-lucide="image" class="w-4 h-4"></i>
                            <span>Hero Banners</span>
                        </a>

                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i data-lucide="folder" class="w-4 h-4"></i>
                            <span>Kategori</span>
                        </a>

                        <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.services.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                            <span>Layanan / Produk</span>
                        </a>

                        <a href="{{ route('admin.portfolios.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.portfolios.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                            <span>Portofolio & Proyek</span>
                        </a>
                        <a href="{{ route('admin.team-members.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.team-members.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            <span>Tim & Manajemen</span>
                        </a>

                        <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.testimonials.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i data-lucide="message-square" class="w-4 h-4"></i>
                            <span>Testimoni</span>
                        </a>
                    @endif

                    @if(Auth::check() && in_array(Auth::user()->role, ['superadmin', 'helpdesk']))
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Interaksi</p>

                        <a href="{{ route('admin.inbox.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.inbox.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                                <span>Inbox Pesan</span>
                            </div>
                            @php $unreadCount = \App\Models\InboxLead::where('status', 'unread')->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="px-2 py-0.5 text-xs font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-full">{{ $unreadCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.faqs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.faqs.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i data-lucide="help-circle" class="w-4 h-4"></i>
                            <span>FAQ / Tanya Jawab</span>
                        </a>
                        <a href="{{ route('admin.chatbot-logs.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.chatbot-logs.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="headphones" class="w-4 h-4 text-cyan-400"></i>
                                <span>Riwayat Chatbot</span>
                            </div>
                            @php $unansweredLogCount = \App\Models\ChatbotLog::where('is_answered', false)->count(); @endphp
                            @if($unansweredLogCount > 0)
                                <span class="px-2 py-0.5 text-[10px] font-extrabold bg-amber-500/20 text-amber-300 border border-amber-500/30 rounded-full" title="{{ $unansweredLogCount }} pertanyaan belum terjawab">{{ $unansweredLogCount }}</span>
                            @endif
                        </a>
                    @endif

                    @if(Auth::check() && Auth::user()->role === 'superadmin')
                        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Sistem</p>

                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i data-lucide="shield-check" class="w-4 h-4 text-red-400"></i>
                            <span>Manajemen Pengguna</span>
                        </a>

                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            <span>Pengaturan SEO</span>
                        </a>
                    @endif
                </nav>
            </div>

            <!-- User Info Footer -->
            <div class="p-4 border-t border-slate-800 bg-slate-900/50">
                <div class="flex items-center justify-between gap-2">
                    <a href="{{ route('admin.profile.edit') }}" title="Click untuk edit profil" class="flex items-center gap-3 overflow-hidden group hover:opacity-80 transition-opacity">
                        <div class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-indigo-400 shrink-0 group-hover:border-indigo-500">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <p class="text-sm font-semibold text-white truncate group-hover:text-indigo-400 transition-colors">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-slate-400 capitalize truncate">{{ Auth::user()->role ?? 'Superadmin' }}</p>
                        </div>
                    </a>

                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.profile.edit') }}" title="Edit Profil Saya" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-lg transition-colors">
                            <i data-lucide="user-cog" class="w-4 h-4"></i>
                        </a>

                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 bg-slate-950">
            <!-- Topbar Header -->
            <header class="h-16 bg-slate-900/60 backdrop-blur border-b border-slate-800 px-6 flex items-center justify-between sticky top-0 z-10">
                <h1 class="text-lg font-bold text-white flex items-center gap-2">
                    @yield('header_title', 'Dashboard Overview')
                </h1>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile.edit') }}" class="px-3 py-1.5 text-xs font-medium text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg flex items-center gap-1.5 transition-colors">
                        <i data-lucide="user" class="w-3.5 h-3.5 text-indigo-400"></i>
                        <span>Profil Saya</span>
                    </a>
                    <a href="{{ url('/api/v1/company-profile') }}" target="_blank" class="px-3 py-1.5 text-xs font-medium text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg flex items-center gap-1.5 transition-colors">
                        <i data-lucide="code" class="w-3.5 h-3.5"></i>
                        <span>Cek REST API</span>
                    </a>
                </div>
            </header>

            <!-- Page Body -->
            <div class="p-6 flex-1 space-y-6">
                <!-- Flash Alerts -->
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400 shrink-0"></i>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-400 shrink-0"></i>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('info'))
                    <div class="p-4 rounded-xl bg-sky-500/10 border border-sky-500/30 text-sky-400 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i data-lucide="info" class="w-5 h-5 text-sky-400 shrink-0"></i>
                            <span class="text-sm font-medium">{{ session('info') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
