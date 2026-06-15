"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { Marquee } from "@/components/shared/marquee";
import { CLIENT_LOGOS } from "@/lib/constants";

export function Clients() {
  return (
    <section className="py-12 md:py-16 bg-[#f8f9fc] border-y border-slate-200">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <ScrollReveal>
          <p className="text-center text-sm font-semibold text-[#4f5b7d] uppercase tracking-wider mb-8">
            Trusted by leading companies across Indonesia
          </p>
        </ScrollReveal>

        <Marquee speed={35}>
          {CLIENT_LOGOS.map((logo) => (
            <div
              key={logo}
              className="flex items-center justify-center h-12 px-8 bg-white border border-slate-200 rounded-xl shadow-sm shrink-0"
            >
              <span className="text-sm font-bold text-[#1e2547]/40 whitespace-nowrap">{logo}</span>
            </div>
          ))}
        </Marquee>
      </div>
    </section>
  );
}
