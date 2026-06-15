import { neon } from '@neondatabase/serverless';
import { drizzle } from 'drizzle-orm/neon-http';
import * as schema from './schema';

// This uses process.env.DATABASE_URL
const sql = neon(process.env.DATABASE_URL!);

// Connect Drizzle to Neon
export const db = drizzle(sql, { schema });
