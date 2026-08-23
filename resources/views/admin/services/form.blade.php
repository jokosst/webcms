@extends('layouts.admin')

@section('title', $service->exists ? 'Edit Layanan' : 'Tambah Layanan Baru')
@section('header_title', $service->exists ? 'Edit Layanan / Produk' : 'Tambah Layanan / Produk Baru')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.services.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Layanan</span>
        </a>
    </div>

    <form action="{{ $service->exists ? route('admin.services.update', $service->id) : route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-5 shadow-lg">
        @csrf
        @if($service->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Judul Layanan / Produk <span class="text-rose-400">*</span></label>
                <input type="text" name="title" value="{{ old('title', $service->title) }}" required
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Custom Web Application Development">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Kategori</label>
                <select name="category_id" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
                    <option value="">-- Tanpa Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $service->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Deskripsi Ringkas (Summary)</label>
            <textarea name="short_description" rows="3" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Penjelasan ringkas mengenai layanan ini...">{{ old('short_description', $service->short_description) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Daftar Fitur Utama / Sub-Pilar (Tiap Baris = 1 Poin List)</label>
            <textarea name="content" rows="4" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Tuliskan poin-poin fitur utama (1 poin per baris)&#10;Contoh:&#10;Portal Web Resmi&#10;Integrasi API Data&#10;Keamanan SSL & SSO">{{ old('content', $service->content) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Ikon Layanan (Nama Ikon Lucide/FontAwesome)</label>
                <input type="text" name="icon" value="{{ old('icon', $service->icon) }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="code, smartphone, server, dll">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Gambar / Ilustrasi</label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                @if($service->image_path)
                    <div class="mt-2 p-2 bg-slate-800 rounded-lg inline-block">
                        <img src="{{ asset('storage/' . $service->image_path) }}" alt="Service" class="h-16 w-24 object-cover rounded-lg border border-slate-700">
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Urutan Tampilan (Sort Order)</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order ?? 1) }}" required min="0" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div class="pt-6">
                <label class="flex items-center gap-3 cursor-pointer text-sm text-slate-300">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                    <span class="font-medium">Tampilkan Layanan (Aktif)</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-800">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>{{ $service->exists ? 'Update Layanan' : 'Simpan Layanan' }}</span>
            </button>
        </div>
    </form>
</div>
@endsection
