"use client";

import { ScrollReveal } from "@/components/shared/scroll-reveal";
import { SectionHeader } from "@/components/shared/section-header";
import { BLOG_POSTS } from "@/lib/constants";
import { ArrowUpRight, Clock, Calendar } from "lucide-react";

export function BlogPreview() {
  return (
    <section id="blog" className="py-16 md:py-24 lg:py-28 bg-white">
      <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-8">
        <ScrollReveal>
          <SectionHeader
            title="Latest from Our"
            highlight="Blog"
            description="Insights, tutorials, and industry trends from our team of experts."
          />
        </ScrollReveal>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {BLOG_POSTS.map((post, i) => (
            <ScrollReveal key={post.slug} delay={i * 0.1}>
              <article className="group bg-white border-2 border-slate-200 border-b-[6px] border-b-slate-300 rounded-2xl overflow-hidden shadow-lg hover:scale-[1.02] transition-all duration-300 h-full flex flex-col">
                {/* Image */}
                <div className="aspect-[16/9] bg-gradient-to-br from-[#0A1E5E] to-[#2563EB] relative overflow-hidden">
                  <div className="absolute inset-0 flex items-center justify-center">
                    <span className="text-white/10 text-8xl font-black">{post.title.charAt(0)}</span>
                  </div>
                  <div className="absolute top-3 left-3">
                    <span className="bg-[#C7F236] text-[#0A1E5E] text-[10px] font-extrabold px-2.5 py-1 rounded-full">
                      {post.category}
                    </span>
                  </div>
                </div>

                {/* Content */}
                <div className="p-5 sm:p-6 flex flex-col flex-grow">
                  <div className="flex items-center gap-4 text-xs text-[#4f5b7d] mb-3">
                    <span className="flex items-center gap-1">
                      <Calendar className="w-3 h-3" />
                      {post.date}
                    </span>
                    <span className="flex items-center gap-1">
                      <Clock className="w-3 h-3" />
                      {post.readTime}
                    </span>
                  </div>

                  <h3 className="text-lg font-bold text-[#1e2547] mb-2 group-hover:text-[#2563EB] transition-colors duration-200 leading-snug">
                    {post.title}
                  </h3>
                  <p className="text-sm text-[#4f5b7d] leading-relaxed mb-4 flex-grow">
                    {post.excerpt}
                  </p>

                  <div className="border-t border-slate-100 pt-3 mt-auto">
                    <button className="flex items-center gap-1 text-[#2563EB] text-sm font-extrabold hover:gap-2 transition-all duration-200 group/link cursor-pointer">
                      Read More
                      <ArrowUpRight className="w-4 h-4 transition-transform duration-200 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5" />
                    </button>
                  </div>
                </div>
              </article>
            </ScrollReveal>
          ))}
        </div>
      </div>
    </section>
  );
}
