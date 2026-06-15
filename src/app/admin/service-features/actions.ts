"use server";

import { db } from "@/db";
import { serviceFeatures } from "@/db/schema";
import { revalidatePath } from "next/cache";
import { eq } from "drizzle-orm";

export async function addServiceFeature(formData: FormData) {
  const name = formData.get("name") as string;
  const priceStr = formData.get("price") as string;
  const serviceIdStr = formData.get("serviceId") as string;
  
  const price = parseInt(priceStr?.replace(/\D/g, "") || "0", 10);
  const serviceId = parseInt(serviceIdStr, 10);

  if (!name || isNaN(serviceId)) return { error: "Name and service are required" };

  try {
    await db.insert(serviceFeatures).values({ name, price, serviceId });
    revalidatePath("/admin/service-features");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Add service feature error", error);
    return { error: "Failed to add service feature" };
  }
}

export async function deleteServiceFeature(id: number) {
  try {
    await db.delete(serviceFeatures).where(eq(serviceFeatures.id, id));
    revalidatePath("/admin/service-features");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Delete service feature error", error);
    return { error: "Failed to delete service feature" };
  }
}

export async function toggleServiceFeatureStatus(id: number, isActive: boolean) {
  try {
    await db.update(serviceFeatures).set({ isActive: !isActive }).where(eq(serviceFeatures.id, id));
    revalidatePath("/admin/service-features");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Toggle service feature error", error);
    return { error: "Failed to toggle service feature status" };
  }
}
