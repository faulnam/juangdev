"use client";

import { useState } from "react";
import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { CheckCircle2, Code2, ArrowRight } from "lucide-react";
import { WHATSAPP_URL } from "@/lib/constants";

const servicesData = [
  {
    name: "Landing Page",
    price: "99.000",
    description: "Website satu halaman yang dirancang khusus untuk fokus pada satu tujuan: konversi. Sangat cocok untuk kampanye iklan, peluncuran produk, atau pendaftaran event.",
    features: [
      "Gratis Domain & Hosting 1 Tahun",
      "Desain Premium & Mobile Responsive",
      "Optimasi Kecepatan & Basic SEO",
      "Integrasi WhatsApp & Social Media",
      "Copywriting Persuasif",
      "Garansi Support & Maintenance",
    ],
  },
  {
    name: "Company Profile",
    price: "199.000",
    description: "Tingkatkan kredibilitas bisnis Anda dengan website profil perusahaan yang profesional. Menampilkan informasi perusahaan, layanan, portofolio, dan kontak dengan elegan.",
    features: [
      "Gratis Domain & Hosting 1 Tahun",
      "Desain Premium & Mobile Responsive",
      "Optimasi Kecepatan & Basic SEO",
      "Halaman About, Services, Contact, dll.",
      "Panel Admin (CMS) yang mudah digunakan",
      "Garansi Support & Maintenance",
    ],
  },
  {
    name: "E-Commerce",
    price: "399.000",
    description: "Buka toko online Anda 24/7. Platform e-commerce lengkap dengan manajemen inventaris, integrasi metode pembayaran (payment gateway), dan penghitungan ongkir otomatis.",
    features: [
      "Gratis Domain & Hosting 1 Tahun",
      "Sistem Keranjang Belanja & Checkout",
      "Integrasi Payment Gateway & Kurir",
      "Manajemen Produk & Pesanan",
      "Panel Admin (CMS) E-Commerce",
      "Garansi Support & Maintenance",
    ],
  },
  {
    name: "Sistem Informasi",
    price: "499.000",
    description: "Sistem informasi berbasis web (ERP, CRM, HRIS, dll) yang dirancang khusus untuk mendigitalkan dan mengotomatiskan proses operasional bisnis Anda.",
    features: [
      "Arsitektur Database Custom",
      "Sistem Autentikasi & Multi Role",
      "Dashboard Analytics & Reporting",
      "Export/Import Data (Excel/PDF)",
      "UI/UX Khusus & Mobile Responsive",
      "Garansi Support 3 Bulan",
    ],
  },
  {
    name: "Custom Web App",
    price: "999.000",
    description: "Punya ide startup atau aplikasi web yang unik? Kami membangun custom web application berskala besar dengan framework modern seperti Next.js, React, atau Laravel.",
    features: [
      "Arsitektur Scalable & High Performance",
      "API Development & Integration",
      "Custom UI/UX Interaktif",
      "Sistem Keamanan Tingkat Lanjut",
      "Deployment ke Cloud (AWS/VPS)",
      "Support & Maintenance Jangka Panjang",
    ],
  },
];

