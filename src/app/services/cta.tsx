"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { ArrowRight } from "lucide-react";
import { WHATSAPP_URL } from "@/lib/constants";
import Link from "next/link";

export function ServicesCTA() {
  return (
    <section className="py-24 md:py-32 bg-[#0A1E5E] relative overflow-hidden flex items-center justify-center">
      {/* Background Glow */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[500px] bg-[#1d4ed8] rounded-full blur-[150px] opacity-30 pointer-events-none" />

      <div className="max-w-4xl mx-auto px-6 text-center relative z-10">
        <ScrollReveal>
          <h2 className="text-3xl md:text-5xl lg:text-[3.5rem] font-black text-white leading-tight tracking-tight mb-6">
            Siap Meluncurkan Proyek <br className="hidden sm:block" />
            Impian <span className="text-[#C7F236] font-serif italic">Anda?</span>
          </h2>
          <p className="text-white/80 text-[0.95rem] md:text-lg max-w-2xl mx-auto leading-relaxed font-medium mb-10">
            Tim ahli kami siap mendengarkan ide Anda, menganalisis kebutuhan bisnis, dan mewujudkannya menjadi produk digital berkinerja tinggi.
          </p>

          <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a
              href={WHATSAPP_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="w-full sm:w-auto px-8 py-4 bg-[#C7F236] text-[#0A1E5E] font-bold rounded-full text-[0.95rem] hover:bg-[#b5dd2a] transition-all shadow-lg shadow-[#C7F236]/20 flex items-center justify-center gap-2"
            >
              Mulai Konsultasi Gratis
              <ArrowRight className="w-4 h-4" />
            </a>
            <Link
              href="/#portfolio"
              className="w-full sm:w-auto px-8 py-4 bg-white/5 text-white font-bold rounded-full text-[0.95rem] border border-white/20 hover:bg-white/10 transition-all flex items-center justify-center"
            >
              View Portfolio
            </Link>
          </div>
        </ScrollReveal>
      </div>
    </section>
  );
}
