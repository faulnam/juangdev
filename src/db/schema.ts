import { pgTable, serial, varchar, text, timestamp, boolean, integer } from 'drizzle-orm/pg-core';

export const contacts = pgTable('contacts', {
  id: serial('id').primaryKey(),
  name: varchar('name', { length: 255 }).notNull(),
  whatsapp: varchar('whatsapp', { length: 50 }).notNull(),
  email: varchar('email', { length: 255 }),
  service: varchar('service', { length: 100 }).notNull(),
  description: text('description').notNull(),
  createdAt: timestamp('created_at').defaultNow().notNull(),
});

export const admins = pgTable('admins', {
  id: serial('id').primaryKey(),
  username: varchar('username', { length: 255 }).notNull().unique(),
  passwordHash: varchar('password_hash', { length: 255 }).notNull(),
  createdAt: timestamp('created_at').defaultNow().notNull(),
});

export const siteSettings = pgTable('site_settings', {
  id: serial('id').primaryKey(),
  key: varchar('key', { length: 255 }).notNull().unique(),
  value: text('value'),
  description: text('description'),
  updatedAt: timestamp('updated_at').defaultNow().notNull(),
});

export const services = pgTable('services', {
  id: serial('id').primaryKey(),
  title: varchar('title', { length: 255 }).notNull(),
  description: text('description').notNull(),
  icon: varchar('icon', { length: 100 }), // e.g. lucide icon name
  imageUrl: varchar('image_url', { length: 255 }),
  category: varchar('category', { length: 100 }),
  startingPrice: varchar('starting_price', { length: 100 }), // keep old column
  features: text('features'), // keep old column
  color: varchar('color', { length: 50 }).default('white'), // keep old column
  basePrice: integer('base_price').default(0).notNull(), // new column
  isActive: boolean('is_active').default(true).notNull(),
  orderIndex: integer('order_index').default(0).notNull(),
  createdAt: timestamp('created_at').defaultNow().notNull(),
  updatedAt: timestamp('updated_at').defaultNow().notNull(),
});

export const serviceFeatures = pgTable('service_features', {
  id: serial('id').primaryKey(),
  serviceId: integer('service_id').references(() => services.id, { onDelete: 'cascade' }).notNull(),
  name: varchar('name', { length: 255 }).notNull(),
  price: integer('price').default(0).notNull(),
  isActive: boolean('is_active').default(true).notNull(),
  createdAt: timestamp('created_at').defaultNow().notNull(),
});

export const designTiers = pgTable('design_tiers', {
  id: serial('id').primaryKey(),
  name: varchar('name', { length: 255 }).notNull(),
  price: integer('price').default(0).notNull(),
  colorTheme: varchar('color_theme', { length: 50 }).default('white').notNull(), // lime, blue, white
  isActive: boolean('is_active').default(true).notNull(),
  createdAt: timestamp('created_at').defaultNow().notNull(),
});

export const pricingPlans = pgTable('pricing_plans', {
  id: serial('id').primaryKey(),
  name: varchar('name', { length: 255 }).notNull(),
  category: varchar('category', { length: 100 }).notNull(), // e.g. "Landing Page"
  price: varchar('price', { length: 100 }).notNull(), // e.g. "99k"
  period: varchar('period', { length: 100 }), // e.g. "/ bulan"
  description: text('description').notNull(),
  features: text('features').notNull(), // Comma separated list
  isPopular: boolean('is_popular').default(false).notNull(),
  isActive: boolean('is_active').default(true).notNull(),
  orderIndex: integer('order_index').default(0).notNull(),
  createdAt: timestamp('created_at').defaultNow().notNull(),
  updatedAt: timestamp('updated_at').defaultNow().notNull(),
});

export const portfolios = pgTable('portfolios', {
  id: serial('id').primaryKey(),
  title: varchar('title', { length: 255 }).notNull(),
  description: text('description').notNull(),
  imageUrl: varchar('image_url', { length: 255 }),
  link: varchar('link', { length: 255 }),
  category: varchar('category', { length: 100 }),
  techs: varchar('techs', { length: 255 }), // Comma separated techs
  isActive: boolean('is_active').default(true).notNull(),
  orderIndex: integer('order_index').default(0).notNull(),
  createdAt: timestamp('created_at').defaultNow().notNull(),
  updatedAt: timestamp('updated_at').defaultNow().notNull(),
});

export const testimonials = pgTable('testimonials', {
  id: serial('id').primaryKey(),
  name: varchar('name', { length: 255 }).notNull(),
  role: varchar('role', { length: 100 }),
  content: text('content').notNull(),
  rating: integer('rating').default(5),
  imageUrl: varchar('image_url', { length: 255 }),
  category: varchar('category', { length: 100 }),
  isActive: boolean('is_active').default(true).notNull(),
  createdAt: timestamp('created_at').defaultNow().notNull(),
});

export const blogs = pgTable('blogs', {
  id: serial('id').primaryKey(),
  title: varchar('title', { length: 255 }).notNull(),
  slug: varchar('slug', { length: 255 }).notNull().unique(),
  excerpt: text('excerpt').notNull(),
  category: varchar('category', { length: 100 }).notNull(),
  readTime: varchar('read_time', { length: 50 }),
  date: varchar('date', { length: 50 }),
  imageUrl: varchar('image_url', { length: 255 }),
  isActive: boolean('is_active').default(true).notNull(),
  createdAt: timestamp('created_at').defaultNow().notNull(),
  updatedAt: timestamp('updated_at').defaultNow().notNull(),
});
