"use client";

import { useState } from "react";
import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { Mail, MessageCircle, MapPin, Check, Send } from "lucide-react";

const services = [
  "UI/UX Design",
  "Web Development",
  "Mobile Apps",
  "Lainnya"
];

export function ProjectEstimator() {
  const [selectedService, setSelectedService] = useState<string>("");
  const [formData, setFormData] = useState({
    name: "",
    phone: "",
    email: "",
    details: "",
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedService) return;
    
    const message = `Halo tim JuangDev, saya ingin konsultasi mengenai proyek baru.\n\n*Layanan:* ${selectedService}\n*Nama:* ${formData.name}\n*WhatsApp:* ${formData.phone}\n*Email:* ${formData.email || '-'}\n\n*Detail Proyek:*\n${formData.details}`;
    const encodedMessage = encodeURIComponent(message);
    window.open(`https://wa.me/6283852174877?text=${encodedMessage}`, '_blank');
  };

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData(prev => ({ ...prev, [e.target.name]: e.target.value }));
  };

  return (
    <section className="py-20 md:py-28 bg-[#f8f9fc]">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <div className="grid grid-cols-1 lg:grid-cols-[1fr_1.3fr] gap-12 lg:gap-20">
          
          {/* Left Column */}
          <div className="flex flex-col">
            <ScrollReveal>
              <h2 className="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-[#1a1f3c] leading-tight tracking-tight mb-5">
                Mari Mulai Sesuatu<br />
                <span className="font-serif italic text-[#2563EB]">yang Luar Biasa</span>
              </h2>
              <p className="text-[#64748b] text-[0.95rem] md:text-base leading-relaxed mb-8 max-w-md font-medium">
                Ceritakan kebutuhan bisnis Anda. Tim ahli kami siap mendengarkan, menganalisis, dan memberikan solusi terbaik untuk pertumbuhan digital Anda.
              </p>
            </ScrollReveal>

            <ScrollReveal delay={0.1}>
              <div className="flex flex-col gap-4">
                {/* Contact Cards */}
                <div className="bg-white border-2 border-slate-100 rounded-2xl p-4 flex items-center gap-5 shadow-sm">
                  <div className="w-11 h-11 rounded-full bg-[#f0f4fc] text-[#2563EB] flex items-center justify-center shrink-0">
                    <Mail className="w-5 h-5" />
                  </div>
                  <div>
                    <p className="text-[0.65rem] font-black text-slate-400 uppercase tracking-wider mb-0.5">Email</p>
                    <p className="font-bold text-[#1a1f3c] text-[0.95rem]">juangdev@gmail.com</p>
                  </div>
                </div>

                <div className="bg-white border-2 border-slate-100 rounded-2xl p-4 flex items-center gap-5 shadow-sm">
                  <div className="w-11 h-11 rounded-full bg-[#f0f4fc] text-[#2563EB] flex items-center justify-center shrink-0">
                    <MessageCircle className="w-5 h-5" />
                  </div>
                  <div>
                    <p className="text-[0.65rem] font-black text-slate-400 uppercase tracking-wider mb-0.5">WhatsApp</p>
                    <p className="font-bold text-[#1a1f3c] text-[0.95rem]">+62 851 9681 1722</p>
                  </div>
                </div>

                <div className="bg-white border-2 border-slate-100 rounded-2xl p-4 flex items-center gap-5 shadow-sm">
                  <div className="w-11 h-11 rounded-full bg-[#f0f4fc] text-[#2563EB] flex items-center justify-center shrink-0">
                    <MapPin className="w-5 h-5" />
                  </div>
                  <div>
                    <p className="text-[0.65rem] font-black text-slate-400 uppercase tracking-wider mb-0.5">Lokasi</p>
                    <p className="font-bold text-[#1a1f3c] text-[0.95rem]">Sidoarjo, Jawa Timur, Indonesia</p>
                  </div>
                </div>
              </div>
            </ScrollReveal>

            <ScrollReveal delay={0.2}>
              {/* Dark Blue Consultation Card */}
              <div className="bg-[#0A1E5E] rounded-2xl p-6 md:p-7 mt-6 shadow-xl shadow-[#0A1E5E]/20">
                <div className="flex items-start gap-4">
                  <div className="w-9 h-9 rounded-full bg-[#C7F236] text-[#0A1E5E] flex items-center justify-center shrink-0 mt-0.5">
                    <Check className="w-5 h-5" strokeWidth={3} />
                  </div>
                  <div>
                    <p className="text-[#C7F236] font-bold text-[0.9rem] mb-1.5 uppercase tracking-wide">Sesi Konsultasi Gratis</p>
                    <p className="text-white/85 text-[0.9rem] leading-relaxed font-medium">
                      Ngobrol santai seputar ide Anda. Kami bantu buatkan strategi dasar tanpa biaya atau komitmen apapun di awal.
                    </p>
                  </div>
                </div>
              </div>
            </ScrollReveal>
          </div>

          {/* Right Column: Form */}
          <div className="relative">
            <ScrollReveal delay={0.1}>
              <div className="bg-white rounded-[2rem] border-2 border-slate-100 shadow-xl shadow-slate-200/50 p-6 sm:p-8 md:p-10">
                <div className="mb-8">
                  <h3 className="text-xl md:text-2xl font-bold text-[#1a1f3c] mb-2">Ceritakan Kebutuhan Anda</h3>
                  <p className="text-[#94a3b8] text-sm font-medium">Isi form di bawah ini dan kami akan segera menghubungi Anda via WhatsApp.</p>
                </div>

                <form onSubmit={handleSubmit} className="space-y-6">
                  {/* Service Selection */}
                  <div>
                    <label className="block text-[0.7rem] font-black text-[#1e2547] uppercase tracking-wider mb-3">Layanan yang Dibutuhkan <span className="text-red-500">*</span></label>
                    <div className="flex flex-wrap gap-2 sm:gap-3">
                      {services.map((service) => (
                        <button
                          key={service}
                          type="button"
                          onClick={() => setSelectedService(service)}
                          className={`px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 border-2 ${
                            selectedService === service
                              ? "bg-[#f0f4fc] border-[#2563EB] text-[#2563EB]"
                              : "bg-white border-slate-200 text-slate-500 hover:border-slate-300 hover:bg-slate-50"
                          }`}
                        >
                          {service}
                        </button>
                      ))}
                    </div>
                  </div>

                  {/* Grid Inputs */}
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                    <div>
                      <label className="block text-[0.7rem] font-black text-[#1e2547] uppercase tracking-wider mb-2">Nama Lengkap <span className="text-red-500">*</span></label>
                      <input
                        type="text"
                        name="name"
                        value={formData.name}
                        onChange={handleInputChange}
                        required
                        placeholder="Cth: Budi Santoso"
                        className="w-full px-5 py-3.5 rounded-full border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 placeholder:font-normal focus:outline-none focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/10 transition-all"
                      />
                    </div>
                    <div>
                      <label className="block text-[0.7rem] font-black text-[#1e2547] uppercase tracking-wider mb-2">Nomor WhatsApp <span className="text-red-500">*</span></label>
                      <input
                        type="tel"
                        name="phone"
                        value={formData.phone}
                        onChange={handleInputChange}
                        required
                        placeholder="Cth: 08123456789"
                        className="w-full px-5 py-3.5 rounded-full border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 placeholder:font-normal focus:outline-none focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/10 transition-all"
                      />
                    </div>
                  </div>

                  {/* Email Input */}
                  <div>
                    <label className="block text-[0.7rem] font-black text-[#1e2547] uppercase tracking-wider mb-2">Alamat Email</label>
                    <input
                      type="email"
                      name="email"
                      value={formData.email}
                      onChange={handleInputChange}
                      placeholder="Cth: budi@perusahaan.com"
                      className="w-full px-5 py-3.5 rounded-full border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 placeholder:font-normal focus:outline-none focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/10 transition-all"
                    />
                  </div>

                  {/* Textarea */}
                  <div>
                    <label className="block text-[0.7rem] font-black text-[#1e2547] uppercase tracking-wider mb-2">Detail Proyek <span className="text-red-500">*</span></label>
                    <textarea
                      name="details"
                      value={formData.details}
                      onChange={handleInputChange}
                      required
                      rows={5}
                      placeholder="Ceritakan gambaran singkat proyek Anda, tujuan yang ingin dicapai, dan estimasi waktu jika ada..."
                      className="w-full px-5 py-4 rounded-[1.5rem] border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 placeholder:font-normal focus:outline-none focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/10 transition-all resize-none"
                    />
                  </div>

                  {/* Submit Area */}
                  <div className="pt-2">
                    <button
                      type="submit"
                      disabled={!selectedService}
                      className={`w-full flex items-center justify-center gap-2 rounded-full py-4 text-[0.95rem] font-bold transition-all duration-300 ${
                        selectedService
                          ? "bg-[#2563EB] text-white hover:bg-[#1d4ed8] shadow-lg shadow-[#2563EB]/25 cursor-pointer"
                          : "bg-slate-100 text-slate-400 cursor-not-allowed"
                      }`}
                    >
                      <Send className="w-4 h-4" strokeWidth={2.5} />
                      Kirim via WhatsApp
                    </button>
                    {!selectedService && (
                      <p className="text-red-500 text-xs font-semibold mt-3 text-center">
                        * Silakan pilih Layanan yang Dibutuhkan terlebih dahulu
                      </p>
                    )}
                  </div>
                </form>
              </div>
            </ScrollReveal>
          </div>

        </div>
      </div>
    </section>
  );
}
