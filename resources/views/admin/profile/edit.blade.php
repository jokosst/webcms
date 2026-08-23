@extends('layouts.admin')

@section('title', 'Edit Profil Saya')
@section('header_title', 'Pengaturan Akun Saya')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-white">Profil Pengguna</h2>
            <p class="text-xs text-slate-400">Kelola nama, email, kata sandi, dan keamanan dua faktor akun Anda.</p>
        </div>
    </div>

    <style>
        input::-ms-reveal, input::-ms-clear { display: none; }
    </style>

    <!-- FORM 1: Informasi Profil & Password -->
    <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Informasi Akun -->
        <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
            <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
                <i data-lucide="user" class="w-5 h-5 text-indigo-400"></i>
                <span>Informasi Akun</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none @error('name') border-rose-500 @enderror"
                           placeholder="Nama Pengguna">
                    @error('name')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Alamat Email (Username Login) <span class="text-rose-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none @error('email') border-rose-500 @enderror"
                           placeholder="nama@email.com">
                    @error('email')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Role Akses Akun</label>
                <input type="text" value="{{ strtoupper($user->role) }}" disabled
                       class="w-full px-4 py-2.5 bg-slate-800/40 border border-slate-800 rounded-xl text-slate-400 text-sm font-bold uppercase cursor-not-allowed">
                <span class="text-[11px] text-slate-500 mt-1 block">Role hak akses diatur oleh Superadmin.</span>
            </div>
        </div>

        <!-- Ubah Password -->
        <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg"
             x-data="{ 
                 showCurrent: false, 
                 showPass: false, 
                 showConfirm: false,
                 newPassword: '',
                 confirmPassword: '',
                 copied: false,
                 generateStrongPassword() {
                     const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=';
                     let pwd = '';
                     const array = new Uint32Array(16);
                     window.crypto.getRandomValues(array);
                     for (let i = 0; i < 16; i++) {
                         pwd += chars[array[i] % chars.length];
                     }
                     this.newPassword = pwd;
                     this.confirmPassword = pwd;
                     this.showPass = true;
                     this.showConfirm = true;
                     this.copied = true;
                     setTimeout(() => { this.copied = false; }, 3000);
                 }
             }">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-800 pb-3 gap-3">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i data-lucide="key-round" class="w-5 h-5 text-indigo-400"></i>
                    <span>Keamanan & Password</span>
                </h3>
                
                <button type="button" @click="generateStrongPassword()" class="px-3 py-1.5 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-400 border border-indigo-500/30 text-xs font-bold transition-all flex items-center gap-1.5 self-start sm:self-auto">
                    <i data-lucide="key" class="w-3.5 h-3.5"></i>
                    <span>⚡ Generate Strong Password</span>
                </button>
            </div>

            <p class="text-xs text-slate-400">Kosongkan kolom password jika Anda tidak ingin mengubah password akun Anda.</p>

            <!-- Notifikasi Password Berhasil Digenerate -->
            <div x-show="copied" x-transition class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span>Password Acak Kuat (16 Karakter) berhasil dibuat dan diisikan otomatis!</span>
            </div>

            <div class="space-y-4 pt-2">
                <!-- Current Password -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password Saat Ini</label>
                    <div class="relative">
                        <input :type="showCurrent ? 'text' : 'password'" 
                               id="current_password"
                               name="current_password"
                               autocomplete="current-password"
                               class="w-full pl-4 pr-11 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none @error('current_password') border-rose-500 @enderror"
                               placeholder="Masukkan password saat ini untuk verifikasi">
                        
                        <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-emerald-400 transition-colors z-10 cursor-pointer" title="Tampilkan / Sembunyikan Password" style="margin-right: 5px;">
                            <svg x-show="!showCurrent" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showCurrent" class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.046 10.046 0 013.122-.463c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-9.771-9.771a3 3 0 114.243 4.243m-4.243-4.243L3 3m18 18l-4.243-4.243" />
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- New Password -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password Baru</label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" 
                                   id="password"
                                   name="password"
                                   x-model="newPassword"
                                   autocomplete="new-password"
                                   class="w-full pl-4 pr-11 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none @error('password') border-rose-500 @enderror"
                                   placeholder="Minimal 6 karakter">
                            
                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-emerald-400 transition-colors z-10 cursor-pointer" title="Tampilkan / Sembunyikan Password" style="margin-right: 5px;">
                                <svg x-show="!showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPass" class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.046 10.046 0 013.122-.463c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-9.771-9.771a3 3 0 114.243 4.243m-4.243-4.243L3 3m18 18l-4.243-4.243" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" 
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   x-model="confirmPassword"
                                   autocomplete="new-password"
                                   class="w-full pl-4 pr-11 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none"
                                   placeholder="Ulangi password baru">
                            
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-emerald-400 transition-colors z-10 cursor-pointer" title="Tampilkan / Sembunyikan Password" style="margin-right: 5px;">
                                <svg x-show="!showConfirm" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirm" class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.046 10.046 0 013.122-.463c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-9.771-9.771a3 3 0 114.243 4.243m-4.243-4.243L3 3m18 18l-4.243-4.243" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Simpan Perubahan Profil</span>
            </button>
        </div>
    </form>

    <!-- BOX TERSENDIRI: Autentikasi Dua Faktor (2FA) -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-5 shadow-lg" x-data="{ showDisableModal: false, showRecoveryModal: {{ session('show_recovery_codes') ? 'true' : 'false' }} }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-800 pb-3 gap-3">
            <div class="flex items-center gap-2">
                <i data-lucide="shield-check" class="w-5 h-5 text-indigo-400"></i>
                <h3 class="text-base font-bold text-white">Autentikasi Dua Faktor (2FA)</h3>
            </div>

            @if($user->hasTwoFactorEnabled())
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 text-xs font-bold self-start sm:self-auto">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>2FA Aktif</span>
                </span>
            @elseif($qrCodeSvg)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/30 text-xs font-bold self-start sm:self-auto">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    <span>Menunggu Verifikasi</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800 text-slate-400 border border-slate-700 text-xs font-bold self-start sm:self-auto">
                    <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                    <span>Non-aktif</span>
                </span>
            @endif
        </div>

        @if($user->hasTwoFactorEnabled())
            <!-- State 1: 2FA is Active -->
            <p class="text-xs text-slate-300 leading-relaxed">
                Autentikasi Dua Faktor (2FA) saat ini <strong class="text-emerald-400">Aktif</strong> pada akun Anda. Setiap kali melakukan login, Anda akan diminta memasukkan kode 6-digit OTP dari aplikasi Google Authenticator / Authy.
            </p>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <button type="button" @click="showRecoveryModal = true" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i data-lucide="key" class="w-4 h-4 text-indigo-400"></i>
                    <span>Lihat / Buat Ulang Kode Pemulihan Darurat</span>
                </button>

                <button type="button" @click="showDisableModal = true" class="px-4 py-2 bg-rose-600/20 hover:bg-rose-600/30 text-rose-400 border border-rose-500/30 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i data-lucide="shield-off" class="w-4 h-4"></i>
                    <span>Nonaktifkan 2FA</span>
                </button>
            </div>
        @elseif($qrCodeSvg)
            <!-- State 2: 2FA QR Code & Secret Key Setup Screen -->
            <div class="p-5 rounded-2xl bg-indigo-950/40 border border-indigo-500/30 space-y-5">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-indigo-600/20 text-indigo-400 rounded-xl shrink-0 mt-0.5">
                        <i data-lucide="qr-code" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Langkah 1 dari 2: Scan QR Code</h4>
                        <p class="text-xs text-slate-300 mt-0.5">Buka aplikasi Google Authenticator / Authy di Smartphone Anda, pilih "+ Tambah Akun", lalu scan QR Code di bawah ini.</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-6 bg-slate-950/80 p-5 rounded-2xl border border-slate-800">
                    <div class="bg-white p-3 rounded-2xl shadow-xl border border-slate-700 shrink-0">
                        {!! $qrCodeSvg !!}
                    </div>
                    <div class="space-y-3 text-center md:text-left">
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Atau Masukkan Kunci Rahasia Secara Manual:</p>
                            <div class="mt-1.5 p-2.5 rounded-xl bg-slate-900 border border-slate-800 font-mono text-sm text-indigo-400 font-bold tracking-widest select-all inline-block">
                                {{ $secretKey }}
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-500">Tipe Akun: TOTP (Time-based One-Time Password) | 30 Detik</p>
                    </div>
                </div>

                <div class="border-t border-indigo-500/20 pt-4 space-y-3">
                    <h4 class="text-sm font-bold text-white flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                        <span>Langkah 2 dari 2: Verifikasi & Aktifkan</span>
                    </h4>
                    <p class="text-xs text-slate-300">Masukkan 6-digit kode OTP yang muncul di aplikasi authenticator Anda untuk mengonfirmasi aktivasi.</p>

                    <form action="{{ route('admin.profile.two-factor.confirm') }}" method="POST" class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        @csrf
                        <input type="text" name="code" maxlength="6" pattern="[0-9]*" inputmode="numeric" required placeholder="000000"
                               class="w-full sm:w-48 px-4 py-2.5 bg-slate-900 border border-indigo-500/50 rounded-xl text-white text-center text-lg font-mono tracking-[0.3em] focus:border-indigo-400 focus:outline-none">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-indigo-600/30 flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                            <span>Verifikasi & Aktifkan 2FA</span>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- State 3: 2FA Disabled -->
            <p class="text-xs text-slate-400 leading-relaxed">
                Tingkatkan keamanan akun Anda dengan Autentikasi Dua Faktor (2FA). Setiap kali login, Anda akan diminta memasukkan kode unik 6-digit dari aplikasi Google Authenticator / Authy.
            </p>

            <form action="{{ route('admin.profile.two-factor.enable') }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                    <span>Aktifkan 2FA Sekarang</span>
                </button>
            </form>
        @endif

        <!-- Modal Nonaktifkan 2FA -->
        <div x-show="showDisableModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" style="display: none;">
            <div @click.away="showDisableModal = false" class="bg-slate-900 border border-slate-800 rounded-3xl p-6 max-w-md w-full space-y-4 shadow-2xl">
                <div class="flex items-center gap-3 text-rose-400">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                    <h3 class="text-lg font-bold text-white">Nonaktifkan 2FA?</h3>
                </div>
                <p class="text-xs text-slate-300">Masukkan password Anda saat ini untuk mengonfirmasi penonaktifan Autentikasi Dua Faktor (2FA).</p>

                <form action="{{ route('admin.profile.two-factor.disable') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('DELETE')

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" required
                               class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-sm focus:border-rose-500 focus:outline-none"
                               placeholder="Password Anda">
                        @error('current_password_2fa')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showDisableModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold">
                            Nonaktifkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Kode Pemulihan Darurat -->
        @if($user->hasTwoFactorEnabled() && !empty($user->two_factor_recovery_codes))
            <div x-show="showRecoveryModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" style="display: none;">
                <div @click.away="showRecoveryModal = false" class="bg-slate-900 border border-slate-800 rounded-3xl p-6 max-w-lg w-full space-y-4 shadow-2xl">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i data-lucide="key" class="w-5 h-5 text-indigo-400"></i>
                            <span>Kode Pemulihan Darurat</span>
                        </h3>
                        <button type="button" @click="showRecoveryModal = false" class="text-slate-400 hover:text-white">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <p class="text-xs text-slate-300 leading-relaxed">
                        Simpan kode pemulihan berikut di tempat aman (seperti Password Manager). Setiap kode hanya bisa digunakan **satu kali** jika HP Anda hilang atau Anda tidak dapat membuka aplikasi 2FA.
                    </p>

                    <div class="grid grid-cols-2 gap-2 bg-slate-950 p-4 rounded-2xl border border-slate-800 font-mono text-xs text-indigo-400 font-bold">
                        @foreach($user->two_factor_recovery_codes as $code)
                            <div class="p-1.5 rounded bg-slate-900 text-center tracking-wider border border-slate-800 select-all">{{ $code }}</div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <form action="{{ route('admin.profile.two-factor.recovery-codes') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold flex items-center gap-1.5">
                                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                <span>Buat Ulang Kode Pemulihan</span>
                            </button>
                        </form>

                        <button type="button" @click="showRecoveryModal = false" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold">
                            Selesai
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
