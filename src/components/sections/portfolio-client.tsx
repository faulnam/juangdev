"use client";

import { useRef } from "react";
import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { SectionHeader } from "@/components/shared/section-header";
import { ArrowUpRight, ChevronLeft, ChevronRight } from "lucide-react";

type Portfolio = {
  id: number;
  title: string;
  description: string;
  imageUrl: string | null;
  link: string | null;
  category: string | null;
};

export function PortfolioClient({ data }: { data: Portfolio[] }) {
  const scrollRef = useRef<HTMLDivElement>(null);

  const scroll = (direction: "left" | "right") => {
    if (!scrollRef.current) return;
    const amount = 400;
    scrollRef.current.scrollBy({
      left: direction === "left" ? -amount : amount,
      behavior: "smooth",
    });
  };

  return (
    <section id="portfolio" className="py-16 md:py-24 lg:py-28 bg-[#f8f9fc] relative overflow-hidden">
      <div className="absolute top-1/2 -right-40 w-96 h-96 rounded-full bg-[#2563EB]/5 blur-3xl pointer-events-none" />

      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <div className="flex flex-col md:flex-row md:items-end justify-between mb-12 md:mb-16 gap-6">
          <ScrollReveal className="max-w-2xl">
            <SectionHeader
              title="Projects We've"
              highlight="Delivered"
              description="Every project is a success story. Here are some of the solutions we've built with our clients."
              align="left"
            />
          </ScrollReveal>

          <div className="flex items-center gap-3 self-start md:self-end">
            <button
              onClick={() => scroll("left")}
              className="w-12 h-12 rounded-full border-2 border-slate-200 border-b-[4px] border-b-slate-300 flex items-center justify-center bg-white text-[#1e2547] hover:border-[#2563EB] hover:text-[#2563EB] hover:bg-[#2563EB]/5 transition-all duration-200 shadow-sm"
              aria-label="Previous project"
            >
              <ChevronLeft className="w-5 h-5" />
            </button>
            <button
              onClick={() => scroll("right")}
              className="w-12 h-12 rounded-full border-2 border-[#2563EB] border-b-[4px] border-b-[#0A1E5E] flex items-center justify-center bg-white text-[#2563EB] hover:bg-[#2563EB] hover:text-white transition-all duration-200 shadow-sm"
              aria-label="Next project"
            >
              <ChevronRight className="w-5 h-5" />
            </button>
          </div>
        </div>

        <div
          ref={scrollRef}
          className="flex overflow-x-auto gap-6 pb-8 snap-x snap-mandatory scroll-smooth [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none] -mx-6 sm:mx-0"
        >
          <div className="w-6 shrink-0 sm:hidden" />

          {data.map((project, i) => (
            <div
              key={project.id}
              className="w-[80vw] sm:w-[385px] shrink-0 snap-start snap-always scroll-ml-6 sm:scroll-ml-0"
            >
              <ScrollReveal delay={i * 0.08}>
                <div className="group bg-white border-2 border-slate-200 border-b-[6px] border-b-slate-300 rounded-2xl overflow-hidden transition-all duration-300 hover:scale-[1.02] shadow-lg flex flex-col h-full">
                  {/* Image */}
                  <div className="aspect-[16/10] bg-gradient-to-br from-[#0A1E5E] to-[#2563EB] flex items-center justify-center relative overflow-hidden border-b-2 border-slate-200">
                    {project.imageUrl ? (
                      // eslint-disable-next-line @next/next/no-img-element
                      <img src={project.imageUrl} alt={project.title} className="w-full h-full object-cover" />
                    ) : (
                      <div className="text-white/20 text-6xl font-black">{project.title.charAt(0)}</div>
                    )}
                    <div className="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                  </div>

                  {/* Content */}
                  <div className="p-5 flex flex-col flex-grow">
                    <div className="mb-2.5">
                      <span className="inline-block bg-[#C7F236] border border-[#b5dd2a] text-[#0A1E5E] text-[10px] font-extrabold rounded px-2 py-0.5 shadow-[1.5px_1.5px_0_rgba(0,0,0,0.08)]">
                        {project.category || "Uncategorized"}
                      </span>
                    </div>
                    <h3 className="text-lg font-extrabold text-slate-900 mb-1.5 group-hover:text-[#2563EB] transition-colors duration-200">
                      {project.title}
                    </h3>
                    <p className="text-slate-600 text-xs sm:text-sm leading-relaxed mb-3 flex-grow line-clamp-3">
                      {project.description}
                    </p>
                    
                    {/* techStack logic if I wanted to implement it, maybe in the future via tags. Skipping for now as it's not in DB yet */}
                    
                    <div className="border-t border-slate-100 pt-3 mt-auto">
                      <a href={project.link || "#"} target="_blank" rel="noopener noreferrer" className="flex items-center gap-1 text-[#2563EB] text-sm font-extrabold hover:gap-2 transition-all duration-200 group/link cursor-pointer">
                        View Case Study
                        <ArrowUpRight className="w-4 h-4 transition-transform duration-200 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5" />
                      </a>
                    </div>
                  </div>
                </div>
              </ScrollReveal>
            </div>
          ))}

          <div className="w-6 shrink-0 sm:hidden" />
        </div>
      </div>
    </section>
  );
}
