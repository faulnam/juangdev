"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";

const guarantees = [
  {
    title: "100%",
    subtitle: "GARANSI HANDOVER",
    theme: "dark",
  },
  {
    title: "21-30 Hari",
    subtitle: "MASA KERJA STANDARD",
    theme: "lime",
  },
  {
    title: "Gratis",
    subtitle: "REVISI 2 KALI",
    theme: "light",
  },
  {
    title: "Respon Cepat",
    subtitle: "DUKUNGAN SIAGA",
    theme: "dark",
  },
];

export function PortfolioGuarantees() {
  return (
    <section className="relative z-20 max-w-6xl mx-auto px-6 sm:px-8 pb-32 pt-10">
      <ScrollReveal>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
          {guarantees.map((item, i) => (
            <div
              key={i}
              className={`rounded-2xl p-6 text-center flex flex-col items-center justify-center min-h-[140px] shadow-xl transition-transform hover:-translate-y-1 ${
                item.theme === "dark"
                  ? "bg-[#0A1E5E] text-white shadow-[#0A1E5E]/20"
                  : item.theme === "lime"
                  ? "bg-[#C7F236] text-[#0A1E5E] shadow-[#C7F236]/20"
                  : "bg-white border-2 border-slate-100 text-[#2563EB] shadow-slate-200/50"
              }`}
            >
              <h3 className={`text-2xl md:text-3xl font-black mb-1 ${item.theme === "light" ? "text-[#2563EB]" : "text-current"}`}>
                {item.title}
              </h3>
              <p className={`text-[0.65rem] font-bold tracking-widest uppercase ${item.theme === "light" ? "text-slate-500" : "text-current opacity-80"}`}>
                {item.subtitle}
              </p>
            </div>
          ))}
        </div>
      </ScrollReveal>
    </section>
  );
}
