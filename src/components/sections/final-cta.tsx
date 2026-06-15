"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { ArrowUpRight, MessageCircle } from "lucide-react";
import { WHATSAPP_URL } from "@/lib/constants";

export function FinalCta() {
  return (
    <section
      id="contact"
      className="py-20 md:py-28 lg:py-32 relative overflow-hidden"
      style={{
        background: "linear-gradient(160deg, #0A1E5E 0%, #071542 50%, #040d2b 100%)",
      }}
    >
      {/* Background effects */}
      <div className="absolute inset-0 pointer-events-none">
        <div className="absolute top-1/4 left-1/4 w-[500px] h-[500px] rounded-full bg-[#2563EB]/10 blur-[120px]" />
        <div className="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] rounded-full bg-[#C7F236]/8 blur-[100px]" />
      </div>

      <div className="max-w-4xl mx-auto px-6 sm:px-8 lg:px-8 relative text-center">
        <ScrollReveal>
          <h2 className="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-black text-white leading-tight tracking-tight mb-6">
            Ready to Build Your Next{" "}
            <span className="font-serif italic text-[#C7F236]">Digital Product</span>?
          </h2>
        </ScrollReveal>

        <ScrollReveal delay={0.15}>
          <p className="text-lg md:text-xl text-white/60 max-w-2xl mx-auto mb-10 leading-relaxed">
            Let&apos;s discuss your idea and turn it into a powerful digital solution
            that drives real business results.
          </p>
        </ScrollReveal>

        <ScrollReveal delay={0.3}>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <a
              href={WHATSAPP_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center justify-center gap-2 rounded-full px-8 py-4 text-base font-semibold bg-[#C7F236] text-[#0A1E5E] border-2 border-[#C7F236] hover:bg-[#b5dd2a] hover:border-[#b5dd2a] shadow-[0_0_30px_-5px_rgba(199,242,54,0.35)] hover:shadow-[0_0_40px_-5px_rgba(199,242,54,0.55)] transition-all duration-300 group"
            >
              Book Free Consultation
              <ArrowUpRight className="w-[18px] h-[18px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
            </a>
            <a
              href={WHATSAPP_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center justify-center gap-2 rounded-full px-8 py-4 text-base font-semibold bg-transparent border-2 border-white/15 text-white hover:bg-white/10 hover:border-white/35 backdrop-blur-md transition-all duration-200"
            >
              <MessageCircle className="w-5 h-5" />
              WhatsApp Us
            </a>
          </div>
        </ScrollReveal>
      </div>
    </section>
  );
}
