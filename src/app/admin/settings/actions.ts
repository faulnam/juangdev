"use server";

import { db } from "@/db";
import { siteSettings } from "@/db/schema";
import { revalidatePath } from "next/cache";

export async function updateSetting(formData: FormData) {
  const key = formData.get("key") as string;
  const value = formData.get("value") as string;

  if (!key) return { error: "Key is required" };

  try {
    await db.insert(siteSettings)
      .values({ key, value })
      .onConflictDoUpdate({
        target: siteSettings.key,
        set: { value, updatedAt: new Date() }
      });
      
    revalidatePath("/admin/settings");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Failed to update setting", error);
    return { error: "Failed to update" };
  }
}
