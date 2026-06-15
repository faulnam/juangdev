"use client";

import { useState, useMemo } from "react";
import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { Mail, MessageCircle, MapPin, Check, Send, Plus, Minus } from "lucide-react";

type Service = { id: number; title: string; basePrice: number; category: string | null; icon: string | null; description: string; imageUrl: string | null };
type DesignTier = { id: number; name: string; price: number; colorTheme: string };
type ServiceFeature = { id: number; serviceId: number; name: string; price: number };

export function ProjectEstimatorClient({
  services,
  designTiers,
  serviceFeatures
}: {
  services: Service[];
  designTiers: DesignTier[];
  serviceFeatures: ServiceFeature[];
}) {
  const [selectedServiceId, setSelectedServiceId] = useState<number | null>(null);
  const [selectedTierId, setSelectedTierId] = useState<number | null>(null);
  const [selectedFeatureIds, setSelectedFeatureIds] = useState<number[]>([]);

  const [formData, setFormData] = useState({
    name: "",
    phone: "",
    email: "",
    details: "",
  });

  const availableFeatures = useMemo(() => {
    if (!selectedServiceId) return [];
    return serviceFeatures.filter(f => f.serviceId === selectedServiceId);
  }, [selectedServiceId, serviceFeatures]);

  const toggleFeature = (id: number) => {
    setSelectedFeatureIds(prev => 
      prev.includes(id) ? prev.filter(fid => fid !== id) : [...prev, id]
    );
  };

  const totalPrice = useMemo(() => {
    let total = 0;
    if (selectedServiceId) {
      const s = services.find(s => s.id === selectedServiceId);
      if (s) total += s.basePrice;
    }
    if (selectedTierId) {
      const t = designTiers.find(t => t.id === selectedTierId);
      if (t) total += t.price;
    }
    selectedFeatureIds.forEach(fid => {
      const f = serviceFeatures.find(f => f.id === fid);
      if (f) total += f.price;
    });
    return total;
  }, [selectedServiceId, selectedTierId, selectedFeatureIds, services, designTiers, serviceFeatures]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedServiceId || !selectedTierId) return;
    
    const serviceName = services.find(s => s.id === selectedServiceId)?.title;
    const tierName = designTiers.find(t => t.id === selectedTierId)?.name;
    const featureNames = selectedFeatureIds.map(fid => serviceFeatures.find(f => f.id === fid)?.name).join(", ") || "None";

    const message = `Hello JuangDev team, I'd like to consult about a new project.\n\n*Selected Estimates:*\n- Layanan: ${serviceName}\n- Design Tier: ${tierName}\n- Add-on Features: ${featureNames}\n*Estimated Price:* Rp ${totalPrice.toLocaleString('id-ID')}\n\n*Name:* ${formData.name}\n*WhatsApp:* ${formData.phone}\n*Email:* ${formData.email || '-'}\n\n*Project Details:*\n${formData.details}`;
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
                Price Estimator<br />
                <span className="font-serif italic text-[#2563EB]">A La Carte</span>
              </h2>
              <p className="text-[#64748b] text-[0.95rem] md:text-base leading-relaxed mb-8 max-w-md font-medium">
                Choose the service, design tier, and add-on features you need. Get an instant price estimate for your digital project.
              </p>
            </ScrollReveal>

            <ScrollReveal delay={0.1}>
              <div className="bg-white border-2 border-slate-100 rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 mt-4">
                <p className="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Total Estimated Cost</p>
                <div className="text-4xl lg:text-5xl font-black text-[#1a1f3c]">
                  Rp {totalPrice.toLocaleString('id-ID')}
                </div>
                <p className="text-xs text-slate-500 mt-4 font-medium">* This price is only an initial estimate and may change depending on the actual project complexity.</p>
              </div>
            </ScrollReveal>
            
            <ScrollReveal delay={0.2}>
              <div className="bg-[#0A1E5E] rounded-2xl p-6 md:p-7 mt-6 shadow-xl shadow-[#0A1E5E]/20">
                <div className="flex items-start gap-4">
                  <div className="w-9 h-9 rounded-full bg-[#C7F236] text-[#0A1E5E] flex items-center justify-center shrink-0 mt-0.5">
                    <Check className="w-5 h-5" strokeWidth={3} />
                  </div>
                  <div>
                    <p className="text-[#C7F236] font-bold text-[0.9rem] mb-1.5 uppercase tracking-wide">Free Consultation Session</p>
                    <p className="text-white/85 text-[0.9rem] leading-relaxed font-medium">
                      A casual chat about your ideas. We help build a basic strategy without any upfront cost or commitment.
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
                
                <form onSubmit={handleSubmit} className="space-y-8">
                  
                  {/* Step 1: Services */}
                  <div>
                    <label className="flex items-center gap-2 text-[0.8rem] font-black text-[#1e2547] uppercase tracking-wider mb-4">
                      <span className="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs">1</span> 
                      Select Main Service <span className="text-red-500">*</span>
                    </label>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                      {services.map((service) => (
                        <button
                          key={service.id}
                          type="button"
                          onClick={() => {
                            setSelectedServiceId(service.id);
                            setSelectedFeatureIds([]); // reset features when service changes
                          }}
                          className={`flex flex-col text-left p-4 rounded-xl border-2 transition-all duration-200 ${
                            selectedServiceId === service.id
                              ? "bg-[#f0f4fc] border-[#2563EB]"
                              : "bg-white border-slate-100 hover:border-slate-300 hover:bg-slate-50"
                          }`}
                        >
                          <span className={`font-bold text-sm ${selectedServiceId === service.id ? "text-[#2563EB]" : "text-slate-700"}`}>
                            {service.title}
                          </span>
                          <span className="text-xs text-slate-500 font-semibold mt-1">
                            + Rp {service.basePrice.toLocaleString('id-ID')}
                          </span>
                        </button>
                      ))}
                    </div>
                  </div>

                  {/* Step 2: Design Tiers */}
                  <div className={!selectedServiceId ? "opacity-50 pointer-events-none" : ""}>
                    <label className="flex items-center gap-2 text-[0.8rem] font-black text-[#1e2547] uppercase tracking-wider mb-4">
                      <span className="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs">2</span> 
                      Design Tier <span className="text-red-500">*</span>
                    </label>
                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
                      {designTiers.map((tier) => (
                        <button
                          key={tier.id}
                          type="button"
                          onClick={() => setSelectedTierId(tier.id)}
                          className={`flex flex-col text-left p-4 rounded-xl border-2 transition-all duration-200 ${
                            selectedTierId === tier.id
                              ? "bg-amber-50 border-amber-500"
                              : "bg-white border-slate-100 hover:border-slate-300 hover:bg-slate-50"
                          }`}
                        >
                          <span className={`font-bold text-sm ${selectedTierId === tier.id ? "text-amber-700" : "text-slate-700"}`}>
                            {tier.name}
                          </span>
                          <span className="text-xs text-slate-500 font-semibold mt-1">
                            {tier.price === 0 ? "Gratis" : `+ Rp ${tier.price.toLocaleString('id-ID')}`}
                          </span>
                        </button>
                      ))}
                    </div>
                  </div>

                  {/* Step 3: Features */}
                  <div className={!selectedServiceId ? "hidden" : ""}>
                    <label className="flex items-center gap-2 text-[0.8rem] font-black text-[#1e2547] uppercase tracking-wider mb-4">
                      <span className="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs">3</span> 
                      Add-on Features (Opsional)
                    </label>
                    {availableFeatures.length === 0 ? (
                      <p className="text-sm text-slate-500 italic bg-slate-50 p-4 rounded-xl border border-slate-100">There are no specific add-on features for this service.</p>
                    ) : (
                      <div className="space-y-2">
                        {availableFeatures.map((feature) => (
                          <button
                            key={feature.id}
                            type="button"
                            onClick={() => toggleFeature(feature.id)}
                            className={`w-full flex items-center justify-between p-3 rounded-xl border transition-all duration-200 ${
                              selectedFeatureIds.includes(feature.id)
                                ? "bg-emerald-50 border-emerald-500"
                                : "bg-white border-slate-200 hover:border-slate-300"
                            }`}
                          >
                            <span className={`font-bold text-sm ${selectedFeatureIds.includes(feature.id) ? "text-emerald-700" : "text-slate-700"}`}>
                              {feature.name}
                            </span>
                            <div className="flex items-center gap-3">
                              <span className="text-xs font-semibold text-slate-500">
                                + Rp {feature.price.toLocaleString('id-ID')}
                              </span>
                              <div className={`w-6 h-6 rounded-md flex items-center justify-center text-white transition-colors ${selectedFeatureIds.includes(feature.id) ? "bg-emerald-500" : "bg-slate-200"}`}>
                                {selectedFeatureIds.includes(feature.id) ? <Check className="w-4 h-4" /> : <Plus className="w-4 h-4 text-slate-400" />}
                              </div>
                            </div>
                          </button>
                        ))}
                      </div>
                    )}
                  </div>

                  <hr className="border-slate-100" />

                  {/* Step 4: Contact Info */}
                  <div className={(!selectedServiceId || !selectedTierId) ? "opacity-50 pointer-events-none" : ""}>
                    <label className="flex items-center gap-2 text-[0.8rem] font-black text-[#1e2547] uppercase tracking-wider mb-4">
                      <span className="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs">4</span> 
                      Your Details
                    </label>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                      <input
                        type="text"
                        name="name"
                        value={formData.name}
                        onChange={handleInputChange}
                        required
                        placeholder="Full Name *"
                        className="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB]"
                      />
                      <input
                        type="tel"
                        name="phone"
                        value={formData.phone}
                        onChange={handleInputChange}
                        required
                        placeholder="WhatsApp *"
                        className="w-full px-5 py-3.5 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB]"
                      />
                    </div>
                    <textarea
                      name="details"
                      value={formData.details}
                      onChange={handleInputChange}
                      required
                      rows={3}
                      placeholder="Briefly tell us about your project details *"
                      className="w-full px-5 py-4 rounded-xl border-2 border-slate-100 bg-[#f8f9fc] text-[0.95rem] font-medium text-[#1a1f3c] placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB] resize-none mb-5"
                    />
                    
                    <button
                      type="submit"
                      disabled={!selectedServiceId || !selectedTierId}
                      className="w-full flex items-center justify-center gap-2 rounded-xl py-4 text-[0.95rem] font-bold transition-all duration-300 bg-[#2563EB] text-white hover:bg-[#1d4ed8] shadow-lg shadow-[#2563EB]/25"
                    >
                      <Send className="w-4 h-4" strokeWidth={2.5} />
                      Send Message via WhatsApp
                    </button>
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
