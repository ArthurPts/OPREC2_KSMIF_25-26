<x-layouts.retro :showNav="true" :showFooter="true">
    <x-slot name="title">KSMIF OPREC — Welcome</x-slot>

    <!-- HERO -->
    <header class="relative bg-bg-main">
        <!-- min-h untuk vertikal center; flex kolom + center -->
        <div
            class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 min-h-[75vh] flex flex-col items-center justify-center text-center">

            <!-- Logo (dibesarkan) -->
            <div class="flex justify-center mb-8">
                <img src="{{ asset('images/logo-ksmif.png') }}" alt="KSMIF"
                    class="w-24 h-24 sm:w-28 sm:h-28 md:w-32 md:h-32 object-contain drop-shadow-2xl"
                    onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 200 200%22%3E%3Crect width=%22200%22 height=%22200%22 fill=%22%231a0f30%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22monospace%22 font-size=%2238%22 fill=%22%2300f6ff%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22%3EKSMIF%3C/text%3E%3C/svg%3E';" />
            </div>

            <!-- Title & Tagline (jarak diperlebar) -->
            <h1
                class="font-pixel text-xs text-text-glow pixel-glow uppercase group-hover:text-white transition-colors text-3xl sm:text-4xl md:text-5xl tracking-wide text-white mb-4 md:mb-6">
                KSMIF OPREC
            </h1>
            <p class="font-raleway text-lg sm:text-xl text-gray-300 mb-8 md:mb-10">
                Open Recruitment Game Besar KSMIF Periode 2025/2026
            </p>

            <!-- Socials -->
            <!-- Socials -->
            <div class="mt-2 mb-12">
                <p class="font-lato text-sm text-gray-400 mb-3">Ikuti kami</p>
                <div class="flex items-center justify-center gap-4">
                    <!-- Instagram -->
                    <a href="https://www.instagram.com/ksm_informatika" target="_blank" rel="noopener"
                        aria-label="Instagram"
                        class="group inline-flex items-center justify-center w-10 h-10 rounded-full border border-white/15 text-gray-300 hover:text-white hover:border-white/40 transition">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path
                                d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm5 5a5 5 0 100 10 5 5 0 000-10zm0 2.5a2.5 2.5 0 110 5 2.5 2.5 0 010-5zm5.25-2.65a1.15 1.15 0 100 2.3 1.15 1.15 0 000-2.3z" />
                        </svg>
                        <span class="sr-only">Instagram</span>
                    </a>
                    <!-- Website -->
                    <a href="https://ksmif.org" target="_blank" rel="noopener" aria-label="Website"
                        class="group inline-flex items-center justify-center w-10 h-10 rounded-full border border-white/15 text-gray-300 hover:text-white hover:border-white/40 transition">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                        <span class="sr-only">Website</span>
                    </a>
                </div>
            </div>

            <!-- CTA (opsional, tinggal tambah di sini kalau mau) -->
        </div>
    </header>
</x-layouts.retro>
