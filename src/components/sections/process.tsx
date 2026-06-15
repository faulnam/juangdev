"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { Monitor, Lightbulb, Handshake, Rocket, ArrowUpRight } from "lucide-react";
import { WHATSAPP_URL } from "@/lib/constants";

const steps = [
  {
    id: "01",
    icon: <Monitor className="w-[1.125rem] h-[1.125rem] text-white" strokeWidth={2} />,
    title: "Konsultasi & Briefing Kebutuhan",
    description:
      "Sampaikan kebutuhan bisnis Anda kepada kami, mulai dari jenis platform yang ingin dibuat, fitur-fitur utama yang dibutuhkan, hingga target audiens yang ingin dicapai.",
  },
  {
    id: "02",
    icon: <Lightbulb className="w-[1.125rem] h-[1.125rem] text-white" strokeWidth={2} />,
    title: "Rekomendasi Solusi & Paket",
    description:
      "Tim kami akan menganalisis kebutuhan Anda dan menyarankan jenis website/aplikasi serta paket investasi terbaik yang paling tepat guna dan efisien untuk bisnis Anda.",
  },
  {
    id: "03",
    icon: <Handshake className="w-[1.125rem] h-[1.125rem] text-white" strokeWidth={2} />,
    title: "Deal & Pengisian Informasi",
    description:
      "Setelah menyetujui proposal, Anda melakukan pembayaran Down Payment (DP) sebesar 50%. Selanjutnya, Anda mengisi data informasi lengkap serta menyerahkan aset (logo, teks, gambar) yang ingin ditampilkan.",
  },
  {
    id: "04",
    icon: <Rocket className="w-[1.125rem] h-[1.125rem] text-white" strokeWidth={2} />,
    title: "Pengerjaan & Serah Terima",
    description:
      "Kami memproses pengerjaan dengan update berkala. Setelah proyek selesai diuji dan disetujui, Anda melakukan pelunasan sisa 50% sebelum website diserahterimakan seutuhnya.",
  },
];

const bullets = [
  "Alur kerja adaptif & fleksibel",
  "Diskusi & konsultasi berkala",
  "Revisi solutif sesuai kesepakatan",
  "Dukungan penuh setelah proyek rilis",
];

export function Process() {
  return (
    <section id="process" className="py-16 md:py-24 lg:py-28 bg-white relative overflow-hidden">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <div className="grid grid-cols-1 lg:grid-cols-[1fr_1.1fr] gap-12 lg:gap-24">
          
          {/* Left Column: Content */}
          <div className="flex flex-col gap-6 lg:sticky lg:top-32 self-start">
            <ScrollReveal>
              <h2 className="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-[#1a1f3c] leading-tight tracking-tight">
                4 Langkah Mudah<br />
                Memulai <span className="font-serif italic text-[#2563EB]">Proyek Anda</span>
              </h2>
            </ScrollReveal>

            <ScrollReveal delay={0.1}>
              <p className="text-[#4f5b7d] text-base md:text-[1.05rem] leading-relaxed">
                Ingin mulai membangun proyek Anda? Hubungi kami langsung dengan klik tombol WhatsApp di bawah. Kami akan memandu Anda secara transparan dari briefing kebutuhan hingga serah terima melalui 4 langkah praktis berikut.
              </p>
            </ScrollReveal>

            <ScrollReveal delay={0.2}>
              <ul className="flex flex-col gap-3.5 my-2">
                {bullets.map((bullet, i) => (
                  <li key={i} className="flex items-center gap-3 text-[0.95rem] text-[#4f5b7d] font-medium">
                    <div className="w-5 h-5 rounded-full bg-[#C7F236] flex items-center justify-center shrink-0">
                      <svg
                        className="w-3 h-3 text-[#0A1E5E]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        strokeWidth={3.5}
                        strokeLinecap="round"
                        strokeLinejoin="round"
                      >
                        <polyline points="20 6 9 17 4 12" />
                      </svg>
                    </div>
                    {bullet}
                  </li>
                ))}
              </ul>
            </ScrollReveal>

            <ScrollReveal delay={0.3}>
              <a
                href={WHATSAPP_URL}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center justify-center gap-2 rounded-full px-7 py-3.5 text-[0.9rem] font-bold bg-[#2563EB] text-white hover:bg-[#1d4ed8] transition-all w-fit shadow-lg shadow-[#2563EB]/25 mt-2"
              >
                Mulai Konsultasi Proyek
                <ArrowUpRight className="w-[1.125rem] h-[1.125rem]" strokeWidth={2.5} />
              </a>
            </ScrollReveal>
          </div>

          {/* Right Column: Timeline */}
          <div className="relative mt-8 lg:mt-3 lg:max-h-[460px] max-h-[400px] overflow-y-auto overflow-x-hidden pr-3 sm:pr-6 scroll-container pb-6">
            <style dangerouslySetInnerHTML={{__html: `
              .scroll-container::-webkit-scrollbar { width: 6px; }
              .scroll-container::-webkit-scrollbar-track { background: transparent; }
              .scroll-container::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
              .scroll-container::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
            `}} />
            
            {/* Vertical Line */}
            <div className="absolute left-[22px] top-6 bottom-12 w-px bg-slate-200" />
            
            <div className="flex flex-col gap-10 md:gap-12 relative">
              {steps.map((step, i) => (
                <ScrollReveal key={step.id} delay={i * 0.1}>
                  <div className="flex gap-6 relative">
                    {/* Icon */}
                    <div className="w-11 h-11 rounded-xl bg-[#2563EB] shadow-lg shadow-[#2563EB]/20 flex items-center justify-center shrink-0 relative z-10">
                      {step.icon}
                    </div>

                    {/* Content */}
                    <div className="flex flex-col gap-2.5 pb-2">
                      <div className="bg-[#f0f4fc] text-[#2563EB] text-[0.65rem] font-black px-2.5 py-0.5 rounded-full w-fit tracking-wider">
                        {step.id}
                      </div>
                      <h3 className="text-[1.15rem] md:text-xl font-bold text-[#1a1f3c] tracking-tight">
                        {step.title}
                      </h3>
                      <p className="text-[#4f5b7d] text-[0.9rem] md:text-[0.95rem] leading-relaxed">
                        {step.description}
                      </p>
                    </div>
                  </div>
                </ScrollReveal>
              ))}
            </div>
          </div>

        </div>
      </div>
    </section>
  );
}
