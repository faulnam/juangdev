<div 
    x-data="{
        isOpen: false,
        messages: [
            {
                id: 'welcome-msg',
                role: 'assistant',
                content: 'Halo! 👋 Saya Layanan Pelanggan JuangDev. Ada yang bisa saya bantu terkait layanan pembuatan website atau aplikasi untuk bisnis Anda?'
            }
        ],
        input: '',
        isLoading: false,
        quickQuestions: [
            'Layanan apa saja yang tersedia?',
            'Berapa harga pembuatan landing page?',
            'Bisa konsultasi gratis?'
        ],
        scrollToBottom() {
            this.$nextTick(() => {
                if (this.$refs.messagesEnd) {
                    this.$refs.messagesEnd.scrollIntoView({ behavior: 'smooth' });
                }
            });
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        messages: this.messages.filter(m => m.content).map(m => ({ role: m.role, content: m.content }))
                    })
                });

                const data = await res.json();
                const replyText = data.reply || 'Halo! Silakan hubungi kami langsung via WhatsApp untuk respon instan.';
                
                const targetMsg = this.messages.find(m => m.id === assistantId);
                if (targetMsg) {
                    targetMsg.content = replyText;
                }
            } catch (err) {
                const targetMsg = this.messages.find(m => m.id === assistantId);
                if (targetMsg) {
                    targetMsg.content = 'Maaf, terjadi gangguan koneksi. Silakan hubungi tim kami via [WhatsApp](https://wa.me/6283852174877).';
                }
            } finally {
                this.isLoading = false;
                this.scrollToBottom();
            }
        },
        formatText(text) {
            if (!text) return '';
            const linkRegex = /\[([^\]]+)\]\(([^)]+)\)/g;
            let formatted = text.replace(linkRegex, '<a href=\'$2\' target=\'_blank\' class=\'underline font-bold text-blue-600 hover:text-blue-800\'>$1</a>');
            const urlRegex = /(https?:\/\/[^\s]+)/g;
            formatted = formatted.replace(urlRegex, function(url) {
                if (url.includes('<a href=')) return url;
                return '<a href=\'' + url + '\' target=\'_blank\' class=\'underline font-bold text-blue-600 hover:text-blue-800\'>' + url + '</a>';
            });
            return formatted.replace(/\n/g, '<br>');
        }
    }"
    class="relative"
