"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { ArrowUpRight } from "lucide-react";
import { WHATSAPP_URL } from "@/lib/constants";

export function CtaBanner() {
  return (
    <section className="py-12 md:py-16 bg-gradient-to-r from-[#2563EB] to-[#0A1E5E] relative overflow-hidden">
      <div className="absolute inset-0 pointer-events-none">
        <div className="absolute top-0 right-0 w-96 h-96 rounded-full bg-[#C7F236]/10 blur-[100px]" />
        <div className="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-white/5 blur-[80px]" />
      </div>

      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 relative">
        <ScrollReveal>
          <div className="flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
              <h3 className="text-2xl md:text-3xl font-bold text-white mb-2">
                Have a project in mind?
              </h3>
              <p className="text-white/70 text-sm md:text-base">
                Let&apos;s turn your idea into a powerful digital solution. Free consultation available.
              </p>
            </div>
            <a
              href={WHATSAPP_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 rounded-full px-8 py-4 text-base font-semibold bg-[#C7F236] text-[#0A1E5E] hover:bg-[#b5dd2a] transition-all duration-200 group shadow-[0_0_30px_-5px_rgba(199,242,54,0.35)] hover:shadow-[0_0_40px_-5px_rgba(199,242,54,0.55)] shrink-0"
            >
              Let&apos;s Talk
              <ArrowUpRight className="w-[18px] h-[18px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
            </a>
          </div>
        </ScrollReveal>
      </div>
    </section>
  );
}
