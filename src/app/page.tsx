import { Navbar } from "@/components/sections/navbar";
import { Hero } from "@/components/sections/hero";
import { About } from "@/components/sections/about";
import { Services } from "@/components/sections/services";

import { Portfolio } from "@/components/sections/portfolio";
import { Process } from "@/components/sections/process";
import { Pricing } from "@/components/sections/pricing";
import { Testimonials } from "@/components/sections/testimonials";

import { FAQ } from "@/components/sections/faq";
import { BlogPreview } from "@/components/sections/blog-preview";
import { ProjectEstimator } from "@/components/sections/project-estimator";
import { FinalCta } from "@/components/sections/final-cta";
import { Footer } from "@/components/sections/footer";
import { FloatingWhatsApp } from "@/components/shared/floating-whatsapp";
import { BackToTop } from "@/components/shared/back-to-top";

export default function Home() {
  return (
    <>
      <Navbar />
      <main className="flex-grow">
        <Hero />
        <About />
        <Services />
        <Portfolio />
        <Process />
        <Pricing />
        <Testimonials />

        <FAQ />
        <BlogPreview />
        <FinalCta />
        <ProjectEstimator />
      </main>
      <Footer />
      <FloatingWhatsApp />
      <BackToTop />
    </>
  );
}
