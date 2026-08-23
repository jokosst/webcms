<!-- FOOTER -->
<footer class="bg-slate-950 border-t border-slate-800/80 text-slate-400 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            
            <!-- Col 1: About & Contact -->
            <div class="md:col-span-3 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 p-1.5 flex items-center justify-center shrink-0">
                        @if(!empty($profile->logo_path))
                            <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $profile->company_name ?? 'Logo' }}" class="w-full h-full object-contain">
                        @else
                            <i data-lucide="cpu" class="w-5 h-5 text-cyan-400"></i>
                        @endif
                    </div>
                    <span class="text-base font-black text-white font-heading tracking-tight">{{ $profile->company_name ?? 'UNIT TIK' }}</span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    {{ $profile->tagline ?? 'Pusat Pengelolaan Sistem Informasi, Jaringan, Ruang Komputer, & Cloud Server House.' }}
                </p>

                <!-- Quick Contact Info -->
                <ul class="text-xs space-y-1.5 text-slate-400 pt-2 border-t border-slate-800/80">
                    @if(!empty($profile->phone))
                        <li class="flex items-center gap-2">
                            <i data-lucide="phone" class="w-3.5 h-3.5 text-cyan-400 flex-shrink-0"></i>
                            <span>{{ $profile->phone }}</span>
                        </li>
                    @endif
                    @if(!empty($profile->email))
                        <li class="flex items-center gap-2">
                            <i data-lucide="mail" class="w-3.5 h-3.5 text-cyan-400 flex-shrink-0"></i>
                            <span>{{ $profile->email }}</span>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Col 2: Fast Links -->
            <div class="md:col-span-2 space-y-3">
                <h4 class="text-xs font-black text-white uppercase tracking-widest">{{ __('Pilar TIK') }}</h4>
                <ul class="text-xs space-y-2">
                    <li><a href="{{ route('home') }}#pilar-kompetensi" class="hover:text-cyan-400 transition-colors">Sistem Aplikasi Web</a></li>
                    <li><a href="{{ route('home') }}#pilar-kompetensi" class="hover:text-cyan-400 transition-colors">Jaringan & Networking</a></li>
                    <li><a href="{{ route('home') }}#pilar-kompetensi" class="hover:text-cyan-400 transition-colors">Ruang Lab Komputer</a></li>
                    <li><a href="{{ route('home') }}#berita" class="hover:text-cyan-400 transition-colors">{{ __('Artikel & Berita TIK') }}</a></li>
                </ul>
            </div>

            <!-- Col 3: Dynamic Related Links from Database -->
            <div class="md:col-span-3 space-y-3">
                <h4 class="text-xs font-black text-white uppercase tracking-widest">{{ __('Link Terkait') }}</h4>
                @if(!empty($profile->related_links) && is_array($profile->related_links) && count($profile->related_links) > 0)
                    <ul class="text-xs space-y-2">
                        @foreach($profile->related_links as $link)
                            @if(!empty($link['title']) && !empty($link['url']))
                                <li>
                                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" class="hover:text-cyan-400 transition-colors flex items-center gap-1.5 group">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5 text-cyan-500/70 group-hover:text-cyan-400 group-hover:translate-x-0.5 transition-transform"></i>
                                        <span>{{ __($link['title']) }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <ul class="text-xs space-y-2">
                        <li><a href="https://untan.ac.id" target="_blank" class="hover:text-cyan-400 transition-colors flex items-center gap-1.5"><i data-lucide="external-link" class="w-3.5 h-3.5 text-cyan-500/70"></i> Portal Utama Universitas</a></li>
                        <li><a href="https://siakad.untan.ac.id" target="_blank" class="hover:text-cyan-400 transition-colors flex items-center gap-1.5"><i data-lucide="external-link" class="w-3.5 h-3.5 text-cyan-500/70"></i> Portal SIAKAD</a></li>
                        <li><a href="https://digilib.untan.ac.id" target="_blank" class="hover:text-cyan-400 transition-colors flex items-center gap-1.5"><i data-lucide="external-link" class="w-3.5 h-3.5 text-cyan-500/70"></i> Perpustakaan Digital</a></li>
                        <li><a href="https://jurnal.untan.ac.id" target="_blank" class="hover:text-cyan-400 transition-colors flex items-center gap-1.5"><i data-lucide="external-link" class="w-3.5 h-3.5 text-cyan-500/70"></i> E-Journal & Riset TIK</a></li>
                    </ul>
                @endif
            </div>

            <!-- Col 4: Maps Embed if available -->
            <div class="md:col-span-4 space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-black text-white uppercase tracking-widest">{{ __('Lokasi Kantor TIK') }}</h4>
                    <span class="text-[10px] font-bold text-cyan-400 bg-cyan-500/10 px-2 py-0.5 rounded-full border border-cyan-500/20">Google Maps Live</span>
                </div>
                @if(!empty($profile->google_maps_embed))
                    <div class="rounded-2xl overflow-hidden border border-slate-800 shadow-2xl h-64 sm:h-72 w-full [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0">
                        {!! $profile->google_maps_embed !!}
                    </div>
                @else
                    <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 text-xs text-slate-400 text-center space-y-2">
                        <i data-lucide="map-pin" class="w-8 h-8 text-cyan-500 mx-auto opacity-50"></i>
                        <p>Pusat Komputer & Data Center Terintegrasi.</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- Copyright Line -->
        <div class="pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
            <p>&copy; {{ date('Y') }} {{ $profile->company_name ?? 'Unit TIK' }}. {{ __('Semua Hak Cipta Dilindungi') }}.</p>
        </div>
    </div>
</footer>

<!-- Initialize Lucide Icons -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
