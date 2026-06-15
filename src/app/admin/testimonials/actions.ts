"use server";

import { db } from "@/db";
import { testimonials } from "@/db/schema";
import { revalidatePath } from "next/cache";
import { eq } from "drizzle-orm";

export async function addTestimonial(formData: FormData) {
  const name = formData.get("name") as string;
  const role = formData.get("role") as string;
  const content = formData.get("content") as string;
  const ratingStr = formData.get("rating") as string;
  const rating = ratingStr ? parseInt(ratingStr) : 5;

  if (!name || !content) return { error: "Name and content are required" };

  try {
    await db.insert(testimonials).values({ name, role, content, rating });
    revalidatePath("/admin/testimonials");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Add testimonial error", error);
    return { error: "Failed to add testimonial" };
  }
}

export async function deleteTestimonial(id: number) {
  try {
    await db.delete(testimonials).where(eq(testimonials.id, id));
    revalidatePath("/admin/testimonials");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Delete testimonial error", error);
    return { error: "Failed to delete testimonial" };
  }
}

export async function toggleTestimonialStatus(id: number, isActive: boolean) {
  try {
    await db.update(testimonials).set({ isActive: !isActive }).where(eq(testimonials.id, id));
    revalidatePath("/admin/testimonials");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Toggle testimonial error", error);
    return { error: "Failed to toggle testimonial status" };
  }
}
