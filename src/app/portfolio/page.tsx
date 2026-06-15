import { Navbar } from "@/components/sections/navbar";
import { Footer } from "@/components/sections/footer";
import { PortfolioHero } from "./hero";
import { PortfolioGrid } from "./grid";
import { PortfolioGuarantees } from "./guarantees";
import { PortfolioCTA } from "./cta";

import { db } from "@/db";
import { portfolios } from "@/db/schema";
import { eq, desc } from "drizzle-orm";

export const metadata = {
  title: "Portfolio - JuangDev",
  description: "Explore the portfolio of digital product case studies designed by JuangDev.",
};

export default async function PortfolioPage() {
  const allPortfolios = await db.select().from(portfolios).where(eq(portfolios.isActive, true)).orderBy(desc(portfolios.createdAt));

  return (
    <>
      <Navbar />
      <main className="flex-grow bg-[#f8f9fc] relative">
        <PortfolioHero />
        <PortfolioGrid initialData={allPortfolios} />
        <PortfolioGuarantees />
        <div className="pb-32 bg-white">
          <PortfolioCTA />
        </div>
      </main>
      <Footer />
    </>
  );
}
