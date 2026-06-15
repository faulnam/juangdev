"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { SectionHeader } from "@/components/shared/section-header";
import { ArrowUpRight, Globe, Monitor, ShoppingBag, Smartphone, Palette, Bot } from "lucide-react";

const iconMap: Record<string, React.ReactNode> = {
  Globe: <Globe className="w-6 h-6" />,
  Monitor: <Monitor className="w-6 h-6" />,
  ShoppingBag: <ShoppingBag className="w-6 h-6" />,
  Smartphone: <Smartphone className="w-6 h-6" />,
  Palette: <Palette className="w-6 h-6" />,
  Bot: <Bot className="w-6 h-6" />,
};

const colorStyles: Record<string, any> = {
  lime: {
    card: "bg-gradient-to-br from-[#C7F236] to-[#b0d62a] border-2 border-[#b0d62a] border-b-[8px] border-b-[#8da61b]",
    title: "text-[#0A1E5E]",
    desc: "text-[#0A1E5E]/75",
    price: "text-[#0A1E5E]",
    priceValue: "text-[#0A1E5E] font-bold",
    btn: "border-[#0A1E5E] text-[#0A1E5E] hover:bg-[#0A1E5E] hover:text-[#C7F236]",
    icon: "bg-[#0A1E5E]/10 text-[#0A1E5E]",
    tag: "bg-[#0A1E5E]/10 border-[#0A1E5E]/20 text-[#0A1E5E]",
  },
  blue: {
    card: "bg-gradient-to-br from-[#2563EB] to-[#0A1E5E] border-2 border-[#2563EB]/30 border-b-[8px] border-b-[#071542]",
    title: "text-white",
    desc: "text-white/80",
    price: "text-white/70",
    priceValue: "text-[#C7F236] font-bold",
    btn: "border-white text-white hover:bg-white hover:text-[#0A1E5E]",
    icon: "bg-white/15 text-white",
    tag: "bg-white/10 border-white/20 text-white/80",
  },
  white: {
    card: "bg-gradient-to-br from-white to-[#f8f9fc] border-2 border-slate-200 border-b-[8px] border-b-slate-300",
    title: "text-[#1e2547]",
    desc: "text-[#4f5b7d]",
    price: "text-[#4f5b7d]",
    priceValue: "text-[#2563EB] font-bold",
    btn: "border-[#1e2547] text-[#1e2547] hover:bg-[#1e2547] hover:text-white",
    icon: "bg-[#2563EB]/10 text-[#2563EB]",
    tag: "bg-slate-100 border-slate-200 text-slate-600",
  },
};

type Service = {
  id: number;
  title: string;
  description: string;
  icon: string | null;
  startingPrice: string | null;
  features: string | null;
  color: string | null;
};

export function ServicesClient({ data }: { data: Service[] }) {
  return (
    <section id="services" className="py-16 md:py-24 lg:py-28 bg-[#f8f9fc]">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <ScrollReveal>
          <SectionHeader
            title="JuangDev is a"
            highlight="modern creative studio"
            titleSuffix="that helps businesses build custom technology solutions."
            align="left"
          />
        </ScrollReveal>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {data.map((service, i) => {
            const styles = colorStyles[service.color || "white"] || colorStyles["white"];
            const featureList = service.features ? service.features.split(",").map(f => f.trim()) : [];
            const iconName = service.icon || "Globe";

            return (
              <ScrollReveal key={service.title} delay={i * 0.08}>
                <div
                  className={`flex flex-col justify-between h-full rounded-2xl p-6 sm:p-8 relative overflow-hidden shadow-xl hover:scale-[1.02] transition-all duration-300 ${styles.card}`}
                >
                  <div className="flex flex-col gap-3">
                    <div className={`w-12 h-12 rounded-xl flex items-center justify-center ${styles.icon}`}>
                      {iconMap[iconName] || <Globe className="w-6 h-6" />}
                    </div>
                    <h3 className={`text-xl sm:text-2xl font-bold tracking-tight ${styles.title}`}>
                      {service.title}
                    </h3>
                    <p className={`text-sm leading-relaxed ${styles.desc}`}>{service.description}</p>
                    <div className="flex flex-wrap gap-1.5 mt-1">
                      {featureList.map((f) => (
                        <span
                          key={f}
                          className={`text-[10px] border px-2 py-0.5 rounded font-semibold ${styles.tag}`}
                        >
                          {f}
                        </span>
                      ))}
                    </div>
                  </div>
                  <div className="mt-5 flex flex-col gap-3">
                    <div className={`text-xs ${styles.price}`}>
                      Starting from{" "}
                      <span className={`text-base ${styles.priceValue}`}>{service.startingPrice}</span>
                    </div>
                    <a
                      href="#contact"
                      className={`inline-flex items-center justify-center gap-2 font-semibold transition-all duration-200 text-sm bg-transparent border-2 w-fit py-1.5 px-4 rounded-xl group ${styles.btn}`}
                    >
                      Learn More
                      <ArrowUpRight className="w-4 h-4 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                    </a>
                  </div>
                </div>
              </ScrollReveal>
            );
          })}
        </div>
      </div>
    </section>
  );
}
