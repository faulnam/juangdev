"use server";

import { db } from "@/db";
import { admins } from "@/db/schema";
import { eq } from "drizzle-orm";
import bcrypt from "bcryptjs";
import { SignJWT } from "jose";
import { cookies } from "next/headers";

const JWT_SECRET = new TextEncoder().encode(
  process.env.JWT_SECRET || 'fallback_secret_for_development_only'
);

export async function loginAdmin(prevState: any, formData: FormData) {
  const username = formData.get("username") as string;
  const password = formData.get("password") as string;

  if (!username || !password) {
    return { error: "Username and password are required" };
  }

  try {
    const adminList = await db.select().from(admins).where(eq(admins.username, username));
    
    if (adminList.length === 0) {
      return { error: "Invalid username or password" };
    }

    const admin = adminList[0];
    const isValid = await bcrypt.compare(password, admin.passwordHash);

    if (!isValid) {
      return { error: "Invalid username or password" };
    }

    // Create JWT
    const token = await new SignJWT({ sub: admin.id.toString(), username: admin.username })
      .setProtectedHeader({ alg: 'HS256' })
      .setIssuedAt()
      .setExpirationTime('1d')
      .sign(JWT_SECRET);

    // Set cookie
    (await cookies()).set("admin_token", token, {
      httpOnly: true,
      secure: process.env.NODE_ENV === "production",
      sameSite: "lax",
      maxAge: 60 * 60 * 24, // 1 day
      path: "/",
    });

    return { success: true };
  } catch (error) {
    console.error("Login error:", error);
    return { error: "An unexpected error occurred" };
  }
}

export async function logoutAdmin() {
  (await cookies()).delete("admin_token");
}
