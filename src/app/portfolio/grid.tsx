"use client";

import { useState } from "react";
import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { ArrowUpRight } from "lucide-react";

const categories = ["Semua", "Landing Page", "Company Profile", "E-Commerce", "Sistem Informasi", "Custom Web App"];

const portfolioData = [
  {
    title: "Anggana Project",
    category: "Company Profile",
    description: "Official website of Anggana Project, perusahaan yang bergerak di bidang layanan kreatif dan manajemen event. Menampilkan layanan, portofolio event, dan informasi corporate dengan desain modern.",
    tags: ["Next.js", "Tailwind CSS", "Framer Motion"],
    color: "bg-blue-600",
  },
  {
    title: "TokoBajuKu",
    category: "E-Commerce",
    description: "Platform e-commerce lengkap dengan sistem manajemen inventaris, pembayaran otomatis, dan integrasi pengiriman untuk brand fashion lokal.",
    tags: ["React", "Node.js", "PostgreSQL", "Payment Gateway"],
    color: "bg-purple-600",
  },
  {
    title: "Dapoer Niknik",
    category: "Landing Page",
    description: "Culinary SME landing page yang menonjolkan layanan katering dengan visual yang menggugah selera. Integrasi langsung pemesanan ke WhatsApp.",
    tags: ["HTML5", "CSS3", "JavaScript"],
    color: "bg-orange-600",
  },
  {
    title: "HRIS Internal Cipta",
    category: "Sistem Informasi",
    description: "Sistem informasi manajemen sumber daya manusia (HRIS) untuk absensi wajah, perhitungan gaji otomatis, dan manajemen cuti karyawan.",
    tags: ["Laravel", "Vue.js", "MySQL", "AWS"],
    color: "bg-rose-700",
  },
  {
    title: "Desa Kalisabuk",
    category: "Company Profile",
    description: "Website profil resmi desa yang terintegrasi dengan Content Management System (CMS) untuk mengelola pengumuman, berita, dan administrasi publik.",
    tags: ["Next.js", "Tailwind CSS", "Prisma", "CMS"],
    color: "bg-emerald-700",
  },
  {
    title: "FinTrack Dashboard",
    category: "Custom Web App",
    description: "Aplikasi web khusus untuk analisis finansial yang dilengkapi dengan dashboard interaktif, grafik real-time, dan eksport data laporan komprehensif.",
    tags: ["Next.js", "TypeScript", "Recharts", "Prisma"],
    color: "bg-cyan-700",
  },
];

export function PortfolioGrid() {
  const [activeCategory, setActiveCategory] = useState("Semua");

  const filteredData = activeCategory === "Semua" 
    ? portfolioData 
    : portfolioData.filter(item => item.category === activeCategory);

  return (
    <section className="pt-20 pb-10 bg-[#f8f9fc]">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        
        {/* Category Filters */}
        <ScrollReveal>
          <div className="flex flex-wrap justify-center gap-3 mb-16">
            {categories.map((cat) => (
              <button
                key={cat}
                onClick={() => setActiveCategory(cat)}
                className={`px-6 py-2.5 rounded-xl font-bold text-[0.95rem] transition-all duration-200 whitespace-nowrap shrink-0 snap-start
                  ${activeCategory === cat 
                    ? "bg-[#2563EB] text-white border-2 border-[#2563EB] shadow-[0_4px_0_#1e3a8a] -translate-y-1" 
                    : "bg-white text-[#4f5b7d] border-2 border-slate-200 shadow-[0_4px_0_#e2e8f0] hover:border-[#2563EB] hover:text-[#2563EB] hover:shadow-[0_4px_0_#2563EB] hover:-translate-y-1"
                  }
                  active:shadow-none active:translate-y-[3px]
                `}
              >
                {cat}
              </button>
            ))}
          </div>
        </ScrollReveal>

        {/* Portfolio Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {filteredData.map((project, i) => (
            <ScrollReveal key={i} delay={i * 0.1}>
              <div className="bg-white rounded-3xl overflow-hidden shadow-xl shadow-slate-200/50 border-2 border-slate-200 border-b-[8px] border-b-slate-300 flex flex-col h-full group hover:-translate-y-2 transition-transform duration-300">
                
                {/* CSS Mockup Image Area */}
                <div className={`w-full h-60 ${project.color} relative flex items-end justify-center p-6 overflow-hidden`}>
                  {/* Decorative background patterns */}
                  <div className="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_center,_white_10%,_transparent_20%)] bg-[length:20px_20px]"></div>
                  <h3 className="absolute top-10 left-0 w-full text-center text-white/30 font-black text-5xl uppercase tracking-widest">{project.title}</h3>
                  
                  {/* Laptop Mockup */}
                  <div className="relative z-10 w-full max-w-[280px]">
                    <div className="bg-slate-800 p-2 rounded-t-xl shadow-2xl relative">
                      {/* Browser top bar */}
                      <div className="flex gap-1.5 mb-2 px-1">
                        <div className="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                        <div className="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                        <div className="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                      </div>
                      {/* Screen content */}
                      <div className="bg-white h-28 rounded-md flex flex-col overflow-hidden">
                        <div className="h-8 bg-slate-100 flex items-center px-4 border-b border-slate-200">
                          <div className="w-16 h-2 bg-slate-300 rounded-full"></div>
                        </div>
                        <div className="flex-grow p-4">
                          <div className="w-24 h-4 bg-slate-200 rounded mb-2"></div>
                          <div className="w-full h-2 bg-slate-100 rounded mb-1"></div>
                          <div className="w-3/4 h-2 bg-slate-100 rounded"></div>
                        </div>
                      </div>
                    </div>
                    {/* Laptop base */}
                    <div className="h-3 bg-slate-300 rounded-b-xl w-[110%] -ml-[5%] relative shadow-xl">
                      <div className="absolute top-0 left-1/2 -translate-x-1/2 w-16 h-1 bg-slate-400 rounded-b-md"></div>
                    </div>
                  </div>
                </div>

                {/* Content Area */}
                <div className="p-8 flex flex-col flex-grow">
                  <div className="mb-4">
                    <span className="inline-block px-3 py-1 bg-[#C7F236] text-[#0A1E5E] text-[0.65rem] font-bold uppercase tracking-wider rounded-md mb-4">
                      {project.category}
                    </span>
                    <h3 className="text-xl font-bold text-[#1a1f3c] mb-3">{project.title}</h3>
                    <p className="text-slate-500 text-[0.9rem] leading-relaxed line-clamp-3">
                      {project.description}
                    </p>
                  </div>

                  <div className="mt-auto">
                    <div className="flex flex-wrap gap-2 mb-6">
                      {project.tags.map((tag, idx) => (
                        <span key={idx} className="px-2.5 py-1 bg-[#f0f4fc] text-slate-500 text-[0.7rem] font-semibold rounded-md border border-slate-100">
                          {tag}
                        </span>
                      ))}
                    </div>
                    <button className="text-[#2563EB] text-sm font-bold flex items-center gap-1 group-hover:gap-2 transition-all">
                      Lihat Detail <ArrowUpRight className="w-4 h-4" />
                    </button>
                  </div>
                </div>

              </div>
            </ScrollReveal>
          ))}
        </div>

      </div>
    </section>
  );
}
