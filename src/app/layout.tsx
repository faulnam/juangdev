import type { Metadata } from "next";
import { Inter, Playfair_Display, Geist_Mono } from "next/font/google";
import "./globals.css";

const inter = Inter({
  variable: "--font-sans",
  subsets: ["latin"],
  display: "swap",
});

const playfair = Playfair_Display({
  variable: "--font-serif",
  subsets: ["latin"],
  display: "swap",
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: "JuangDev — Build Websites & Custom Software That Grow Your Business",
  description:
    "JuangDev helps startups, SMEs, and enterprises build modern websites, web applications, mobile apps, dashboards, ERP systems, and AI-powered solutions. Free consultation available.",
  icons: {
    icon: "/logo2.png",
  },
  keywords: [
    "software house",
    "web development",
    "mobile app development",
    "custom software",
    "ERP system",
    "CRM development",
    "UI UX design",
    "Next.js development",
    "React development",
    "JuangDev",
  ],
  authors: [{ name: "JuangDev" }],
  creator: "JuangDev",
  openGraph: {
    title: "JuangDev — Build Websites & Custom Software That Grow Your Business",
    description:
      "JuangDev helps startups, SMEs, and enterprises build modern websites, web applications, mobile apps, and AI-powered solutions.",
    url: "https://juaangdev.com",
    siteName: "JuangDev",
    locale: "en_US",
    type: "website",
  },
  twitter: {
    card: "summary_large_image",
    title: "JuangDev — Premium Software Development",
    description:
      "Build modern websites, web apps, mobile apps, and enterprise solutions with JuangDev.",
  },
  robots: {
    index: true,
    follow: true,
  },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html
      lang="en"
      className={`${inter.variable} ${playfair.variable} ${geistMono.variable} h-full antialiased`}
    >
      <body className="min-h-full flex flex-col">{children}</body>
    </html>
  );
}
