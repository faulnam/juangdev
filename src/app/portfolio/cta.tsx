"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { ArrowRight } from "lucide-react";
import { WHATSAPP_URL } from "@/lib/constants";

export function PortfolioCTA() {
  return (
    <section className="relative -mt-20 md:-mt-24 z-10 max-w-6xl mx-auto px-6 sm:px-8">
      <ScrollReveal>
        <div className="bg-[#0A1E5E] rounded-[2.5rem] p-10 md:p-16 lg:p-20 text-center relative overflow-hidden shadow-2xl">
          {/* Background Glow */}
          <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[400px] bg-[#1d4ed8] rounded-full blur-[120px] opacity-40 pointer-events-none" />
          
          <div className="relative z-10">
            <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-white/20 bg-white/5 backdrop-blur-sm mb-6">
              <span className="text-[#C7F236] text-xs font-bold uppercase tracking-wider">Wujudkan Ide Anda</span>
            </div>
            
            <h2 className="text-3xl md:text-4xl lg:text-5xl font-black text-white leading-tight tracking-tight mb-6 max-w-3xl mx-auto">
              Ingin membuat produk digital berkualitas tinggi seperti di atas?
            </h2>
            
            <p className="text-white/80 text-[0.95rem] md:text-base max-w-xl mx-auto leading-relaxed font-medium mb-10">
              Hubungi kami sekarang untuk berdiskusi langsung mengenai konsep, teknologi, dan biaya estimasi proyek baru Anda.
            </p>
            
            <a
              href={WHATSAPP_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#C7F236] text-[#0A1E5E] font-bold rounded-full text-[0.95rem] hover:bg-[#b5dd2a] transition-all shadow-lg shadow-[#C7F236]/20"
            >
              Mulai Proyek Sekarang
              <ArrowRight className="w-4 h-4" />
            </a>
          </div>
        </div>
      </ScrollReveal>
    </section>
  );
}
