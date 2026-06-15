"use client";

import { useState, useMemo } from "react";
import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { ArrowRight, Calculator } from "lucide-react";
import { WHATSAPP_URL } from "@/lib/constants";

const services = [
  { id: "landing", name: "Landing Page", price: 99000 },
  { id: "company", name: "Company Profile", price: 199000 },
  { id: "ecommerce", name: "E-Commerce", price: 399000 },
  { id: "sistem", name: "Sistem Informasi", price: 499000 },
  { id: "webapp", name: "Custom Web App", price: 999000 },
];

const features = [
  { id: "domain", name: "Domain .com", price: 150000 },
  { id: "hosting", name: "Hosting", price: 350000 },
  { id: "maintenance", name: "Maintenance Website", price: 200000 },
  { id: "seo", name: "SEO Advanced", price: 650000 },
  { id: "copywriting", name: "Copywriting", price: 300000 },
  { id: "payment", name: "Payment Gateway", price: 350000 },
  { id: "api", name: "API Integration", price: 650000 },
  { id: "blog", name: "Blog/News System", price: 350000 },
  { id: "lang", name: "Multi-Language", price: 550000 },
  { id: "analytics", name: "Google Analytics Setup", price: 150000 },
];

const designs = [
  { id: "basic", name: "Basic Template", price: 0 },
  { id: "premium", name: "Custom Premium", price: 2500000 },
  { id: "highend", name: "High-end Interactive", price: 5000000 },
];

