import { db } from './src/db/index';
import { blogs } from './src/db/schema';
import * as dotenv from 'dotenv';

dotenv.config({ path: '.env.local' });

const BLOG_POSTS = [
  {
    title: "Why Next.js is the Future of Web Development in 2026",
    excerpt: "Discover how Next.js 15 with React Server Components is revolutionizing the way we build modern web applications.",
    category: "Technology",
    date: "June 10, 2026",
    readTime: "5 min read",
    imageUrl: "/blog/nextjs-future.jpg",
    slug: "nextjs-future-2026",
  },
  {
    title: "10 Essential Features Every E-Commerce Website Needs",
    excerpt: "From seamless checkout to personalized recommendations — learn what separates successful online stores from the rest.",
    category: "E-Commerce",
    date: "June 5, 2026",
    readTime: "7 min read",
    imageUrl: "/blog/ecommerce-features.jpg",
    slug: "ecommerce-essential-features",
  },
  {
    title: "How AI is Transforming Business Automation",
    excerpt: "Explore how AI-powered chatbots, workflow automation, and predictive analytics are helping businesses save time and money.",
    category: "AI & Automation",
    date: "May 28, 2026",
    readTime: "6 min read",
    imageUrl: "/blog/ai-automation.jpg",
    slug: "ai-business-automation",
  },
];

async function seedBlogs() {
  console.log("Seeding blogs...");
  try {
    const existingBlogs = await db.select().from(blogs);
    if (existingBlogs.length === 0) {
      await db.insert(blogs).values(BLOG_POSTS);
      console.log("Successfully seeded blogs!");
    } else {
      console.log("Blogs already seeded.");
    }
  } catch (e) {
    console.error("Failed to seed blogs:", e);
  }
}

seedBlogs();
