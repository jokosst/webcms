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

    <!-- Section Asisten IT Helpdesk (Smart Chatbot Widget & Google Gemini AI) -->
    <div class="bg-slate-900 rounded-2xl border border-cyan-500/30 p-6 space-y-6 shadow-xl relative overflow-hidden" 
         x-data="{ 
            botActive: {{ old('chatbot_enabled', $settings['chatbot_enabled'] ?? '1') == '1' ? 'true' : 'false' }},
            aiActive: {{ old('chatbot_ai_enabled', $settings['chatbot_ai_enabled'] ?? '1') == '1' ? 'true' : 'false' }},
            landingHelpdeskMode: '{{ old('landing_helpdesk_mode', $settings['landing_helpdesk_mode'] ?? 'ticket_form') }}',
            headerHelpdeskAction: '{{ old('header_helpdesk_action', $settings['header_helpdesk_action'] ?? 'chatbot') }}',
            showApiKey: false,
            testingAi: false,
            aiTestResult: null,
            aiTestSuccess: false,
            testGeminiConnection() {
                const key = document.getElementById('gemini_api_key_input').value;
                const model = document.getElementById('gemini_model_select').value;
                if (!key) {
                    alert('Silakan masukkan Gemini API Key terlebih dahulu.');
                    return;
                }
                this.testingAi = true;
                this.aiTestResult = null;
                fetch('{{ route('admin.settings.test-gemini') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ api_key: key, model: model })
                })
                .then(res => res.json())
                .then(data => {
                    this.testingAi = false;
                    this.aiTestSuccess = data.success;
                    this.aiTestResult = data.message;
                })
                .catch(err => {
                    this.testingAi = false;
                    this.aiTestSuccess = false;
                    this.aiTestResult = 'Gagal menghubungi server: ' + err.message;
                });
            }
         }">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Chatbot Master Toggle Header -->
        <div class="border-b border-slate-800 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center border border-cyan-500/30 shadow-lg shadow-cyan-500/20">
                    <i data-lucide="headphones" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span>Asisten IT Helpdesk (Smart Chatbot & AI)</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider transition-colors"
                              :class="botActive ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700'">
                            <span x-text="botActive ? '● AKTIF' : '○ NONAKTIF'"></span>
                        </span>
                    </h3>
                    <p class="text-xs text-slate-400">Pusat asisten interaktif di web publik untuk konsultasi solusi IT dan pembuatan tiket support otomatis.</p>
                </div>
            </div>

            <!-- Segmented Toggle Control Chatbot Master -->
            <div class="flex items-center gap-2 flex-shrink-0 bg-slate-800/90 p-1.5 rounded-2xl border border-slate-700/80 shadow-inner">
                <input type="hidden" name="chatbot_enabled" :value="botActive ? '1' : '0'">

                <!-- Tombol AKTIF -->
                <button type="button" 
                        @click="botActive = true"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2"
                        :style="botActive ? 'background: linear-gradient(135deg, #06b6d4, #0891b2); color: #ffffff; box-shadow: 0 4px 12px rgba(6, 182, 212, 0.35);' : 'background: transparent; color: #94a3b8;'">
                    <span class="w-2 h-2 rounded-full" :style="botActive ? 'background-color: #ffffff;' : 'background-color: #64748b;'"></span>
                    <span>Aktif (ON)</span>
                </button>

                <!-- Tombol NONAKTIF -->
                <button type="button" 
                        @click="botActive = false"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2"
                        :style="!botActive ? 'background: #334155; color: #f1f5f9; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);' : 'background: transparent; color: #64748b;'">
                    <span class="w-2 h-2 rounded-full" :style="!botActive ? 'background-color: #f87171;' : 'background-color: #475569;'"></span>
                    <span>Nonaktif (OFF)</span>
                </button>
            </div>
        </div>

        <div class="space-y-6" :class="botActive ? 'opacity-100' : 'opacity-60 pointer-events-none'">
            
            <!-- Basic Bot Identity Config -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nama Tampilan Asisten</label>
                    <input type="text" name="chatbot_bot_name" value="{{ old('chatbot_bot_name', $settings['chatbot_bot_name'] ?? 'Asisten IT UPA. TIK') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-cyan-500 focus:outline-none" placeholder="Contoh: Asisten IT UPA. TIK">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Tagline / Sub-Judul Header</label>
                    <input type="text" name="chatbot_bot_tagline" value="{{ old('chatbot_bot_tagline', $settings['chatbot_bot_tagline'] ?? 'Layanan Bantuan IT Cepat & Tiket Support') }}" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-cyan-500 focus:outline-none" placeholder="Layanan Bantuan IT Otomatis">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Kalimat Sapaan Pembuka (Welcome Greeting)</label>
                <textarea name="chatbot_welcome_message" rows="2" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-cyan-500 focus:outline-none" placeholder="Halo Kak! Ada kendala teknis seputar layanan TIK yang bisa saya bantu hari ini?">{{ old('chatbot_welcome_message', $settings['chatbot_welcome_message'] ?? 'Halo Kak! Selamat datang di Pusat Bantuan UPA. TIK. Ada kendala seputar layanan TIK kampus yang bisa saya bantu hari ini? Silakan klik topik di bawah atau ketik pertanyaan langsung ya.') }}</textarea>
            </div>

            <!-- SUB-SECTION: INTEGRASI GOOGLE GEMINI AI (GENERATIVE KNOWLEDGE) -->
            <div class="p-5 rounded-2xl bg-gradient-to-r from-slate-950/80 to-indigo-950/40 border border-indigo-500/30 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-800/80 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center border border-indigo-500/30">
                            <i data-lucide="sparkles" class="w-4 h-4 text-indigo-400"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white flex items-center gap-2">
                                <span>Mesin Kecerdasan Buatan (Google Gemini AI)</span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider"
                                      :class="aiActive ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700'">
                                    <span x-text="aiActive ? 'AI AKTIF' : 'FAQ LOKAL'"></span>
                                </span>
                            </h4>
                            <p class="text-[11px] text-slate-400">Menghubungkan asisten ke Google Gemini dengan basis pengetahuan (*RAG Grounding*) dari data FAQ resmi instansi.</p>
                        </div>
                    </div>

                    <!-- AI Toggle -->
                    <div class="flex items-center gap-1.5 bg-slate-900 p-1 rounded-xl border border-slate-800">
                        <input type="hidden" name="chatbot_ai_enabled" :value="aiActive ? '1' : '0'">
                        <button type="button" 
                                @click="aiActive = true"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all"
                                :style="aiActive ? 'background: #6366f1; color: #ffffff;' : 'color: #94a3b8;'">
                            Aktif (AI)
                        </button>
                        <button type="button" 
                                @click="aiActive = false"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all"
                                :style="!aiActive ? 'background: #334155; color: #ffffff;' : 'color: #94a3b8;'">
                            Lokal (FAQ)
                        </button>
                    </div>
                </div>

                <div class="space-y-4" x-show="aiActive" x-transition>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Gemini API Key -->
                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                <span>Google Gemini API Key</span>
                                <span class="text-rose-400">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showApiKey ? 'text' : 'password'" 
                                       id="gemini_api_key_input"
                                       name="chatbot_gemini_api_key" 
                                       value="{{ old('chatbot_gemini_api_key', $settings['chatbot_gemini_api_key'] ?? '') }}" 
                                       class="w-full pl-4 pr-10 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs font-mono focus:border-indigo-500 focus:outline-none" 
                                       placeholder="AIzaSy... atau AQ.Ab8RN6...">
                                <button type="button" 
                                        @click="showApiKey = !showApiKey" 
                                        class="absolute right-3 top-2.5 text-slate-400 hover:text-white">
                                    <i :data-lucide="showApiKey ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Gemini Model Selection -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Model AI</label>
                            <select name="chatbot_ai_model" 
                                    id="gemini_model_select"
                                    class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:border-indigo-500 focus:outline-none">
                                <option value="gemini-3.5-flash-lite" {{ old('chatbot_ai_model', $settings['chatbot_ai_model'] ?? '') == 'gemini-3.5-flash-lite' ? 'selected' : '' }}>gemini-3.5-flash-lite (Sangat Cepat & Stabil)</option>
                                <option value="gemini-3.6-flash" {{ old('chatbot_ai_model', $settings['chatbot_ai_model'] ?? 'gemini-3.6-flash') == 'gemini-3.6-flash' ? 'selected' : '' }}>gemini-3.6-flash (Direkomendasikan)</option>
                                <option value="gemini-3.7-flash" {{ old('chatbot_ai_model', $settings['chatbot_ai_model'] ?? '') == 'gemini-3.7-flash' ? 'selected' : '' }}>gemini-3.7-flash</option>
                                <option value="gemini-3.8-flash" {{ old('chatbot_ai_model', $settings['chatbot_ai_model'] ?? '') == 'gemini-3.8-flash' ? 'selected' : '' }}>gemini-3.8-flash</option>
                                <option value="gemini-flash-latest" {{ old('chatbot_ai_model', $settings['chatbot_ai_model'] ?? '') == 'gemini-flash-latest' ? 'selected' : '' }}>gemini-flash-latest</option>
                            </select>
                        </div>
                    </div>

                    <!-- System Prompt Instruction -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Instruksi Karakter / System Prompt AI</label>
                        <textarea name="chatbot_ai_system_prompt" rows="2" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs leading-relaxed focus:border-indigo-500 focus:outline-none" placeholder="Instruksi kepribadian dan batasan AI...">{{ old('chatbot_ai_system_prompt', $settings['chatbot_ai_system_prompt'] ?? 'Kamu adalah Asisten Cerdas IT Helpdesk UPA. TIK Universitas Tanjungpura (Pontianak). Jawablah pertanyaan pengunjung secara ramah, sopan, jelas, dan membantu dalam Bahasa Indonesia. Utamakan rujukan dari FAQ resmi kampus. Namun jika pertanyaan tidak ada di FAQ, kamu diperbolehkan menjawab menggunakan pengetahuan umum IT dan keahlianmu secara solutif.') }}</textarea>
                    </div>

                    <!-- Test Button & Status Message -->
                    <div class="pt-1 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <button type="button" 
                                @click="testGeminiConnection()" 
                                :disabled="testingAi"
                                class="px-4 py-2 rounded-xl bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 hover:text-white border border-indigo-500/40 text-xs font-bold transition-all flex items-center gap-2 cursor-pointer">
                            <i data-lucide="zap" class="w-3.5 h-3.5 text-amber-400"></i>
                            <span x-show="!testingAi">🔌 Tes Koneksi Google Gemini API</span>
                            <span x-show="testingAi">Menguji Koneksi API...</span>
                        </button>

                        <div x-show="aiTestResult" class="text-xs font-semibold flex items-center gap-2" style="display: none;">
                            <span class="w-2 h-2 rounded-full" :class="aiTestSuccess ? 'bg-emerald-400' : 'bg-rose-400'"></span>
                            <span :class="aiTestSuccess ? 'text-emerald-400' : 'text-rose-400'" x-text="aiTestResult"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Pesan Fallback (Jika Jawaban Belum Ditemukan)</label>
                <textarea name="chatbot_fallback_message" rows="2" class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-cyan-500 focus:outline-none" placeholder="Mohon maaf, saya belum memiliki informasi lengkap...">{{ old('chatbot_fallback_message', $settings['chatbot_fallback_message'] ?? 'Wah, mohon maaf ya Kak, saya belum menemukan jawaban yang tepat dari data FAQ. Namun jangan khawatir, silakan klik tombol "Buat Tiket Support" di bawah agar segera ditindaklanjuti oleh petugas Helpdesk kami!') }}</textarea>
            </div>
        </div>

        <!-- SUB-SECTION: PENGATURAN TAMPILAN LANDING PAGE & TOMBOL HEADER -->
        <div class="pt-6 border-t border-slate-800 space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h4 class="text-sm font-bold text-white flex items-center gap-2">
                        <i data-lucide="layout-template" class="w-4 h-4 text-cyan-400"></i>
                        <span>Tampilan Tiket di Landing Page & Aksi Tombol Header</span>
                    </h4>
                    <p class="text-xs text-slate-400 mt-1">
                        Atur apa yang tampil pada bagian <code class="px-1.5 py-0.5 rounded bg-slate-800 text-cyan-300 font-mono text-[11px]">#helpdesk</code> di halaman utama dan fungsi tombol <strong>IT Helpdesk</strong> di header.
                    </p>
                </div>
            </div>

            <!-- Info banner if Chatbot Master is OFF -->
            <div x-show="!botActive" x-transition class="p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs flex items-start gap-2.5" style="display: none;">
                <i data-lucide="info" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5"></i>
                <span>
                    Karena <strong>Asisten IT Helpdesk (Chatbot & AI)</strong> saat ini <strong>NONAKTIF (OFF)</strong>, landing page otomatis menampilkan <strong>Formulir Tiket Helpdesk</strong> dan tombol header diarahkan ke formulir tersebut. Aktifkan Chatbot di atas agar Chat AI dapat diakses lewat tombol header secara bersamaan.
                </span>
            </div>

            <!-- Setting 1: Landing Page #helpdesk Display Mode -->
            <div class="space-y-2.5" :class="botActive ? 'opacity-100' : 'opacity-60 pointer-events-none'">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                    Tampilan Section Helpdesk di Landing Page
                </label>
                <input type="hidden" name="landing_helpdesk_mode" :value="landingHelpdeskMode">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Option 1: Ticket Form Only (Default) -->
                    <button type="button"
                            @click="landingHelpdeskMode = 'ticket_form'"
                            class="p-4 rounded-xl border text-left transition-all cursor-pointer space-y-1.5"
                            :style="landingHelpdeskMode === 'ticket_form'
                                ? 'background: rgba(6, 182, 212, 0.12); border-color: #06b6d4; box-shadow: 0 4px 16px rgba(6, 182, 212, 0.15);'
                                : 'background: rgba(15, 23, 42, 0.7); border-color: #334155;'">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-bold text-white flex items-center gap-1.5">
                                <i data-lucide="ticket" class="w-3.5 h-3.5 text-cyan-400"></i>
                                <span>Formulir Tiket Saja</span>
                            </span>
                            <span class="w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center shrink-0"
                                  :style="landingHelpdeskMode === 'ticket_form' ? 'border-color: #06b6d4;' : 'border-color: #64748b;'">
                                <span x-show="landingHelpdeskMode === 'ticket_form'" class="w-1.5 h-1.5 rounded-full" style="background-color: #06b6d4;"></span>
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Form Tiket Helpdesk tampil di landing page, sementara Chat AI tetap aktif di tombol header.
                        </p>
                    </button>

                    <!-- Option 2: Chatbot Banner Only -->
                    <button type="button"
                            @click="landingHelpdeskMode = 'chatbot_banner'"
                            class="p-4 rounded-xl border text-left transition-all cursor-pointer space-y-1.5"
                            :style="landingHelpdeskMode === 'chatbot_banner'
                                ? 'background: rgba(6, 182, 212, 0.12); border-color: #06b6d4; box-shadow: 0 4px 16px rgba(6, 182, 212, 0.15);'
                                : 'background: rgba(15, 23, 42, 0.7); border-color: #334155;'">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-bold text-white flex items-center gap-1.5">
                                <i data-lucide="message-square" class="w-3.5 h-3.5 text-indigo-400"></i>
                                <span>Banner Chat AI Saja</span>
                            </span>
                            <span class="w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center shrink-0"
                                  :style="landingHelpdeskMode === 'chatbot_banner' ? 'border-color: #06b6d4;' : 'border-color: #64748b;'">
                                <span x-show="landingHelpdeskMode === 'chatbot_banner'" class="w-1.5 h-1.5 rounded-full" style="background-color: #06b6d4;"></span>
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Menampilkan banner Asisten Virtual Chat AI dan menyembunyikan form tiket manual di landing page.
                        </p>
                    </button>

                    <!-- Option 3: Both -->
                    <button type="button"
                            @click="landingHelpdeskMode = 'both'"
                            class="p-4 rounded-xl border text-left transition-all cursor-pointer space-y-1.5"
                            :style="landingHelpdeskMode === 'both'
                                ? 'background: rgba(6, 182, 212, 0.12); border-color: #06b6d4; box-shadow: 0 4px 16px rgba(6, 182, 212, 0.15);'
                                : 'background: rgba(15, 23, 42, 0.7); border-color: #334155;'">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-bold text-white flex items-center gap-1.5">
                                <i data-lucide="layers" class="w-3.5 h-3.5 text-emerald-400"></i>
                                <span>Tampilkan Keduanya</span>
                            </span>
                            <span class="w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center shrink-0"
                                  :style="landingHelpdeskMode === 'both' ? 'border-color: #06b6d4;' : 'border-color: #64748b;'">
                                <span x-show="landingHelpdeskMode === 'both'" class="w-1.5 h-1.5 rounded-full" style="background-color: #06b6d4;"></span>
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Menampilkan Banner Chat AI dan Formulir Tiket Helpdesk secara bersamaan di landing page.
                        </p>
                    </button>
                </div>
            </div>

            <!-- Setting 2: Header IT Helpdesk Button Action -->
            <div class="space-y-2.5" :class="botActive ? 'opacity-100' : 'opacity-60 pointer-events-none'">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                    Aksi Tombol Header "IT Helpdesk" (Desktop & Mobile)
                </label>
                <input type="hidden" name="header_helpdesk_action" :value="headerHelpdeskAction">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Header Action 1: Open Chat AI -->
                    <button type="button"
                            @click="headerHelpdeskAction = 'chatbot'"
                            class="p-3.5 rounded-xl border text-left transition-all cursor-pointer space-y-1"
                            :style="headerHelpdeskAction === 'chatbot'
                                ? 'background: rgba(99, 102, 241, 0.15); border-color: #6366f1; box-shadow: 0 4px 16px rgba(99, 102, 241, 0.15);'
                                : 'background: rgba(15, 23, 42, 0.7); border-color: #334155;'">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-bold text-white flex items-center gap-1.5">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-indigo-400"></i>
                                <span>Buka Chat AI Asisten</span>
                            </span>
                            <span class="w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center shrink-0"
                                  :style="headerHelpdeskAction === 'chatbot' ? 'border-color: #818cf8;' : 'border-color: #64748b;'">
                                <span x-show="headerHelpdeskAction === 'chatbot'" class="w-1.5 h-1.5 rounded-full" style="background-color: #818cf8;"></span>
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400">
                            Klik tombol header langsung memunculkan popup Chat AI (bisa dari halaman mana saja).
                        </p>
                    </button>

                    <!-- Header Action 2: Scroll to Ticket Form -->
                    <button type="button"
                            @click="headerHelpdeskAction = 'scroll_form'"
                            class="p-3.5 rounded-xl border text-left transition-all cursor-pointer space-y-1"
                            :style="headerHelpdeskAction === 'scroll_form'
                                ? 'background: rgba(99, 102, 241, 0.15); border-color: #6366f1; box-shadow: 0 4px 16px rgba(99, 102, 241, 0.15);'
                                : 'background: rgba(15, 23, 42, 0.7); border-color: #334155;'">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-bold text-white flex items-center gap-1.5">
                                <i data-lucide="arrow-down-circle" class="w-3.5 h-3.5 text-cyan-400"></i>
                                <span>Arahkan ke Form Tiket (#helpdesk)</span>
                            </span>
                            <span class="w-3.5 h-3.5 rounded-full border-2 flex items-center justify-center shrink-0"
                                  :style="headerHelpdeskAction === 'scroll_form' ? 'border-color: #818cf8;' : 'border-color: #64748b;'">
                                <span x-show="headerHelpdeskAction === 'scroll_form'" class="w-1.5 h-1.5 rounded-full" style="background-color: #818cf8;"></span>
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400">
                            Klik tombol header akan mengarahkan pengunjung ke bagian #helpdesk di landing page.
                        </p>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Layanan Fasilitas & Laboratorium Komputer -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-5 shadow-lg"
         x-data="{ labActive: {{ old('lab_booking_enabled', $settings['lab_booking_enabled'] ?? '1') == '1' ? 'true' : 'false' }} }">
        <h3 class="text-base font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
            <i data-lucide="monitor" class="w-5 h-5 text-indigo-400"></i>
            <span>Layanan Peminjaman Laboratorium Komputer</span>
        </h3>

        <div class="space-y-4">
            <!-- Toggle Aktif/Nonaktif -->
            <div class="p-4 rounded-xl border flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 transition-colors"
                 :class="labActive ? 'bg-emerald-950/20 border-emerald-500/30' : 'bg-rose-950/25 border-rose-500/40'">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider flex items-center gap-2" :class="labActive ? 'text-emerald-300' : 'text-rose-300'">
                        <span x-text="labActive ? 'Layanan Online Aktif (Dibuka)' : 'Layanan Online Nonaktif (Ditutup Sementara)'"></span>
                    </h4>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Izinkan atau tutup sementara formulir pengajuan reservasi lab secara online oleh civitas akademika.
                    </p>
                </div>

                <!-- Segmented Toggle Control Layanan Lab -->
                <div class="inline-flex items-center gap-2 flex-shrink-0 w-fit bg-slate-800/90 p-1.5 rounded-2xl border border-slate-700/80 shadow-inner">
                    <input type="hidden" name="lab_booking_enabled" :value="labActive ? '1' : '0'">

                    <!-- Tombol AKTIF -->
                    <button type="button"
                            @click="labActive = true"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer"
                            :style="labActive ? 'background: linear-gradient(135deg, #10b981, #059669); color: #ffffff; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);' : 'background: transparent; color: #94a3b8;'">
                        <span class="w-2 h-2 rounded-full" :style="labActive ? 'background-color: #ffffff;' : 'background-color: #64748b;'"></span>
                        <span>Aktif (ON)</span>
                    </button>

                    <!-- Tombol NONAKTIF -->
                    <button type="button"
                            @click="labActive = false"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer"
                            :style="!labActive ? 'background: linear-gradient(135deg, #e11d48, #be123c); color: #ffffff; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.35);' : 'background: transparent; color: #64748b;'">
                        <span class="w-2 h-2 rounded-full" :style="!labActive ? 'background-color: #ffffff;' : 'background-color: #475569;'"></span>
                        <span>Nonaktif (OFF)</span>
                    </button>
                </div>
            </div>

            <!-- Pesan Penutupan Kustom -->
            <div x-show="!labActive" x-transition class="space-y-2">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                    Pesan Pengumuman Penutupan Sementara
                </label>
                <textarea name="lab_booking_closed_message" rows="3" 
                          class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none"
                          placeholder="Mohon maaf, layanan pengajuan peminjaman laboratorium komputer sedang ditutup sementara...">{{ old('lab_booking_closed_message', $settings['lab_booking_closed_message'] ?? 'Mohon maaf, layanan pengajuan peminjaman laboratorium komputer sedang ditutup sementara. Silakan hubungi pengelola UPA TIK untuk informasi lebih lanjut.') }}</textarea>
                <p class="text-[11px] text-slate-400">
                    Pesan ini akan otomatis ditampilkan di bagian atas kalender publik saat layanan ditutup.
                </p>
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>
@endsection
