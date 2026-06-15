import { db } from "@/db";
import { testimonials } from "@/db/schema";
import { eq } from "drizzle-orm";
import { TestimonialsClient } from "./testimonials-client";

export async function Testimonials() {
  const data = await db.select().from(testimonials).where(eq(testimonials.isActive, true)).orderBy(testimonials.createdAt);
  
  return <TestimonialsClient data={data} />;
}
