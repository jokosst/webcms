@extends('layouts.admin')

@section('title', $article->exists ? 'Edit Artikel' : 'Tulis Artikel Baru')
@section('header_title', $article->exists ? 'Edit Artikel' : 'Tulis Artikel Baru')

@push('styles')
<style>
    /* Styling for TinyMCE Rich Text Editor Container */
    .tox-tinymce {
        border-radius: 0.75rem !important;
        border-color: #334155 !important; /* border-slate-700 */
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3) !important;
    }
    .tox .tox-menubar {
        background-color: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    .tox .tox-mbtn {
        color: #0f172a !important;
        font-weight: 600 !important;
    }
    .tox .tox-mbtn:hover {
        background-color: #e2e8f0 !important;
        color: #4f46e5 !important;
    }
    .tox .tox-toolbar, .tox .tox-toolbar__overflow, .tox .tox-toolbar__primary {
        background-color: #ffffff !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    .tox .tox-tbtn {
        color: #0f172a !important;
    }
    .tox .tox-tbtn:hover {
        background-color: #e2e8f0 !important;
        color: #4f46e5 !important;
    }
    .tox .tox-tbtn--enabled, .tox .tox-tbtn--active {
        background-color: #eef2ff !important;
        color: #4f46e5 !important;
    }
    .tox .tox-statusbar {
        background-color: #f1f5f9 !important;
        border-top: 1px solid #e2e8f0 !important;
        color: #64748b !important;
    }
</style>
@endpush

@section('content')
<div class="w-full space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.articles.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Artikel</span>
        </a>
    </div>

    <form action="{{ $article->exists ? route('admin.articles.update', $article->id) : route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" id="articleForm" class="space-y-6">
        @csrf
        @if($article->exists)
            @method('PUT')
        @endif

        <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-5 shadow-lg">
            <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
                <i data-lucide="newspaper" class="w-5 h-5 text-indigo-400"></i>
                <span>Konten Utama Artikel</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Judul Artikel <span class="text-rose-400">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $article->title) }}" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Judul artikel menarik...">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Kategori Artikel</label>
                    <select name="category_id" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
                        <option value="">-- Tanpa Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Ringkasan / Excerpt</label>
                <textarea name="excerpt" rows="2" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Ringkasan singkat artikel...">{{ old('excerpt', $article->excerpt) }}</textarea>
            </div>

            <!-- Full-Featured Rich Text Editor (TinyMCE MS Word Style) -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Isi Konten Lengkap Artikel <span class="text-rose-400">*</span></label>
                <textarea name="content" id="editor" class="w-full bg-slate-800 text-slate-100 rounded-xl border border-slate-700 min-h-[300px]">{!! old('content', $article->content) !!}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Gambar Utama (Featured Image)</label>
                    <input type="file" name="featured_image_file" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                    @if($article->featured_image)
                        <div class="mt-2 p-2 bg-slate-800 rounded-lg inline-block">
                            <img src="{{ asset('storage/' . $article->featured_image) }}" alt="Featured" class="h-20 w-36 object-cover rounded-lg border border-slate-700">
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Status Publikasi <span class="text-rose-400">*</span></label>
                    <select name="status" required class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
                        <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft (Hanya disimpan di CMS)</option>
                        <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published (Tampil di Web Profil)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Tanggal Terbit</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}" 
                           class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none">
                    <span class="text-[10px] text-slate-500 mt-1 block">Kosongkan untuk tanggal terbit otomatis (saat ini).</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Akses Komentar <span class="text-rose-400">*</span></label>
                    <select name="allow_comments" required class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
                        <option value="1" {{ old('allow_comments', $article->allow_comments ?? true) ? 'selected' : '' }}>💬 Buka Komentar (Aktif)</option>
                        <option value="0" {{ !old('allow_comments', $article->allow_comments ?? true) ? 'selected' : '' }}>🔒 Tutup Komentar (Matikan)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Section SEO Metadata -->
        <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
            <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
                <i data-lucide="search" class="w-5 h-5 text-indigo-400"></i>
                <span>Pengaturan SEO Artikel</span>
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Meta Title SEO</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $article->seo_meta['meta_title'] ?? '') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Judul pada pencarian Google">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Meta Description SEO</label>
                    <textarea name="meta_description" rows="2" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Deskripsi singkat untuk mesin pencari Google...">{{ old('meta_description', $article->seo_meta['meta_description'] ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $article->seo_meta['meta_keywords'] ?? '') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="teknologi, cloud, laravel, bisnis">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>{{ $article->exists ? 'Update Artikel' : 'Simpan Artikel' }}</span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<!-- TinyMCE 6 Open Source CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        tinymce.init({
            selector: '#editor',
            height: 520,
            menubar: 'file edit view insert format table tools help',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount', 'emoticons'
            ],
            toolbar1: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor removeformat | alignleft aligncenter alignright alignjustify | numlist bullist | outdent indent',
            toolbar2: 'table image media link | charmap emoticons | code fullscreen preview | help',
            branding: false,
            promotion: false,
            content_style: `
                body { 
                    font-family: Inter, system-ui, -apple-system, sans-serif; 
                    font-size: 15px; 
                    line-height: 1.7;
                    color: #0f172a; 
                    background-color: #ffffff; 
                    padding: 1.25rem; 
                }
                p { margin-bottom: 1rem; }
                h1, h2, h3, h4, h5, h6 { font-weight: 800; color: #0f172a; margin-top: 1.5rem; margin-bottom: 0.75rem; }
                img { max-width: 100%; height: auto; border-radius: 0.5rem; }
                table { border-collapse: collapse; width: 100%; margin-bottom: 1rem; }
                table td, table th { border: 1px solid #cbd5e1; padding: 8px 12px; }
                blockquote { border-left: 4px solid #6366f1; padding-left: 1rem; font-style: italic; color: #475569; }
            `,
            images_upload_handler: function (blobInfo, progress) {
                return new Promise((resolve, reject) => {
                    resolve('data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64());
                });
            }
        });
    });
</script>
@endpush
@endsection
