@extends('layouts.admin')

@section('title', 'Profil')
@section('header_title', 'Kelola Profil')

@section('content')
<form action="{{ route('admin.company-profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-5xl">
    @csrf
    @method('PUT')

    <!-- Section 1: Identitas -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-5 shadow-lg">
        <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
            <i data-lucide="building-2" class="w-5 h-5 text-indigo-400"></i>
            <span>Identitas</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nama Perusahaan <span class="text-rose-400">*</span></label>
                <input type="text" name="company_name" value="{{ old('company_name', $profile->company_name) }}" required
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Tagline</label>
                <input type="text" name="tagline" value="{{ old('tagline', $profile->tagline) }}"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Transformasi Digital Terdepan">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Pengumuman Running Text (Top Ticker)</label>
                <textarea name="announcement_ticker" rows="2"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Selamat Datang di Portal Resmi. Jam Operasional Pelayanan : Senin - Jumat (08:00 - 15:30 WIB).">{{ old('announcement_ticker', $profile->announcement_ticker) }}</textarea>
            </div>

            <!-- Logo Upload & Preview -->
            <div class="p-4 bg-slate-800/40 border border-slate-800 rounded-xl space-y-3">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Logo Perusahaan</label>
                <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                @if($profile->logo_path)
                    <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 space-y-1">
                        <span class="text-[11px] text-slate-400 block">Logo Tersimpan saat ini:</span>
                        <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="Logo Tersimpan" class="h-14 max-w-full object-contain">
                    </div>
                @else
                    <p class="text-xs text-slate-500 italic">Belum ada logo tersimpan.</p>
                @endif
            </div>

            <!-- Favicon Upload & Preview -->
            <div class="p-4 bg-slate-800/40 border border-slate-800 rounded-xl space-y-3">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Favicon Icon (Browser Tab)</label>
                <input type="file" name="favicon" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                @if($profile->favicon_path)
                    <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 space-y-1">
                        <span class="text-[11px] text-slate-400 block">Favicon Tersimpan saat ini:</span>
                        <img src="{{ asset('storage/' . $profile->favicon_path) }}" alt="Favicon Tersimpan" class="w-10 h-10 object-contain">
                    </div>
                @else
                    <p class="text-xs text-slate-500 italic">Belum ada favicon tersimpan.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Section 2: Tentang, Visi, Misi -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
        <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
            <i data-lucide="file-text" class="w-5 h-5 text-indigo-400"></i>
            <span>Tentang, Visi & Misi</span>
        </h3>

        <div class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Tentang Perusahaan (About Us)</label>
                <textarea name="about_us" rows="4" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">{{ old('about_us', $profile->about_us) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Visi Perusahaan</label>
                    <textarea name="vision" rows="3" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">{{ old('vision', $profile->vision) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Misi Perusahaan</label>
                    <textarea name="mission" rows="3" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">{{ old('mission', $profile->mission) }}</textarea>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Sejarah & Milestone</label>
                <textarea name="history" rows="3" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">{{ old('history', $profile->history) }}</textarea>
            </div>
        </div>
    </div>

    <!-- Section 2.4: Statistik Demografi -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
        <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
            <i data-lucide="bar-chart-3" class="w-5 h-5 text-indigo-400"></i>
            <span>Statistik Demografi</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Jumlah</label>
                <input type="text" name="stat_population" value="{{ old('stat_population', $profile->stat_population ?? '12') }}"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: 3,450+">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Luas Wilayah</label>
                <input type="text" name="stat_area" value="{{ old('stat_area', $profile->stat_area ?? '') }}"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: 12.5 km²">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Wilayah</label>
                <input type="text" name="stat_subdistricts" value="{{ old('stat_subdistricts', $profile->stat_subdistricts ?? '') }}"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: 1">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Projek</label>
                <input type="text" name="stat_umkm" value="{{ old('stat_umkm', $profile->stat_umkm ?? '50+') }}"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: 48+">
            </div>
        </div>
    </div>
    <!-- Section 2.5: Profil Pimpinan / Kepala  -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
        <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
            <i data-lucide="user-check" class="w-5 h-5 text-indigo-400"></i>
            <span>Profil Pimpinan / Kepala</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nama Pimpinan / Kepala</label>
                <input type="text" name="leader_name" value="{{ old('leader_name', $profile->leader_name) }}"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Drs. H. Ahmad Subagyo">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Jabatan Pimpinan</label>
                <input type="text" name="leader_position" value="{{ old('leader_position', $profile->leader_position) }}"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Kepala">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Masa Jabatan</label>
                <input type="text" name="leader_term" value="{{ old('leader_term', $profile->leader_term) }}"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Masa Jabatan 2021 - 2027">
            </div>

            <!-- Leader Photo Upload & Preview -->
            <div class="md:col-span-3 p-4 bg-slate-800/40 border border-slate-800 rounded-xl space-y-3">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Foto Pimpinan / Kepala</label>
                <input type="file" name="leader_photo" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                @if($profile->leader_photo_path)
                    <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 space-y-1 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $profile->leader_photo_path) }}" alt="Foto Pimpinan" class="w-16 h-16 rounded-xl object-cover border border-slate-700">
                        <div>
                            <span class="text-xs font-bold text-white block">{{ $profile->leader_name ?? 'Foto Pimpinan Tersimpan' }}</span>
                            <span class="text-[11px] text-slate-400 block">{{ $profile->leader_position ?? 'Tersimpan' }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-slate-500 italic">Belum ada foto pimpinan tersimpan.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Section 3: Kontak & Lokasi -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
        <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
            <i data-lucide="map-pin" class="w-5 h-5 text-indigo-400"></i>
            <span>Kontak & Lokasi Kantor</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email Perusahaan</label>
                <input type="email" name="email" value="{{ old('email', $profile->email) }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Telepon Kantor</label>
                <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp', $profile->whatsapp) }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                <textarea name="address" rows="2" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">{{ old('address', $profile->address) }}</textarea>
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Google Maps Embed iframe HTML</label>
                <textarea name="google_maps_embed" rows="2" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl font-mono text-xs text-slate-300 focus:border-indigo-500 focus:outline-none">{{ old('google_maps_embed', $profile->google_maps_embed) }}</textarea>
            </div>
        </div>
    </div>

    <!-- Section 4: Sosial Media -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
        <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
            <i data-lucide="share-2" class="w-5 h-5 text-indigo-400"></i>
            <span>Tautan Sosial Media</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Social Media X (Twitter)</label>
                <input type="url" name="social_x" value="{{ old('social_x', $profile->social_media['x'] ?? '') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="https://x.com/username">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Facebook URL</label>
                <input type="url" name="social_facebook" value="{{ old('social_facebook', $profile->social_media['facebook'] ?? '') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="https://facebook.com/namapage">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Instagram URL</label>
                <input type="url" name="social_instagram" value="{{ old('social_instagram', $profile->social_media['instagram'] ?? '') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="https://instagram.com/username">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">LinkedIn URL</label>
                <input type="url" name="social_linkedin" value="{{ old('social_linkedin', $profile->social_media['linkedin'] ?? '') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="https://linkedin.com/company/name">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">YouTube URL</label>
                <input type="url" name="social_youtube" value="{{ old('social_youtube', $profile->social_media['youtube'] ?? '') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="https://youtube.com/@channel">
            </div>
        </div>
    </div>

    <!-- Section 5: Link Terkait Footer -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg" 
         x-data="{ 
             links: {{ json_encode(old('related_links', $profile->related_links ?? [
                 ['title' => 'Portal Utama Universitas', 'url' => 'https://untan.ac.id'],
                 ['title' => 'Sistem Informasi Akademik (SIAKAD)', 'url' => 'https://siakad.untan.ac.id'],
                 ['title' => 'Perpustakaan Digital', 'url' => 'https://digilib.untan.ac.id'],
                 ['title' => 'E-Journal & Riset TIK', 'url' => 'https://jurnal.untan.ac.id']
             ])) }},
             addLink() {
                 this.links.push({ title: '', url: '' });
             },
             removeLink(index) {
                 this.links.splice(index, 1);
             }
         }">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i data-lucide="link" class="w-5 h-5 text-indigo-400"></i>
                <span>Link Terkait (Footer Related Links)</span>
            </h3>
            <button type="button" @click="addLink()" class="px-3 py-1.5 rounded-lg bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 text-xs font-bold border border-indigo-500/30 flex items-center gap-1.5">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Link</span>
            </button>
        </div>

        <p class="text-xs text-slate-400">Daftar tautan eksternal/internal terkait yang akan ditampilkan secara dinamis pada footer halaman depan.</p>

        <div class="space-y-3">
            <template x-for="(link, index) in links" :key="index">
                <div class="flex items-center gap-3 bg-slate-800/40 p-3 rounded-xl border border-slate-800">
                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">Nama / Judul Link</label>
                            <input type="text" :name="'related_links['+index+'][title]'" x-model="link.title" placeholder="Contoh: Portal SIAKAD" class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-xs focus:border-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">URL / Alamat Link</label>
                            <input type="url" :name="'related_links['+index+'][url]'" x-model="link.url" placeholder="https://siakad.untan.ac.id" class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-xs focus:border-indigo-500 focus:outline-none">
                        </div>
                    </div>
                    <button type="button" @click="removeLink(index)" class="p-2 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 text-xs font-bold border border-rose-500/20 transition-colors shrink-0 mt-5" title="Hapus Link">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </template>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end">
        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>
@endsection
