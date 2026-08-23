@extends('layouts.admin')

@section('title', $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna Baru')
@section('page_title', $isEdit ? 'Edit Akun Pengguna' : 'Tambah Akun Pengguna Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all flex items-center gap-2 border border-slate-700">
            <i data-lucide="arrow-left" class="w-4 h-4 text-red-400"></i>
            <span>Kembali ke Daftar Pengguna</span>
        </a>
    </div>

    <!-- User Form Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
        <div>
            <h2 class="text-xl font-extrabold text-white font-heading">
                {{ $isEdit ? 'Form Edit Akun Pengguna' : 'Form Tambah Akun Pengguna Baru' }}
            </h2>
            <p class="text-xs text-slate-400 mt-1">
                {{ $isEdit ? 'Perbarui informasi profil, alamat email, hak akses role, atau ubah kata sandi akun.' : 'Isi formulir berikut untuk memberikan akses login ke sistem Admin Panel.' }}
            </p>
        </div>

        <form action="{{ $isEdit ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Nama Lengkap Pengguna *</label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name', $user->name) }}" 
                       required 
                       placeholder="Contoh: Joko Susanto / Operator" 
                       class="w-full px-4 py-3 rounded-xl bg-slate-800 border border-slate-700 text-white text-xs focus:outline-none focus:border-red-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Alamat Email (Username Login) *</label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}" 
                       required 
                       placeholder="nama@gmail.com" 
                       class="w-full px-4 py-3 rounded-xl bg-slate-800 border border-slate-700 text-white text-xs focus:outline-none focus:border-red-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Hak Akses Role Pengguna *</label>
                <select name="role" required class="w-full px-4 py-3 rounded-xl bg-slate-800 border border-slate-700 text-white text-xs focus:outline-none focus:border-red-500">
                    <option value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>Superadmin (Akses Penuh Seluruh Sistem)</option>
                    <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>Editor (Kelola Berita & Artikel)</option>
                </select>
                @error('role')
                    <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Fields -->
            <div class="pt-4 border-t border-slate-800 space-y-4"
                 x-data="{ 
                     showPass: false, 
                     showConfirmPass: false,
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
                         this.showConfirmPass = true;
                         this.copied = true;
                         setTimeout(() => { this.copied = false; }, 3000);
                     }
                 }">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-extrabold text-white font-heading">
                            {{ $isEdit ? 'Ubah Kata Sandi (Kosongkan jika tidak diubah)' : 'Kata Sandi (Password) *' }}
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Minimal 6 karakter kombinasi huruf, angka, dan simbol.</p>
                    </div>

                    <button type="button" @click="generateStrongPassword()" class="px-3 py-1.5 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-400 border border-emerald-500/30 text-xs font-bold transition-all flex items-center gap-1.5 self-start sm:self-auto">
                        <i data-lucide="key" class="w-3.5 h-3.5"></i>
                        <span>⚡ Generate Strong Password</span>
                    </button>
                </div>

                <div x-show="copied" x-transition class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    <span>Password Acak Kuat (16 Karakter) berhasil dibuat dan diisikan otomatis!</span>
                </div>

                <style>
                    input::-ms-reveal, input::-ms-clear { display: none; }
                </style>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Kata Sandi Baru {{ $isEdit ? '' : '*' }}</label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" 
                                   id="password"
                                   name="password" 
                                   x-model="newPassword"
                                   autocomplete="new-password"
                                   {{ $isEdit ? '' : 'required' }} 
                                   placeholder="******" 
                                   class="w-full pl-4 pr-10 py-3 rounded-xl bg-slate-800 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500 @error('password') border-red-500 @enderror">
                            <button type="button" 
                                    @click="showPass = !showPass" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-emerald-400 transition-colors z-10 cursor-pointer" 
                                    title="Tampilkan / Sembunyikan Kata Sandi" style="margin-right: 5px;">
                                <svg x-show="!showPass" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPass" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.046 10.046 0 013.122-.463c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-9.771-9.771a3 3 0 114.243 4.243m-4.243-4.243L3 3m18 18l-4.243-4.243" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi Baru {{ $isEdit ? '' : '*' }}</label>
                        <div class="relative">
                            <input :type="showConfirmPass ? 'text' : 'password'" 
                                   id="password_confirmation"
                                   name="password_confirmation" 
                                   x-model="confirmPassword"
                                   autocomplete="new-password"
                                   {{ $isEdit ? '' : 'required' }} 
                                   placeholder="******" 
                                   class="w-full pl-4 pr-10 py-3 rounded-xl bg-slate-800 border border-slate-700 text-white text-xs focus:outline-none focus:border-emerald-500">
                            <button type="button" 
                                    @click="showConfirmPass = !showConfirmPass" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-emerald-400 transition-colors z-10 cursor-pointer" 
                                    title="Tampilkan / Sembunyikan Konfirmasi Kata Sandi" style="margin-right: 5px;">
                                <svg x-show="!showConfirmPass" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirmPass" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.046 10.046 0 013.122-.463c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-9.771-9.771a3 3 0 114.243 4.243m-4.243-4.243L3 3m18 18l-4.243-4.243" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="pt-6 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-extrabold border-2 border-emerald-400 shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2 hover:scale-[1.02]">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>{{ $isEdit ? 'Simpan Perubahan' : 'Buat Akun Baru' }}</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
