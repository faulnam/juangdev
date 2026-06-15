import { db } from './src/db/index';
import { services, designTiers, serviceFeatures, pricingPlans, portfolios, testimonials } from './src/db/schema';
import { eq } from 'drizzle-orm';
import * as dotenv from 'dotenv';

dotenv.config({ path: '.env.local' });

const SERVICES = [
  { icon: "Globe", title: "Landing Page", description: "Halaman web tunggal yang dioptimasi secara khusus untuk memaksimalkan konversi pemasaran bisnis Anda.", startingPrice: "99K", features: "Desain Responsif, SEO Basic, Mobile Friendly", color: "lime", basePrice: 99000 },
  { icon: "Monitor", title: "Company Profile", description: "Website profesional dan elegan untuk membangun kredibilitas serta menampilkan identitas bisnis Anda ke publik.", startingPrice: "199K", features: "Hingga 5 Halaman, Galeri, Form Kontak", color: "blue", basePrice: 199000 },
  { icon: "ShoppingBag", title: "E-Commerce", description: "Toko online modern dengan sistem belanja terstruktur, lengkap dengan katalog produk dan checkout.", startingPrice: "399K", features: "Katalog Produk, Keranjang Belanja, Integrasi WA", color: "white", basePrice: 399000 },
  { icon: "Bot", title: "Sistem Informasi", description: "Sistem digitalisasi pendataan dan pelaporan untuk mempermudah operasional internal perusahaan Anda.", startingPrice: "499K", features: "Manajemen Data, Dashboard Analitik, Export Laporan", color: "blue", basePrice: 499000 },
  { icon: "Palette", title: "Custom Web App", description: "Pengembangan aplikasi web khusus dengan fitur kompleks yang dirancang mengikuti alur bisnis unik Anda.", startingPrice: "999K", features: "Desain UI/UX Custom, API Integration, Sistem Login/Role", color: "lime", basePrice: 999000 },
];

const DESIGN_TIERS = [
  { name: "Putih (Basic)", price: 0, colorTheme: "white" },
  { name: "Biru Gelap (Pro)", price: 1500000, colorTheme: "blue" },
  { name: "Hijau Neon (Premium)", price: 3500000, colorTheme: "lime" },
];

const PORTFOLIOS = [
  { title: "Healthcare ERP", category: "Web Application", description: "Complete hospital management system with patient records, appointment scheduling, billing, and pharmacy management.", techStack: "Next.js, Node.js, PostgreSQL, Docker", imageUrl: "/portfolio/healthcare-erp.jpg", link: "" },
  { title: "School Management System", category: "Web Application", description: "End-to-end school platform handling student enrollment, grading, attendance, and parent communication.", techStack: "Laravel, Vue.js, MySQL, Redis", imageUrl: "/portfolio/school-management.jpg", link: "" },
  { title: "Property Marketplace", category: "E-Commerce", description: "Real estate listing platform with advanced search, virtual tours, and integrated mortgage calculator.", techStack: "Next.js, Prisma, PostgreSQL, AWS", imageUrl: "/portfolio/property-marketplace.jpg", link: "" },
  { title: "Restaurant POS", category: "Web Application", description: "Point-of-sale system with table management, kitchen display, and real-time order tracking.", techStack: "React, Express, MongoDB, Socket.io", imageUrl: "/portfolio/restaurant-pos.jpg", link: "" },
  { title: "Construction Dashboard", category: "Web Application", description: "Project management dashboard for construction companies with resource allocation and progress tracking.", techStack: "Next.js, NestJS, PostgreSQL, Docker", imageUrl: "/portfolio/construction-dashboard.jpg", link: "" },
  { title: "Travel Booking System", category: "E-Commerce", description: "Online travel agency platform with hotel, flight, and package booking with payment integration.", techStack: "React, Laravel, MySQL, Stripe", imageUrl: "/portfolio/travel-booking.jpg", link: "" },
  { title: "Financial Dashboard", category: "Web Application", description: "Real-time financial analytics dashboard with interactive charts, portfolio tracking, and market data.", techStack: "Next.js, Python, PostgreSQL, Redis", imageUrl: "/portfolio/financial-dashboard.jpg", link: "" },
  { title: "E-Commerce Platform", category: "E-Commerce", description: "Multi-vendor marketplace with inventory management, order processing, and analytics dashboard.", techStack: "Next.js, Node.js, MongoDB, Stripe", imageUrl: "/portfolio/ecommerce-platform.jpg", link: "" },
];

