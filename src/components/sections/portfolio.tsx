import { db } from "@/db";
import { portfolios } from "@/db/schema";
import { eq } from "drizzle-orm";
import { PortfolioClient } from "./portfolio-client";

export async function Portfolio() {
  const data = await db.select().from(portfolios).where(eq(portfolios.isActive, true)).orderBy(portfolios.orderIndex, portfolios.createdAt);
  
  return <PortfolioClient data={data} />;
}
