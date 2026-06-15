"use client";

import { useState } from "react";
import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { FAQ_ITEMS, WHATSAPP_URL } from "@/lib/constants";
import { Plus, X, ArrowUpRight } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";

export function FAQ() {
  const [openIndex, setOpenIndex] = useState<number | null>(0);

  return (
    <section id="faq" className="py-20 md:py-28 lg:py-32 bg-white relative overflow-hidden">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <div className="grid grid-cols-1 lg:grid-cols-[1fr_1.5fr] gap-12 lg:gap-24 items-start">
          
          {/* Left Column: Title & CTA */}
          <div className="lg:sticky lg:top-32">
            <ScrollReveal>
              <h2 className="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-[#1a1f3c] leading-tight tracking-tight mb-5">
                Ada yang Ingin<br />
                <span className="text-[#2563EB] font-serif italic">Ditanyakan?</span>
              </h2>
              <p className="text-[#64748b] text-[0.95rem] md:text-[1.05rem] leading-relaxed mb-8 max-w-sm">
                Pertanyaan yang paling sering kami terima dari calon klien. Tidak menemukan jawaban yang Anda cari? Langsung hubungi kami.
              </p>
              <a
                href={WHATSAPP_URL}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center justify-center gap-2 rounded-full px-8 py-3.5 text-[0.9rem] font-bold bg-[#2563EB] text-white hover:bg-[#1d4ed8] transition-all w-fit shadow-lg shadow-[#2563EB]/25"
              >
                Hubungi Kami
                <ArrowUpRight className="w-[1.125rem] h-[1.125rem]" strokeWidth={2.5} />
              </a>
            </ScrollReveal>
          </div>

          {/* Right Column: Scrollable FAQ List */}
          <div className="relative">
            <ScrollReveal delay={0.1}>
              <div className="lg:max-h-[550px] max-h-[500px] overflow-y-auto pr-3 sm:pr-6 pb-6 scroll-container border-t border-slate-100 lg:border-t-0">
                <style dangerouslySetInnerHTML={{__html: `
                  .scroll-container::-webkit-scrollbar { width: 5px; }
                  .scroll-container::-webkit-scrollbar-track { background: transparent; }
                  .scroll-container::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
                  .scroll-container::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
                `}} />
                
                <div className="flex flex-col">
                  {FAQ_ITEMS.map((item, i) => {
                    const isOpen = openIndex === i;
                    const numStr = (i + 1).toString().padStart(2, '0');
                    return (
                      <div key={i} className="border-b border-slate-200 last:border-b-0 py-5">
                        <button
                          onClick={() => setOpenIndex(isOpen ? null : i)}
                          className="w-full flex items-center justify-between text-left gap-4 group cursor-pointer"
                          aria-expanded={isOpen}
                        >
                          <div className="flex items-center gap-4 sm:gap-6">
                            <div className="w-8 h-8 rounded-lg bg-[#f0f4fc] text-[#2563EB] text-[0.7rem] font-black flex items-center justify-center shrink-0">
                              {numStr}
                            </div>
                            <span className={`text-[0.95rem] md:text-base font-bold transition-colors ${isOpen ? "text-[#2563EB]" : "text-[#1a1f3c] group-hover:text-[#2563EB]"}`}>
                              {item.question}
                            </span>
                          </div>
                          
                          <div className={`w-8 h-8 rounded-full flex items-center justify-center shrink-0 border transition-all duration-300 ${
                            isOpen 
                              ? 'bg-[#2563EB] border-[#2563EB] text-white shadow-md shadow-[#2563EB]/30' 
                              : 'bg-white border-slate-200 text-slate-400 group-hover:border-[#2563EB] group-hover:text-[#2563EB]'
                          }`}>
                            {isOpen ? <X className="w-4 h-4" strokeWidth={3} /> : <Plus className="w-4 h-4" strokeWidth={2.5} />}
                          </div>
                        </button>

                        <AnimatePresence initial={false}>
                          {isOpen && (
                            <motion.div
                              initial={{ height: 0, opacity: 0 }}
                              animate={{ height: "auto", opacity: 1 }}
                              exit={{ height: 0, opacity: 0 }}
                              transition={{ duration: 0.3, ease: [0.25, 0.4, 0.25, 1] }}
                              className="overflow-hidden"
                            >
                              <div className="pl-[3rem] sm:pl-[3.5rem] pt-5 pb-2 pr-1">
                                <div className="bg-[#f8f9fc] rounded-[1.25rem] p-5 sm:p-6 text-[0.9rem] sm:text-[0.95rem] text-[#64748b] font-medium leading-relaxed shadow-sm shadow-slate-100">
                                  {item.answer}
                                </div>
                              </div>
                            </motion.div>
                          )}
                        </AnimatePresence>
                      </div>
                    );
                  })}
                </div>
              </div>
            </ScrollReveal>
          </div>

        </div>
      </div>
    </section>
  );
}
