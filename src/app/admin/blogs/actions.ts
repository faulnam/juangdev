"use server";

import { db } from "@/db";
import { blogs } from "@/db/schema";
import { revalidatePath } from "next/cache";
import { eq } from "drizzle-orm";

export async function addBlog(formData: FormData) {
  const title = formData.get("title") as string;
  const excerpt = formData.get("excerpt") as string;
  const category = formData.get("category") as string;
  const readTime = formData.get("readTime") as string;
  const date = formData.get("date") as string;
  const imageUrl = formData.get("imageUrl") as string;

  if (!title || !excerpt || !category) return { error: "Title, excerpt, and category are required" };

  const slug = title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');

  try {
    await db.insert(blogs).values({ 
      title, 
      slug,
      excerpt, 
      category, 
      readTime, 
      date,
      imageUrl 
    });
    revalidatePath("/admin/blogs");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Add blog error", error);
    return { error: "Failed to add blog" };
  }
}

export async function deleteBlog(id: number) {
  try {
    await db.delete(blogs).where(eq(blogs.id, id));
    revalidatePath("/admin/blogs");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Delete blog error", error);
    return { error: "Failed to delete blog" };
  }
}

export async function toggleBlogStatus(id: number, isActive: boolean) {
  try {
    await db.update(blogs).set({ isActive: !isActive }).where(eq(blogs.id, id));
    revalidatePath("/admin/blogs");
    revalidatePath("/");
    return { success: true };
  } catch (error) {
    console.error("Toggle blog error", error);
    return { error: "Failed to toggle blog status" };
  }
}
