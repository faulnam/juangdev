import { db } from "@/db";
import { blogs } from "@/db/schema";
import { eq } from "drizzle-orm";
import { BlogPreviewClient } from "./blog-preview-client";

export async function BlogPreview() {
  const data = await db.select().from(blogs).where(eq(blogs.isActive, true)).orderBy(blogs.createdAt);
  
  return <BlogPreviewClient data={data} />;
}
