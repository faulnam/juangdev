"use client";

import { useState } from "react";
import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { FAQ_ITEMS } from "@/lib/constants";
import { Plus, X } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";

export function ServicesFAQ() {
  const [openIndex, setOpenIndex] = useState<number | null>(0);

  return (
    <section className="py-20 md:py-28 bg-white relative overflow-hidden">
      <div className="max-w-4xl mx-auto px-6 sm:px-8 lg:px-8">
        <ScrollReveal>
          <div className="text-center mb-16">
            <h2 className="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-[#1a1f3c] leading-tight tracking-tight mb-5">
              Pertanyaan yang Sering <span className="text-[#2563EB] font-serif italic">Diajukan</span>
            </h2>
            <p className="text-[#64748b] text-[0.95rem] md:text-base leading-relaxed max-w-2xl mx-auto font-medium">
              Kami merangkum pertanyaan yang paling sering ditanyakan oleh klien kami sebelum memulai proyek digital.
            </p>
          </div>
        </ScrollReveal>

        <ScrollReveal delay={0.1}>
          <div className="flex flex-col gap-4">
            {FAQ_ITEMS.slice(0, 5).map((item, i) => {
              const isOpen = openIndex === i;
              return (
                <div key={i} className={`border-2 rounded-[1.5rem] overflow-hidden transition-all duration-300 ${isOpen ? 'border-[#2563EB] shadow-md shadow-[#2563EB]/10' : 'border-slate-100 hover:border-slate-200'}`}>
                  <button
                    onClick={() => setOpenIndex(isOpen ? null : i)}
                    className="w-full flex items-center justify-between text-left px-6 py-5 sm:px-8 cursor-pointer group bg-white"
                  >
                    <span className={`text-[0.95rem] md:text-base font-bold transition-colors pr-4 ${isOpen ? "text-[#2563EB]" : "text-[#1a1f3c] group-hover:text-[#2563EB]"}`}>
                      {item.question}
                    </span>
                    
                    <div className={`w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-all duration-300 ${
                      isOpen 
                        ? 'bg-[#2563EB] text-white' 
                        : 'bg-slate-50 text-slate-400 group-hover:bg-[#f0f4fc] group-hover:text-[#2563EB]'
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
                        className="bg-white"
                      >
                        <div className="px-6 sm:px-8 pb-6 pt-2">
                          <p className="text-[0.95rem] text-[#64748b] font-medium leading-relaxed">
                            {item.answer}
                          </p>
                        </div>
                      </motion.div>
                    )}
                  </AnimatePresence>
                </div>
              );
            })}
          </div>
        </ScrollReveal>
      </div>
    </section>
  );
}
