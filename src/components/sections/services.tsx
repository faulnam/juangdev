import { db } from "@/db";
import { services } from "@/db/schema";
import { eq } from "drizzle-orm";
import { ServicesClient } from "./services-client";

export async function Services() {
  const data = await db.select().from(services).where(eq(services.isActive, true)).orderBy(services.orderIndex, services.createdAt);
  
  return <ServicesClient data={data} />;
}
