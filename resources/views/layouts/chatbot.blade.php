<!-- SMART IT HELPDESK CHATBOT MODAL (TRIGGERED VIA HEADER & HELPDESK BUTTONS) -->
<div id="it-helpdesk-chatbot-modal"
     x-data="itHelpdeskChatbot()" 
     x-init="initChatbot()" 
     @open-chatbot.window="toggleChat(true)"
     @keydown.escape.window="isOpen = false"
     class="font-sans">

    <!-- BACKDROP & MODAL OVERLAY (ONLY SHOWN WHEN IS_OPEN IS TRUE) -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-slate-950/75 backdrop-blur-md">

        <!-- CLICK OUTSIDE TO CLOSE CONTAINER -->
        <div @click.outside="isOpen = false"
             x-show="isOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="w-full max-w-lg h-[640px] max-h-[92vh] bg-slate-950 rounded-3xl border border-cyan-500/30 shadow-2xl shadow-cyan-950/80 flex flex-col overflow-hidden text-slate-100 relative">

            <!-- HEADER -->
            <div class="px-6 py-4 bg-gradient-to-r from-slate-900 via-slate-900/95 to-cyan-950/80 border-b border-cyan-500/20 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3.5">
                    <div class="relative">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-cyan-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-cyan-500/30 border border-cyan-400/30">
                            <i data-lucide="headphones" class="w-5 h-5"></i>
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-500 rounded-full border-2 border-slate-950"></span>
                    </div>
                    <div>
                        <h4 class="text-sm font-extrabold text-white flex items-center gap-1.5" x-text="botConfig.bot_name || 'Asisten IT UPA. TIK'"></h4>
                        <p class="text-[11px] text-cyan-400/90 flex items-center gap-1 font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span x-text="botConfig.bot_tagline || 'Layanan Bantuan IT Cepat & Tiket Support'"></span>
                        </p>
                    </div>
                </div>

                <!-- Header Action Buttons -->
                <div class="flex items-center gap-1.5">
                    <button @click="resetChat()" type="button" title="Mulai Ulang Percakapan" class="p-2 rounded-xl text-slate-400 hover:text-cyan-400 hover:bg-slate-800 transition-colors">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </button>
                    <button @click="isOpen = false" type="button" title="Tutup Chat (Esc)" class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-slate-800 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <!-- MESSAGES STREAM AREA -->
            <div id="chatbot-messages-container" class="flex-1 p-5 overflow-y-auto space-y-4 text-xs scroll-smooth">

                <!-- Loop Messages -->
                <template x-for="(msg, index) in messages" :key="index">
                    <div>
                        <!-- BOT MESSAGE -->
                        <template x-if="msg.sender === 'bot'">
                            <div class="flex items-start gap-3 max-w-[90%]">
                                <div class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center flex-shrink-0 border border-cyan-500/30 mt-0.5">
                                    <i data-lucide="headphones" class="w-4 h-4"></i>
                                </div>
                                <div class="space-y-2">
                                    <div class="p-4 rounded-2xl rounded-tl-sm bg-slate-900 border border-slate-800 text-slate-200 leading-relaxed shadow-lg font-sans"
                                         x-html="formatMarkdown(msg.text)">
                                    </div>

                                    <!-- Action Buttons: Solved Feedback / Open Ticket -->
                                    <template x-if="msg.show_feedback && !msg.feedback_given">
                                        <div class="flex items-center gap-2 pt-1">
                                            <button @click="giveFeedback(msg, true)" type="button" class="px-3 py-1.5 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer">
                                                <span>👍 Membantu</span>
                                            </button>
                                            <button @click="openTicketForm(msg.topic || '')" type="button" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 text-white border border-cyan-400/30 text-xs font-bold shadow-md shadow-cyan-500/20 transition-all flex items-center gap-1.5 transform active:scale-95 cursor-pointer">
                                                <i data-lucide="ticket" class="w-3.5 h-3.5"></i>
                                                <span>Buat Tiket ke Helpdesk</span>
                                            </button>
                                        </div>
                                    </template>

                                    <!-- Feedback Response -->
                                    <template x-if="msg.feedback_given === 'solved'">
                                        <p class="text-[11px] text-emerald-400 font-medium italic">Senang bisa membantu kendala Kakak! 😊</p>
                                    </template>

                                    <!-- Offer Ticket Button if Unmatched -->
                                    <template x-if="msg.offer_ticket">
                                        <div class="pt-1 flex flex-wrap gap-2">
                                            <button @click="openTicketForm('')" type="button" class="px-4 py-2 rounded-xl bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 text-white font-bold text-xs shadow-lg shadow-cyan-500/25 flex items-center gap-2 transition-all transform active:scale-95">
                                                <i data-lucide="ticket" class="w-4 h-4"></i>
                                                <span>Buat Tiket Pengaduan Helpdesk</span>
                                            </button>
                                            <button @click="openTrackingModal()" type="button" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 font-bold text-xs border border-slate-700 flex items-center gap-2 transition-all transform active:scale-95">
                                                <i data-lucide="search" class="w-4 h-4"></i>
                                                <span>Lacak Tiket Lain</span>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- USER MESSAGE -->
                        <template x-if="msg.sender === 'user'">
                            <div class="flex items-end justify-end gap-2">
                                <div class="max-w-[85%] p-4 rounded-2xl rounded-tr-sm bg-gradient-to-r from-cyan-600 to-indigo-600 text-white leading-relaxed shadow-lg shadow-cyan-900/30">
                                    <p x-text="msg.text"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- INITIAL QUICK QUESTIONS (TOP FAQ CHIPS) -->
                <template x-if="messages.length === 1 && quickQuestions.length > 0">
                    <div class="pt-2 space-y-2">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5 text-cyan-400"></i>
                            <span>Pertanyaan Paling Sering Dicari:</span>
                        </p>
                        <div class="flex flex-col gap-2">
                            <template x-for="faq in quickQuestions" :key="faq.id">
                                <button @click="askQuickQuestion(faq.question)" 
                                        type="button" 
                                        class="w-full text-left p-3 rounded-xl bg-slate-900/90 hover:bg-cyan-950/70 text-slate-300 hover:text-cyan-300 border border-slate-800 hover:border-cyan-500/40 text-xs transition-all duration-200 flex items-center justify-between group">
                                    <span class="line-clamp-1 font-medium" x-text="faq.question"></span>
                                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-500 group-hover:text-cyan-400 transform group-hover:translate-x-0.5 transition-transform flex-shrink-0 ml-2"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- TYPING INDICATOR -->
                <div x-show="isLoading" class="flex items-start gap-3 max-w-[80%]" style="display: none;">
                    <div class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center flex-shrink-0 border border-cyan-500/30">
                        <i data-lucide="headphones" class="w-4 h-4"></i>
                    </div>
                    <div class="p-3.5 rounded-2xl rounded-tl-sm bg-slate-900 border border-slate-800 text-slate-400 flex items-center gap-1.5 shadow-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-bounce" style="animation-delay: 0ms;"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-bounce" style="animation-delay: 150ms;"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-bounce" style="animation-delay: 300ms;"></span>
                        <span class="text-[11px] font-medium text-slate-400 ml-1.5">Sedang memproses...</span>
                    </div>
                </div>

                <!-- INLINE TICKET FORM MODAL / CARD -->
                <div x-show="isTicketFormOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     style="display: none;"
                     class="p-5 rounded-2xl bg-slate-900 border border-cyan-500/40 shadow-2xl space-y-3.5">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-2.5">
                        <div class="flex items-center gap-2">
                            <i data-lucide="ticket" class="w-4 h-4 text-cyan-400"></i>
                            <h5 class="text-xs font-bold text-white uppercase tracking-wider">Formulir Tiket Helpdesk TIK</h5>
                        </div>
                        <button @click="isTicketFormOpen = false" type="button" class="text-slate-400 hover:text-rose-400">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <form @submit.prevent="submitTicketForm()" class="space-y-3">
                        <!-- Honeypot -->
                        <input type="text" name="hp_security_check" x-model="ticketForm.hp_security_check" style="display:none !important;" tabindex="-1" autocomplete="off">

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap *</label>
                            <input type="text" x-model="ticketForm.name" required placeholder="Contoh: Budi Santoso (Staf/Mahasiswa)" class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:border-cyan-500 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Email Aktif *</label>
                                <input type="email" x-model="ticketForm.email" required placeholder="email@untan.ac.id" class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:border-cyan-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">No. WhatsApp / HP</label>
                                <input type="text" x-model="ticketForm.phone" placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:border-cyan-500 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Judul / Subjek Kendala *</label>
                            <input type="text" x-model="ticketForm.subject" required placeholder="Contoh: Reset Password Akun SSO / SIAKAD" class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:border-cyan-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Deskripsi Lengkap Kendala *</label>
                            <textarea x-model="ticketForm.message" rows="2" required placeholder="Jelaskan detail kendala atau kronologi..." class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:border-cyan-500 focus:outline-none"></textarea>
                        </div>

                        <!-- VERIFIKASI KEAMANAN (ANTI-BOT) -->
                        <div class="space-y-1.5 pt-1">
                            <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-cyan-400"></i>
                                <span>Verifikasi Keamanan (Anti-Bot) *</span>
                            </label>
                            <div class="flex items-center gap-2.5">
                                <div class="px-3.5 py-2 rounded-xl bg-cyan-500/15 border border-cyan-500/30 text-cyan-300 font-mono font-bold text-xs shrink-0 flex items-center gap-1.5 select-none">
                                    <i data-lucide="calculator" class="w-3.5 h-3.5 text-cyan-400"></i>
                                    <span x-text="captchaNum1 + ' + ' + captchaNum2 + ' = ?'"></span>
                                </div>
                                <input type="number" 
                                       x-model="ticketForm.captcha_input" 
                                       required 
                                       placeholder="Hasil penjumlahan..." 
                                       class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:border-cyan-500 focus:outline-none">
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-2">
                            <button @click="isTicketFormOpen = false" type="button" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-colors">
                                Batal
                            </button>
                            <button type="submit" :disabled="isSubmittingTicket" class="px-5 py-2 rounded-xl bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 disabled:opacity-50 text-white font-bold text-xs shadow-lg shadow-cyan-600/30 flex items-center gap-2 transition-all cursor-pointer">
                                <span x-show="!isSubmittingTicket">Kirim Tiket</span>
                                <span x-show="isSubmittingTicket">Mengirim...</span>
                                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- INLINE TRACK TICKET MODAL / CARD -->
                <div x-show="isTrackingModalOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     style="display: none;"
                     class="p-5 rounded-2xl bg-slate-900 border border-cyan-500/40 shadow-2xl space-y-3.5">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-2.5">
                        <div class="flex items-center gap-2">
                            <i data-lucide="search" class="w-4 h-4 text-cyan-400"></i>
                            <h5 class="text-xs font-bold text-white uppercase tracking-wider">Lacak Status Tiket Support</h5>
                        </div>
                        <button @click="isTrackingModalOpen = false" type="button" class="text-slate-400 hover:text-rose-400">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <form @submit.prevent="submitTrackingForm()" class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Nomor Tiket Support *</label>
                            <input type="text" x-model="trackForm.ticket_id" required placeholder="Contoh: TKT-00005 atau 5" class="w-full px-3.5 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:border-cyan-500 focus:outline-none uppercase font-mono font-bold tracking-wider placeholder:font-normal placeholder:normal-case">
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Email Terdaftar (Opsional)</label>
                            <input type="email" x-model="trackForm.email" placeholder="email@untan.ac.id (untuk verifikasi tambahan)" class="w-full px-3.5 py-2 bg-slate-800 border border-slate-700 rounded-xl text-white text-xs focus:border-cyan-500 focus:outline-none">
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-1">
                            <button @click="isTrackingModalOpen = false" type="button" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-colors">
                                Batal
                            </button>
                            <button type="submit" :disabled="isTrackingTicket || !trackForm.ticket_id.trim()" class="px-5 py-2 rounded-xl bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 disabled:opacity-50 text-white font-bold text-xs shadow-lg shadow-cyan-600/30 flex items-center gap-2 transition-all cursor-pointer">
                                <span x-show="!isTrackingTicket">Lacak Sekarang</span>
                                <span x-show="isTrackingTicket">Mencari...</span>
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- INPUT BAR FOOTER (SPACIOUS FULL WIDTH WITH TRACKING FOOTNOTE) -->
            <div class="p-4 bg-slate-900/95 border-t border-slate-800/90 flex-shrink-0">
                <form @submit.prevent="submitMessage()" class="w-full">
                    <!-- Message Input Full Width -->
                    <div class="relative w-full">
                        <input type="text" 
                               x-ref="messageInput"
                               x-model="inputText" 
                               :disabled="isLoading"
                               placeholder="Ketik pertanyaan atau nomor tiket (contoh: TKT-00005)..." 
                               class="w-full pl-4 pr-12 py-3 bg-slate-950 border border-slate-800 rounded-2xl text-white text-xs placeholder-slate-500 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 focus:outline-none transition-all shadow-inner">
                        
                        <!-- Send Button Inside Input -->
                        <button type="submit" 
                                :disabled="!inputText.trim() || isLoading" 
                                class="absolute right-1.5 top-1.5 bottom-1.5 px-3 rounded-xl bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 disabled:opacity-30 disabled:hover:from-cyan-600 disabled:hover:to-indigo-600 text-white transition-all flex items-center justify-center cursor-pointer shadow-md">
                            <i data-lucide="send" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </form>

                <!-- Helpful Tracking Footnote at the Very Bottom -->
                <div class="flex items-center justify-between mt-2.5 pt-2 border-t border-slate-800/80 text-[11px] text-slate-400">
                    <div class="flex items-center gap-1.5 truncate">
                        <i data-lucide="ticket" class="w-3.5 h-3.5 text-cyan-400 shrink-0"></i>
                        <span class="truncate">Punya nomor tiket? Ketik <strong class="text-cyan-300 font-mono">TKT-00005</strong> untuk lacak.</span>
                    </div>
                    <button type="button" @click="openTrackingModal()" class="text-cyan-400 hover:text-cyan-300 font-bold hover:underline shrink-0 flex items-center gap-1 cursor-pointer ml-2">
                        <i data-lucide="search" class="w-3 h-3"></i>
                        <span>Lacak Tiket</span>
                    </button>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- CHATBOT JAVASCRIPT LOGIC & GLOBAL CONTROLLER -->
