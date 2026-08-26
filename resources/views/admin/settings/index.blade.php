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
                                <option value="gemini-3.6-flash" {{ old('chatbot_ai_model', $settings['chatbot_ai_model'] ?? 'gemini-3.6-flash') == 'gemini-3.6-flash' ? 'selected' : '' }}>gemini-3.6-flash (Direkomendasikan)</option>
                                <option value="gemini-3.7-flash" {{ old('chatbot_ai_model', $settings['chatbot_ai_model'] ?? '') == 'gemini-3.7-flash' ? 'selected' : '' }}>gemini-3.7-flash</option>
                                <option value="gemini-2.5-flash" {{ old('chatbot_ai_model', $settings['chatbot_ai_model'] ?? '') == 'gemini-2.5-flash' ? 'selected' : '' }}>gemini-2.5-flash</option>
                                <option value="gemini-flash-latest" {{ old('chatbot_ai_model', $settings['chatbot_ai_model'] ?? '') == 'gemini-flash-latest' ? 'selected' : '' }}>gemini-flash-latest</option>
                            </select>
                        </div>
                    </div>

                    <!-- System Prompt Instruction -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Instruksi Karakter / System Prompt AI</label>
                        <textarea name="chatbot_ai_system_prompt" rows="2" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs leading-relaxed focus:border-indigo-500 focus:outline-none" placeholder="Instruksi kepribadian dan batasan AI...">{{ old('chatbot_ai_system_prompt', $settings['chatbot_ai_system_prompt'] ?? 'Kamu adalah Asisten Cerdas IT Helpdesk UPA. TIK Universitas Tanjungpura (Pontianak). Jawablah pertanyaan pengunjung secara ramah, sopan, jelas, dan membantu dalam Bahasa Indonesia. Gunakan data FAQ dan informasi layanan resmi TIK kampus sebagai rujukan utama. Jika informasi tidak tersedia sama sekali di data, sampaikan dengan sopan dan sarankan untuk membuat tiket pengaduan helpdesk. Format jawaban dengan rapi menggunakan Markdown.') }}</textarea>
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
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>
@endsection