>
    <!-- Floating Round Button -->
    <button 
        id="chatbot-toggle-btn"
        @click="isOpen = !isOpen; if(isOpen) { scrollToBottom(); }"
        class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 rounded-full shadow-2xl transition-all duration-300 hover:scale-105 active:scale-95 text-white"
        :class="isOpen ? 'bg-slate-800 rotate-90' : 'bg-[#0A1E5E] hover:bg-[#122d78] rotate-0'"
        aria-label="Buka Chat Layanan Pelanggan"
    >
        <span x-show="!isOpen">
            <i data-lucide="message-square" class="w-6 h-6"></i>
        </span>
        <span x-show="isOpen" x-cloak>
            <i data-lucide="x" class="w-6 h-6"></i>
        </span>
        
        <!-- Pulsing Online Dot -->
        <span x-show="!isOpen" class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-emerald-400 border-2 border-white rounded-full animate-pulse"></span>
    </button>

    <!-- Chat Modal Window -->
    <div 
        x-show="isOpen" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-6 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-6 scale-95"
        class="fixed bottom-24 right-4 sm:right-6 z-50 flex flex-col overflow-hidden bg-white border border-slate-200 shadow-2xl rounded-2xl w-[92vw] sm:w-[380px] h-[520px] max-h-[calc(100vh-120px)]"
    >
        <!-- Header -->
        <div class="bg-[#0A1E5E] px-4 py-3.5 flex items-center gap-3 shrink-0">
            <div class="relative">
                <div class="w-9 h-9 rounded-full bg-white/15 flex items-center justify-center text-[#C7F236]">
                    <i data-lucide="bot" class="w-5 h-5"></i>
                </div>
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-400 border-2 border-[#0A1E5E] rounded-full"></span>
            </div>
            
            <div class="flex-1 min-w-0">
                <p class="text-white font-bold text-sm leading-tight">Layanan Pelanggan</p>
                <p class="text-blue-200 text-xs mt-0.5">JuangDev • Aktif Sekarang</p>
            </div>
            
            <button 
                @click="isOpen = false"
                class="w-7 h-7 flex items-center justify-center rounded-full text-white/70 hover:text-white hover:bg-white/10 transition-colors"
            >
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Messages Body -->
        <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-3.5 bg-slate-50">
            <template x-for="m in messages" :key="m.id">
                <div 
                    class="flex gap-2 items-end"
                    :class="m.role === 'user' ? 'flex-row-reverse' : 'flex-row'"
                >
                    <!-- Avatar -->
                    <div 
                        class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 text-xs font-bold shadow-xs"
                        :class="m.role === 'user' ? 'bg-[#2563EB] text-white' : 'bg-white border border-slate-200 text-[#2563EB]'"
                    >
                        <span x-show="m.role === 'user'">U</span>
                        <span x-show="m.role !== 'user'">🤖</span>
                    </div>

                    <!-- Bubble -->
                    <div 
                        class="max-w-[80%] px-4 py-2.5 rounded-2xl text-sm leading-relaxed shadow-xs"
                        :class="m.role === 'user' 
                            ? 'bg-[#2563EB] text-white rounded-br-xs' 
                            : 'bg-white text-slate-800 border border-slate-200/80 rounded-bl-xs'"
                    >
                        <div x-show="m.content" x-html="formatText(m.content)"></div>
                        <div x-show="!m.content" class="flex items-center gap-1.5 py-1">
                            <span class="w-2 h-2 rounded-full bg-slate-400 animate-bounce" style="animation-delay: 0ms"></span>
                            <span class="w-2 h-2 rounded-full bg-slate-400 animate-bounce" style="animation-delay: 150ms"></span>
                            <span class="w-2 h-2 rounded-full bg-slate-400 animate-bounce" style="animation-delay: 300ms"></span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Quick Questions Prompt (when only 1 message) -->
            <div x-show="messages.length === 1" class="flex flex-col gap-1.5 mt-2">
                <p class="text-xs text-slate-400 pl-9 font-medium">Pertanyaan Populer:</p>
                <template x-for="q in quickQuestions" :key="q">
                    <button 
                        @click="sendMessage(q)"
                        class="self-end text-xs bg-white border border-slate-200 text-slate-700 px-3.5 py-1.5 rounded-full hover:border-[#2563EB] hover:text-[#2563EB] hover:bg-blue-50/50 transition-all text-left shadow-xs font-medium"
                        x-text="q"
                    >
                    </button>
                </template>
            </div>

            <div x-ref="messagesEnd"></div>
        </div>

        <!-- Input Bar -->
        <div class="p-3 bg-white border-t border-slate-200 shrink-0">
            <form @submit.prevent="sendMessage()" class="flex items-center gap-2">
                <input 
                    type="text"
                    x-model="input"
                    placeholder="Tulis pesan Anda..."
                    :disabled="isLoading"
                    class="flex-1 bg-slate-50 border border-slate-200 rounded-full px-4 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB] transition-all"
                >
                <button 
                    type="submit"
                    :disabled="isLoading || !input.trim()"
                    class="w-9 h-9 rounded-full bg-[#2563EB] text-white flex items-center justify-center shrink-0 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#1d4ed8] transition-colors"
                >
                    <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
            <p class="text-center text-[10px] text-slate-400 mt-2 font-medium">Didukung oleh Gemini AI • JuangDev</p>
        </div>
    </div>
</div>
