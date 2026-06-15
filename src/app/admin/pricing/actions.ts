"use server";

import { db } from "@/db";
import { pricingPlans } from "@/db/schema";
import { revalidatePath } from "next/cache";
import { eq } from "drizzle-orm";

export async function addPricingPlan(formData: FormData) {
  const name = formData.get("name") as string;
  const description = formData.get("description") as string;
  const category = formData.get("category") as string;
  const price = formData.get("price") as string;
  const period = formData.get("period") as string;
  const features = formData.get("features") as string;
  const isPopular = formData.get("isPopular") === "on";

  if (!name || !price || !features || !category) return { error: "Missing required fields" };

  try {
    await db.insert(pricingPlans).values({ 
      name, 
      description, 
      category, 
      price, 
      period, 
      features, 
      isPopular 
    });
    revalidatePath("/admin/pricing");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Add pricing plan error", error);
    return { error: "Failed to add pricing plan" };
  }
}

export async function deletePricingPlan(id: number) {
  try {
    await db.delete(pricingPlans).where(eq(pricingPlans.id, id));
    revalidatePath("/admin/pricing");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Delete pricing plan error", error);
    return { error: "Failed to delete pricing plan" };
  }
}

export async function togglePricingPlanStatus(id: number, isActive: boolean) {
  try {
    await db.update(pricingPlans).set({ isActive: !isActive }).where(eq(pricingPlans.id, id));
    revalidatePath("/admin/pricing");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Toggle pricing plan error", error);
    return { error: "Failed to toggle pricing plan status" };
  }
}
