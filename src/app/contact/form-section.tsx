"use client";

import { useState } from "react";
import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { CheckCircle2, Send } from "lucide-react";

const services = ["UI/UX Design", "Web Development", "Mobile Apps", "Lainnya"];

export function ContactFormSection() {
  const [selectedService, setSelectedService] = useState("");
  const [formData, setFormData] = useState({
    name: "",
    whatsapp: "",
    email: "",
    description: ""
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const isFormValid = selectedService !== "" && formData.name !== "" && formData.description !== "";

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!isFormValid) return;

    const message = `Hello JuangDev, I would like to consult about a new project.\n\n*Required Service:* ${selectedService}\n*Full Name:* ${formData.name}\n*WhatsApp:* ${formData.whatsapp}\n*Email:* ${formData.email || "-"}\n\n*Project Overview:*\n${formData.description}`;
    window.open(`https://wa.me/6283852174877?text=${encodeURIComponent(message)}`, '_blank');
  };

  return (
    <section className="pt-48 md:pt-56 pb-24 bg-[#f8f9fc] relative z-10">
      <div className="max-w-6xl mx-auto px-6 sm:px-8">
        <div className="grid grid-cols-1 lg:grid-cols-[1fr_1.3fr] gap-12 lg:gap-20">
          
          {/* Left Column: Context & Commitment */}
          <ScrollReveal delay={0.3}>
            <div className="flex flex-col h-full">
              <h2 className="text-3xl md:text-4xl lg:text-5xl font-black text-[#1a1f3c] leading-tight tracking-tight mb-6">
                Ceritakan Rencana <br className="hidden lg:block"/>
                Besar <span className="text-[#2563EB] font-serif italic">Bisnis Anda</span>
              </h2>
              <p className="text-[#64748b] text-[0.95rem] md:text-base leading-relaxed font-medium mb-10 max-w-md">
                We believe great digital products start with a deep understanding of the problem to be solved. Fill out the consultation form and our team will contact you shortly.
              </p>

              <div className="bg-[#0A1E5E] rounded-3xl p-8 md:p-10 shadow-2xl shadow-[#0A1E5E]/20 relative overflow-hidden mt-auto border-2 border-[#0A1E5E] border-b-[8px] border-b-[#05113A]">
                <div className="absolute top-0 right-0 w-64 h-64 bg-[#1d4ed8] rounded-full blur-[80px] opacity-20 -translate-y-1/2 translate-x-1/3" />
                
                <h3 className="text-[#C7F236] text-xs font-bold uppercase tracking-widest mb-8 flex items-center gap-2">
                  <span className="w-2 h-2 rounded-full bg-[#C7F236]"></span> Our Service Commitment
                </h3>

                <div className="space-y-6 relative z-10">
                  <div className="flex gap-4">
                    <CheckCircle2 className="w-6 h-6 text-[#C7F236] shrink-0" />
                    <div>
                      <h4 className="text-white font-bold text-[0.95rem] mb-1">Waktu Respon Cepat</h4>
                      <p className="text-white/60 text-xs leading-relaxed">Balasan via WhatsApp dalam waktu kurang dari 2 jam kerja.</p>
                    </div>
                  </div>
                  <div className="flex gap-4">
                    <CheckCircle2 className="w-6 h-6 text-[#C7F236] shrink-0" />
                    <div>
                      <h4 className="text-white font-bold text-[0.95rem] mb-1">Estimasi Biaya Transparan</h4>
                      <p className="text-white/60 text-xs leading-relaxed">Rincian biaya sesuai modul fitur tanpa markup abal-abal.</p>
                    </div>
                  </div>
                  <div className="flex gap-4">
                    <CheckCircle2 className="w-6 h-6 text-[#C7F236] shrink-0" />
                    <div>
                      <h4 className="text-white font-bold text-[0.95rem] mb-1">Kerahasiaan Ide Terjamin</h4>
                      <p className="text-white/60 text-xs leading-relaxed">Kami menghormati kerahasiaan konsep dengan menanda tangani kesepakatan NDA.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </ScrollReveal>

          {/* Right Column: Interactive Form */}
          <ScrollReveal delay={0.4}>
            <div className="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-2xl shadow-slate-200/50 border-2 border-slate-200 border-b-[8px] border-b-slate-300">
              <div className="mb-8 border-b border-slate-100 pb-6">
                <h3 className="text-2xl font-black text-[#1a1f3c] mb-2">Formulir Konsultasi Proyek</h3>
                <p className="text-slate-500 text-sm font-medium">Mohon diisi detail di bawah ini agar kami dapat memetakan rencana proyek Anda.</p>
              </div>

              <form onSubmit={handleSubmit} className="space-y-6">
                
                {/* Services Pills */}
                <div>
                  <label className="block text-[#1a1f3c] text-xs font-bold uppercase tracking-wider mb-3">Required Service *</label>
                  <div className="flex flex-wrap gap-2">
                    {services.map((srv) => (
                      <button
                        key={srv}
                        type="button"
                        onClick={() => setSelectedService(srv)}
                        className={`px-4 py-2 rounded-full text-xs font-bold transition-all border-2 ${
                          selectedService === srv
                            ? "bg-[#2563EB] text-white border-[#2563EB] shadow-md shadow-[#2563EB]/20"
                            : "bg-white text-slate-500 border-slate-200 hover:border-slate-300"
                        }`}
                      >
                        {srv}
                      </button>
                    ))}
                  </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label htmlFor="name" className="block text-[#1a1f3c] text-xs font-bold uppercase tracking-wider mb-2">Full Name *</label>
                    <input
                      type="text"
                      id="name"
                      name="name"
                      value={formData.name}
                      onChange={handleChange}
                      placeholder="Cth: Akmal Nugraha"
                      className="w-full px-5 py-3.5 bg-[#f8f9fc] border border-slate-200 rounded-full text-sm focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 transition-all font-medium text-[#1a1f3c]"
                      required
                    />
                  </div>
                  <div>
                    <label htmlFor="whatsapp" className="block text-[#1a1f3c] text-xs font-bold uppercase tracking-wider mb-2">Nomor WhatsApp *</label>
                    <input
                      type="tel"
                      id="whatsapp"
                      name="whatsapp"
                      value={formData.whatsapp}
                      onChange={handleChange}
                      placeholder="Cth: 0851xxxxxx"
                      className="w-full px-5 py-3.5 bg-[#f8f9fc] border border-slate-200 rounded-full text-sm focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 transition-all font-medium text-[#1a1f3c]"
                      required
                    />
                  </div>
                </div>

                <div>
                  <label htmlFor="email" className="block text-[#1a1f3c] text-xs font-bold uppercase tracking-wider mb-2">Alamat Email (Opsional)</label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    placeholder="Cth: akmal@perusahaan.com"
                    className="w-full px-5 py-3.5 bg-[#f8f9fc] border border-slate-200 rounded-full text-sm focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 transition-all font-medium text-[#1a1f3c]"
                  />
                </div>

                <div>
                  <label htmlFor="description" className="block text-[#1a1f3c] text-xs font-bold uppercase tracking-wider mb-2">Gambaran Rencana Proyek *</label>
                  <textarea
                    id="description"
                    name="description"
                    value={formData.description}
                    onChange={handleChange}
                    rows={4}
                    placeholder="Ceritakan singkat produk digital yang ingin Anda buat, target rilis, dan estimasi dana jika ada..."
                    className="w-full px-5 py-4 bg-[#f8f9fc] border border-slate-200 rounded-[1.5rem] text-sm focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 transition-all resize-none font-medium text-[#1a1f3c]"
                    required
                  ></textarea>
                </div>

                <div className="pt-2">
                  <button
                    type="submit"
                    disabled={!isFormValid}
                    className={`w-full py-4 rounded-full font-bold text-sm flex items-center justify-center gap-2 transition-all ${
                      isFormValid
                        ? "bg-[#2563EB] text-white shadow-lg shadow-[#2563EB]/30 hover:bg-[#1d4ed8]"
                        : "bg-slate-100 text-slate-400 cursor-not-allowed"
                    }`}
                  >
                    <Send className="w-4 h-4" /> Kirim Formulir Konsultasi
                  </button>
                  <p className="text-center text-[0.65rem] text-slate-400 mt-4 font-medium">* Seluruh data yang Anda kirimkan bersifat rahasia.</p>
                </div>

              </form>
            </div>
          </ScrollReveal>

        </div>
      </div>
    </section>
  );
}
