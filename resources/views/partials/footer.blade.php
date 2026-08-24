@php
    $whatsappNumber = $settings['whatsapp_number'] ?? '6283852174877';
    $whatsappMsg = urlencode("Halo JuangDev, saya ingin berkonsultasi mengenai pembuatan website/aplikasi.");
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}";
    $email = $settings['email'] ?? 'hello@juangdev.com';
    $phone = $settings['phone'] ?? '+62 812-3456-7890';
    $address = $settings['address'] ?? 'Jakarta, Indonesia';

    // Social media URLs from settings
    $instagram = $settings['instagram_url'] ?? '';
    $xTwitter = $settings['x_url'] ?? '';
    $threads = $settings['threads_url'] ?? '';
    $github = $settings['github_url'] ?? '';
    $linkedin = $settings['linkedin_url'] ?? '';
    $tiktok = $settings['tiktok_url'] ?? '';
@endphp

<footer class="bg-[#0A1E5E] text-white pt-16 pb-12 border-t border-white/10">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-10 sm:gap-8 lg:gap-8 pb-12 border-b border-white/10">
            
            <!-- Col 1: Brand & Bio -->
            <div class="sm:col-span-2 lg:col-span-2 flex flex-col items-start">
                <a href="{{ route('home') }}" class="inline-block mb-5 group" aria-label="JuangDev — Beranda">
                    <img 
                        src="{{ asset('logo4.png') }}?v={{ filemtime(public_path('logo4.png')) }}" 
                        alt="JuangDev" 
                        loading="lazy"
                        decoding="async"
                        class="h-20 sm:h-24 md:h-28 lg:h-32 w-auto object-contain transition-transform duration-300 group-hover:scale-105"
                    >
                </a>
                
                <p class="text-white/70 text-sm leading-relaxed max-w-sm mb-6 font-medium">
                    JuangDev adalah studio teknologi kreatif yang berfokus pada pembuatan website berkonversi tinggi, aplikasi web modern, dan solusi digital kustom untuk memajukan bisnis Anda.
                </p>

                <!-- Social Icons -->
                <div class="flex items-center gap-2.5">
                    @if($instagram)
                        <a href="{{ $instagram }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-white/10 hover:bg-[#C7F236] hover:text-[#0A1E5E] text-white flex items-center justify-center transition-all duration-200 hover:scale-105" aria-label="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    @endif

                    @if($xTwitter)
                        <a href="{{ $xTwitter }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-white/10 hover:bg-[#C7F236] hover:text-[#0A1E5E] text-white flex items-center justify-center transition-all duration-200 hover:scale-105" aria-label="X / Twitter">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                    @endif

                    @if($threads)
                        <a href="{{ $threads }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-white/10 hover:bg-[#C7F236] hover:text-[#0A1E5E] text-white flex items-center justify-center transition-all duration-200 hover:scale-105" aria-label="Threads">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.186 24h-.007c-3.581-.024-6.334-1.205-8.184-3.509C2.35 18.44 1.5 15.586 1.472 12.01v-.017c.03-3.579.879-6.43 2.525-8.482C5.845 1.205 8.6.024 12.18 0h.014c2.746.02 5.043.725 6.826 2.098 1.677 1.29 2.858 3.13 3.509 5.467l-2.04.569c-1.104-3.96-3.898-5.984-8.304-6.015-2.91.022-5.11.936-6.54 2.717C4.307 6.504 3.616 8.914 3.59 12c.025 3.086.718 5.496 2.057 7.164 1.432 1.783 3.631 2.698 6.54 2.717 2.623-.02 4.358-.631 5.8-2.045 1.647-1.613 1.618-3.593 1.09-4.798-.31-.71-.873-1.3-1.634-1.75-.192 1.352-.622 2.446-1.278 3.272-.866 1.089-2.11 1.68-3.694 1.757-1.199.058-2.305-.243-3.113-.847-.934-.698-1.47-1.738-1.508-2.926-.062-1.93 1.39-3.56 3.888-4.363 1.373-.441 2.933-.614 4.64-.516.48-2.537-.142-4.28-1.85-5.2-.925-.498-2.093-.734-3.356-.68-1.52.067-2.8.535-3.695 1.353-.875.8-1.38 1.886-1.5 3.225l-2.105-.215c.165-1.834.854-3.36 2.049-4.452C8.11 2.216 9.758 1.611 11.664 1.527c1.677-.073 3.236.244 4.49.916 2.503 1.34 3.385 3.81 2.82 7.143.867.432 1.6 1.013 2.182 1.724.834 1.02 1.293 2.292 1.327 3.678.042 1.693-.398 3.278-1.27 4.578C19.8 21.372 17.5 23.045 14.1 23.847c-.633.149-1.28.153-1.914.153zm1.515-8.69c-.328 0-.664.013-1.008.04-1.637.158-2.645.886-2.612 1.888.017.533.247.984.664 1.296.468.35 1.127.516 1.852.483 1.058-.05 1.877-.453 2.436-1.198.42-.56.72-1.3.87-2.194-.712-.208-1.46-.315-2.202-.315z"/>
                            </svg>
                        </a>
                    @endif

                    @if($github)
                        <a href="{{ $github }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-white/10 hover:bg-[#C7F236] hover:text-[#0A1E5E] text-white flex items-center justify-center transition-all duration-200 hover:scale-105" aria-label="GitHub">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                            </svg>
                        </a>
                    @endif

                    @if($linkedin)
                        <a href="{{ $linkedin }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-white/10 hover:bg-[#C7F236] hover:text-[#0A1E5E] text-white flex items-center justify-center transition-all duration-200 hover:scale-105" aria-label="LinkedIn">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.45a1.62 1.62 0 1 0 0 3.24 1.62 1.62 0 0 0 0-3.24z"/>
                            </svg>
                        </a>
                    @endif

                    @if($tiktok)
                        <a href="{{ $tiktok }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-white/10 hover:bg-[#C7F236] hover:text-[#0A1E5E] text-white flex items-center justify-center transition-all duration-200 hover:scale-105" aria-label="TikTok">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.88-2.88 2.89 2.89 0 0 1 2.88-2.88c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 0 0-.79-.05A6.34 6.34 0 0 0 3.15 15.2a6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V8.76a8.26 8.26 0 0 0 4.82 1.56V6.88a4.84 4.84 0 0 1-1.06-.19z"/>
                            </svg>
                        </a>
                    @endif

                    <!-- WhatsApp Icon -->
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-[#C7F236] text-[#0A1E5E] flex items-center justify-center transition-all duration-200 font-bold hover:scale-110 shadow-md shadow-[#C7F236]/20" aria-label="WhatsApp">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Col 2: Services Links -->
            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Layanan Kami</h4>
                <ul class="space-y-2.5 text-sm text-white/70">
                    <li><a href="{{ route('services') }}" class="hover:text-[#C7F236] transition-colors">Landing Page</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-[#C7F236] transition-colors">Company Profile</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-[#C7F236] transition-colors">E-Commerce</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-[#C7F236] transition-colors">Sistem Informasi</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-[#C7F236] transition-colors">Custom Web App</a></li>
                </ul>
            </div>

            <!-- Col 3: Quick Navigation -->
            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Navigasi</h4>
                <ul class="space-y-2.5 text-sm text-white/70">
                    <li><a href="{{ route('home') }}#about" class="hover:text-[#C7F236] transition-colors">Tentang Kami</a></li>
                    <li><a href="{{ route('portfolio') }}" class="hover:text-[#C7F236] transition-colors">Portofolio</a></li>
                    <li><a href="{{ route('home') }}#pricing" class="hover:text-[#C7F236] transition-colors">Paket Harga</a></li>
                    <li><a href="{{ route('blog') }}" class="hover:text-[#C7F236] transition-colors">Blog &amp; Artikel</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[#C7F236] transition-colors">Kontak</a></li>
                </ul>
            </div>

            <!-- Col 4: Contact & Office -->
            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Kontak Kami</h4>
                <ul class="space-y-3 text-sm text-white/70">
                    <li class="flex items-start gap-2.5">
                        <i data-lucide="mail" class="w-4 h-4 text-[#C7F236] shrink-0 mt-0.5"></i>
                        <span>{{ $email }}</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <i data-lucide="phone" class="w-4 h-4 text-[#C7F236] shrink-0 mt-0.5"></i>
                        <span>{{ $phone }}</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <i data-lucide="map-pin" class="w-4 h-4 text-[#C7F236] shrink-0 mt-0.5"></i>
                        <span>{{ $address }}</span>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Copyright -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-white/60">
            <p>&copy; {{ date('Y') }} JuangDev. Hak Cipta Dilindungi Undang-Undang.</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.login') }}" class="text-white/40 hover:text-white/80 transition-colors">Panel Admin</a>
                <a href="#" class="hover:text-[#C7F236] transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-[#C7F236] transition-colors">Syarat &amp; Ketentuan</a>
            </div>
        </div>
    </div>
</footer>
