"use client";

import { useState, useEffect } from "react";
import { ArrowUpRight, Star, FolderOpen, Users, CalendarDays } from "lucide-react";
import { TRUST_INDICATORS, WHATSAPP_URL } from "@/lib/constants";
import { motion, AnimatePresence } from "framer-motion";

const PRICING_LIST = [
  { price: "99K", label: "Landing Page" },
  { price: "199K", label: "Company Profile" },
  { price: "399K", label: "E-Commerce" },
  { price: "499K", label: "Information System" },
  { price: "999K", label: "Custom Web App" },
];

const iconMap: Record<string, React.ReactNode> = {
  star: <Star className="w-3.5 h-3.5 fill-[#C7F236] text-[#C7F236]" />,
  folder: <FolderOpen className="w-3.5 h-3.5 text-[#C7F236]" />,
  users: <Users className="w-3.5 h-3.5 text-[#C7F236]" />,
  calendar: <CalendarDays className="w-3.5 h-3.5 text-[#C7F236]" />,
};

export function Hero() {
  const [pricingIndex, setPricingIndex] = useState(0);

  useEffect(() => {
    const interval = setInterval(() => {
      setPricingIndex((prev) => (prev + 1) % PRICING_LIST.length);
    }, 3000);
    return () => clearInterval(interval);
  }, []);

  return (
    <section
      id="hero"
      className="relative min-h-[100dvh] flex items-center justify-center overflow-hidden"
      style={{
        background: "linear-gradient(160deg, #1a3fa0 0%, #122d78 45%, #0A1E5E 100%)",
      }}
    >
      {/* Background Glow */}
      <div className="absolute inset-0 pointer-events-none select-none overflow-hidden" aria-hidden="true">
        <div
          className="absolute top-[-5%] left-[-15%] w-[900px] h-[900px] rounded-full blur-[180px]"
          style={{ background: "radial-gradient(circle, rgba(37,99,235,0.40) 0%, transparent 70%)" }}
        />
        <div
          className="absolute bottom-[-10%] right-[-10%] w-[700px] h-[700px] rounded-full blur-[140px]"
          style={{ background: "radial-gradient(circle, rgba(37,99,235,0.30) 0%, transparent 70%)" }}
        />
        <div className="absolute top-[30%] right-[-5%] w-[500px] h-[500px] rounded-full bg-[#C7F236]/10 blur-[120px]" />
      </div>

      <div className="relative z-10 w-full max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 pt-24 sm:pt-32 lg:pt-40 pb-12 sm:pb-20">
        <div className="flex flex-col-reverse lg:flex-row gap-10 lg:gap-8 items-center justify-between">
          {/* Left Content */}
          <div className="w-full lg:w-[57%] flex flex-col items-start text-left">
            <motion.h1
              className="max-w-2xl text-left"
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, ease: [0.25, 0.4, 0.25, 1] }}
            >
              <span className="block text-[1.85rem] sm:text-5xl lg:text-[3.5rem] xl:text-[4rem] font-black text-white leading-[1.15] tracking-tight">
                Build{" "}
                <span className="font-serif italic text-[#C7F236] text-[1.05em] font-medium tracking-normal">
                  Websites
                </span>{" "}
                &amp;
              </span>
              <span className="block text-[1.85rem] sm:text-5xl lg:text-[3.5rem] xl:text-[4rem] font-black text-white leading-[1.15] tracking-tight mt-2">
                Custom{" "}
                <span className="font-serif italic text-[#C7F236] text-[1.05em] font-medium tracking-normal">
                  Software
                </span>
              </span>
              <span className="block text-[1.85rem] sm:text-5xl lg:text-[3.5rem] xl:text-[4rem] font-black text-white leading-[1.15] tracking-tight mt-2">
                That Grow Your Business.
              </span>
            </motion.h1>

            <motion.p
              className="text-[0.925rem] sm:text-[1.125rem] lg:text-[1.2rem] text-white/70 leading-relaxed max-w-xl mt-5 font-medium"
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.15 }}
            >
              JuangDev helps startups, SMEs, and enterprises build modern websites,
              web applications, mobile apps, dashboards, ERP systems, and AI-powered
              solutions that accelerate business growth.
            </motion.p>

            {/* CTA Buttons */}
            <motion.div
              className="flex flex-col sm:flex-row gap-4 justify-start mt-8 w-full sm:w-auto"
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.3 }}
            >
              <a
                href={WHATSAPP_URL}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center justify-center gap-2 rounded-full px-8 py-4 text-base font-semibold bg-[#C7F236] text-[#0A1E5E] border-2 border-[#C7F236] hover:bg-[#b5dd2a] hover:border-[#b5dd2a] w-full sm:w-auto shadow-[0_0_30px_-5px_rgba(199,242,54,0.35)] hover:shadow-[0_0_40px_-5px_rgba(199,242,54,0.55)] transition-all duration-300 group"
              >
                Start Your Project
                <ArrowUpRight className="w-[18px] h-[18px] transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
              </a>
              <a
                href="#portfolio"
                className="inline-flex items-center justify-center gap-2 rounded-full px-8 py-4 text-base font-semibold bg-transparent border-2 border-white/15 text-white hover:bg-white/10 hover:border-white/35 w-full sm:w-auto backdrop-blur-md transition-all duration-200"
              >
                View Portfolio
              </a>
            </motion.div>

            {/* Trust Indicators */}
            <motion.div
              className="flex flex-wrap gap-6 mt-10"
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ duration: 0.8, delay: 0.5 }}
            >
              {TRUST_INDICATORS.map((item) => (
                <div key={item.label} className="flex items-center gap-2">
                  {iconMap[item.icon]}
                  <div>
                    <span className="text-white font-bold text-sm">{item.value}</span>
                    <span className="text-white/50 text-xs ml-1">{item.label}</span>
                  </div>
                </div>
              ))}
            </motion.div>
          </div>

          {/* Right Side — Floating Cards */}
          <motion.div
            className="w-full lg:w-[38%] relative h-[300px] sm:h-[400px] lg:h-[450px] flex items-center justify-center lg:-translate-y-12 lg:-translate-x-8"
            initial={{ opacity: 0, scale: 0.95 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 1, delay: 0.2 }}
          >
            {/* Central Glow */}
            <div className="absolute inset-0 bg-gradient-to-tr from-[#2563EB]/20 to-transparent rounded-full blur-3xl pointer-events-none" />

            {/* Central Image with Green Shape */}
            <div className="relative w-[280px] sm:w-[360px] h-[340px] sm:h-[460px] flex items-end justify-center mt-10 lg:mt-0">
              {/* Green Shape */}
              <div className="absolute bottom-0 w-[85%] h-[75%] bg-[#c6f036] rounded-[2.5rem] sm:rounded-[3rem]" />
              
              {/* Person Image */}
              <img 
                src="/orang.png" 
                alt="JuangDev Client" 
                className="relative z-10 w-[95%] h-auto object-contain drop-shadow-2xl"
              />
            </div>

            {/* Floating Card: Client Satisfaction */}
            <motion.div
              className="absolute left-[-10%] sm:left-[-15%] top-[20%] z-20 bg-white border-2 border-slate-200 border-b-[4px] sm:border-b-[6px] border-b-slate-300 shadow-xl py-3 sm:py-4 px-4 sm:px-5 rounded-xl sm:rounded-2xl flex flex-col items-center text-center cursor-default"
              animate={{ y: [0, -8, 0] }}
              transition={{ duration: 4, repeat: Infinity, ease: "easeInOut" }}
            >
              <div className="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-[#c6f036] text-[#0A1E5E] flex items-center justify-center shrink-0 mb-1.5 sm:mb-2 shadow-[0_4px_12px_rgba(199,242,54,0.35)]">
                <svg className="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" strokeWidth="3" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
              </div>
              <p className="text-sm sm:text-lg font-black text-black leading-none mb-0.5">99%</p>
              <p className="text-[8px] sm:text-[10px] text-black/70 font-bold uppercase tracking-wider">Client Satisfaction</p>
            </motion.div>

            {/* Floating Card: Rating */}
            <motion.div
              className="absolute right-[-5%] sm:right-[-10%] top-[10%] sm:top-[15%] z-20 bg-white border-2 border-slate-200 border-b-[4px] sm:border-b-[6px] border-b-slate-300 shadow-xl py-3 sm:py-4 px-4 sm:px-6 rounded-xl sm:rounded-2xl flex flex-col items-start text-left cursor-default"
              animate={{ y: [0, -6, 0] }}
              transition={{ duration: 3.5, repeat: Infinity, ease: "easeInOut", delay: 0.5 }}
            >
              <p className="text-sm sm:text-xl font-black text-black leading-none mb-1">⭐ 5.0 Rating</p>
              <p className="text-[8px] sm:text-[10px] text-black/70 font-bold uppercase tracking-wider">100+ Projects Done</p>
            </motion.div>

            {/* Floating Card: Support */}
            <motion.div
              className="absolute right-[-10%] sm:right-[-15%] top-[45%] sm:top-[50%] z-20 bg-white border-2 border-slate-200 border-b-[4px] sm:border-b-[6px] border-b-slate-300 shadow-xl py-3 sm:py-4 px-4 sm:px-6 rounded-xl sm:rounded-2xl flex flex-col items-start text-left cursor-default"
              animate={{ y: [0, -7, 0] }}
              transition={{ duration: 4.2, repeat: Infinity, ease: "easeInOut", delay: 1 }}
            >
              <p className="text-sm sm:text-2xl font-black text-black leading-none mb-1">24/7</p>
              <p className="text-[8px] sm:text-[10px] text-black/70 font-bold uppercase tracking-wider">Support Available</p>
            </motion.div>

            {/* Floating Card: Fast Delivery */}
            <motion.div
              className="absolute left-[-5%] sm:left-[-10%] bottom-[15%] sm:bottom-[10%] z-20 bg-white border-2 border-slate-200 border-b-[4px] sm:border-b-[6px] border-b-slate-300 shadow-xl py-3 sm:py-4 px-4 sm:px-6 rounded-xl sm:rounded-2xl flex flex-col items-start text-left cursor-default"
              animate={{ y: [0, -5, 0] }}
              transition={{ duration: 3.8, repeat: Infinity, ease: "easeInOut", delay: 0.8 }}
            >
              <p className="text-sm sm:text-lg font-black text-black leading-none mb-1">Fast Delivery</p>
              <p className="text-[8px] sm:text-[10px] text-black/70 font-bold uppercase tracking-wider">Premium Quality</p>
            </motion.div>

            {/* Floating Card: Pricing */}
            <motion.div
              className="absolute right-[5%] sm:right-[0%] bottom-[0%] sm:bottom-[-5%] z-20 bg-white border-2 border-slate-200 border-b-[4px] sm:border-b-[6px] border-b-slate-300 shadow-xl py-3 sm:py-4 px-4 sm:px-6 rounded-xl sm:rounded-2xl flex flex-col items-start text-left cursor-default overflow-hidden min-w-[130px] sm:min-w-[170px]"
              animate={{ y: [0, -6, 0] }}
              transition={{ duration: 4.5, repeat: Infinity, ease: "easeInOut", delay: 1.5 }}
            >
              <div className="relative w-full flex flex-col">
                <AnimatePresence mode="wait">
                  <motion.div
                    key={pricingIndex}
                    initial={{ opacity: 0, y: 15 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: -15 }}
                    transition={{ duration: 0.4 }}
                    className="flex flex-col w-full"
                  >
                    <p className="text-sm sm:text-xl font-black text-black leading-none mb-1">Starts from {PRICING_LIST[pricingIndex].price}</p>
                    <p className="text-[8px] sm:text-[10px] text-black/70 font-bold uppercase tracking-wider min-h-[15px]">{PRICING_LIST[pricingIndex].label}</p>
                  </motion.div>
                </AnimatePresence>
              </div>
            </motion.div>
          </motion.div>
        </div>
      </div>
    </section>
  );
}
