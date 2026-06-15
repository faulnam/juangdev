"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { Mail, Phone, MapPin, ArrowRight } from "lucide-react";

export function ContactHero() {
  return (
    <section className="relative pt-32 pb-48 md:pt-40 md:pb-56 bg-[#0A1E5E] overflow-visible flex items-center justify-center flex-col">
      {/* Glow Effect */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[500px] bg-[#1d4ed8] rounded-full blur-[150px] opacity-30 pointer-events-none" />

      <div className="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <ScrollReveal>
          <h1 className="text-4xl md:text-5xl lg:text-[4rem] font-black text-white leading-tight tracking-tight mb-6">
            Hubungi <span className="text-[#C7F236] font-serif italic">Kami</span>
          </h1>
          <p className="text-white/80 text-[0.95rem] md:text-base max-w-2xl mx-auto leading-relaxed font-medium mb-12">
            Have a big idea you want to realize or questions about our services? Send your message, we're ready to collaborate.
          </p>
        </ScrollReveal>
      </div>

      {/* Overlapping Contact Info Cards */}
      <div className="absolute bottom-0 translate-y-1/2 left-0 right-0 z-20">
        <div className="max-w-6xl mx-auto px-6 sm:px-8">
          <ScrollReveal delay={0.2}>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              
              {/* Email Card */}
              <a href="mailto:halo@juangdev.com" className="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border-2 border-slate-200 border-b-[8px] border-b-slate-300 flex items-start gap-5 hover:-translate-y-2 transition-transform duration-300 group">
                <div className="w-12 h-12 rounded-full bg-[#f0f4fc] text-[#2563EB] flex items-center justify-center shrink-0 group-hover:bg-[#2563EB] group-hover:text-white transition-colors">
                  <Mail className="w-5 h-5" />
                </div>
                <div>
                  <p className="text-slate-400 text-[0.65rem] font-bold uppercase tracking-wider mb-1">Kirim Email</p>
                  <p className="text-[#1a1f3c] font-bold mb-3">halo@juangdev.com</p>
                  <p className="text-[#2563EB] text-xs font-bold flex items-center gap-1">Tulis Email <ArrowRight className="w-3 h-3" /></p>
                </div>
              </a>

              {/* WhatsApp Card */}
              <a href="https://wa.me/6283852174877" target="_blank" rel="noopener noreferrer" className="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border-2 border-slate-200 border-b-[8px] border-b-slate-300 flex items-start gap-5 hover:-translate-y-2 transition-transform duration-300 group">
                <div className="w-12 h-12 rounded-full bg-[#f0f4fc] text-[#2563EB] flex items-center justify-center shrink-0 group-hover:bg-[#2563EB] group-hover:text-white transition-colors">
                  <Phone className="w-5 h-5" />
                </div>
                <div>
                  <p className="text-slate-400 text-[0.65rem] font-bold uppercase tracking-wider mb-1">Mulai Chat WhatsApp</p>
                  <p className="text-[#1a1f3c] font-bold mb-3">+62 838 5217 4877</p>
                  <p className="text-[#2563EB] text-xs font-bold flex items-center gap-1">Kirim Pesan <ArrowRight className="w-3 h-3" /></p>
                </div>
              </a>

              {/* Location Card */}
              <a href="https://maps.google.com/?q=Sidoarjo,JawaTimur" target="_blank" rel="noopener noreferrer" className="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border-2 border-slate-200 border-b-[8px] border-b-slate-300 flex items-start gap-5 hover:-translate-y-2 transition-transform duration-300 group">
                <div className="w-12 h-12 rounded-full bg-[#f0f4fc] text-[#2563EB] flex items-center justify-center shrink-0 group-hover:bg-[#2563EB] group-hover:text-white transition-colors">
                  <MapPin className="w-5 h-5" />
                </div>
                <div>
                  <p className="text-slate-400 text-[0.65rem] font-bold uppercase tracking-wider mb-1">Kunjungi Lokasi</p>
                  <p className="text-[#1a1f3c] font-bold mb-3">Sidoarjo, Jawa Timur</p>
                  <p className="text-[#2563EB] text-xs font-bold flex items-center gap-1">Lihat Peta <ArrowRight className="w-3 h-3" /></p>
                </div>
              </a>

            </div>
          </ScrollReveal>
        </div>
      </div>
    </section>
  );
}
