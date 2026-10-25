@extends('layouts.admin')

@section('title', $laboratory->exists ? 'Edit Laboratorium' : 'Tambah Laboratorium')
@section('header_title', $laboratory->exists ? 'Edit Data Laboratorium' : 'Tambah Laboratorium Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-white">{{ $laboratory->exists ? 'Edit: ' . $laboratory->name : 'Tambah Laboratorium Baru' }}</h2>
            <p class="text-xs text-slate-400">Pastikan informasi kapasitas PC dan fasilitas terisi akurat.</p>
        </div>
        <a href="{{ route('admin.laboratories.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700 flex items-center gap-1.5 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs space-y-1">
            <span class="font-bold block">Terjadi kesalahan pengisian form:</span>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" 
          action="{{ $laboratory->exists ? route('admin.laboratories.update', $laboratory->id) : route('admin.laboratories.store') }}" 
          enctype="multipart/form-data" 
          class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 space-y-6 shadow-xl text-xs text-slate-300">
        @csrf
        @if($laboratory->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Nama Lab -->
            <div>
                <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                    Nama Laboratorium <span class="text-rose-400">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $laboratory->name) }}" required
                       placeholder="Contoh: Lab Komputer 1 (Multimedia & Desain)"
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-cyan-500 text-xs">
            </div>

            <!-- Lokasi Ruangan -->
            <div>
                <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                    Lokasi Ruangan / Lantai
                </label>
                <input type="text" name="room_number" value="{{ old('room_number', $laboratory->room_number) }}"
                       placeholder="Contoh: Gedung UPA TIK Lt. 2 (Ruang 201)"
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-cyan-500 text-xs">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Kapasitas PC Workstation -->
            <div>
                <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                    Kapasitas Workstation PC (Unit) <span class="text-rose-400">*</span>
                </label>
                <input type="number" name="capacity" value="{{ old('capacity', $laboratory->capacity ?? 30) }}" required min="1" max="500"
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-cyan-500 text-xs">
                <span class="text-[10px] text-slate-500">Jumlah komputer yang siap digunakan peserta.</span>
            </div>

            <!-- Urutan Tampil -->
            <div>
                <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                    Urutan Tampil <span class="text-rose-400">*</span>
                </label>
                <input type="number" name="order_index" value="{{ old('order_index', $laboratory->order_index ?? 1) }}" required min="0"
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-cyan-500 text-xs">
            </div>
        </div>

        <!-- Spesifikasi PC -->
        <div>
            <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                Spesifikasi Utama Komputer
            </label>
            <input type="text" name="pc_specs" value="{{ old('pc_specs', $laboratory->pc_specs) }}"
                   placeholder="Contoh: Intel Core i7-12700, 16GB RAM, RTX 3060, SSD 512GB NVMe, Monitor 24 inch"
                   class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-cyan-500 text-xs">
        </div>

        <!-- Fasilitas (Newline delimited) -->
        <div>
            <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                Daftar Fasilitas Pendukung (1 Baris = 1 Fasilitas)
            </label>
            @php
                $rawFacilities = old('facilities_raw', is_array($laboratory->facilities) ? implode("\n", $laboratory->facilities) : '');
            @endphp
            <textarea name="facilities_raw" rows="4" 
                      placeholder="Contoh:&#10;40 Unit PC High-End + 1 PC Master Instruktur&#10;Proyektor Laser HD 4000 Lumens&#10;AC Split 2 PK (2 Unit)&#10;Gigabit LAN & WiFi 6 Terintegrasi"
                      class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-cyan-500 text-xs font-mono">{{ $rawFacilities }}</textarea>
            <span class="text-[10px] text-slate-500">Ketik fasilitas per baris untuk ditampilkan sebagai daftar bullet pada halaman publik.</span>
        </div>

        <!-- Deskripsi Lab -->
        <div>
            <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                Deskripsi / Peruntukan Laboratorium
            </label>
            <textarea name="description" rows="3" placeholder="Jelaskan peruntukan laboratorium ini (misal: praktikum coding, ujian CBT, workshop multimedia)."
                      class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-cyan-500 text-xs">{{ old('description', $laboratory->description) }}</textarea>
        </div>

        <!-- 2 Columns: PIC & Kontak -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                    Nama PIC / Penanggung Jawab Lab
                </label>
                <input type="text" name="pic_name" value="{{ old('pic_name', $laboratory->pic_name) }}"
                       placeholder="Contoh: Budi Santoso, S.Kom., M.T."
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-cyan-500 text-xs">
            </div>

            <div>
                <label class="block font-bold text-white mb-1.5 uppercase tracking-wider text-[11px]">
                    Kontak WhatsApp PIC
                </label>
                <input type="text" name="pic_contact" value="{{ old('pic_contact', $laboratory->pic_contact) }}"
                       placeholder="Contoh: 0812-xxxx-xxxx"
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-cyan-500 text-xs">
            </div>
        </div>

        <!-- Status Aktif Checkbox -->
        <div class="pt-2">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $laboratory->is_active ?? true) ? 'checked' : '' }}
                       class="w-4 h-4 rounded text-indigo-600 bg-slate-950 border-slate-700 focus:ring-indigo-500">
                <div>
                    <span class="font-bold text-white">Status Aktif Laboratorium</span>
                    <p class="text-[11px] text-slate-400">Jika dicentang, laboratorium ini akan muncul di kalender dan formulir reservasi publik.</p>
                </div>
            </label>
        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
            <a href="{{ route('admin.laboratories.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>{{ $laboratory->exists ? 'Simpan Perubahan' : 'Tambah Laboratorium' }}</span>
            </button>
        </div>
    </form>

</div>
@endsection
