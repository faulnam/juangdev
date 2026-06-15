"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { ArrowRight } from "lucide-react";
import { WHATSAPP_URL } from "@/lib/constants";

const mobileFeatures = [
  {
    title: "User-Friendly Interface",
    description: "Desain UI/UX yang intuitif dan mudah digunakan oleh pengguna dari berbagai kalangan.",
  },
  {
    title: "Performa Cepat & Stabil",
    description: "Dibangun dengan framework modern (Flutter/React Native) untuk menjamin aplikasi berjalan mulus.",
  },
  {
    title: "Keamanan Data Terjamin",
    description: "Sistem enkripsi dan autentikasi berlapis untuk melindungi data pengguna dan bisnis Anda.",
  },
  {
    title: "Push Notification Aktif",
    description: "Tingkatkan engagement dengan mengirim notifikasi langsung ke smartphone pengguna.",
  },
];

export function MobileService() {
  return (
    <section className="py-20 bg-white">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <ScrollReveal>
          <div className="mb-10">
            <h2 className="text-3xl md:text-4xl font-black text-[#1a1f3c] leading-tight tracking-tight mb-4">
              Pembuatan Aplikasi <span className="text-[#2563EB] font-serif italic">Mobile</span>
            </h2>
            <p className="text-[#64748b] text-[0.95rem] md:text-base leading-relaxed max-w-2xl font-medium">
              Perluas jangkauan bisnis Anda ke jutaan pengguna smartphone dengan aplikasi mobile Android & iOS yang cepat, aman, dan mudah digunakan.
            </p>
          </div>

          <div className="bg-[#0A1E5E] rounded-[2rem] p-8 md:p-12 shadow-2xl shadow-[#0A1E5E]/20 relative overflow-hidden border-2 border-[#0A1E5E] border-b-[8px] border-b-[#05113A]">
            {/* Glow */}
            <div className="absolute bottom-0 left-0 w-[500px] h-[500px] bg-[#1d4ed8] rounded-full blur-[120px] opacity-20 translate-y-1/2 -translate-x-1/3 pointer-events-none" />

            <div className="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-12 lg:gap-20 relative z-10">
              
              {/* Left Content: Features list */}
              <div className="flex flex-col justify-between">
                <div className="space-y-6 mb-12">
                  {mobileFeatures.map((feature, i) => (
                    <div key={i} className="flex gap-5">
                      <div className="w-8 h-8 rounded-full bg-[#C7F236] text-[#0A1E5E] flex items-center justify-center font-black text-sm shrink-0 shadow-lg shadow-[#C7F236]/20">
                        {i + 1}
                      </div>
                      <div>
                        <h4 className="text-white font-bold text-base mb-1.5">{feature.title}</h4>
                        <p className="text-white/70 text-[0.85rem] leading-relaxed max-w-md">
                          {feature.description}
                        </p>
                      </div>
                    </div>
                  ))}
                </div>

                <div className="flex flex-wrap items-center justify-between gap-6 border-t border-white/10 pt-8">
                  <div>
                    <p className="text-white/50 text-xs font-bold uppercase tracking-wider mb-2">Estimasi Biaya Mulai</p>
                    <p className="text-3xl font-black text-[#C7F236]">Rp 7.499.000</p>
                  </div>
                  
                  <div className="flex items-center gap-4">
                    <a
                      href={WHATSAPP_URL}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="px-8 py-3.5 bg-[#C7F236] text-[#0A1E5E] font-bold rounded-full text-sm hover:bg-[#b5dd2a] transition-all shadow-lg shadow-[#C7F236]/20"
                    >
                      Mulai Proyek Ini
                    </a>
                    <button className="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition-all">
                      <ArrowRight className="w-5 h-5" />
                    </button>
                  </div>
                </div>
              </div>

              {/* Right Content: Phone Mockup Simulation */}
              <div className="hidden lg:flex items-center justify-center relative">
                {/* Decorative circles behind phone */}
                <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] rounded-full border border-white/10"></div>
                <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] rounded-full border border-white/5"></div>
                
                {/* Phone Frame */}
                <div className="w-[280px] h-[580px] bg-white rounded-[3rem] p-2.5 shadow-2xl relative z-10">
                  <div className="w-full h-full bg-[#f8f9fc] rounded-[2.5rem] relative overflow-hidden border border-slate-100 flex flex-col">
                    
                    {/* Dynamic Island / Notch */}
                    <div className="absolute top-0 inset-x-0 h-7 flex justify-center z-20">
                      <div className="w-24 h-5 bg-black rounded-b-2xl"></div>
                    </div>

                    {/* Fake App Content */}
                    <div className="pt-12 px-6 pb-6 flex-grow flex flex-col">
                      <div className="flex items-center justify-between mb-8">
                        <div>
                          <p className="text-[0.65rem] text-slate-500 font-bold uppercase tracking-wider mb-1">Total Balance</p>
                          <h3 className="text-2xl font-black text-[#1a1f3c]">$12,450.00</h3>
                        </div>
                        <div className="w-10 h-10 rounded-full bg-slate-200 border-2 border-white shadow-sm overflow-hidden">
                          <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" alt="avatar" className="w-full h-full object-cover" />
                        </div>
                      </div>

                      <div className="bg-[#2563EB] rounded-2xl p-5 text-white shadow-lg shadow-[#2563EB]/30 mb-6 relative overflow-hidden">
                        <div className="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl" />
                        <p className="text-white/80 text-xs mb-1 font-medium">Virtual Card</p>
                        <p className="font-mono text-sm tracking-widest mb-4">**** **** **** 4281</p>
                        <div className="flex justify-between items-end">
                          <p className="text-xs font-bold uppercase">John Doe</p>
                          <div className="w-8 h-5 bg-white/20 rounded flex items-center justify-center"><span className="text-[0.5rem] font-bold">VISA</span></div>
                        </div>
                      </div>

                      <div className="flex-grow">
                        <h4 className="text-[#1a1f3c] font-bold text-sm mb-4">Recent Transactions</h4>
                        <div className="space-y-4">
                          {[1, 2, 3].map((_, idx) => (
                            <div key={idx} className="flex items-center justify-between">
                              <div className="flex items-center gap-3">
                                <div className="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                                  <div className="w-5 h-5 rounded-full border-2 border-slate-300"></div>
                                </div>
                                <div>
                                  <p className="text-sm font-bold text-[#1a1f3c]">Netflix Subs</p>
                                  <p className="text-[0.65rem] text-slate-500">Today, 10:00 AM</p>
                                </div>
                              </div>
                              <p className="text-sm font-bold text-[#1a1f3c]">-$15.99</p>
                            </div>
                          ))}
                        </div>
                      </div>
                    </div>

                    {/* Bottom Nav Bar */}
                    <div className="h-16 bg-white border-t border-slate-100 flex items-center justify-around px-4">
                      <div className="w-10 h-10 rounded-full bg-[#2563EB]/10 flex items-center justify-center text-[#2563EB]"><span className="w-4 h-4 bg-current rounded-sm"></span></div>
                      <div className="w-10 h-10 rounded-full flex items-center justify-center text-slate-400"><span className="w-4 h-4 bg-current rounded-sm"></span></div>
                      <div className="w-10 h-10 rounded-full flex items-center justify-center text-slate-400"><span className="w-4 h-4 border-2 border-current rounded-full"></span></div>
                    </div>

                  </div>
                </div>
              </div>

            </div>
          </div>
        </ScrollReveal>
      </div>
    </section>
  );
}
