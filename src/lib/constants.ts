// ============================================================
// JuangDev — All Static Data & Constants
// ============================================================

// ---- Navigation ----
export const NAV_LINKS = [
  { label: "Home", href: "/#hero" },
  { label: "Services", href: "/services" },
  { label: "Portfolio", href: "/portfolio" },
  { label: "Pricing", href: "/#pricing" },
  { label: "Contact", href: "/contact" },
] as const;

export const WHATSAPP_NUMBER = "6283852174877";
export const WHATSAPP_MESSAGE = "Hello JuangDev, I would like to consult about website/application development.";
export const WHATSAPP_URL = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(WHATSAPP_MESSAGE)}`;

// ---- Hero Trust Indicators ----
export const TRUST_INDICATORS = [
  { value: "5.0", label: "Rating", icon: "star" },
  { value: "100+", label: "Projects", icon: "folder" },
  { value: "50+", label: "Happy Clients", icon: "users" },
  { value: "3+", label: "Years Experience", icon: "calendar" },
] as const;

// ---- Statistics ----
export const STATISTICS = [
  { value: 100, suffix: "+", label: "Completed Projects" },
  { value: 50, suffix: "+", label: "Happy Clients" },
  { value: 3, suffix: "+", label: "Years Experience" },
  { value: 99, suffix: "%", label: "Client Satisfaction" },
] as const;

// ---- Services ----
export const SERVICES = [
  {
    icon: "Globe",
    title: "Landing Page",
    description: "A single-page website highly optimized to maximize your business marketing conversion.",
    features: ["Responsive Design", "Basic SEO", "Mobile Friendly"],
    startingPrice: "99K",
    color: "lime" as const,
  },
  {
    icon: "Monitor",
    title: "Company Profile",
    description: "A professional and elegant website to build credibility and showcase your business identity to the public.",
    features: ["Up to 5 Pages", "Gallery", "Contact Form"],
    startingPrice: "199K",
    color: "blue" as const,
  },
  {
    icon: "ShoppingBag",
    title: "E-Commerce",
    description: "Modern online store with structured shopping systems, complete with product catalogs and checkout.",
    features: ["Product Catalog", "Shopping Cart", "WhatsApp Integration"],
    startingPrice: "399K",
    color: "white" as const,
  },
  {
    icon: "Bot",
    title: "Information System",
    description: "Data digitalization and reporting system to simplify your company's internal operations.",
    features: ["Data Management", "Analytics Dashboard", "Export Reports"],
    startingPrice: "499K",
    color: "blue" as const,
  },
  {
    icon: "Palette",
    title: "Custom Web App",
    description: "Custom web application development with complex features designed to follow your unique business flow.",
    features: ["Custom UI/UX Design", "API Integration", "Login/Role System"],
    startingPrice: "999K",
    color: "lime" as const,
  },
] as const;

// ---- Why Choose Us ----
export const WHY_CHOOSE_US = [
  {
    icon: "Users",
    title: "Experienced Team",
    description: "Our developers and designers bring years of expertise across diverse industries and technologies.",
  },
  {
    icon: "Zap",
    title: "Fast Development",
    description: "Agile methodology ensures rapid delivery without compromising quality or attention to detail.",
  },
  {
    icon: "Layers",
    title: "Scalable Architecture",
    description: "Built to grow with your business — from startup MVP to enterprise-grade infrastructure.",
  },
  {
    icon: "BadgeDollarSign",
    title: "Transparent Pricing",
    description: "No hidden fees. Clear project scoping, detailed proposals, and milestone-based payments.",
  },
  {
    icon: "Cpu",
    title: "Modern Technology Stack",
    description: "We use cutting-edge frameworks like Next.js, React, Laravel, Flutter, and cloud-native tools.",
  },
  {
    icon: "HeadphonesIcon",
    title: "Long-Term Support",
    description: "Post-launch maintenance, monitoring, and feature updates to keep your product running smoothly.",
  },
] as const;

// ---- Tech Stack ----
export const TECH_STACK = {
  Frontend: [
    { name: "Next.js", icon: "/tech/nextjs.svg" },
    { name: "React", icon: "/tech/react.svg" },
    { name: "Vue", icon: "/tech/vue.svg" },
    { name: "TailwindCSS", icon: "/tech/tailwind.svg" },
    { name: "TypeScript", icon: "/tech/typescript.svg" },
  ],
  Backend: [
    { name: "Laravel", icon: "/tech/laravel.svg" },
    { name: "Node.js", icon: "/tech/nodejs.svg" },
    { name: "NestJS", icon: "/tech/nestjs.svg" },
    { name: "Express", icon: "/tech/express.svg" },
    { name: "Python", icon: "/tech/python.svg" },
  ],
  Database: [
    { name: "PostgreSQL", icon: "/tech/postgresql.svg" },
    { name: "MySQL", icon: "/tech/mysql.svg" },
    { name: "MongoDB", icon: "/tech/mongodb.svg" },
    { name: "Redis", icon: "/tech/redis.svg" },
  ],
  "Cloud & DevOps": [
    { name: "Docker", icon: "/tech/docker.svg" },
    { name: "AWS", icon: "/tech/aws.svg" },
    { name: "Vercel", icon: "/tech/vercel.svg" },
    { name: "Firebase", icon: "/tech/firebase.svg" },
  ],
  Tools: [
    { name: "GitHub", icon: "/tech/github.svg" },
    { name: "Figma", icon: "/tech/figma.svg" },
    { name: "Postman", icon: "/tech/postman.svg" },
    { name: "Prisma", icon: "/tech/prisma.svg" },
  ],
} as const;

// ---- Portfolio Projects ----
export const PORTFOLIO_PROJECTS = [
  {
    title: "Healthcare ERP",
    category: "Web Application",
    description: "Complete hospital management system with patient records, appointment scheduling, billing, and pharmacy management.",
    techStack: ["Next.js", "Node.js", "PostgreSQL", "Docker"],
    image: "/portfolio/healthcare-erp.jpg",
  },
  {
    title: "School Management System",
    category: "Web Application",
    description: "End-to-end school platform handling student enrollment, grading, attendance, and parent communication.",
    techStack: ["Laravel", "Vue.js", "MySQL", "Redis"],
    image: "/portfolio/school-management.jpg",
  },
  {
    title: "Property Marketplace",
    category: "E-Commerce",
    description: "Real estate listing platform with advanced search, virtual tours, and integrated mortgage calculator.",
    techStack: ["Next.js", "Prisma", "PostgreSQL", "AWS"],
    image: "/portfolio/property-marketplace.jpg",
  },
  {
    title: "Restaurant POS",
    category: "Web Application",
    description: "Point-of-sale system with table management, kitchen display, and real-time order tracking.",
    techStack: ["React", "Express", "MongoDB", "Socket.io"],
    image: "/portfolio/restaurant-pos.jpg",
  },
  {
    title: "Construction Dashboard",
    category: "Web Application",
    description: "Project management dashboard for construction companies with resource allocation and progress tracking.",
    techStack: ["Next.js", "NestJS", "PostgreSQL", "Docker"],
    image: "/portfolio/construction-dashboard.jpg",
  },
  {
    title: "Travel Booking System",
    category: "E-Commerce",
    description: "Online travel agency platform with hotel, flight, and package booking with payment integration.",
    techStack: ["React", "Laravel", "MySQL", "Stripe"],
    image: "/portfolio/travel-booking.jpg",
  },
  {
    title: "Financial Dashboard",
    category: "Web Application",
    description: "Real-time financial analytics dashboard with interactive charts, portfolio tracking, and market data.",
    techStack: ["Next.js", "Python", "PostgreSQL", "Redis"],
    image: "/portfolio/financial-dashboard.jpg",
  },
  {
    title: "E-Commerce Platform",
    category: "E-Commerce",
    description: "Multi-vendor marketplace with inventory management, order processing, and analytics dashboard.",
    techStack: ["Next.js", "Node.js", "MongoDB", "Stripe"],
    image: "/portfolio/ecommerce-platform.jpg",
  },
] as const;

// ---- Process Steps ----
export const PROCESS_STEPS = [
  {
    step: 1,
    title: "Discovery",
    description: "We deep-dive into your business goals, target audience, and technical requirements.",
    items: ["Requirement Gathering", "Business Analysis", "Competitor Research"],
  },
  {
    step: 2,
    title: "Planning",
    description: "We create a detailed roadmap, define milestones, and plan the architecture.",
    items: ["UI Flow & Sitemap", "Technical Architecture", "Project Roadmap"],
  },
  {
    step: 3,
    title: "UI/UX Design",
    description: "Our designers create stunning, user-centered interfaces you'll love.",
    items: ["Wireframes", "High-Fidelity Prototype", "Design Approval"],
  },
  {
    step: 4,
    title: "Development",
    description: "Our engineers build your product using modern, battle-tested technologies.",
    items: ["Frontend Development", "Backend & API", "Database & Integration"],
  },
  {
    step: 5,
    title: "Testing",
    description: "Rigorous quality assurance ensures a flawless, bug-free experience.",
    items: ["QA Testing", "Bug Fixing", "Performance Optimization"],
  },
  {
    step: 6,
    title: "Deployment",
    description: "We launch your product and provide ongoing support and maintenance.",
    items: ["Server Setup", "Production Launch", "Post-Launch Maintenance"],
  },
] as const;

// ---- Pricing ----
export const PRICING_PLANS = [
  {
    name: "Starter",
    description: "Perfect for personal websites & landing pages",
    price: "Rp 2.5 Jt",
    period: "/ project",
    popular: false,
    features: [
      "1 Page Responsive Website",
      "Mobile-Friendly Design",
      "SEO Basic Setup",
      "WhatsApp CTA Integration",
      "Contact Form",
      "2x Revisions",
      "1 Month Support",
    ],
  },
  {
    name: "Professional",
    description: "Most popular — ideal for SMEs & growing businesses",
    price: "Rp 7.5 Jt",
    period: "/ project",
    popular: true,
    features: [
      "Up to 10 Pages",
      "Custom UI/UX Design",
      "CMS Integration",
      "SEO Advanced Setup",
      "Blog System",
      "Analytics Dashboard",
      "Payment Gateway",
      "5x Revisions",
      "3 Months Support",
      "Free Domain & Hosting 1 Year",
    ],
  },
  {
    name: "Enterprise",
    description: "Custom solution for large-scale applications",
    price: "Custom",
    period: "contact us",
    popular: false,
    features: [
      "Unlimited Pages & Features",
      "Custom Web Application",
      "ERP/CRM Development",
      "API Development & Integration",
      "Cloud Infrastructure Setup",
      "CI/CD Pipeline",
      "Dedicated Team",
      "Unlimited Revisions",
      "12 Months Support",
      "Priority Response Time",
    ],
  },
] as const;

// ---- Testimonials ----
export const TESTIMONIALS = [
  {
    name: "Ahmad Rizki",
    company: "PT. Nusantara Digital",
    role: "CEO",
    avatar: "/testimonials/avatar-1.jpg",
    rating: 5,
    review: "JuangDev transformed our online presence completely. The website they built for us increased our leads by 300% in just 3 months. Their attention to detail is extraordinary.",
  },
  {
    name: "Sarah Putri",
    company: "TechStart Indonesia",
    role: "Founder",
    avatar: "/testimonials/avatar-2.jpg",
    rating: 5,
    review: "From concept to deployment, JuangDev delivered our MVP in record time. The code quality is top-notch and they continue to support us as we scale.",
  },
  {
    name: "Budi Santoso",
    company: "Maju Bersama Group",
    role: "CTO",
    avatar: "/testimonials/avatar-3.jpg",
    rating: 5,
    review: "Their ERP solution streamlined our entire operation. We saved 40% on operational costs within the first year. Highly recommend their enterprise solutions.",
  },
  {
    name: "Diana Kusuma",
    company: "Sehat Selalu Clinic",
    role: "Director",
    avatar: "/testimonials/avatar-4.jpg",
    rating: 5,
    review: "The healthcare management system they built is intuitive and reliable. Our staff adopted it quickly and patient satisfaction has improved significantly.",
  },
  {
    name: "Reza Firmansyah",
    company: "PropertyKu",
    role: "Co-founder",
    avatar: "/testimonials/avatar-5.jpg",
    rating: 5,
    review: "JuangDev built our property marketplace from scratch. The platform handles thousands of listings seamlessly and the UX is world-class.",
  },
  {
    name: "Linda Maharani",
    company: "Fashion Outlet ID",
    role: "Owner",
    avatar: "/testimonials/avatar-6.jpg",
    rating: 5,
    review: "Our e-commerce platform by JuangDev boosted sales by 250%. The checkout flow is smooth, and the admin dashboard makes management a breeze.",
  },
  {
    name: "Hendra Wijaya",
    company: "BuildPro Construction",
    role: "Project Manager",
    avatar: "/testimonials/avatar-7.jpg",
    rating: 5,
    review: "The project management dashboard they developed helps us track 50+ construction sites in real-time. Game-changing for our operations.",
  },
  {
    name: "Maya Anggraini",
    company: "EduTech Solutions",
    role: "CEO",
    avatar: "/testimonials/avatar-8.jpg",
    rating: 5,
    review: "JuangDev built our school management system that now serves 10,000+ students. Their understanding of the education sector is impressive.",
  },
] as const;

// ---- Client Logos ----
export const CLIENT_LOGOS = [
  "Nusantara Digital",
  "TechStart Indonesia",
  "Maju Bersama Group",
  "Sehat Selalu",
  "PropertyKu",
  "Fashion Outlet ID",
  "BuildPro",
  "EduTech Solutions",
  "FoodPanda Indonesia",
  "TravelGo",
  "FinanceHub",
  "GreenEnergy Co",
] as const;

// ---- Industries ----
export const INDUSTRIES = [
  { icon: "Heart", name: "Healthcare", description: "Hospital & clinic management systems" },
  { icon: "GraduationCap", name: "Education", description: "Learning platforms & school systems" },
  { icon: "ShoppingCart", name: "Retail", description: "POS, inventory & e-commerce solutions" },
  { icon: "Factory", name: "Manufacturing", description: "Production & supply chain management" },
  { icon: "Truck", name: "Logistics", description: "Fleet tracking & warehouse management" },
  { icon: "Landmark", name: "Finance", description: "Fintech, dashboards & payment systems" },
  { icon: "Building2", name: "Real Estate", description: "Property listing & management platforms" },
  { icon: "UtensilsCrossed", name: "Hospitality", description: "Hotel booking & restaurant systems" },
] as const;

// ---- FAQ ----
export const FAQ_ITEMS = [
  {
    question: "How long does development take?",
    answer: "Timeline depends on project complexity. A landing page takes 3-7 days, a company profile website 1-2 weeks, a web application 4-8 weeks, and a mobile app 6-12 weeks. We'll provide a detailed timeline during the planning phase.",
  },
  {
    question: "How much does a website cost?",
    answer: "Our pricing starts from Rp 2.5 million for a landing page. Company profiles range from Rp 5-10 million, web applications from Rp 15-50 million, depending on features and complexity. We offer transparent pricing with no hidden fees.",
  },
  {
    question: "Can you redesign existing websites?",
    answer: "Absolutely! We specialize in website redesigns and migrations. We'll analyze your current site, identify improvement areas, and create a modern, high-performing version while preserving your SEO rankings.",
  },
  {
    question: "Do you provide maintenance?",
    answer: "Yes! All our projects include post-launch support (1-12 months depending on the package). We also offer ongoing maintenance plans for hosting, security updates, performance monitoring, and feature additions.",
  },
  {
    question: "What technologies do you use?",
    answer: "We use modern, battle-tested technologies: Next.js, React, Vue for frontend; Laravel, Node.js, NestJS for backend; PostgreSQL, MongoDB for databases; Docker, AWS, Vercel for deployment; and Flutter for mobile apps.",
  },
  {
    question: "How do payments work?",
    answer: "We use a milestone-based payment structure: typically 30% upfront to start development, 40% at mid-project review, and 30% upon completion and deployment. We accept bank transfer and support installment plans for larger projects.",
  },
  {
    question: "Can I request custom features?",
    answer: "Of course! Custom development is our specialty. Whether it's AI integration, complex API connections, custom dashboards, or unique business logic — we'll build exactly what you need. Let's discuss your requirements in a free consultation.",
  },
] as const;

// ---- Blog Posts ----
export const BLOG_POSTS = [
  {
    title: "Why Next.js is the Future of Web Development in 2026",
    excerpt: "Discover how Next.js 15 with React Server Components is revolutionizing the way we build modern web applications.",
    category: "Technology",
    date: "June 10, 2026",
    readTime: "5 min read",
    image: "/blog/nextjs-future.jpg",
    slug: "nextjs-future-2026",
  },
  {
    title: "10 Essential Features Every E-Commerce Website Needs",
    excerpt: "From seamless checkout to personalized recommendations — learn what separates successful online stores from the rest.",
    category: "E-Commerce",
    date: "June 5, 2026",
    readTime: "7 min read",
    image: "/blog/ecommerce-features.jpg",
    slug: "ecommerce-essential-features",
  },
  {
    title: "How AI is Transforming Business Automation",
    excerpt: "Explore how AI-powered chatbots, workflow automation, and predictive analytics are helping businesses save time and money.",
    category: "AI & Automation",
    date: "May 28, 2026",
    readTime: "6 min read",
    image: "/blog/ai-automation.jpg",
    slug: "ai-business-automation",
  },
] as const;

// ---- Footer ----
export const FOOTER_LINKS = {
  Company: [
    { label: "About", href: "#about" },
    { label: "Services", href: "#services" },
    { label: "Portfolio", href: "#portfolio" },
    { label: "Careers", href: "#" },
  ],
  Resources: [
    { label: "Blog", href: "#blog" },
    { label: "FAQ", href: "#faq" },
    { label: "Documentation", href: "#" },
    { label: "Privacy Policy", href: "#" },
  ],
  Services: [
    { label: "Website Development", href: "#services" },
    { label: "Software Development", href: "#services" },
    { label: "Mobile Apps", href: "#services" },
    { label: "UI/UX Design", href: "#services" },
  ],
} as const;

export const SOCIAL_LINKS = [
  { name: "GitHub", href: "https://github.com/juaangdev", icon: "Github" },
  { name: "LinkedIn", href: "https://linkedin.com/company/juaangdev", icon: "Linkedin" },
  { name: "Instagram", href: "https://instagram.com/juaangdev", icon: "Instagram" },
  { name: "Facebook", href: "https://facebook.com/juaangdev", icon: "Facebook" },
] as const;

export const CONTACT_INFO = {
  email: "hello@juaangdev.com",
  phone: "+62 812-3456-7890",
  whatsapp: WHATSAPP_NUMBER,
  location: "Jakarta, Indonesia",
} as const;
