<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentikasi Dua Faktor (2FA) - {{ config('app.name', 'Admin CMS') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md space-y-6" x-data="{ useRecovery: false }">
        <!-- Logo Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-600/20 border border-indigo-500/30 text-indigo-400 shadow-xl shadow-indigo-600/20 mb-2">
                <i data-lucide="shield-check" class="w-7 h-7"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Verifikasi Dua Faktor (2FA)</h1>
            <p class="text-xs text-slate-400" x-show="!useRecovery">Masukkan 6-digit kode OTP dari aplikasi Google Authenticator / Authy Anda.</p>
            <p class="text-xs text-slate-400" x-show="useRecovery">Masukkan salah satu Kode Pemulihan Darurat (Recovery Code) akun Anda.</p>
        </div>

        <!-- Card Form -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.2fa.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- OTP Input Mode -->
                <div x-show="!useRecovery" class="space-y-2">
                    <label for="code" class="block text-xs font-bold text-slate-300 uppercase tracking-wider text-center">Kode OTP 6-Digit</label>
                    <input type="text" 
                           name="code" 
                           id="code"
                           maxlength="6"
                           pattern="[0-9]*"
                           inputmode="numeric"
                           placeholder="000000" 
                           autofocus
                           class="w-full text-center text-2xl font-mono tracking-[0.5em] px-4 py-3 rounded-2xl bg-slate-800 border border-slate-700 text-white focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                </div>

                <!-- Recovery Code Mode -->
                <div x-show="useRecovery" class="space-y-2">
                    <label for="recovery_code" class="block text-xs font-bold text-slate-300 uppercase tracking-wider text-center">Kode Pemulihan Darurat</label>
                    <input type="text" 
                           name="recovery_code" 
                           id="recovery_code"
                           placeholder="xxxxx-xxxxx" 
                           class="w-full text-center text-sm font-mono tracking-wider px-4 py-3 rounded-2xl bg-slate-800 border border-slate-700 text-white focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                </div>

                <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="key" class="w-4 h-4"></i>
                    <span>Verifikasi & Masuk</span>
                </button>
            </form>

            <div class="pt-4 border-t border-slate-800 text-center">
                <button type="button" 
                        @click="useRecovery = !useRecovery" 
                        class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold transition-colors flex items-center justify-center gap-1.5 mx-auto">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                    <span x-show="!useRecovery">Gunakan Kode Pemulihan Darurat</span>
                    <span x-show="useRecovery">Gunakan Kode OTP Aplikasi 2FA</span>
                </button>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('admin.login') }}" class="text-xs text-slate-500 hover:text-slate-400 transition-colors flex items-center justify-center gap-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke halaman login</span>
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
