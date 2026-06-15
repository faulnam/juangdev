"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { ArrowUpRight, Palette, CheckCircle2, ChevronRight, LayoutTemplate, Smartphone } from "lucide-react";
import { WHATSAPP_URL } from "@/lib/constants";

export function UiUxService() {
  return (
    <section className="py-20 bg-[#f8f9fc]">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <ScrollReveal>
          <div className="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
              <h2 className="text-3xl md:text-4xl font-black text-[#1a1f3c] leading-tight tracking-tight mb-4">
                Riset & Desain <span className="text-[#2563EB] font-serif italic">UI/UX</span>
              </h2>
              <p className="text-[#64748b] text-[0.95rem] md:text-base leading-relaxed max-w-2xl font-medium">
                Desain yang indah tidak ada artinya tanpa pengalaman pengguna yang baik. Kami menggabungkan estetika visual dengan riset mendalam untuk menciptakan produk yang disukai pengguna.
              </p>
            </div>
            <a
              href={WHATSAPP_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 px-6 py-3 rounded-full text-sm font-bold border-2 border-[#2563EB] text-[#2563EB] hover:bg-[#2563EB] hover:text-white transition-all shrink-0 w-fit"
            >
              Tanya Layanan Ini <ArrowUpRight className="w-4 h-4" />
            </a>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-[1fr_1fr] gap-8">
            
            {/* Left Column */}
            <div className="flex flex-col gap-6">
              {/* Main Info Card */}
              <div className="bg-[#0A1E5E] rounded-3xl p-8 md:p-10 shadow-xl flex-grow">
                <div className="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center mb-6 text-white backdrop-blur-sm border border-white/20">
                  <Palette className="w-6 h-6" />
                </div>
                <h3 className="text-xl font-bold text-white mb-4">UI/UX Research & Prototype</h3>
                <p className="text-white/80 text-[0.95rem] leading-relaxed font-medium mb-10">
                  Kami merancang produk digital yang tidak hanya estetik secara visual, tetapi juga fungsional dan mudah digunakan oleh target audiens Anda. Dari wireframe hingga interaktif prototype.
                </p>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div className="bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-sm">
                    <CheckCircle2 className="w-5 h-5 text-[#C7F236] mb-3" />
                    <h4 className="text-white font-bold text-sm mb-1">User Research</h4>
                    <p className="text-white/60 text-xs">Analisis kebutuhan pengguna & kompetitor.</p>
                  </div>
                  <div className="bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-sm">
                    <CheckCircle2 className="w-5 h-5 text-[#C7F236] mb-3" />
                    <h4 className="text-white font-bold text-sm mb-1">Prototyping</h4>
                    <p className="text-white/60 text-xs">Simulasi interaktif sebelum development.</p>
                  </div>
                </div>
              </div>

              {/* Price CTA Card */}
              <div className="bg-[#2563EB] rounded-3xl p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6 shadow-xl shadow-[#2563EB]/20">
                <div className="flex items-center gap-5">
                  <div className="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white shrink-0">
                    <span className="font-bold text-lg">Rp</span>
                  </div>
                  <div>
                    <p className="text-white/70 text-xs font-bold uppercase tracking-wider mb-1">Harga Mulai Dari</p>
                    <p className="text-2xl font-black text-white">499.000</p>
                  </div>
                </div>
                <button className="px-8 py-3.5 bg-white text-[#2563EB] font-bold rounded-full text-sm hover:bg-slate-50 transition-all shadow-md shrink-0">
                  Pelajari Detail
                </button>
              </div>
            </div>

            {/* Right Column: Mockups */}
            <div className="flex flex-col gap-6">
              {/* Dashboard Mockup */}
              <div className="bg-[#C7F236] rounded-3xl p-8 shadow-xl relative overflow-hidden flex-grow flex flex-col">
                <div className="absolute top-0 right-0 w-64 h-64 bg-white/20 rounded-full blur-[80px]" />
                
                <div className="flex items-center gap-3 mb-6 relative z-10">
                  <div className="w-10 h-10 rounded-full bg-white/50 flex items-center justify-center">
                    <LayoutTemplate className="w-5 h-5 text-[#0A1E5E]" />
                  </div>
                  <div>
                    <h3 className="text-[#0A1E5E] font-black text-lg">Dashboard Interface</h3>
                    <p className="text-[#0A1E5E]/70 text-xs font-bold uppercase tracking-wider">Web App Design</p>
                  </div>
                </div>

                <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex-grow relative z-10 flex flex-col justify-center">
                  {/* Fake UI Elements */}
                  <div className="flex items-center justify-between mb-8">
                    <div className="w-32 h-4 bg-slate-200 rounded-full"></div>
                    <div className="flex gap-2">
                      <div className="w-8 h-8 rounded-full bg-slate-100"></div>
                      <div className="w-8 h-8 rounded-full bg-slate-100"></div>
                    </div>
                  </div>
                  <div className="grid grid-cols-3 gap-4 mb-6">
                    <div className="h-20 bg-slate-50 border border-slate-100 rounded-xl p-3 flex flex-col justify-end">
                      <div className="w-12 h-2 bg-slate-200 rounded-full mb-2"></div>
                      <div className="w-20 h-4 bg-slate-300 rounded-full"></div>
                    </div>
                    <div className="h-20 bg-[#2563EB]/5 border border-[#2563EB]/20 rounded-xl p-3 flex flex-col justify-end">
                      <div className="w-12 h-2 bg-[#2563EB]/40 rounded-full mb-2"></div>
                      <div className="w-20 h-4 bg-[#2563EB] rounded-full"></div>
                    </div>
                    <div className="h-20 bg-slate-50 border border-slate-100 rounded-xl p-3 flex flex-col justify-end">
                      <div className="w-12 h-2 bg-slate-200 rounded-full mb-2"></div>
                      <div className="w-20 h-4 bg-slate-300 rounded-full"></div>
                    </div>
                  </div>
                  <div className="h-24 bg-slate-50 border border-slate-100 rounded-xl w-full"></div>
                </div>
              </div>

              {/* Mobile App Mockup */}
              <div className="bg-white border-2 border-slate-100 rounded-3xl p-8 shadow-sm flex items-center justify-between">
                <div className="flex items-center gap-5">
                  <div className="w-14 h-14 rounded-full bg-[#f0f4fc] flex items-center justify-center text-[#2563EB]">
                    <Smartphone className="w-6 h-6" />
                  </div>
                  <div>
                    <h3 className="text-[#1a1f3c] font-bold text-[1.1rem] mb-1">Mobile App UI</h3>
                    <p className="text-slate-500 text-sm font-medium">Desain antarmuka iOS & Android</p>
                  </div>
                </div>
                <div className="w-10 h-10 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400">
                  <ChevronRight className="w-5 h-5" />
                </div>
              </div>
            </div>

          </div>
        </ScrollReveal>
      </div>
    </section>
  );
}
