"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { Marquee } from "@/components/shared/marquee";
import { Star } from "lucide-react";

type Testimonial = {
  id: number;
  name: string;
  role: string | null;
  category: string | null;
  content: string;
  rating: number | null;
  imageUrl: string | null;
};

export function TestimonialsClient({ data }: { data: Testimonial[] }) {
  if (!data || data.length === 0) return null;

  return (
    <section className="py-20 md:py-28 lg:py-32 bg-[#0B1126] relative overflow-hidden">
      {/* Background subtle gradients */}
      <div className="absolute top-0 right-0 w-[600px] h-[600px] bg-[#1a2d6b] rounded-full blur-[140px] opacity-30 -translate-y-1/2 translate-x-1/3" />
      <div className="absolute bottom-0 left-0 w-[600px] h-[600px] bg-[#14265e] rounded-full blur-[140px] opacity-30 translate-y-1/2 -translate-x-1/3" />

      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8 relative z-10">
        <div className="mb-16 md:mb-20">
          <ScrollReveal className="max-w-3xl mx-auto text-center">
            <h2 className="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-white leading-tight tracking-tight mb-5">
              What Our <span className="text-[#C7F236] font-serif italic">Clients Say</span>
            </h2>
            <p className="text-[#94a3b8] text-base md:text-[1.05rem] leading-relaxed max-w-2xl mx-auto font-medium">
              Honest testimonials from business owners who have entrusted their digital platforms with the JuangDev team.
            </p>
          </ScrollReveal>
        </div>
      </div>

      <ScrollReveal>
        <div className="relative">
          {/* Gradient Edges for Marquee to fade out smoothly */}
          <div className="absolute left-0 top-0 bottom-0 w-16 md:w-40 bg-gradient-to-r from-[#0B1126] to-transparent z-10 pointer-events-none" />
          <div className="absolute right-0 top-0 bottom-0 w-16 md:w-40 bg-gradient-to-l from-[#0B1126] to-transparent z-10 pointer-events-none" />
          
          <Marquee speed={40} pauseOnHover={true} className="py-4">
            {data.map((testimonial, index) => {
              // Extract initials (e.g. "John Doe" -> "JD")
              const initials = testimonial.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

              return (
                <div
                  key={`${testimonial.id}-${index}`}
                  className="w-[85vw] sm:w-[380px] shrink-0 mx-3"
                >
                  <div className="bg-white rounded-[1.5rem] p-8 md:p-9 shadow-xl h-full flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1">
                    <div>
                      {/* Quote Mark */}
                      <div className="text-[#a5b4fc] font-serif text-[4rem] leading-none h-10 mb-6 opacity-80">
                        &ldquo;
                      </div>
                      
                      {/* Review Text */}
                      <p className="text-[#1e293b] text-[0.95rem] leading-relaxed font-semibold mb-8">
                        {testimonial.content}
                      </p>
                    </div>

                    <div>
                      {/* Stars */}
                      <div className="flex items-center gap-1 mb-5">
                        {Array.from({ length: testimonial.rating || 5 }).map((_, si) => (
                          <Star key={si} className="w-4 h-4 fill-[#eab308] text-[#eab308]" />
                        ))}
                      </div>

                      {/* User Info */}
                      <div className="flex items-center gap-3.5">
                        {testimonial.imageUrl ? (
                          // eslint-disable-next-line @next/next/no-img-element
                          <img src={testimonial.imageUrl} alt={testimonial.name} className="w-11 h-11 rounded-full object-cover shrink-0" />
                        ) : (
                          <div className="w-11 h-11 rounded-full bg-[#1e40af] flex items-center justify-center text-white text-[0.85rem] font-bold shrink-0">
                            {initials}
                          </div>
                        )}
                        <div className="flex flex-col">
                          <p className="text-[0.95rem] font-bold text-[#0f172a] leading-tight">
                            {testimonial.name}
                          </p>
                          <p className="text-[0.8rem] font-semibold text-[#64748b] mt-0.5">
                            {testimonial.role || "Client"}
                            {testimonial.category && ` • ${testimonial.category}`}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              );
            })}
          </Marquee>
        </div>
      </ScrollReveal>
    </section>
  );
}
