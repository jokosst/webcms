@extends('layouts.admin')

@section('title', 'Pengaturan SEO & Global')
@section('header_title', 'Kelola Pengaturan SEO & Situs')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6 max-w-4xl">
    @csrf
    @method('PUT')

    <!-- Section SEO Global -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
        <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
            <i data-lucide="search" class="w-5 h-5 text-indigo-400"></i>
            <span>Global Meta SEO</span>
        </h3>

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Default Meta Title Web Profil</label>
                <input type="text" name="seo_default_title" value="{{ old('seo_default_title', $settings['seo_default_title'] ?? '') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Nusantara Digital Solusindo - Enterprise Software House">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Default Meta Description Web Profil</label>
                <textarea name="seo_default_description" rows="3" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Deskripsi utama web profil untuk mesin pencari Google...">{{ old('seo_default_description', $settings['seo_default_description'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Section Integrasi Skrip & Analytics -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
        <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
            <i data-lucide="code" class="w-5 h-5 text-indigo-400"></i>
            <span>Integrasi Analitik & Widget</span>
        </h3>

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Google Analytics Measurement ID</label>
                <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="G-XXXXXXXXXX">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Pesan Floating WhatsApp Default</label>
                <input type="text" name="whatsapp_default_message" value="{{ old('whatsapp_default_message', $settings['whatsapp_default_message'] ?? 'Halo, saya tertarik berkonsultasi mengenai layanan perusahaan Anda.') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
            </div>
        </div>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Simpan Pengaturan</span>
        </button>
    </div>
</form>
@endsection
