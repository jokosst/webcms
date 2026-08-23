@extends('layouts.admin')

@section('title', $testimonial->exists ? 'Edit Testimoni' : 'Tambah Testimoni')
@section('header_title', $testimonial->exists ? 'Edit Testimoni Klien' : 'Tambah Testimoni Klien Baru')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.testimonials.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Testimoni</span>
        </a>
    </div>

    <form action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial->id) : route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-5 shadow-lg">
        @csrf
        @if($testimonial->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nama Klien <span class="text-rose-400">*</span></label>
                <input type="text" name="client_name" value="{{ old('client_name', $testimonial->client_name) }}" required
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Hendrik Kurniawan">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Perusahaan Klien</label>
                <input type="text" name="client_company" value="{{ old('client_company', $testimonial->client_company) }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="PT Maju Sejahtera">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Jabatan Klien</label>
                <input type="text" name="client_position" value="{{ old('client_position', $testimonial->client_position) }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="VP of Operations">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Rating (1 - 5 Bintang) <span class="text-rose-400">*</span></label>
                <select name="rating" required class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
                    <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Bintang)</option>
                    <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4 Bintang)</option>
                    <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>⭐⭐⭐ (3 Bintang)</option>
                    <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>⭐⭐ (2 Bintang)</option>
                    <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>⭐ (1 Bintang)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Foto / Avatar Klien</label>
                <input type="file" name="avatar" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                @if($testimonial->avatar_path)
                    <div class="mt-2 p-2 bg-slate-800 rounded-lg inline-block">
                        <img src="{{ asset('storage/' . $testimonial->avatar_path) }}" alt="Avatar" class="w-12 h-12 object-cover rounded-full border border-slate-700">
                    </div>
                @endif
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Isi Testimoni / Ulasan <span class="text-rose-400">*</span></label>
            <textarea name="review_content" rows="4" required class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Tulis ulasan pengalaman positif dari klien...">{{ old('review_content', $testimonial->review_content) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Urutan Tampilan (Sort Order)</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order ?? 1) }}" required min="0" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div class="pt-6">
                <label class="flex items-center gap-3 cursor-pointer text-sm text-slate-300">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $testimonial->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                    <span class="font-medium">Tampilkan Testimoni (Aktif)</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-800">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>{{ $testimonial->exists ? 'Update Testimoni' : 'Simpan Testimoni' }}</span>
            </button>
        </div>
    </form>
</div>
@endsection
