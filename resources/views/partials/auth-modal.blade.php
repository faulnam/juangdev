<!-- Reusable Customer Auth Modal (Login / Register / Google Firebase) -->
<div 
    x-data="authModalComponent()" 
    x-cloak 
    @open-auth-modal.window="openModal($event.detail)"
    @keydown.escape.window="showAuthModal = false"
    class="relative z-[100]"
>
    <!-- Backdrop Overlay -->
    <div 
        x-show="showAuthModal" 
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="showAuthModal = false"
        class="fixed inset-0 bg-slate-950/75 backdrop-blur-xs transition-opacity"
    ></div>

    <!-- Modal Dialog -->
    <div 
        x-show="showAuthModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-3 sm:p-6"
    >
        <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all text-slate-800 my-auto max-h-[92vh] flex flex-col">
            
            <!-- Header with Dark Brand Accent -->
            <div class="relative bg-[#0A1E5E] text-white p-5 pt-6 pb-5 shrink-0 overflow-hidden">
                <!-- Hero Grid Pattern -->
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff10_1px,transparent_1px),linear-gradient(to_bottom,#ffffff10_1px,transparent_1px)] bg-[size:1.25rem_1.25rem] pointer-events-none"></div>

                <div class="relative z-10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img 
                            src="{{ asset('logo3.png') }}?v={{ filemtime(public_path('logo3.png')) }}" 
                            alt="JuangDev" 
                            class="h-7 w-auto object-contain"
                        >
                    </div>
                    <button 
                        type="button" 
                        @click="showAuthModal = false"
                        class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors border border-white/15 cursor-pointer"
                    >
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="relative z-10 mt-2.5">
                    <h3 class="text-base sm:text-lg font-bold text-white tracking-tight" x-text="authMode === 'login' ? 'Masuk ke Akun Pelanggan' : 'Daftar Akun JuangDev'"></h3>
                    <p class="text-xs text-white/70 font-medium mt-0.5" x-text="authMode === 'login' ? 'Pantau pesanan, progres proyek & invoice Anda secara instan.' : 'Daftar mudah tanpa OTP dengan Email & WhatsApp aktif.'"></p>
                </div>
            </div>

            <!-- Modal Content & Forms (Scrollable Body) -->
            <div class="p-5 sm:p-6 space-y-4 overflow-y-auto flex-1">
                
                <!-- Notice Banner when Triggered from Order/Estimator -->
                <template x-if="authNotice">
                    <div class="bg-blue-50/80 border border-blue-200/80 rounded-2xl p-3.5 flex items-start gap-3 text-xs text-blue-900">
                        <i data-lucide="info" class="w-4 h-4 text-blue-600 shrink-0 mt-0.5"></i>
                        <span x-text="authNotice" class="font-medium leading-relaxed"></span>
                    </div>
                </template>

                <!-- Error Alert -->
                <template x-if="errorMessage">
                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-3.5 flex items-start gap-2.5 text-xs text-rose-800">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0 mt-0.5"></i>
                        <span x-text="errorMessage" class="font-medium"></span>
                    </div>
                </template>

                <!-- Google Firebase 1-Click Button -->
                <div>
                    <button 
                        type="button" 
                        @click="signInWithGoogle()"
                        :disabled="isLoadingGoogle"
                        class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-2xl border border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50 text-slate-800 text-xs sm:text-sm font-bold shadow-2xs transition-all active:scale-[0.98] disabled:opacity-50"
                    >
                        <!-- Google SVG Icon -->
                        <svg class="w-4 h-4" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span x-text="isLoadingGoogle ? 'Menghubungkan Google...' : 'Lanjutkan dengan Google'"></span>
                    </button>
                </div>

                <!-- Divider -->
                <div class="relative flex items-center justify-center">
                    <div class="border-t border-slate-200 w-full"></div>
                    <span class="bg-white px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider shrink-0">atau dengan email</span>
                </div>

                <!-- 1. LOGIN FORM -->
                <form x-show="authMode === 'login'" @submit.prevent="submitLogin()" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
                        <input 
                            type="email" 
                            x-model="loginData.email" 
                            required 
                            placeholder="nama@email.com"
                            class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-medium focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/15 transition-all"
                        >
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700">Password</label>
                        </div>
                        <input 
                            type="password" 
                            x-model="loginData.password" 
                            required 
                            placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-medium focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/15 transition-all"
                        >
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" x-model="loginData.remember" class="w-4 h-4 rounded text-[#2563EB] focus:ring-[#2563EB]/20 border-slate-300">
                            <span class="text-xs text-slate-600 font-medium">Ingat Saya</span>
                        </label>
                    </div>

                    <button 
                        type="submit" 
                        :disabled="isSubmitting"
                        class="w-full py-3.5 px-4 rounded-2xl bg-[#0A1E5E] hover:bg-[#122d78] text-white text-xs sm:text-sm font-bold shadow-md shadow-[#0A1E5E]/20 transition-all active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-2"
                    >
                        <span x-text="isSubmitting ? 'Memproses...' : 'Masuk Sekarang'"></span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>

                    <div class="text-center pt-2">
                        <p class="text-xs text-slate-500 font-medium">
                            Belum punya akun? 
                            <button type="button" @click="authMode = 'register'; errorMessage = ''" class="font-bold text-[#2563EB] hover:underline ml-1">
                                Daftar Gratis
                            </button>
                        </p>
                    </div>
                </form>

                <!-- 2. REGISTER FORM (TANPA OTP, EMAIL + NO ASLI) -->
                <form x-show="authMode === 'register'" @submit.prevent="submitRegister()" class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                        <input 
                            type="text" 
                            x-model="registerData.name" 
                            required 
                            placeholder="Contoh: Bagas Pratama"
                            class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-medium focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/15 transition-all"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email Aktif</label>
                        <input 
                            type="email" 
                            x-model="registerData.email" 
                            required 
                            placeholder="nama@email.com"
                            class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-medium focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/15 transition-all"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP Asli</label>
                        <div class="relative">
                            <input 
                                type="tel" 
                                x-model="registerData.phone" 
                                required 
                                placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-medium focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/15 transition-all"
                            >
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1 font-medium">*Untuk konfirmasi tagihan resmi &amp; update pengerjaan proyek.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                            <input 
                                type="password" 
                                x-model="registerData.password" 
                                required 
                                placeholder="Min. 6 karakter"
                                class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-medium focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/15 transition-all"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi</label>
                            <input 
                                type="password" 
                                x-model="registerData.password_confirmation" 
                                required 
                                placeholder="Ulangi password"
                                class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm font-medium focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/15 transition-all"
                            >
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        :disabled="isSubmitting"
                        class="w-full py-3.5 px-4 rounded-2xl bg-[#0A1E5E] hover:bg-[#122d78] text-white text-xs sm:text-sm font-bold shadow-md shadow-[#0A1E5E]/20 transition-all active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-2 mt-2"
                    >
                        <span x-text="isSubmitting ? 'Mendaftarkan...' : 'Daftar Akun Sekarang'"></span>
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </button>

                    <div class="text-center pt-2">
                        <p class="text-xs text-slate-500 font-medium">
                            Sudah punya akun? 
                            <button type="button" @click="authMode = 'login'; errorMessage = ''" class="font-bold text-[#2563EB] hover:underline ml-1">
                                Masuk di Sini
                            </button>
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Firebase Web SDK Integration Script -->
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-auth-compat.js"></script>

<script>
    const firebaseConfig = {
        apiKey: "{{ config('services.firebase.api_key', '') }}",
        authDomain: "{{ config('services.firebase.auth_domain', '') }}",
        projectId: "{{ config('services.firebase.project_id', '') }}",
        storageBucket: "{{ config('services.firebase.storage_bucket', '') }}",
        messagingSenderId: "{{ config('services.firebase.messaging_sender_id', '') }}",
        appId: "{{ config('services.firebase.app_id', '') }}"
    };
    
    if (!firebase.apps.length) {
        try {
            firebase.initializeApp(firebaseConfig);
        } catch (e) {
            console.warn("Firebase Init notice: ", e.message);
        }
    }

    function authModalComponent() {
        return {
            showAuthModal: false,
            authMode: 'login', // 'login' or 'register'
            authNotice: '',
            errorMessage: '',
            isSubmitting: false,
            isLoadingGoogle: false,
            onSuccessCallback: null,
            loginData: {
                email: '',
                password: '',
                remember: true
            },
            registerData: {
                name: '',
                email: '',
                phone: '',
                password: '',
                password_confirmation: ''
            },
            openModal(opts = {}) {
                this.authMode = opts.mode || 'login';
                this.authNotice = opts.notice || '';
                this.errorMessage = '';
                this.onSuccessCallback = opts.onSuccess || null;
                this.showAuthModal = true;
                this.$nextTick(() => {
                    if (window.lucide) { lucide.createIcons(); }
                });
            },
            submitLogin() {
                this.errorMessage = '';
                this.isSubmitting = true;
                fetch('{{ route("login.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.loginData)
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    this.isSubmitting = false;
                    if (status === 200 && body.success) {
                        this.showAuthModal = false;
                        if (typeof this.onSuccessCallback === 'function') {
                            this.onSuccessCallback(body.user);
                        } else {
                            window.location.reload();
                        }
                    } else {
                        this.errorMessage = body.message || 'Login gagal. Periksa kembali email dan password Anda.';
                    }
                })
                .catch(e => {
                    this.isSubmitting = false;
                    this.errorMessage = 'Terjadi gangguan koneksi. Silakan coba kembali.';
                });
            },
            submitRegister() {
                this.errorMessage = '';
                this.isSubmitting = true;
                fetch('{{ route("register.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.registerData)
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    this.isSubmitting = false;
                    if (status === 200 && body.success) {
                        this.showAuthModal = false;
                        if (typeof this.onSuccessCallback === 'function') {
                            this.onSuccessCallback(body.user);
                        } else {
                            window.location.reload();
                        }
                    } else {
                        this.errorMessage = body.message || Object.values(body.errors || {})[0]?.[0] || 'Pendaftaran gagal.';
                    }
                })
                .catch(e => {
                    this.isSubmitting = false;
                    this.errorMessage = 'Terjadi gangguan koneksi. Silakan coba kembali.';
                });
            },
            signInWithGoogle() {
                this.errorMessage = '';
                this.isLoadingGoogle = true;

                try {
                    const provider = new firebase.auth.GoogleAuthProvider();
                    firebase.auth().signInWithPopup(provider)
                    .then((result) => {
                        const user = result.user;
                        return fetch('{{ route("auth.google-firebase") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                uid: user.uid,
                                name: user.displayName || 'Customer JuangDev',
                                email: user.email,
                                avatar: user.photoURL || '',
                                phone: user.phoneNumber || ''
                            })
                        });
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isLoadingGoogle = false;
                        if (data.success) {
                            this.showAuthModal = false;
                            if (typeof this.onSuccessCallback === 'function') {
                                this.onSuccessCallback(data.user);
                            } else {
                                window.location.reload();
                            }
                        } else {
                            this.errorMessage = data.message || 'Gagal masuk dengan Google.';
                        }
                    })
                    .catch((err) => {
                        this.isLoadingGoogle = false;
                        if (err.code !== 'auth/popup-closed-by-user') {
                            console.error("Firebase Auth:", err);
                            this.errorMessage = 'Google Login: ' + (err.message || 'Gagal menghubungkan Google.');
                        }
                    });
                } catch (e) {
                    this.isLoadingGoogle = false;
                    this.errorMessage = 'Firebase SDK belum siap: ' + e.message;
                }
            }
        };
    }
</script>