export function WebService() {
  const [activeIndex, setActiveIndex] = useState(0);
  const activeService = servicesData[activeIndex];

  return (
    <section className="py-20 bg-white">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <ScrollReveal>
          <div className="mb-10">
            <h2 className="text-3xl md:text-4xl font-black text-[#1a1f3c] leading-tight tracking-tight mb-4">
              Pembuatan Website & <span className="text-[#2563EB] font-serif italic">Sistem Informasi</span>
            </h2>
            <p className="text-[#64748b] text-[0.95rem] md:text-base leading-relaxed max-w-3xl font-medium">
              Tingkatkan kehadiran digital bisnis Anda dengan website responsif, cepat, dan profesional yang dirancang khusus untuk mendatangkan lebih banyak konversi.
            </p>
          </div>

          {/* Tags as Tabs */}
          <div className="flex flex-wrap gap-2 mb-8">
            {servicesData.map((svc, i) => (
              <button
                key={i}
                onClick={() => setActiveIndex(i)}
                className={`px-6 py-2.5 rounded-xl font-bold text-[0.95rem] transition-all duration-200 whitespace-nowrap shrink-0 snap-start
                  ${activeIndex === i
                    ? "bg-[#2563EB] text-white border-2 border-[#2563EB] shadow-[0_4px_0_#1e3a8a] -translate-y-1" 
                    : "bg-white text-[#4f5b7d] border-2 border-slate-200 shadow-[0_4px_0_#e2e8f0] hover:border-[#2563EB] hover:text-[#2563EB] hover:shadow-[0_4px_0_#2563EB] hover:-translate-y-1"
                  }
                  active:shadow-none active:translate-y-[3px]
                `}
              >
                {svc.name}
              </button>
            ))}
          </div>

          {/* Main Card */}
          <div className="bg-[#0A1E5E] rounded-3xl p-8 md:p-12 shadow-2xl shadow-[#0A1E5E]/20 relative overflow-hidden transition-all duration-500 border-2 border-[#0A1E5E] border-b-[8px] border-b-[#05113A]">
            {/* Glow */}
            <div className="absolute top-0 right-0 w-96 h-96 bg-[#1d4ed8] rounded-full blur-[100px] opacity-20 -translate-y-1/2 translate-x-1/3" />
            
            <div className="grid grid-cols-1 lg:grid-cols-[1fr_1fr] gap-12 lg:gap-20 relative z-10">
              
              {/* Left Content */}
              <div className="flex flex-col justify-between">
                <div>
                  <div className="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center mb-6 text-white backdrop-blur-sm border border-white/20">
                    <Code2 className="w-6 h-6" />
                  </div>
                  <h3 className="text-2xl font-bold text-white mb-3">{activeService.name}</h3>
                  <p className="text-white/80 text-[0.95rem] leading-relaxed font-medium mb-12">
                    {activeService.description}
                  </p>
                </div>

                <div>
                  <div className="flex items-end justify-between mb-8 border-b border-white/10 pb-6">
                    <div>
                      <p className="text-white/50 text-xs font-bold uppercase tracking-wider mb-2">Mulai Dari</p>
                      <p className="text-4xl font-black text-[#C7F236]">Rp {activeService.price}</p>
                    </div>
                    <a href="#pricing" className="text-white/70 hover:text-white text-sm font-semibold flex items-center gap-1 transition-colors">
                      Lihat Paket <ArrowRight className="w-4 h-4" />
                    </a>
                  </div>

                  <div className="flex flex-wrap items-center gap-4">
                    <a
                      href={WHATSAPP_URL}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="px-8 py-3.5 bg-[#C7F236] text-[#0A1E5E] font-bold rounded-full text-sm hover:bg-[#b5dd2a] transition-all shadow-lg shadow-[#C7F236]/20"
                    >
                      Mulai Proyek Ini
                    </a>
                    <button className="px-6 py-3.5 bg-transparent text-white border border-white/20 hover:bg-white/5 font-semibold rounded-full text-sm transition-all">
                      Pelajari Detail
                    </button>
                  </div>
                </div>
              </div>

              {/* Right Content: Features List */}
              <div className="flex flex-col justify-center">
                <div className="bg-[#05113A] rounded-2xl p-8 border border-white/5">
                  <h3 className="text-white font-bold text-lg mb-6">Yang Anda Dapatkan:</h3>
                  <ul className="space-y-4">
                    {activeService.features.map((feature, i) => (
                      <li key={i} className="flex items-start gap-3">
                        <CheckCircle2 className="w-5 h-5 text-[#C7F236] shrink-0 mt-0.5" />
                        <span className="text-white/80 text-sm font-medium">{feature}</span>
                      </li>
                    ))}
                  </ul>
                </div>
              </div>

            </div>
          </div>
        </ScrollReveal>
      </div>
    </section>
  );
}
