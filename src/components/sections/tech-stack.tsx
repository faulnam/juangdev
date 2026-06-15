"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { SectionHeader } from "@/components/shared/section-header";
import { TECH_STACK } from "@/lib/constants";

export function TechStack() {
  return (
    <section className="py-16 md:py-24 lg:py-28 bg-[#f8f9fc] relative overflow-hidden">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <ScrollReveal>
          <SectionHeader
            title="Technologies"
            highlight="We Use"
            description="We leverage modern, battle-tested technologies to build performant, scalable, and maintainable solutions."
          />
        </ScrollReveal>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
          {Object.entries(TECH_STACK).map(([category, techs], ci) => (
            <ScrollReveal key={category} delay={ci * 0.1}>
              <div className="bg-white border-2 border-slate-200 border-b-[6px] border-b-slate-300 rounded-2xl p-6 shadow-lg h-full">
                <h3 className="text-sm font-bold text-[#2563EB] uppercase tracking-wider mb-4">{category}</h3>
                <div className="flex flex-col gap-3">
                  {techs.map((tech) => (
                    <div
                      key={tech.name}
                      className="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-100 hover:bg-[#2563EB]/5 hover:border-[#2563EB]/20 transition-all duration-200 group"
                    >
                      <div className="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm group-hover:shadow-md transition-shadow">
                        <span className="text-xs font-bold text-[#1e2547]">{tech.name.slice(0, 2)}</span>
                      </div>
                      <span className="text-sm font-semibold text-[#1e2547]">{tech.name}</span>
                    </div>
                  ))}
                </div>
              </div>
            </ScrollReveal>
          ))}
        </div>
      </div>
    </section>
  );
}
