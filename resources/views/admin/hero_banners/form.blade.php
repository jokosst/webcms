@extends('layouts.admin')

@section('title', $banner->exists ? 'Edit Hero Banner' : 'Tambah Hero Banner')
@section('header_title', $banner->exists ? 'Edit Hero Banner' : 'Tambah Hero Banner Baru')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.hero-banners.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Banner</span>
        </a>
    </div>

    <form action="{{ $banner->exists ? route('admin.hero-banners.update', $banner->id) : route('admin.hero-banners.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-5 shadow-lg">
        @csrf
        @if($banner->exists)
            @method('PUT')
        @endif

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Badge / Teks Label Merah Atas (Pill Badge)</label>
            <input type="text" name="badge_text" value="{{ old('badge_text', $banner->badge_text) }}"
                class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Portal Transparansi & Pelayanan Digital Desa">
            <span class="text-[11px] text-slate-400 mt-1 block">Teks label berwarna merah di atas judul banner hero.</span>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Judul Utama Banner <span class="text-rose-400">*</span></label>
            <input type="text" name="title" value="{{ old('title', $banner->title) }}" required
                class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Solusi Digital Terbaik untuk Bisnis Anda">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Subtitle / Deskripsi Singkat</label>
            <textarea name="subtitle" rows="3" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Penjelasan singkat promosi banner...">{{ old('subtitle', $banner->subtitle) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Teks Tombol CTA</label>
                <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Lihat Layanan">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Tautan / Link Tombol CTA</label>
                <input type="text" name="button_link" value="{{ old('button_link', $banner->button_link) }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: #services atau /layanan">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Gambar Background Banner</label>
            <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
            @if($banner->image_path)
                <div class="mt-2 p-2 bg-slate-800 rounded-lg inline-block">
                    <img src="{{ asset('storage/' . $banner->image_path) }}" alt="Banner" class="h-20 w-48 object-cover rounded-lg border border-slate-700">
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Urutan Tampilan (Sort Order)</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 1) }}" required min="0" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div class="pt-6">
                <label class="flex items-center gap-3 cursor-pointer text-sm text-slate-300">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                    <span class="font-medium">Tampilkan Banner (Aktif)</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-800">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>{{ $banner->exists ? 'Update Banner' : 'Simpan Banner' }}</span>
            </button>
        </div>
    </form>
</div>
@endsection