<script>
window.itHelpdeskChatbotInstance = null;

window.openItChatbot = function() {
    if (window.itHelpdeskChatbotInstance) {
        window.itHelpdeskChatbotInstance.toggleChat(true);
    } else {
        window.dispatchEvent(new CustomEvent('open-chatbot'));
    }
};

function itHelpdeskChatbot() {
    return {
        isOpen: false,
        isLoading: false,
        isTicketFormOpen: false,
        isSubmittingTicket: false,
        isTrackingModalOpen: false,
        isTrackingTicket: false,
        inputText: '',
        botConfig: {
            bot_name: 'Asisten IT UPA. TIK',
            bot_tagline: 'Layanan Bantuan IT Cepat & Tiket Support',
            welcome_message: 'Halo Kak! Selamat datang di Pusat Bantuan UPA. TIK. Ada kendala seputar layanan TIK kampus yang bisa saya bantu hari ini? Silakan klik topik di bawah atau ketik pertanyaan langsung ya.',
        },
        quickQuestions: [],
        messages: [],
        captchaNum1: 4,
        captchaNum2: 5,
        ticketForm: {
            name: '',
            email: '',
            phone: '',
            subject: '',
            message: '',
            hp_security_check: '',
            captcha_input: ''
        },
        trackForm: {
            ticket_id: '',
            email: ''
        },

        initChatbot() {
            window.itHelpdeskChatbotInstance = this;
            this.generateCaptcha();

            // Fetch initial configuration from API
            fetch('/api/v1/chatbot/init')
                .then(res => res.json())
                .then(data => {
                    if (data.bot_name) this.botConfig.bot_name = data.bot_name;
                    if (data.bot_tagline) this.botConfig.bot_tagline = data.bot_tagline;
                    if (data.welcome_message) this.botConfig.welcome_message = data.welcome_message;
                    if (data.quick_questions) this.quickQuestions = data.quick_questions;

                    // Initialize welcome message
                    this.messages = [{
                        sender: 'bot',
                        text: this.botConfig.welcome_message,
                        show_feedback: false,
                        offer_ticket: false
                    }];
                    this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                })
                .catch(() => {
                    this.messages = [{
                        sender: 'bot',
                        text: this.botConfig.welcome_message,
                        show_feedback: false,
                        offer_ticket: false
                    }];
                });
        },

        generateCaptcha() {
            this.captchaNum1 = Math.floor(Math.random() * 7) + 3;
            this.captchaNum2 = Math.floor(Math.random() * 8) + 1;
            if (this.ticketForm) {
                this.ticketForm.captcha_input = '';
            }
        },

        toggleChat(forceOpen = null) {
            if (forceOpen === true) {
                this.isOpen = true;
            } else if (forceOpen === false) {
                this.isOpen = false;
            } else {
                this.isOpen = !this.isOpen;
            }

            if (this.isOpen) {
                this.$nextTick(() => {
                    this.scrollToBottom();
                    if (this.$refs.messageInput) this.$refs.messageInput.focus();
                    if (window.lucide) lucide.createIcons();
                });
            }
        },

        resetChat() {
            this.messages = [{
                sender: 'bot',
                text: this.botConfig.welcome_message,
                show_feedback: false,
                offer_ticket: false
            }];
            this.isTicketFormOpen = false;
            this.isTrackingModalOpen = false;
            this.$nextTick(() => {
                this.scrollToBottom();
                if (window.lucide) lucide.createIcons();
            });
        },

        askQuickQuestion(question) {
            this.inputText = question;
            this.submitMessage();
        },

        submitMessage() {
            const query = this.inputText.trim();
            if (!query || this.isLoading) return;

            // Push user message
            this.messages.push({
                sender: 'user',
                text: query
            });

            this.inputText = '';
            this.isLoading = true;
            this.$nextTick(() => {
                this.scrollToBottom();
                if (window.lucide) lucide.createIcons();
            });

            // Send to Backend API
            fetch('/api/v1/chatbot/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: query })
            })
            .then(res => res.json())
            .then(data => {
                this.isLoading = false;
                if (data.success) {
                    this.messages.push({
                        sender: 'bot',
                        text: data.bot_reply,
                        show_feedback: data.found && !data.is_ticket_tracking,
                        topic: data.question_title || query,
                        offer_ticket: data.offer_ticket || false
                    });
                } else {
                    this.messages.push({
                        sender: 'bot',
                        text: data.message || 'Mohon maaf, terjadi gangguan koneksi ke server.',
                        offer_ticket: true
                    });
                }
                this.$nextTick(() => {
                    this.scrollToBottom();
                    if (window.lucide) lucide.createIcons();
                });
            })
            .catch(() => {
                this.isLoading = false;
                this.messages.push({
                    sender: 'bot',
                    text: 'Mohon maaf, sistem sedang mengalami kendala jaringan. Silakan coba lagi atau gunakan form tiket bantuan.',
                    offer_ticket: true
                });
                this.$nextTick(() => {
                    this.scrollToBottom();
                    if (window.lucide) lucide.createIcons();
                });
            });
        },

        giveFeedback(msg, isSolved) {
            msg.feedback_given = isSolved ? 'solved' : 'unsolved';
            if (!isSolved) {
                this.openTicketForm(msg.topic || '');
            }
        },

        openTicketForm(defaultSubject) {
            this.generateCaptcha();
            this.isTrackingModalOpen = false;
            this.ticketForm.subject = defaultSubject ? 'Kendala: ' + defaultSubject : '';
            this.isTicketFormOpen = true;
            this.$nextTick(() => {
                this.scrollToBottom();
                if (window.lucide) lucide.createIcons();
            });
        },

        openTrackingModal() {
            this.isTicketFormOpen = false;
            this.isTrackingModalOpen = true;
            this.$nextTick(() => {
                this.scrollToBottom();
                if (window.lucide) lucide.createIcons();
            });
        },

        submitTrackingForm() {
            const ticketId = this.trackForm.ticket_id.trim();
            if (!ticketId || this.isTrackingTicket) return;

            this.isTrackingTicket = true;

            // Push simulated user message
            this.messages.push({
                sender: 'user',
                text: 'Lacak Status Tiket #' + ticketId
            });

            this.isTrackingModalOpen = false;
            this.isLoading = true;
            this.$nextTick(() => {
                this.scrollToBottom();
                if (window.lucide) lucide.createIcons();
            });

            fetch('/api/v1/chatbot/track-ticket', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.trackForm)
            })
            .then(res => res.json())
            .then(data => {
                this.isTrackingTicket = false;
                this.isLoading = false;

                if (data.success && data.ticket) {
                    const t = data.ticket;
                    let replyText = `🎫 **INFORMASI STATUS TIKET SUPPORT**\n━━━━━━━━━━━━━━━━━━━━━━\n`
                        + `📌 **Nomor Tiket** : \`#${t.ticket_id}\`\n`
                        + `👤 **Pemohon** : \`${t.name}\`\n`
                        + `📋 **Judul Kendala** : ${t.subject}\n`
                        + `📅 **Tgl Pengajuan** : ${t.created_at}\n`
                        + `📊 **Status Saat Ini** : **${t.status_label}**\n`
                        + `━━━━━━━━━━━━━━━━━━━━━━`;

                    if (t.status === 'replied' && t.reply_message) {
                        replyText += `\n\n💬 **Balasan Petugas Helpdesk:**\n"${t.reply_message}"\n\n⏱️ *Dibalas pada: ${t.replied_at || '-' }*\n📧 *Salinan balasan resmi juga telah dikirimkan ke email pemohon.*`;
                    } else if (t.status === 'read' || t.status === 'in_progress') {
                        replyText += `\n\nℹ️ *Tiket Kakak telah diterima dan saat ini sedang ditindaklanjuti oleh staf IT UPA. TIK.*`;
                    } else {
                        replyText += `\n\nℹ️ *Tiket Kakak telah masuk ke antrean Helpdesk kami dan sedang menunggu giliran penanganan oleh petugas piket.*`;
                    }

                    this.messages.push({
                        sender: 'bot',
                        text: replyText,
                        show_feedback: false,
                        offer_ticket: false
                    });

                    // Reset form
                    this.trackForm = { ticket_id: '', email: '' };
                } else {
                    this.messages.push({
                        sender: 'bot',
                        text: data.message || `Nomor tiket **#${ticketId}** tidak ditemukan di database Helpdesk kami.`,
                        show_feedback: false,
                        offer_ticket: true
                    });
                }

                this.$nextTick(() => {
                    this.scrollToBottom();
                    if (window.lucide) lucide.createIcons();
                });
            })
            .catch(() => {
                this.isTrackingTicket = false;
                this.isLoading = false;
                this.messages.push({
                    sender: 'bot',
                    text: 'Terjadi gangguan jaringan saat melacak tiket. Silakan coba beberapa saat lagi.',
                    offer_ticket: true
                });
                this.$nextTick(() => {
                    this.scrollToBottom();
                    if (window.lucide) lucide.createIcons();
                });
            });
        },

        submitTicketForm() {
            if (this.isSubmittingTicket) return;

            // Validate Anti-Bot Math Captcha
            const expectedSum = this.captchaNum1 + this.captchaNum2;
            if (parseInt(this.ticketForm.captcha_input) !== expectedSum) {
                alert('Jawaban verifikasi keamanan (Anti-Bot) salah. Silakan coba lagi.');
                this.generateCaptcha();
                return;
            }

            this.isSubmittingTicket = true;

            const payload = {
                ...this.ticketForm,
                captcha_num1: this.captchaNum1,
                captcha_num2: this.captchaNum2,
                captcha_input: parseInt(this.ticketForm.captcha_input)
            };

            fetch('/api/v1/chatbot/ticket', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                this.isSubmittingTicket = false;
                this.isTicketFormOpen = false;

                if (data.success) {
                    this.messages.push({
                        sender: 'bot',
                        text: data.message || 'Tiket berhasil dikirim ke Helpdesk.',
                        show_feedback: false
                    });

                    // Reset form
                    this.ticketForm = {
                        name: '',
                        email: '',
                        phone: '',
                        subject: '',
                        message: '',
                        hp_security_check: '',
                        captcha_input: ''
                    };
                } else {
                    alert(data.message || 'Gagal mengirim tiket. Pastikan semua data terisi dengan benar.');
                    this.generateCaptcha();
                }

                this.$nextTick(() => {
                    this.scrollToBottom();
                    if (window.lucide) lucide.createIcons();
                });
            })
            .catch(() => {
                this.isSubmittingTicket = false;
                alert('Terjadi kesalahan saat mengirim tiket. Silakan coba beberapa saat lagi.');
                this.generateCaptcha();
            });
        },

        scrollToBottom() {
            const container = document.getElementById('chatbot-messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },

        formatMarkdown(text) {
            if (!text) return '';
            let formatted = text
                // Escape HTML
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                // Bold **text**
                .replace(/\*\*(.*?)\*\*/g, '<strong class="text-white font-bold">$1</strong>')
                // Italic *text*
                .replace(/\*(.*?)\*/g, '<em class="text-cyan-300 font-medium">$1</em>')
                // Code block or inline code `text`
                .replace(/`([^`]+)`/g, '<code class="px-1.5 py-0.5 rounded bg-slate-800 text-cyan-300 font-mono text-[11px]">$1</code>')
                // Line breaks
                .replace(/\n/g, '<br>');
            return formatted;
        }
    }
}
</script>
