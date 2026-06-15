import { db } from "@/db";
import { services, portfolios, testimonials, contacts } from "@/db/schema";
import { Briefcase, ImageIcon, Users, MessageSquare } from "lucide-react";

export default async function AdminDashboard() {
  const allServices = await db.select().from(services);
  const allPortfolios = await db.select().from(portfolios);
  const allTestimonials = await db.select().from(testimonials);
  const allContacts = await db.select().from(contacts);

  const stats = [
    { label: "Total Services", value: allServices.length, icon: Briefcase },
    { label: "Total Portfolios", value: allPortfolios.length, icon: ImageIcon },
    { label: "Total Testimonials", value: allTestimonials.length, icon: Users },
    { label: "Contact Messages", value: allContacts.length, icon: MessageSquare },
  ];

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {stats.map((stat, i) => (
          <div key={i} className="bg-white border border-slate-200 rounded-xl p-6 flex items-center shadow-sm">
            <div className="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center mr-4 shrink-0">
              <stat.icon className="w-6 h-6 text-slate-700" />
            </div>
            <div>
              <p className="text-2xl font-bold text-slate-900 leading-none">{stat.value}</p>
              <p className="text-xs font-semibold text-slate-500 mt-1 uppercase tracking-wider">{stat.label}</p>
            </div>
          </div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm p-6 h-[400px]">
          <h3 className="text-lg font-bold text-slate-800 mb-4">Activity Overview</h3>
          <div className="h-full pb-10 flex items-center justify-center border-b-2 border-l-2 border-slate-100 relative">
            <p className="text-slate-400">Chart Placeholder (Activity)</p>
          </div>
        </div>

        <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-[400px]">
          <div className="p-4 border-b border-slate-100">
            <h3 className="font-bold text-slate-800">Status Detail</h3>
          </div>
          <div className="divide-y divide-slate-50 flex-1 overflow-y-auto">
            <div className="flex justify-between items-center p-4 hover:bg-slate-50 transition-colors">
              <span className="text-sm font-medium text-slate-600">Active Services</span>
              <span className="font-bold text-slate-900">{allServices.filter(s => s.isActive).length}</span>
            </div>
            <div className="flex justify-between items-center p-4 hover:bg-slate-50 transition-colors">
              <span className="text-sm font-medium text-slate-600">Active Portfolios</span>
              <span className="font-bold text-slate-900">{allPortfolios.filter(p => p.isActive).length}</span>
            </div>
            <div className="flex justify-between items-center p-4 hover:bg-slate-50 transition-colors">
              <span className="text-sm font-medium text-slate-600">Active Testimonials</span>
              <span className="font-bold text-slate-900">{allTestimonials.filter(t => t.isActive).length}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
