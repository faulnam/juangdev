"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";

export function PortfolioHero() {
  return (
    <section className="relative pt-32 pb-24 md:pt-40 md:pb-32 bg-[#0A1E5E] overflow-hidden flex items-center justify-center">
      {/* Glow Effect */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[500px] bg-[#1d4ed8] rounded-full blur-[150px] opacity-30 pointer-events-none" />

      <div className="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <ScrollReveal>
          <h1 className="text-4xl md:text-5xl lg:text-[4rem] font-black text-white leading-tight tracking-tight mb-6">
            Karya & <span className="text-[#C7F236] font-serif italic">Portofolio</span>
          </h1>
          <p className="text-white/80 text-[0.95rem] md:text-base max-w-2xl mx-auto leading-relaxed font-medium">
            Jelajahi portofolio studi kasus produk digital rancangan kami. Kami memadukan kode berkualitas tinggi dengan pengalaman visual yang memikat.
          </p>
        </ScrollReveal>
      </div>
    </section>
  );
}
