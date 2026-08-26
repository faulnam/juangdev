@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '62859171681988';
    $whatsappMsg = urlencode("Halo JuangDev, saya ingin berkonsultasi mengenai proyek website/aplikasi.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";
@endphp

<div 
    x-data="{
        isOpen: false,
        activeTab: 'text', // 'text' or 'wa'
        messages: [
            {
                id: 'welcome-msg',
                role: 'assistant',
                content: 'Halo! 👋 Ada yang bisa saya bantu terkait layanan website & software JuangDev hari ini?'
            }
        ],
        input: '',
        isLoading: false,
        quickQuestions: [
            'Berapa harga pembuatan landing page?',
            'Layanan apa saja yang tersedia?',
            'Bagaimana alur pemesanan & DP 50%?',
            'Bisa konsultasi via WhatsApp?'
        ],
        init() {
            this.$nextTick(() => {
                if (window.lucide) {
                    lucide.createIcons();
                }
            });
        },
        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.scrollToBottom();
            }
        },
        scrollToBottom() {
            this.$nextTick(() => {
                if (window.lucide) {
                    lucide.createIcons();
                }
                if (this.$refs.messagesEnd) {
                    this.$refs.messagesEnd.scrollIntoView({ behavior: 'smooth' });
                }
            });
        },
        resetChat() {
            this.messages = [
                {
                    id: 'welcome-msg-' + Date.now(),
                    role: 'assistant',
                    content: 'Halo! Sesi percakapan telah direset. Silakan tanyakan seputar kebutuhan pembuatan website, estimasi biaya, atau fitur kustom.'
                }
            ];
            this.scrollToBottom();
        },
        async sendMessage(text) {
            const userMsg = (text || this.input || '').trim();
            if (!userMsg || this.isLoading) return;

            this.messages.push({
                id: 'user-' + Date.now(),
                role: 'user',
                content: userMsg
            });
            this.input = '';
            this.isLoading = true;
            this.scrollToBottom();

            const assistantId = 'assistant-' + Date.now();
            this.messages.push({
                id: assistantId,
                role: 'assistant',
                content: ''
            });

            try {
                const res = await fetch('{{ route('api.chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        messages: this.messages.filter(m => m.content).map(m => ({ role: m.role, content: m.content }))
                    })
                });

                const data = await res.json();
                const replyText = data.reply || 'Halo! Terima kasih. Anda juga dapat berkonsultasi langsung dengan tim kami via [WhatsApp JuangDev]({{ $whatsappUrl }}).';
                
                const targetMsg = this.messages.find(m => m.id === assistantId);
                if (targetMsg) {
                    targetMsg.content = replyText;
                }
            } catch (err) {
                const targetMsg = this.messages.find(m => m.id === assistantId);
                if (targetMsg) {
                    targetMsg.content = 'Terima kasih atas pertanyaannya! Anda dapat langsung menghubungi tim kami via WhatsApp di [WhatsApp JuangDev]({{ $whatsappUrl }}) untuk respon cepat.';
                }
            } finally {
                this.isLoading = false;
                this.scrollToBottom();
            }
        },
        formatText(text) {
            if (!text) return '';
            
            // Bold **text**
            let formatted = text.replace(/\*\*(.*?)\*\*/g, '<strong class=\'font-bold text-[#0A1E5E]\'>$1</strong>');
            
            // Markdown Links [Label](URL)
            const linkRegex = /\[([^\]]+)\]\(([^)]+)\)/g;
            formatted = formatted.replace(linkRegex, '<a href=\'$2\' target=\'_blank\' class=\'text-[#2563EB] hover:text-[#0A1E5E] font-bold underline inline-flex items-center gap-0.5\'>$1 ↗</a>');
            
            // Plain URL detection
            const urlRegex = /(https?:\/\/[^\s<]+)/g;
            formatted = formatted.replace(urlRegex, function(url) {
                if (url.includes('<a href=')) return url;
                return '<a href=\'' + url + '\' target=\'_blank\' class=\'text-[#2563EB] hover:text-[#0A1E5E] font-bold underline inline-flex items-center gap-0.5\'>' + url + ' ↗</a>';
            });
            
            // Bullet points
            formatted = formatted.replace(/^• (.*$)/gim, '<div class=\'flex items-start gap-2 my-1\'><span class=\'text-[#2563EB] font-bold\'>•</span><span>$1</span></div>');
            formatted = formatted.replace(/^- (.*$)/gim, '<div class=\'flex items-start gap-2 my-1\'><span class=\'text-[#0A1E5E] font-bold\'>-</span><span>$1</span></div>');
            
            // Line breaks
            return formatted.replace(/\n/g, '<br>');
        }
    }"
    class="relative select-none"
