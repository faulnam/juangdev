"use client";

import { useState, useEffect } from "react";
import { usePathname } from "next/navigation";
import { Menu, X, ArrowUpRight } from "lucide-react";
import { NAV_LINKS, WHATSAPP_URL } from "@/lib/constants";
import { motion, AnimatePresence } from "framer-motion";

export function Navbar() {
  const [scrolled, setScrolled] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);
  const pathname = usePathname();

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 50);
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    if (mobileOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }
    return () => { document.body.style.overflow = ""; };
  }, [mobileOpen]);

  // Helper function to determine if a link is active
  const isActive = (href: string) => {
    // If it's a hash link on the homepage, we only consider it active if we are actually exactly on that hash?
    // For now, let's just make the page routes active.
    if (href === "/services" && pathname.startsWith("/services")) return true;
    if (href === "/portfolio" && pathname.startsWith("/portfolio")) return true;
    if (href === "/contact" && pathname.startsWith("/contact")) return true;
    if (href.startsWith("/#") && pathname === "/") {
      // It's hard to highlight hash accurately without intersection observer,
      // but let's highlight "Home" if we're on the homepage.
      if (href === "/#hero") return true;
      return false;
    }
    return pathname === href;
  };

  return (
    <header className="fixed top-0 left-0 right-0 z-50 w-full flex justify-center pointer-events-none">
      <div
        className={`pointer-events-auto transition-all duration-500 ease-in-out w-full px-4 sm:px-6 lg:px-8 flex items-center justify-between mx-auto ${scrolled
            ? "max-w-[55rem] mt-4 rounded-2xl bg-white/95 backdrop-blur-xl border border-slate-200 shadow-xl shadow-black/5 h-14 md:h-16"
            : "max-w-7xl mt-0 rounded-none bg-transparent border-transparent h-16 md:h-20"
          }`}
      >
        {/* Logo */}
        <a href="/#hero" className="flex items-center group" aria-label="JuangDev — Home">
          <span className={`text-xl md:text-2xl font-serif font-bold tracking-tight transition-colors duration-300 ${scrolled ? "text-black" : "text-white"}`}>
            Juang Dev
          </span>
        </a>

        {/* Desktop Nav */}
        <nav className="hidden md:flex items-center gap-1" aria-label="Main navigation">
          {NAV_LINKS.map((link) => {
            const active = isActive(link.href);
            return (
              <a
                key={link.href}
                href={link.href}
                className={`px-3 lg:px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-300 ${active
                    ? scrolled
                      ? "text-white bg-[#1a1f3c]"
                      : "text-[#0A1E5E] bg-[#C7F236]"
                    : scrolled
                      ? "text-[#4f5b7d] hover:text-[#2563EB] hover:bg-[#2563EB]/5 font-medium"
                      : "text-white/80 hover:text-white hover:bg-white/10 font-medium"
                  }`}
              >
                {link.label}
              </a>
            );
          })}
        </nav>

        {/* Desktop CTA */}
        <div className="hidden md:block">
          <a
            href={WHATSAPP_URL}
            target="_blank"
            rel="noopener noreferrer"
            className={`inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-semibold transition-all duration-200 group ${scrolled
                ? "bg-[#2563EB] text-white border-2 border-[#2563EB] hover:bg-[#1a4fd4] hover:border-[#1a4fd4] shadow-md shadow-[#2563EB]/20"
                : "bg-[#C7F236] text-[#0A1E5E] border-2 border-[#C7F236] hover:bg-[#b5dd2a] hover:border-[#b5dd2a] shadow-[0_0_20px_-5px_rgba(199,242,54,0.3)] hover:shadow-[0_0_30px_-5px_rgba(199,242,54,0.5)]"
              }`}
          >
            Free Consultation
            <ArrowUpRight className="w-4 h-4 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
          </a>
        </div>

        {/* Mobile Toggle */}
        <button
          className={`md:hidden p-2 rounded-lg transition-colors ${scrolled ? "text-[#0A1E5E] hover:bg-[#0A1E5E]/5" : "text-white hover:bg-white/10"}`}
          onClick={() => setMobileOpen(!mobileOpen)}
          aria-label="Toggle menu"
          aria-expanded={mobileOpen}
        >
          {mobileOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
        </button>
      </div>

      {/* Mobile Menu */}
      <AnimatePresence>
        {mobileOpen && (
          <motion.div
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -20 }}
            className="pointer-events-auto fixed inset-0 top-16 bg-[#0A1E5E]/95 backdrop-blur-xl z-40 md:hidden"
          >
            <nav className="flex flex-col gap-2 p-6">
              {NAV_LINKS.map((link, i) => {
                const active = isActive(link.href);
                return (
                  <motion.a
                    key={link.href}
                    href={link.href}
                    onClick={() => setMobileOpen(false)}
                    initial={{ opacity: 0, x: -20 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: i * 0.05 }}
                    className={`px-4 py-3 rounded-xl text-lg font-bold transition-all ${active
                        ? "text-[#0A1E5E] bg-[#C7F236]"
                        : "text-white/80 hover:text-white hover:bg-white/10 font-medium"
                      }`}
                  >
                    {link.label}
                  </motion.a>
                );
              })}
              <motion.a
                href={WHATSAPP_URL}
                target="_blank"
                rel="noopener noreferrer"
                initial={{ opacity: 0, x: -20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: NAV_LINKS.length * 0.05 }}
                className="mt-4 inline-flex items-center justify-center gap-2 rounded-full px-6 py-3.5 text-base font-semibold bg-[#C7F236] text-[#0A1E5E]"
              >
                Free Consultation
                <ArrowUpRight className="w-5 h-5" />
              </motion.a>
            </nav>
          </motion.div>
        )}
      </AnimatePresence>
    </header>
  );
}
