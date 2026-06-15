import * as dotenv from 'dotenv';
dotenv.config({ path: '.env.local' });

import { db } from "./index";
import { admins } from "./schema";
import bcrypt from "bcryptjs";

async function seedAdmin() {
  const username = process.argv[2] || "admin";
  const password = process.argv[3] || "password123";

  console.log(`Seeding admin: ${username}...`);

  try {
    const passwordHash = await bcrypt.hash(password, 10);
    await db.insert(admins).values({
      username,
      passwordHash,
    });
    console.log(`Admin '${username}' created successfully!`);
    process.exit(0);
  } catch (error) {
    console.error("Error creating admin:", error);
    process.exit(1);
  }
}

seedAdmin();