const TESTIMONIALS = [
  { name: "Ahmad Rizki", company: "PT. Nusantara Digital", role: "CEO", rating: 5, review: "JuangDev transformed our online presence completely. The website they built for us increased our leads by 300% in just 3 months. Their attention to detail is extraordinary." },
  { name: "Sarah Putri", company: "TechStart Indonesia", role: "Founder", rating: 5, review: "From concept to deployment, JuangDev delivered our MVP in record time. The code quality is top-notch and they continue to support us as we scale." },
  { name: "Budi Santoso", company: "Maju Bersama Group", role: "CTO", rating: 5, review: "Their ERP solution streamlined our entire operation. We saved 40% on operational costs within the first year. Highly recommend their enterprise solutions." },
  { name: "Diana Kusuma", company: "Sehat Selalu Clinic", role: "Director", rating: 5, review: "The healthcare management system they built is intuitive and reliable. Our staff adopted it quickly and patient satisfaction has improved significantly." },
  { name: "Reza Firmansyah", company: "PropertyKu", role: "Co-founder", rating: 5, review: "JuangDev built our property marketplace from scratch. The platform handles thousands of listings seamlessly and the UX is world-class." },
  { name: "Linda Maharani", company: "Fashion Outlet ID", role: "Owner", rating: 5, review: "Our e-commerce platform by JuangDev boosted sales by 250%. The checkout flow is smooth, and the admin dashboard makes management a breeze." },
  { name: "Hendra Wijaya", company: "BuildPro Construction", role: "Project Manager", rating: 5, review: "The project management dashboard they developed helps us track 50+ construction sites in real-time. Game-changing for our operations." },
  { name: "Maya Anggraini", company: "EduTech Solutions", role: "CEO", rating: 5, review: "JuangDev built our school management system that now serves 10,000+ students. Their understanding of the education sector is impressive." },
];

const PRICING_DATA = [
  { category: "Landing Page", name: "Starter", price: "99k", description: "Sempurna untuk validasi ide & produk tunggal", popular: false, features: "1 Halaman Landing Page, Desain Template Premium, Mobile Responsive, Tombol WhatsApp, Waktu Pengerjaan 2 Hari" },
  { category: "Landing Page", name: "Growth", price: "299k", description: "Pilihan terbaik untuk konversi marketing", popular: true, features: "Desain UI/UX Custom, Copywriting Persuasif, Integrasi Google Analytics, Setup Domain & Hosting, Waktu Pengerjaan 5 Hari" },
  { category: "Landing Page", name: "Scale", price: "499k", description: "Desain kompleks dengan animasi interaktif", popular: false, features: "Animasi Interaktif (GSAP), A/B Testing Setup, Integrasi Email Marketing, Prioritas Support, Waktu Pengerjaan 7 Hari" },
  { category: "Company Profile", name: "Basic", price: "199k", description: "Profil bisnis profesional & elegan", popular: false, features: "Maksimal 3 Halaman, Desain Responsif, Form Kontak, Galeri Foto, Waktu Pengerjaan 3 Hari" },
  { category: "Company Profile", name: "Professional", price: "499k", description: "Tampil meyakinkan di mata klien", popular: true, features: "Hingga 7 Halaman, Desain UI/UX Custom, CMS untuk Update Konten, SEO Basic Setup, Waktu Pengerjaan 7 Hari" },
  { category: "Company Profile", name: "Corporate", price: "899k", description: "Untuk perusahaan berskala besar", popular: false, features: "Halaman Tak Terbatas, Multi-bahasa (Bilingual), Portal Karir, Integrasi Sistem Internal, Waktu Pengerjaan 14 Hari" },
  { category: "E-Commerce", name: "Basic Store", price: "399k", description: "Mulai jualan online dengan cepat", popular: false, features: "Katalog hingga 50 Produk, Keranjang Belanja, Integrasi WhatsApp Checkout, Desain Mobile Friendly" },
  { category: "E-Commerce", name: "Pro Store", price: "799k", description: "Toko online otomatis & scalable", popular: true, features: "Produk Tak Terbatas, Payment Gateway Integrasi, Perhitungan Ongkir Otomatis, Manajemen Inventaris, Dashboard Analitik" },
  { category: "E-Commerce", name: "Marketplace", price: "999k", description: "Sistem toko online kompleks & custom", popular: false, features: "Multi-vendor Setup, Sistem Poin/Diskon, Integrasi POS Internal, Aplikasi Mobile WebView, Dukungan Prioritas 24/7" },
  { category: "Sistem Informasi", name: "Basic App", price: "499k", description: "Aplikasi web internal sederhana", popular: false, features: "Modul Login/Register, CRUD Data Basic, Export PDF/Excel, Database Setup" },
  { category: "Sistem Informasi", name: "Pro App", price: "899k", description: "Sistem operasional lengkap & aman", popular: true, features: "Multi-role Akses (Admin/User), Dashboard Analitik, Notifikasi Email/WA, API Integration, Setup Cloud Server" },
  { category: "Sistem Informasi", name: "Enterprise App", price: "999k+", description: "Sistem ERP/CRM skala perusahaan", popular: false, features: "Modul Tak Terbatas, Arsitektur Microservices, Keamanan Tingkat Tinggi, SLA Guarantee 99.9%, Maintenance 6 Bulan" },
  { category: "Custom Web App", name: "Starter", price: "999k", description: "Aplikasi web fungsional untuk bisnis", popular: false, features: "Aplikasi React/Next.js, Desain UI/UX Custom, API Integration, Basic Admin Panel" },
  { category: "Custom Web App", name: "Pro", price: "1.499k", description: "Aplikasi kompleks dengan sistem canggih", popular: true, features: "Sistem Autentikasi Kompleks, Realtime Features, Dashboard Analytics, Cloud Server Setup" },
  { category: "Custom Web App", name: "Enterprise", price: "2.999k", description: "Solusi enterprise berskala besar", popular: false, features: "Arsitektur Skalabel, SLA Guarantee 99.9%, Prioritas Support, Maintenance Mingguan" },
];

