"use client";

import { ReactNode } from "react";
import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import { 
  LayoutDashboard, Settings, Briefcase, ImageIcon, Users, 
  LogOut, User as UserIcon, Bell, ExternalLink, BadgeDollarSign, Palette, Layers, FileText
} from "lucide-react";

function Sidebar() {
  const pathname = usePathname();
  const router = useRouter();

  const handleLogout = async () => {
    document.cookie = "admin_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    router.push("/admin/login");
    router.refresh();
  };

  const NavItem = ({ href, label, icon: Icon }: any) => {
    const isActive = pathname.startsWith(href);
    return (
      <Link 
        href={href} 
        className={`flex items-center px-6 py-3 transition-colors text-sm ${
          isActive 
            ? "bg-slate-100 text-slate-900 font-bold border-l-4 border-slate-900" 
            : "text-slate-500 hover:bg-slate-50 hover:text-slate-900 border-l-4 border-transparent font-medium"
        }`}
      >
        <Icon className={`mr-4 h-5 w-5 ${isActive ? "text-slate-900" : "text-slate-400"}`} />
        {label}
      </Link>
    );
  };

  return (
    <aside className="w-64 bg-white border-r border-slate-200 flex flex-col h-full flex-shrink-0">
      <div className="p-6 border-b border-slate-100 flex items-center gap-3">
        {/* Placeholder Logo */}
        <div className="w-8 h-8 bg-blue-600 rounded-md flex items-center justify-center text-white font-bold">J</div>
        <div>
          <h2 className="text-lg font-bold text-slate-900 leading-none tracking-tight">JuangDev</h2>
          <p className="text-xs text-slate-500 mt-1">Admin Panel</p>
        </div>
      </div>

      <div className="flex-1 overflow-y-auto py-4">
        <div className="px-6 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
          Main Menu
        </div>
        <nav className="space-y-0.5">
          <NavItem href="/admin/dashboard" label="Dashboard" icon={LayoutDashboard} />
          
          <div className="py-2 px-6 mt-4">
            <p className="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">A La Carte Services</p>
          </div>
          <NavItem href="/admin/services" label="Layanan Utama" icon={Briefcase} />
          <NavItem href="/admin/service-features" label="Fitur Tambahan" icon={Layers} />
          <NavItem href="/admin/design-tiers" label="Tingkat Design" icon={Palette} />
          
          <div className="py-2 px-6 mt-4">
            <p className="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Content</p>
          </div>
          <NavItem href="/admin/portfolios" label="Portfolios" icon={ImageIcon} />
          <NavItem href="/admin/blogs" label="Blog Posts" icon={FileText} />
          <NavItem href="/admin/pricing" label="Pricing Packages" icon={BadgeDollarSign} />
          <NavItem href="/admin/testimonials" label="Testimonials & Reviews" icon={Users} />
        </nav>

        <div className="px-6 mt-8 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
          Manage
        </div>
        <nav className="space-y-0.5">
          <NavItem href="/admin/settings" label="Site Settings" icon={Settings} />
          <NavItem href="/admin/users" label="Staff" icon={UserIcon} />
          <NavItem href="#" label="Notifications" icon={Bell} />
          
          <div className="my-4 border-t border-slate-100"></div>
          
          <a href="/" target="_blank" className="flex items-center px-6 py-3 text-sm font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-900 border-l-4 border-transparent">
            <ExternalLink className="mr-4 h-5 w-5 text-slate-400" />
            View Website
          </a>
          <button onClick={handleLogout} className="w-full flex items-center px-6 py-3 text-sm font-medium text-slate-500 hover:bg-slate-50 hover:text-red-600 border-l-4 border-transparent">
            <LogOut className="mr-4 h-5 w-5 text-slate-400" />
            Logout
          </button>
        </nav>
      </div>
    </aside>
  );
}

function TopNav() {
  const pathname = usePathname();
  const segments = pathname.split('/').filter(Boolean);
  const currentSegment = segments[segments.length - 1];
  const title = currentSegment.charAt(0).toUpperCase() + currentSegment.slice(1);

  return (
    <header className="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 flex-shrink-0">
      <div>
        <h1 className="text-xl font-bold text-slate-800">{title}</h1>
        <p className="text-xs text-slate-500">Dashboard / {title}</p>
      </div>
      <div className="flex items-center gap-4">
        <div className="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-full py-1.5 px-3 cursor-pointer hover:bg-slate-100 transition-colors">
          <div className="w-6 h-6 bg-slate-800 text-white rounded-full flex items-center justify-center text-xs font-bold">AD</div>
          <span className="text-sm font-medium text-slate-700">Admin JuangDev</span>
        </div>
      </div>
    </header>
  );
}

export default function AdminLayout({ children }: { children: ReactNode }) {
  return (
    <div className="flex h-screen bg-[#f8fafc]">
      <Sidebar />
      <div className="flex-1 flex flex-col min-w-0 overflow-hidden">
        <TopNav />
        <main className="flex-1 overflow-y-auto p-6 md:p-8">
          <div className="max-w-[1400px] mx-auto">
            {children}
          </div>
        </main>
      </div>
    </div>
  );
}
