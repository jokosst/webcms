@extends('layouts.admin')

@section('title', $portfolio->exists ? 'Edit Proyek' : 'Tambah Proyek Baru')
@section('header_title', $portfolio->exists ? 'Edit Portofolio Proyek' : 'Tambah Portofolio Proyek Baru')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.portfolios.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Portofolio</span>
        </a>
    </div>

    <form action="{{ $portfolio->exists ? route('admin.portfolios.update', $portfolio->id) : route('admin.portfolios.store') }}" method="POST" enctype="multipart/form-data" id="portfolioForm" class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-5 shadow-lg">
        @csrf
        @if($portfolio->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Judul Proyek <span class="text-rose-400">*</span></label>
                <input type="text" name="title" value="{{ old('title', $portfolio->title) }}" required
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Sistem Informasi Logistik & Supply Chain">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Kategori Proyek</label>
                <select name="category_id" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
                    <option value="">-- Tanpa Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $portfolio->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Pengguna / Nama Klien</label>
                <input type="text" name="client_name" value="{{ old('client_name', $portfolio->client_name) }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Fakultas / Unit Kerja / Klien">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Urutan Tampilan</label>
                <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $portfolio->sort_order ?? 0) }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="0">
                <span class="text-[10px] text-slate-500 mt-1 block">Makin kecil angka (misal: 0, 1, 2) makin depan urutannya.</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Tanggal Selesai Proyek</label>
                <input type="date" name="completion_date" value="{{ old('completion_date', $portfolio->completion_date ? $portfolio->completion_date->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">URL Website Proyek</label>
                <input type="url" name="project_url" value="{{ old('project_url', $portfolio->project_url) }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="https://clientdomain.com">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Ringkasan Proyek</label>
            <textarea name="short_description" rows="2" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Ringkasan pengerjaan proyek...">{{ old('short_description', $portfolio->short_description) }}</textarea>
        </div>

        <!-- Rich Text Editor (Quill) for Portfolio -->
        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Detail Rincian Proyek (Rich Text Editor)</label>
            <input type="hidden" name="content" id="portfolioContentInput" value="{{ old('content', $portfolio->content) }}">
            <div id="portfolioQuillEditor" class="bg-slate-800 text-slate-100 rounded-xl border border-slate-700 min-h-[200px]">
                {!! old('content', $portfolio->content) !!}
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Gambar Thumbnail Proyek</label>
            <input type="file" name="thumbnail" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
            @if($portfolio->thumbnail_path)
                <div class="mt-2 p-2 bg-slate-800 rounded-lg inline-block">
                    <img src="{{ asset('storage/' . $portfolio->thumbnail_path) }}" alt="Thumbnail" class="h-20 w-32 object-cover rounded-lg border border-slate-700">
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
            <label class="flex items-center gap-3 cursor-pointer text-sm text-slate-300">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $portfolio->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                <span class="font-medium">Tampilkan sebagai Proyek Unggulan (Featured)</span>
            </label>

            <label class="flex items-center gap-3 cursor-pointer text-sm text-slate-300">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $portfolio->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                <span class="font-medium">Status Publikasi (Aktif)</span>
            </label>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-800">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>{{ $portfolio->exists ? 'Update Proyek' : 'Simpan Proyek' }}</span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quill = new Quill('#portfolioQuillEditor', {
            theme: 'snow',
            placeholder: 'Tuliskan rincian detail proyek, teknologi, dan hasil...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'code-block'],
                    ['clean']
                ]
            }
        });

        const form = document.getElementById('portfolioForm');
        form.onsubmit = function () {
            document.getElementById('portfolioContentInput').value = quill.root.innerHTML;
        };
    });
</script>
@endpush
@endsection