async function seed() {
  console.log("Seeding started...");

  try {
    // Check if services are empty before seeding
    const existingServices = await db.select().from(services);
    if (existingServices.length === 0) {
      console.log("Seeding services...");
      await db.insert(services).values(SERVICES);
    }

    const existingTiers = await db.select().from(designTiers);
    if (existingTiers.length === 0) {
      console.log("Seeding design tiers...");
      await db.insert(designTiers).values(DESIGN_TIERS);
    }

    const existingPortfolios = await db.select().from(portfolios);
    if (existingPortfolios.length === 0) {
      console.log("Seeding portfolios...");
      await db.insert(portfolios).values(PORTFOLIOS.map((p, i) => ({ ...p, orderIndex: i })));
    }

    const existingTestimonials = await db.select().from(testimonials);
    if (existingTestimonials.length === 0) {
      console.log("Seeding testimonials...");
      await db.insert(testimonials).values(TESTIMONIALS.map(t => ({ ...t, content: t.review })));
    }

    const existingPricing = await db.select().from(pricingPlans);
    if (existingPricing.length === 0) {
      console.log("Seeding pricing plans...");
      await db.insert(pricingPlans).values(PRICING_DATA.map((p, i) => ({
        name: p.name,
        category: p.category,
        price: p.price,
        period: "",
        description: p.description,
        features: p.features,
        isPopular: p.popular,
        orderIndex: i
      })));
    }

    // Seed dummy features for the first service if there's any
    const existingFeatures = await db.select().from(serviceFeatures);
    if (existingFeatures.length === 0) {
      const allSvc = await db.select().from(services);
      if (allSvc.length > 0) {
        console.log("Seeding sample features for the first service...");
        await db.insert(serviceFeatures).values([
          { serviceId: allSvc[0].id, name: "SEO Optimization Advanced", price: 500000 },
          { serviceId: allSvc[0].id, name: "Payment Gateway", price: 800000 },
          { serviceId: allSvc[0].id, name: "Multi-Language", price: 1200000 },
        ]);
      }
    }

    console.log("Seeding completed!");
  } catch (err) {
    console.error("Seeding failed!", err);
  }
}

seed();
