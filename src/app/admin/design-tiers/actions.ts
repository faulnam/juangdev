"use server";

import { db } from "@/db";
import { designTiers } from "@/db/schema";
import { revalidatePath } from "next/cache";
import { eq } from "drizzle-orm";

export async function addDesignTier(formData: FormData) {
  const name = formData.get("name") as string;
  const priceStr = formData.get("price") as string;
  const colorTheme = formData.get("colorTheme") as string;
  
  const price = parseInt(priceStr?.replace(/\D/g, "") || "0", 10);

  if (!name || !colorTheme) return { error: "Name and color theme are required" };

  try {
    await db.insert(designTiers).values({ name, price, colorTheme });
    revalidatePath("/admin/design-tiers");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Add design tier error", error);
    return { error: "Failed to add design tier" };
  }
}

export async function deleteDesignTier(id: number) {
  try {
    await db.delete(designTiers).where(eq(designTiers.id, id));
    revalidatePath("/admin/design-tiers");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Delete design tier error", error);
    return { error: "Failed to delete design tier" };
  }
}

export async function toggleDesignTierStatus(id: number, isActive: boolean) {
  try {
    await db.update(designTiers).set({ isActive: !isActive }).where(eq(designTiers.id, id));
    revalidatePath("/admin/design-tiers");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Toggle design tier error", error);
    return { error: "Failed to toggle design tier status" };
  }
}