>
    <!-- 1. Floating Circular Launcher Button (Neo-Brutalist Stamp Style) -->
    <div class="fixed bottom-6 right-6 z-50 flex items-center gap-3">
        <!-- Floating Prompt Pill -->
        <button 
            type="button"
            x-show="!isOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-x-2"
            x-transition:enter-end="opacity-100 translate-x-0"
            @click="toggleChat()"
            class="hidden sm:inline-flex items-center gap-2 bg-white text-[#0A1E5E] text-xs font-black px-4 py-2.5 rounded-full border-2 border-[#0A1E5E] shadow-[3px_3px_0px_#0A1E5E] hover:shadow-[1px_1px_0px_#0A1E5E] hover:translate-x-0.5 hover:translate-y-0.5 transition-all cursor-pointer"
        >
            <span class="w-2.5 h-2.5 rounded-full bg-[#C7F236] border border-[#0A1E5E]"></span>
            <span>Tanya JuangDev</span>
        </button>

        <!-- Round Stamp Button -->
        <button 
            id="chatbot-toggle-btn"
            type="button"
            @click="toggleChat()"
            class="relative w-14 h-14 shrink-0 rounded-full border-2 border-[#0A1E5E] shadow-[4px_4px_0px_#0A1E5E] hover:shadow-[2px_2px_0px_#0A1E5E] hover:translate-x-0.5 hover:translate-y-0.5 active:translate-x-1 active:translate-y-1 active:shadow-none transition-all flex items-center justify-center cursor-pointer text-[#0A1E5E] focus:outline-none"
            :class="isOpen ? 'bg-white' : 'bg-[#C7F236] hover:bg-[#b5dd2a]'"
            aria-label="Buka Chat Layanan JuangDev"
        >
            <!-- Closed State Icon: Speech Bubble -->
            <span x-show="!isOpen" class="flex items-center justify-center">
                <svg class="w-7 h-7 text-[#0A1E5E]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </span>

            <!-- Open State Icon: Close X -->
            <span x-show="isOpen" x-cloak class="flex items-center justify-center">
                <i data-lucide="x" class="w-6 h-6 stroke-[3] text-[#0A1E5E]"></i>
            </span>
        </button>
    </div>

    <!-- 2. Chat Modal Window (Exact Layout Matching Reference) -->
    <div 
        x-show="isOpen" 
        x-cloak
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="fixed bottom-24 right-4 sm:right-6 z-50 flex flex-col bg-[#FDFCF7] border-2 border-[#0A1E5E] shadow-[6px_6px_0px_#0A1E5E] rounded-[2rem] w-[92vw] sm:w-[380px] h-[520px] max-h-[calc(100vh-120px)] overflow-hidden transition-all duration-200"
    >
        <!-- Header: Top Lime Bar with Segmented Tab Pill & Circular Close Button -->
        <div class="bg-[#C7F236] px-4 py-3.5 border-b-2 border-[#0A1E5E] flex items-center justify-between gap-2 shrink-0">
            
            <!-- Left: Segmented Tabs [ Text Chat | Voice Chat / WhatsApp ] -->
            <div class="inline-flex items-center bg-white/40 p-1 rounded-xl border-2 border-[#0A1E5E] gap-1">
                <button 
                    type="button"
                    @click="activeTab = 'text'"
                    class="px-3.5 py-1 rounded-lg text-xs font-black transition-all cursor-pointer"
                    :class="activeTab === 'text' 
                        ? 'bg-white text-[#0A1E5E] border-2 border-[#0A1E5E] shadow-[1px_1px_0px_#0A1E5E]' 
                        : 'text-[#0A1E5E] hover:text-[#0A1E5E]/80 font-bold'"
                >
                    Text Chat
                </button>

                <a 
                    href="{{ $whatsappUrl }}" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="px-3.5 py-1 rounded-lg text-xs font-bold text-[#0A1E5E] hover:bg-white/50 transition-all inline-flex items-center gap-1 cursor-pointer"
                >
                    <span>WhatsApp</span>
                    <i data-lucide="arrow-up-right" class="w-3 h-3 stroke-[2.5]"></i>
                </a>
            </div>

            <!-- Right Actions: Reset & Close -->
            <div class="flex items-center gap-1.5">
                <!-- Reset Button -->
                <button 
                    type="button"
                    @click="resetChat()"
                    title="Reset Chat"
                    class="w-8 h-8 rounded-full bg-white hover:bg-slate-100 text-[#0A1E5E] border-2 border-[#0A1E5E] flex items-center justify-center transition-all shadow-[1px_1px_0px_#0A1E5E] hover:shadow-none hover:translate-x-0.5 hover:translate-y-0.5 cursor-pointer"
                >
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 stroke-[2.5]"></i>
                </button>

                <!-- Circular Close Button (as seen in mockup) -->
                <button 
                    type="button"
                    @click="isOpen = false"
                    title="Tutup"
                    class="w-8 h-8 rounded-full bg-white hover:bg-rose-50 text-[#0A1E5E] border-2 border-[#0A1E5E] flex items-center justify-center transition-all shadow-[1px_1px_0px_#0A1E5E] hover:shadow-none hover:translate-x-0.5 hover:translate-y-0.5 cursor-pointer"
                >
                    <i data-lucide="x" class="w-4 h-4 stroke-[3]"></i>
                </button>
            </div>
        </div>

        <!-- Body: Chat Messages (Clean Cream/White Area) -->
        <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-3.5 bg-[#FAF9F5] text-xs sm:text-sm">
            <template x-for="m in messages" :key="m.id">
                <div 
                    class="flex flex-col gap-1 max-w-[88%]"
                    :class="m.role === 'user' ? 'self-end items-end' : 'self-start items-start'"
                >
                    <!-- Message Bubble -->
                    <div 
                        class="p-3.5 rounded-2xl border-2 border-[#0A1E5E] shadow-[2px_2px_0px_#0A1E5E] leading-relaxed select-text"
                        :class="m.role === 'user' 
                            ? 'bg-[#0A1E5E] text-white rounded-br-xs font-medium' 
                            : 'bg-white text-[#0A1E5E] rounded-tl-xs font-normal'"
                    >
                        <div x-show="m.content" x-html="formatText(m.content)" class="space-y-1"></div>
                        
                        <!-- Typing Dots -->
                        <div x-show="!m.content" class="flex items-center gap-1.5 py-1 px-1">
                            <span class="w-2 h-2 rounded-full bg-[#0A1E5E] animate-bounce" style="animation-delay: 0ms"></span>
                            <span class="w-2 h-2 rounded-full bg-[#0A1E5E] animate-bounce" style="animation-delay: 150ms"></span>
                            <span class="w-2 h-2 rounded-full bg-[#0A1E5E] animate-bounce" style="animation-delay: 300ms"></span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Quick Suggested Questions (Shown initially) -->
            <div x-show="messages.length === 1" class="flex flex-col gap-2 mt-2 pt-2 border-t border-slate-300">
                <p class="text-[11px] text-[#0A1E5E]/70 pl-1 font-bold uppercase tracking-wider">Pertanyaan Populer:</p>
                <div class="flex flex-wrap gap-1.5">
                    <template x-for="q in quickQuestions" :key="q">
                        <button 
                            type="button"
                            @click="sendMessage(q)"
                            class="text-xs bg-white hover:bg-[#C7F236] text-[#0A1E5E] border-2 border-[#0A1E5E] px-3.5 py-1.5 rounded-full font-bold shadow-[2px_2px_0px_#0A1E5E] hover:shadow-none hover:translate-x-0.5 hover:translate-y-0.5 transition-all text-left cursor-pointer"
                            x-text="q"
                        >
                        </button>
                    </template>
                </div>
            </div>

            <div x-ref="messagesEnd"></div>
        </div>

        <!-- Footer / Input Bar (Matching Reference Mockup) -->
        <div class="p-3 bg-white border-t-2 border-[#0A1E5E] shrink-0">
            <form @submit.prevent="sendMessage()" class="flex items-center gap-2">
                <!-- Pill Input Field with Bold Border -->
                <input 
                    type="text"
                    x-model="input"
                    placeholder="Tanya sesuatu..."
                    :disabled="isLoading"
                    class="flex-1 bg-[#F5F5EE] border-2 border-[#0A1E5E] rounded-full px-4 py-2.5 text-xs sm:text-sm text-[#0A1E5E] placeholder:text-[#0A1E5E]/40 font-semibold focus:outline-none focus:bg-white transition-colors disabled:opacity-50"
                >

                <!-- Circular Send Button with Bold Border & Arrow Icon -->
                <button 
                    type="submit"
                    :disabled="isLoading || !input.trim()"
                    class="w-10 h-10 rounded-full bg-[#C7F236] hover:bg-[#b5dd2a] text-[#0A1E5E] border-2 border-[#0A1E5E] flex items-center justify-center shrink-0 disabled:opacity-40 disabled:cursor-not-allowed shadow-[2px_2px_0px_#0A1E5E] hover:shadow-none hover:translate-x-0.5 hover:translate-y-0.5 active:scale-95 transition-all cursor-pointer font-bold"
                    title="Kirim Pesan"
                >
                    <svg class="w-4 h-4 fill-current ml-0.5" viewBox="0 0 24 24">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </form>
        </div>

    </div>
</div>
