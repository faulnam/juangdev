import { Navbar } from "@/components/sections/navbar";
import { Footer } from "@/components/sections/footer";
import { ServicesHero } from "./hero";
import { WebService } from "./web-service";
import { MobileService } from "./mobile-service";
import { CostPlanner } from "./cost-planner";
import { ServicesFAQ } from "./faq";
import { ServicesCTA } from "./cta";

export const metadata = {
  title: "Services - JuangDev",
  description: "Layanan pembuatan website, aplikasi mobile, dan desain UI/UX dari JuangDev.",
};

export default function ServicesPage() {
  return (
    <>
      <Navbar />
      <main className="flex-grow bg-[#f8f9fc]">
        <ServicesHero />
        <WebService />
        <MobileService />
        <CostPlanner />
        <ServicesCTA />
        <ServicesFAQ />
      </main>
      <Footer />
    </>
  );
}
