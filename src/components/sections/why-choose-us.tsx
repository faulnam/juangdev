"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { SectionHeader } from "@/components/shared/section-header";
import { WHY_CHOOSE_US } from "@/lib/constants";
import { Users, Zap, Layers, BadgeDollarSign, Cpu, Headphones } from "lucide-react";

const iconMap: Record<string, React.ReactNode> = {
  Users: <Users className="w-6 h-6" />,
  Zap: <Zap className="w-6 h-6" />,
  Layers: <Layers className="w-6 h-6" />,
  BadgeDollarSign: <BadgeDollarSign className="w-6 h-6" />,
  Cpu: <Cpu className="w-6 h-6" />,
  HeadphonesIcon: <Headphones className="w-6 h-6" />,
};

export function WhyChooseUs() {
  return (
    <section className="py-16 md:py-24 lg:py-28 bg-white relative overflow-hidden">
      <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] rounded-full bg-[#2563EB]/3 blur-[120px] pointer-events-none" />

      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 relative">
        <ScrollReveal>
          <SectionHeader
            title="Why Choose"
            highlight="JuangDev"
            description="We combine technical excellence with deep business understanding to deliver solutions that truly make a difference."
          />
        </ScrollReveal>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {WHY_CHOOSE_US.map((item, i) => (
            <ScrollReveal key={item.title} delay={i * 0.08}>
              <div className="group relative bg-white border-2 border-slate-200 border-b-[6px] border-b-slate-300 rounded-2xl p-6 sm:p-8 shadow-lg hover:scale-[1.02] hover:border-[#2563EB]/30 hover:border-b-[#2563EB]/40 transition-all duration-300 h-full">
                {/* Gradient overlay on hover */}
                <div className="absolute inset-0 rounded-2xl bg-gradient-to-br from-[#2563EB]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none" />
                <div className="relative">
                  <div className="w-12 h-12 rounded-xl bg-[#2563EB]/10 text-[#2563EB] flex items-center justify-center mb-4 group-hover:bg-[#2563EB] group-hover:text-white transition-all duration-300">
                    {iconMap[item.icon]}
                  </div>
                  <h3 className="text-lg font-bold text-[#1e2547] mb-2">{item.title}</h3>
                  <p className="text-[#4f5b7d] text-sm leading-relaxed">{item.description}</p>
                </div>
              </div>
            </ScrollReveal>
          ))}
        </div>
      </div>
    </section>
  );
}
