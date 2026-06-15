import { db } from "@/db";
import { pricingPlans } from "@/db/schema";
import { eq } from "drizzle-orm";
import { PricingClient } from "./pricing-client";

export async function Pricing() {
  const data = await db.select().from(pricingPlans).where(eq(pricingPlans.isActive, true)).orderBy(pricingPlans.orderIndex, pricingPlans.createdAt);
  
  return <PricingClient plans={data} />;
}
