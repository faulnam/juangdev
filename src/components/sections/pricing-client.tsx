"use client";

import { useState, useMemo } from "react";
import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { SectionHeader } from "@/components/shared/section-header";
import { Check, ArrowUpRight } from "lucide-react";
import { WHATSAPP_URL } from "@/lib/constants";

type Plan = {
  id: number;
  name: string;
  category: string;
  price: string;
  period: string | null;
  description: string;
  features: string;
  isPopular: boolean;
};

export function PricingClient({ plans }: { plans: Plan[] }) {
  const categories = useMemo(() => {
    const cats = Array.from(new Set(plans.map(p => p.category)));
    // Optional: Sort categories if needed, but keeping the insertion order is usually fine.
    return cats;
  }, [plans]);

  const [activeCategory, setActiveCategory] = useState(categories[0] || "");

  const activePlans = useMemo(() => {
    return plans.filter(p => p.category === activeCategory);
  }, [plans, activeCategory]);

  if (categories.length === 0) return null;

  return (
    <section id="pricing" className="py-16 md:py-24 lg:py-28 bg-[#f8f9fc]">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <ScrollReveal>
          <SectionHeader
            title="Paket & Investasi Terbaik Untuk"
            highlight="Bisnis Anda"
            description="Harga transparan, tanpa biaya tersembunyi. Pilih paket yang paling sesuai dengan kebutuhan bisnis Anda."
          />
        </ScrollReveal>

        {/* Categories Filter / Tabs */}
        <ScrollReveal delay={0.1}>
          <div className="flex overflow-x-auto gap-4 pb-8 mb-4 justify-start lg:justify-center snap-x snap-mandatory scroll-smooth [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none] px-1 pt-2">
            {categories.map((cat) => {
              const isActive = activeCategory === cat;
              return (
                <button
                  key={cat}
                  onClick={() => setActiveCategory(cat)}
                  className={`px-6 py-2.5 rounded-xl font-bold text-[0.95rem] transition-all duration-200 whitespace-nowrap shrink-0 snap-start
                    ${isActive 
                      ? "bg-[#2563EB] text-white border-2 border-[#2563EB] shadow-[0_4px_0_#1e3a8a] -translate-y-1" 
                      : "bg-white text-[#4f5b7d] border-2 border-slate-200 shadow-[0_4px_0_#e2e8f0] hover:border-[#2563EB] hover:text-[#2563EB] hover:shadow-[0_4px_0_#2563EB] hover:-translate-y-1"
                    }
                    active:shadow-none active:translate-y-[3px]
                  `}
                >
                  {cat}
                </button>
              );
            })}
          </div>
        </ScrollReveal>

        {/* Pricing Cards Grid */}
        <div className="flex overflow-x-auto gap-6 pb-6 snap-x snap-mandatory scroll-smooth [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none] -mx-6 md:grid md:grid-cols-3 md:gap-8 md:items-stretch md:mx-0">
          <div className="w-4 shrink-0 snap-start md:hidden" />

          {activePlans.map((plan, i) => {
            const isPopular = plan.isPopular;
            const featureList = plan.features.split(",").map(f => f.trim()).filter(Boolean);

            return (
              <ScrollReveal
                key={`${activeCategory}-${plan.name}`}
                delay={i * 0.1}
                className="w-[82vw] sm:w-[350px] md:w-auto shrink-0 snap-start snap-always scroll-ml-4 md:scroll-ml-0"
              >
                <div
                  className={`flex flex-col rounded-[2rem] overflow-hidden relative transition-all duration-300 h-full hover:-translate-y-2 ${
                    isPopular
                      ? "bg-gradient-to-br from-[#c8f135] to-[#a3c922] border-2 border-[#a3c922] border-b-[8px] border-b-[#82a313] shadow-2xl shadow-[#C7F236]/20 scale-[1.02] md:scale-105"
                      : "bg-white border-2 border-slate-200 border-b-[8px] border-b-slate-300 shadow-xl shadow-slate-200/50"
                  }`}
                >
                  {isPopular && (
                    <div className="absolute top-5 right-5">
                      <span className="bg-[#0A1E5E] text-white text-[0.65rem] font-black uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-lg">
                        Paling Populer
                      </span>
                    </div>
                  )}

                  {/* Header */}
                  <div className={`px-8 pt-9 pb-7 border-b-2 ${isPopular ? "border-[#0A1E5E]/10" : "border-slate-100"}`}>
                    <h4 className={`text-2xl font-black mb-2 ${isPopular ? "text-[#0A1E5E]" : "text-[#1a1f3c]"}`}>
                      {plan.name}
                    </h4>
                    <p className={`text-[0.85rem] leading-relaxed pr-8 font-medium ${isPopular ? "text-[#0A1E5E]/70" : "text-[#4f5b7d]"}`}>
                      {plan.description}
                    </p>
                    <div className="mt-6 flex items-baseline gap-1.5">
                      <span className={`text-[2rem] sm:text-[2.5rem] font-black tracking-tight leading-none ${isPopular ? "text-[#0A1E5E]" : "text-[#1a1f3c]"}`}>
                        <span className="text-xl font-bold mr-1">Rp</span>{plan.price}
                      </span>
                      {plan.period && (
                        <span className={`text-sm font-semibold ${isPopular ? "text-[#0A1E5E]/60" : "text-slate-500"}`}>
                          {plan.period}
                        </span>
                      )}
                    </div>
                  </div>

                  {/* Features */}
                  <div className="px-8 py-7 flex-1 flex flex-col bg-gradient-to-b from-transparent to-black/[0.02]">
                    <ul className="flex flex-col gap-3.5 mb-8 flex-1">
                      {featureList.map((feature) => (
                        <li key={feature} className="flex items-start gap-3.5">
                          <div className={`w-[1.125rem] h-[1.125rem] rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 ${isPopular ? "bg-[#0A1E5E]" : "bg-[#2563EB]/10"}`}>
                            <Check className={`w-3 h-3 ${isPopular ? "text-[#C7F236]" : "text-[#2563EB]"}`} strokeWidth={3} />
                          </div>
                          <span className={`text-[0.9rem] font-semibold leading-snug ${isPopular ? "text-[#0A1E5E]/85" : "text-slate-700"}`}>
                            {feature}
                          </span>
                        </li>
                      ))}
                    </ul>

                    <a
                      href={WHATSAPP_URL}
                      target="_blank"
                      rel="noopener noreferrer"
                      className={`inline-flex items-center justify-center gap-2 rounded-full py-4 text-[0.9rem] font-bold transition-all duration-300 group w-full ${
                        isPopular
                          ? "bg-[#0A1E5E] text-white hover:bg-[#071542] shadow-xl shadow-[#0A1E5E]/20"
                          : "bg-white border-2 border-slate-200 text-[#1a1f3c] hover:border-[#2563EB] hover:text-[#2563EB] shadow-md"
                      }`}
                    >
                      Pilih Paket Ini
                      <ArrowUpRight className="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" strokeWidth={2.5} />
                    </a>
                  </div>
                </div>
              </ScrollReveal>
            );
          })}

          <div className="w-4 shrink-0 md:hidden" />
        </div>
      </div>
    </section>
  );
}
