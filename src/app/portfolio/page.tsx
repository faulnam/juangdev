import { Navbar } from "@/components/sections/navbar";
import { Footer } from "@/components/sections/footer";
import { PortfolioHero } from "./hero";
import { PortfolioGrid } from "./grid";
import { PortfolioGuarantees } from "./guarantees";
import { PortfolioCTA } from "./cta";

export const metadata = {
  title: "Portfolio - JuangDev",
  description: "Jelajahi portofolio studi kasus produk digital rancangan JuangDev.",
};

export default function PortfolioPage() {
  return (
    <>
      <Navbar />
      <main className="flex-grow bg-[#f8f9fc] relative">
        <PortfolioHero />
        <PortfolioGrid />
        <PortfolioGuarantees />
        <div className="pb-32 bg-white">
          <PortfolioCTA />
        </div>
      </main>
      <Footer />
    </>
  );
}
