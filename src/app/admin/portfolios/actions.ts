"use server";

import { db } from "@/db";
import { portfolios } from "@/db/schema";
import { revalidatePath } from "next/cache";
import { eq } from "drizzle-orm";

export async function addPortfolio(formData: FormData) {
  const title = formData.get("title") as string;
  const description = formData.get("description") as string;
  const imageUrl = formData.get("imageUrl") as string;
  const link = formData.get("link") as string;
  const category = formData.get("category") as string;

  if (!title || !description) return { error: "Title and description are required" };

  try {
    await db.insert(portfolios).values({ title, description, imageUrl, link, category });
    revalidatePath("/admin/portfolios");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Add portfolio error", error);
    return { error: "Failed to add portfolio item" };
  }
}

export async function deletePortfolio(id: number) {
  try {
    await db.delete(portfolios).where(eq(portfolios.id, id));
    revalidatePath("/admin/portfolios");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Delete portfolio error", error);
    return { error: "Failed to delete portfolio item" };
  }
}

export async function togglePortfolioStatus(id: number, isActive: boolean) {
  try {
    await db.update(portfolios).set({ isActive: !isActive }).where(eq(portfolios.id, id));
    revalidatePath("/admin/portfolios");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Toggle portfolio error", error);
    return { error: "Failed to toggle portfolio status" };
  }
}

export async function updatePortfolio(id: number, formData: FormData) {
  const title = formData.get("title") as string;
  const description = formData.get("description") as string;
  const imageUrl = formData.get("imageUrl") as string;
  const link = formData.get("link") as string;
  const category = formData.get("category") as string;

  if (!title || !description) return { error: "Title and description are required" };

  try {
    await db.update(portfolios)
      .set({ title, description, imageUrl, link, category, updatedAt: new Date() })
      .where(eq(portfolios.id, id));
    revalidatePath("/admin/portfolios");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Update portfolio error", error);
    return { error: "Failed to update portfolio item" };
  }
}
