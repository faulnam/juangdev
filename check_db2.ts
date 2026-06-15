import { drizzle } from 'drizzle-orm/neon-http';
import { neon } from '@neondatabase/serverless';
import * as dotenv from 'dotenv';
import { sql } from 'drizzle-orm';

dotenv.config({ path: '.env.local' });

const sqlQuery = neon(process.env.DATABASE_URL!);
const db = drizzle(sqlQuery);

async function check() {
  try {
    const res = await db.execute(sql`SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'`);
    console.log("Tables:", res.rows.map(r => r.table_name));
    
    // Also try querying pricing_plans
    const plans = await db.execute(sql`SELECT * FROM pricing_plans`);
    console.log("Pricing plans:", plans.rows);
  } catch (err) {
    console.error("Error:", err);
  }
}

check();
