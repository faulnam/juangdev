"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { SectionHeader } from "@/components/shared/section-header";
import { INDUSTRIES } from "@/lib/constants";
import { Heart, GraduationCap, ShoppingCart, Factory, Truck, Landmark, Building2, UtensilsCrossed } from "lucide-react";

const iconMap: Record<string, React.ReactNode> = {
  Heart: <Heart className="w-5 h-5" />,
  GraduationCap: <GraduationCap className="w-5 h-5" />,
  ShoppingCart: <ShoppingCart className="w-5 h-5" />,
  Factory: <Factory className="w-5 h-5" />,
  Truck: <Truck className="w-5 h-5" />,
  Landmark: <Landmark className="w-5 h-5" />,
  Building2: <Building2 className="w-5 h-5" />,
  UtensilsCrossed: <UtensilsCrossed className="w-5 h-5" />,
};

export function Industries() {
  return (
    <section className="py-16 md:py-24 lg:py-28 bg-white">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <ScrollReveal>
          <SectionHeader
            title="Industries"
            highlight="We Serve"
            description="Our solutions span across diverse industries, each tailored to sector-specific requirements and challenges."
          />
        </ScrollReveal>

        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
          {INDUSTRIES.map((industry, i) => (
            <ScrollReveal key={industry.name} delay={i * 0.06}>
              <div className="group bg-white border-2 border-slate-200 border-b-[5px] border-b-slate-300 rounded-2xl p-5 sm:p-6 shadow-md hover:scale-[1.03] hover:shadow-lg hover:border-[#2563EB]/30 transition-all duration-300 text-center h-full flex flex-col items-center gap-3">
                <div className="w-12 h-12 rounded-xl bg-[#2563EB]/10 text-[#2563EB] flex items-center justify-center group-hover:bg-[#2563EB] group-hover:text-white transition-all duration-300">
                  {iconMap[industry.icon]}
                </div>
                <h3 className="text-sm sm:text-base font-bold text-[#1e2547]">{industry.name}</h3>
                <p className="text-xs text-[#4f5b7d] leading-relaxed hidden sm:block">{industry.description}</p>
              </div>
            </ScrollReveal>
          ))}
        </div>
      </div>
    </section>
  );
}
