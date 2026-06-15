"use client";

import { useState } from "react";
import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { ChevronDown } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";

const contactFaqs = [
  {
    question: "Berapa lama respon setelah saya mengisi form?",
    answer: "Kami berkomitmen untuk memberikan balasan melalui pesan WhatsApp atau Email maksimal dalam waktu 2 jam kerja setelah formulir Anda kami terima."
  },
  {
    question: "Apakah ada biaya untuk sesi konsultasi pertama?",
    answer: "Sama sekali tidak. Sesi diskusi awal dan estimasi proyek kami berikan secara gratis tanpa komitmen apapun dari pihak Anda."
  },
  {
    question: "Apakah JuangDev menerima NDA (perjanjian kerahasiaan)?",
    answer: "Tentu. Kami menghargai inovasi bisnis Anda. Kami sangat terbuka dan siap untuk menandatangani Non-Disclosure Agreement (NDA) sebelum Anda membagikan detail rahasia proyek."
  },
  {
    question: "Bagaimana sistem pembayaran proyek di JuangDev?",
    answer: "Sistem pembayaran kami menggunakan skema termin. Umumnya dibagi menjadi 2 tahap: 50% Down Payment (DP) sebelum proyek dimulai, dan 50% pelunasan setelah proyek selesai 100% dan siap diserahterimakan."
  }
];

export function ContactFAQ() {
  const [openIndex, setOpenIndex] = useState<number | null>(null);

  const toggleOpen = (index: number) => {
    setOpenIndex(openIndex === index ? null : index);
  };

  return (
    <section className="pt-10 pb-20 bg-white">
      <div className="max-w-6xl mx-auto px-6 sm:px-8">
        <ScrollReveal>
          <div className="text-center mb-16">
            <h2 className="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-[#1a1f3c] leading-tight tracking-tight mb-5">
              Pertanyaan Seputar Proses Kerja
            </h2>
            <p className="text-[#64748b] text-[0.95rem] md:text-base leading-relaxed max-w-2xl mx-auto font-medium">
              Berikut ini hal-hal mendasar yang sering ditanyakan oleh klien sebelum bekerja sama dengan kami.
            </p>
          </div>
        </ScrollReveal>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
          {contactFaqs.map((faq, index) => {
            const isOpen = openIndex === index;
            return (
              <ScrollReveal key={index} delay={index * 0.1}>
                <div 
                  className={`border rounded-[1.25rem] overflow-hidden transition-all duration-300 h-full flex flex-col ${
                    isOpen ? 'border-[#2563EB] shadow-md shadow-[#2563EB]/10' : 'border-slate-200 hover:border-[#2563EB]/50'
                  }`}
                >
                  <button
                    onClick={() => toggleOpen(index)}
                    className="w-full flex items-center justify-between text-left px-6 py-5 cursor-pointer group bg-white"
                  >
                    <span className={`text-[0.95rem] font-bold transition-colors pr-4 ${isOpen ? "text-[#2563EB]" : "text-[#1a1f3c] group-hover:text-[#2563EB]"}`}>
                      {faq.question}
                    </span>
                    
                    <div className={`w-6 h-6 rounded-full flex items-center justify-center shrink-0 transition-transform duration-300 ${
                      isOpen ? 'bg-[#2563EB]/10 text-[#2563EB] rotate-180' : 'bg-slate-50 text-slate-400 group-hover:text-[#2563EB]'
                    }`}>
                      <ChevronDown className="w-4 h-4" strokeWidth={2.5} />
                    </div>
                  </button>

                  <AnimatePresence initial={false}>
                    {isOpen && (
                      <motion.div
                        initial={{ height: 0, opacity: 0 }}
                        animate={{ height: "auto", opacity: 1 }}
                        exit={{ height: 0, opacity: 0 }}
                        transition={{ duration: 0.3, ease: [0.25, 0.4, 0.25, 1] }}
                        className="bg-white"
                      >
                        <div className="px-6 pb-6 pt-2">
                          <p className="text-[0.9rem] text-[#64748b] font-medium leading-relaxed">
                            {faq.answer}
                          </p>
                        </div>
                      </motion.div>
                    )}
                  </AnimatePresence>
                </div>
              </ScrollReveal>
            );
          })}
        </div>
      </div>
    </section>
  );
}
