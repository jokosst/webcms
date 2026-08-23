@extends('layouts.admin')

@section('title', $member->exists ? 'Edit Anggota Tim' : 'Tambah Anggota Tim')
@section('header_title', $member->exists ? 'Edit Profil Anggota Tim' : 'Tambah Anggota Tim Baru')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.team-members.index') }}" class="text-xs font-semibold text-slate-400 hover:text-white flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Tim</span>
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-400 text-xs space-y-1">
            <p class="font-bold">Gagal Menyimpan! Harap periksa inputan berikut:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $member->exists ? route('admin.team-members.update', $member->id) : route('admin.team-members.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-5 shadow-lg">
        @csrf
        @if($member->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $member->name) }}" required
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Budi Santoso">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Jabatan / Position <span class="text-rose-400">*</span></label>
                <input type="text" name="position" value="{{ old('position', $member->position) }}" required
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Chief Executive Officer">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Bio / Profil Singkat</label>
            <textarea name="bio" rows="3" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Pengalaman & latar belakang singkat...">{{ old('bio', $member->bio) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Foto Profil</label>
            <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
            @if($member->photo_path)
                <div class="mt-2 p-2 bg-slate-800 rounded-lg inline-block">
                    <img src="{{ asset('storage/' . $member->photo_path) }}" alt="Photo" class="w-16 h-16 object-cover rounded-full border border-slate-700">
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">LinkedIn URL</label>
                <input type="url" name="linkedin" value="{{ old('linkedin', $member->social_links['linkedin'] ?? '') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="https://linkedin.com/in/username">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Twitter URL</label>
                <input type="url" name="twitter" value="{{ old('twitter', $member->social_links['twitter'] ?? '') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="https://twitter.com/username">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Instagram URL</label>
                <input type="url" name="instagram" value="{{ old('instagram', $member->social_links['instagram'] ?? '') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="https://instagram.com/username">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Urutan Tampilan (Sort Order)</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $member->sort_order ?? 1) }}" required min="0" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div class="pt-6">
                <label class="flex items-center gap-3 cursor-pointer text-sm text-slate-300">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $member->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                    <span class="font-medium">Tampilkan Profil (Aktif)</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-800">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>{{ $member->exists ? 'Update Anggota Tim' : 'Simpan Anggota Tim' }}</span>
            </button>
        </div>
    </form>
</div>
@endsection
