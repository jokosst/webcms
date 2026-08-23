<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-100 flex items-center justify-center p-4 bg-slate-950">

    <!-- Main Container Card -->
    <div class="w-full max-w-md mx-auto text-center bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
        
        <!-- Clean Not Found Icon Illustration -->
        <div class="flex flex-col items-center justify-center space-y-3 pt-2">
            <div class="w-20 h-20 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400 shadow-lg shadow-indigo-500/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Big 404 Text -->
            <div class="text-5xl font-extrabold text-white tracking-widest font-heading">
                404
            </div>
        </div>

        <!-- Title & Description -->
        <div class="space-y-2">
            <h1 class="text-xl font-bold text-white font-heading">
                Halaman Tidak Ditemukan
            </h1>
            <p class="text-xs text-slate-400 leading-relaxed max-w-sm mx-auto">
                Maaf, halaman atau artikel berita yang Anda cari tidak tersedia, telah dihapus, atau link yang Anda tuju salah.
            </p>
        </div>

        <!-- Search Form (Side-by-side flex, zero overlap) -->
        <form action="{{ route('articles.index') }}" method="GET" class="w-full max-w-sm mx-auto pt-1">
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           placeholder="Cari berita lain..." 
                           class="w-full pl-10 pr-3 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white text-xs placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-colors">
                </div>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition-all shadow-md shrink-0">
                    Cari
                </button>
            </div>
        </form>

        <!-- Navigation Action Buttons -->
        <div class="pt-4 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2">
                <i data-lucide="home" class="w-4 h-4"></i>
                <span>Kembali ke Beranda</span>
            </a>

            <a href="{{ route('articles.index') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold border border-slate-700 transition-all flex items-center justify-center gap-2">
                <i data-lucide="newspaper" class="w-4 h-4 text-slate-400"></i>
                <span>Jelajahi Berita</span>
            </a>
        </div>

        <!-- Footer Copyright -->
        <p class="text-[11px] text-slate-500 font-medium pt-1">
            Website Resmi &copy; {{ date('Y') }}. created UPA. TIK UNTAN
        </p>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
