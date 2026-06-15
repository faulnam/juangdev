"use client";

import Image from "next/image";
import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { SectionHeader } from "@/components/shared/section-header";

export function About() {
  return (
    <section id="about" className="py-16 md:py-24 lg:py-28 bg-white relative overflow-hidden">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 relative">

        {/* ── Section Header ── */}
        <ScrollReveal>
          <SectionHeader
            title="About"
            highlight="JuangDev"
            description="JuangDev serves as a technology studio and strategic partner dedicated to building professional websites, custom mobile apps, and innovative digital products specifically designed to accelerate your business growth."
          />
        </ScrollReveal>

        {/* ── Bento Grid ── */}
        <div className="grid grid-cols-1 lg:grid-cols-[1.3fr_1fr_1fr] gap-6">

          {/* ── LEFT COLUMN ── */}
          <div className="flex flex-col gap-6">

            {/* Card: Laptop photo */}
            <ScrollReveal delay={0.05} className="flex-1 flex flex-col">
              <div className="relative rounded-[2rem] overflow-hidden min-h-[300px] flex-1 bg-[#05111f] shadow-[0_20px_50px_-15px_rgba(0,0,0,0.3)]">
                <Image
                  src="/about-laptop.png"
                  alt="JuangDev laptop mockup"
                  fill
                  className="object-cover object-center opacity-90"
                  sizes="(max-width: 1024px) 100vw, 40vw"
                />
                {/* subtle overlay for brand feel */}
                <div className="absolute inset-0 bg-gradient-to-t from-[#05111f]/60 via-transparent to-transparent" />
              </div>
            </ScrollReveal>

            {/* Card: Stats – 100% | 30 Hari */}
            <ScrollReveal delay={0.15}>
              <div className="rounded-[2rem] bg-[#1a3a8f] shadow-[0_20px_50px_-15px_rgba(26,58,143,0.4)] p-8 flex flex-row items-center justify-center gap-6 lg:gap-10 shrink-0">
                {/* 100% */}
                <div className="text-center flex-1">
                  <p className="text-4xl lg:text-5xl font-black text-[#C7F236] leading-none mb-3 tracking-tighter">
                    100%
                  </p>
                  <p className="text-[10px] lg:text-[11px] font-bold text-white/70 uppercase tracking-[0.2em] leading-tight">
                    Full Transparency
                  </p>
                </div>

                {/* Divider */}
                <div className="w-px h-16 bg-white/15 shrink-0" />

                {/* 30 Hari */}
                <div className="text-center flex-1">
                  <p className="text-4xl lg:text-5xl font-black text-white leading-none mb-3 tracking-tighter">
                    30 Days
                  </p>
                  <p className="text-[10px] lg:text-[11px] font-bold text-white/70 uppercase tracking-[0.2em] leading-tight">
                    Post-deployment Support
                  </p>
                </div>
              </div>
            </ScrollReveal>
          </div>

          {/* ── MIDDLE COLUMN – Lime card ── */}
          <ScrollReveal delay={0.2} className="h-full">
            <div className="rounded-[2rem] bg-[#c8f135] shadow-[0_20px_50px_-15px_rgba(200,241,53,0.3)] h-full flex flex-col overflow-hidden">
              <div className="p-8 pb-6">
                <h3 className="text-2xl lg:text-3xl font-black text-[#0f1f5c] mb-4 leading-snug tracking-tight">
                  Digital Solutions<br />For Your Business
                </h3>
                <p className="text-[#0f1f5c]/80 text-[0.85rem] leading-relaxed font-medium">
                  We deliver custom website development ranging from Landing Pages, Company
                  Profiles, to E-Commerce that are fast, responsive, secure, and SEO-optimized
                  to maximize your business digital presence.
                </p>
              </div>

              {/* Tablet photo – flush to bottom */}
              <div className="mt-auto relative h-56 lg:h-64 overflow-hidden w-full shrink-0">
                <Image
                  src="/about-tablet.png"
                  alt="Dashboard analytics on tablet"
                  fill
                  className="object-cover object-top"
                  sizes="(max-width: 1024px) 100vw, 30vw"
                />
              </div>
            </div>
          </ScrollReveal>

          {/* ── RIGHT COLUMN – Dark blue card ── */}
          <ScrollReveal delay={0.3} className="h-full">
            <div className="rounded-[2rem] bg-[#1a3a8f] shadow-[0_20px_50px_-15px_rgba(26,58,143,0.4)] h-full flex flex-col overflow-hidden">
              {/* Team photo – flush top */}
              <div className="relative h-56 lg:h-60 overflow-hidden w-full shrink-0 bg-slate-100">
                <Image
                  src="/about-team.png"
                  alt="JuangDev team collaboration"
                  fill
                  className="object-cover object-center"
                  sizes="(max-width: 1024px) 100vw, 30vw"
                />
              </div>

              <div className="p-8 pt-6 flex-1 flex flex-col justify-end">
                <h3 className="text-2xl lg:text-3xl font-black text-white mb-4 leading-snug tracking-tight">
                  Transparency &amp;<br />Ongoing Support
                </h3>
                <p className="text-white/80 text-[0.85rem] leading-relaxed font-medium">
                  We provide full transparency from weekly progress updates to complete
                  project handover, backed by regular maintenance services to ensure
                  your website's stability.
                </p>
              </div>
            </div>
          </ScrollReveal>

        </div>
      </div>
    </section>
  );
}