export function CostPlanner() {
  const [selectedService, setSelectedService] = useState(services[0]);
  const [selectedFeatures, setSelectedFeatures] = useState<string[]>([]);
  const [selectedDesign, setSelectedDesign] = useState(designs[0]);

  const toggleFeature = (id: string) => {
    setSelectedFeatures(prev => 
      prev.includes(id) ? prev.filter(f => f !== id) : [...prev, id]
    );
  };

  const totalCost = useMemo(() => {
    const serviceCost = selectedService.price;
    const featuresCost = selectedFeatures.reduce((acc, id) => {
      const feature = features.find(f => f.id === id);
      return acc + (feature ? feature.price : 0);
    }, 0);
    const designCost = selectedDesign.price;
    return serviceCost + featuresCost + designCost;
  }, [selectedService, selectedFeatures, selectedDesign]);

  const formatRupiah = (number: number) => {
    return new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    }).format(number);
  };

  const handleConsult = () => {
    const fNames = selectedFeatures.map(id => features.find(f => f.id === id)?.name).join(", ");
    const msg = `Hello JuangDev, I have tried the project estimation calculator.\n\n*Layanan:* ${selectedService.name}\n*Fitur:* ${fNames || '-'}\n*Desain:* ${selectedDesign.name}\n*Total Estimate:* ${formatRupiah(totalCost)}\n\nI would like to consult further regarding this.`;
    window.open(`https://wa.me/6283852174877?text=${encodeURIComponent(msg)}`, '_blank');
  };

  return (
    <section className="py-20 bg-[#f8f9fc]">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <ScrollReveal>
          <div className="text-center mb-16">
            <h2 className="text-3xl md:text-4xl font-black text-[#1a1f3c] leading-tight tracking-tight mb-4">
              Interactive Project <span className="text-[#2563EB] font-serif italic">Cost Planner</span>
            </h2>
            <p className="text-[#64748b] text-[0.95rem] md:text-base leading-relaxed max-w-2xl mx-auto font-medium">
              Hitung estimasi biaya proyek Anda secara transparan dengan kalkulator interaktif kami sebelum memulai konsultasi.
            </p>
          </div>
        </ScrollReveal>

        <div className="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-8 lg:gap-12 items-start">
          
          {/* Left Column: Options */}
          <ScrollReveal delay={0.1}>
            <div className="bg-white rounded-3xl p-6 md:p-10 shadow-xl shadow-slate-200/50 border-2 border-slate-200 border-b-[8px] border-b-slate-300 flex flex-col gap-10">
              
              {/* Step 1 */}
              <div>
                <div className="flex items-center gap-3 mb-5">
                  <div className="w-8 h-8 rounded-full bg-[#2563EB] text-white flex items-center justify-center font-bold text-sm">1</div>
                  <h3 className="font-bold text-[#1a1f3c] text-lg">Select Service Type</h3>
                </div>
                <div className="flex flex-wrap gap-3 pl-11">
                  {services.map(svc => (
                    <button
                      key={svc.id}
                      onClick={() => setSelectedService(svc)}
                      className={`px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 border-2 ${
                        selectedService.id === svc.id
                          ? "bg-[#2563EB]/10 border-[#2563EB] text-[#2563EB]"
                          : "bg-white border-slate-200 text-slate-500 hover:border-[#2563EB]/50 hover:text-[#2563EB]"
                      }`}
                    >
                      {svc.name}
                    </button>
                  ))}
                </div>
              </div>

              {/* Step 2 */}
              <div>
                <div className="flex items-center gap-3 mb-5">
                  <div className="w-8 h-8 rounded-full bg-[#2563EB] text-white flex items-center justify-center font-bold text-sm">2</div>
                  <h3 className="font-bold text-[#1a1f3c] text-lg">Add-on Features</h3>
                </div>
                <div className="flex flex-wrap gap-3 pl-11">
                  {features.map(ft => {
                    const isSelected = selectedFeatures.includes(ft.id);
                    return (
                      <button
                        key={ft.id}
                        onClick={() => toggleFeature(ft.id)}
                        className={`px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 border-2 ${
                          isSelected
                            ? "bg-[#2563EB]/10 border-[#2563EB] text-[#2563EB]"
                            : "bg-white border-slate-200 text-slate-500 hover:border-[#2563EB]/50 hover:text-[#2563EB]"
                        }`}
                      >
                        {ft.name}
                      </button>
                    );
                  })}
                </div>
              </div>

              {/* Step 3 */}
              <div>
                <div className="flex items-center gap-3 mb-5">
                  <div className="w-8 h-8 rounded-full bg-[#2563EB] text-white flex items-center justify-center font-bold text-sm">3</div>
                  <h3 className="font-bold text-[#1a1f3c] text-lg">Tingkat Desain UI/UX</h3>
                </div>
                <div className="flex flex-wrap gap-3 pl-11">
                  {designs.map(dsg => (
                    <button
                      key={dsg.id}
                      onClick={() => setSelectedDesign(dsg)}
                      className={`px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 border-2 ${
                        selectedDesign.id === dsg.id
                          ? "bg-[#2563EB]/10 border-[#2563EB] text-[#2563EB]"
                          : "bg-white border-slate-200 text-slate-500 hover:border-[#2563EB]/50 hover:text-[#2563EB]"
                      }`}
                    >
                      {dsg.name}
                    </button>
                  ))}
                </div>
              </div>

            </div>
          </ScrollReveal>

          {/* Right Column: Receipt / Calculator Card */}
          <ScrollReveal delay={0.2} className="lg:sticky lg:top-32">
            <div className="bg-[#0A1E5E] rounded-3xl p-8 md:p-10 shadow-2xl shadow-[#0A1E5E]/20 relative overflow-hidden flex flex-col h-full min-h-[450px] border-2 border-[#0A1E5E] border-b-[8px] border-b-[#05113A]">
              <div className="absolute top-0 right-0 w-64 h-64 bg-[#1d4ed8] rounded-full blur-[80px] opacity-20 -translate-y-1/2 translate-x-1/3" />
              
              <div className="flex items-center gap-3 mb-8 relative z-10 border-b border-white/10 pb-6">
                <div className="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-[#C7F236]">
                  <Calculator className="w-5 h-5" />
                </div>
                <h3 className="text-white font-bold text-xl">Total Estimate</h3>
              </div>

              <div className="flex-grow space-y-6 relative z-10 mb-8">
                <div className="flex justify-between items-end">
                  <div className="flex flex-col">
                    <span className="text-white/60 text-xs font-bold uppercase tracking-wider mb-1">Layanan</span>
                    <span className="text-white font-medium text-[0.95rem]">{selectedService.name}</span>
                  </div>
                  <span className="text-white font-bold">{formatRupiah(selectedService.price)}</span>
                </div>

                {selectedFeatures.length > 0 && (
                  <div className="flex justify-between items-start">
                    <div className="flex flex-col">
                      <span className="text-white/60 text-xs font-bold uppercase tracking-wider mb-1">Add-on Features ({selectedFeatures.length})</span>
                      <div className="flex flex-col gap-1">
                        {selectedFeatures.map(id => {
                          const f = features.find(x => x.id === id);
                          return f ? <span key={id} className="text-white font-medium text-[0.95rem]">+ {f.name}</span> : null;
                        })}
                      </div>
                    </div>
                  </div>
                )}

                <div className="flex justify-between items-end">
                  <div className="flex flex-col">
                    <span className="text-white/60 text-xs font-bold uppercase tracking-wider mb-1">Desain</span>
                    <span className="text-white font-medium text-[0.95rem]">{selectedDesign.name}</span>
                  </div>
                  <span className="text-white font-bold">{formatRupiah(selectedDesign.price)}</span>
                </div>
              </div>

              <div className="relative z-10 pt-6 border-t border-white/10">
                <p className="text-white/60 text-xs font-bold uppercase tracking-wider mb-2">Total Estimated Cost</p>
                <p className="text-4xl font-black text-[#C7F236] mb-8">{formatRupiah(totalCost)}</p>

                <button 
                  onClick={handleConsult}
                  className="w-full py-4 bg-[#C7F236] text-[#0A1E5E] font-bold rounded-full text-[0.95rem] hover:bg-[#b5dd2a] transition-all shadow-lg shadow-[#C7F236]/20 flex justify-center items-center gap-2"
                >
                  Konsultasikan Estimasi Ini
                  <ArrowRight className="w-4 h-4" />
                </button>
              </div>

            </div>
          </ScrollReveal>

        </div>
      </div>
    </section>
  );
}
