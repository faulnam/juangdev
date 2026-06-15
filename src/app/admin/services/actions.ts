"use server";

import { db } from "@/db";
import { services } from "@/db/schema";
import { revalidatePath } from "next/cache";
import { eq } from "drizzle-orm";

export async function addService(formData: FormData) {
  const title = formData.get("title") as string;
  const description = formData.get("description") as string;
  const icon = formData.get("icon") as string;
  const category = formData.get("category") as string;
  const imageUrl = formData.get("imageUrl") as string;
  const startingPrice = formData.get("startingPrice") as string; // keeping for backward compatibility if needed
  const features = formData.get("features") as string;
  const color = formData.get("color") as string;
  
  const basePriceStr = formData.get("basePrice") as string;
  const basePrice = parseInt(basePriceStr?.replace(/\D/g, "") || "0", 10);

  if (!title || !description) return { error: "Title and description are required" };

  try {
    await db.insert(services).values({ 
      title, 
      description, 
      icon, 
      category, 
      imageUrl, 
      startingPrice, 
      features, 
      color: color || "white",
      basePrice
    });
    revalidatePath("/admin/services");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Add service error", error);
    return { error: "Failed to add service" };
  }
}

export async function deleteService(id: number) {
  try {
    await db.delete(services).where(eq(services.id, id));
    revalidatePath("/admin/services");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Delete service error", error);
    return { error: "Failed to delete service" };
  }
}

export async function toggleServiceStatus(id: number, isActive: boolean) {
  try {
    await db.update(services).set({ isActive: !isActive }).where(eq(services.id, id));
    revalidatePath("/admin/services");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Toggle service error", error);
    return { error: "Failed to toggle service status" };
  }
}
