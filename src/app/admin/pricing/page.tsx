import { db } from "@/db";
import { pricingPlans } from "@/db/schema";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { addPricingPlan, deletePricingPlan, togglePricingPlanStatus } from "./actions";
import { Edit, Trash2, EyeOff, Plus, Search, Filter, BadgeDollarSign, Star } from "lucide-react";

export default async function PricingPage() {
  const allPricingPlans = await db.select().from(pricingPlans).orderBy(pricingPlans.createdAt);

  return (
    <div className="space-y-6">
      
      {/* List Card */}
      <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div className="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
            <BadgeDollarSign className="w-5 h-5 text-slate-500" /> Daftar Paket Harga
          </h2>
          <a href="#add-form" className="bg-slate-900 text-white hover:bg-slate-800 px-4 py-2 rounded-md font-semibold text-sm flex items-center transition-colors">
            <Plus className="w-4 h-4 mr-2" /> Tambah Paket
          </a>
        </div>

        <div className="p-4 border-b border-slate-100 flex flex-col md:flex-row gap-4 bg-slate-50/50">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <Input className="pl-9 bg-white" placeholder="Cari paket..." />
          </div>
          <select className="border border-slate-200 rounded-md bg-white px-3 py-2 text-sm outline-none w-full md:w-48 text-slate-600">
            <option>Semua Kategori</option>
            <option>Landing Page</option>
            <option>Company Profile</option>
            <option>E-Commerce</option>
            <option>Sistem Informasi</option>
            <option>Custom Web App</option>
          </select>
          <Button variant="outline" className="bg-white"><Filter className="w-4 h-4 mr-2" /> Filter</Button>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-sm text-left">
            <thead className="bg-slate-50 text-slate-500 text-xs font-bold uppercase border-b border-slate-200">
              <tr>
                <th className="px-6 py-4">NAMA PAKET</th>
                <th className="px-6 py-4">KATEGORI</th>
                <th className="px-6 py-4">HARGA</th>
                <th className="px-6 py-4">POPULER</th>
                <th className="px-6 py-4">STATUS</th>
                <th className="px-6 py-4 text-right">AKSI</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {allPricingPlans.length === 0 ? (
                <tr>
                  <td colSpan={6} className="px-6 py-8 text-center text-slate-500">Belum ada paket harga.</td>
                </tr>
              ) : (
                allPricingPlans.map((item) => (
                  <tr key={item.id} className="hover:bg-slate-50/50 transition-colors">
                    <td className="px-6 py-4">
                      <p className="font-bold text-slate-900 flex items-center gap-2">
                        {item.name}
                      </p>
                      <p className="text-xs text-slate-500 mt-1 line-clamp-1 max-w-[200px]">{item.description}</p>
                    </td>
                    <td className="px-6 py-4">
                      <span className="bg-slate-100 text-slate-700 font-bold px-3 py-1 rounded text-[10px] uppercase tracking-wider border border-slate-200">
                        {item.category}
                      </span>
                    </td>
                    <td className="px-6 py-4">
                      <span className="font-bold text-slate-900">{item.price}</span>
                      {item.period && <span className="text-xs text-slate-500 ml-1">{item.period}</span>}
                    </td>
                    <td className="px-6 py-4">
                      {item.isPopular ? (
                        <span className="flex items-center gap-1 text-yellow-500 font-bold text-xs"><Star className="w-3 h-3 fill-current"/> Ya</span>
                      ) : (
                        <span className="text-slate-400 text-xs">Tidak</span>
                      )}
                    </td>
                    <td className="px-6 py-4">
                      <span className={`font-bold text-xs ${item.isActive ? "text-slate-700" : "text-red-500"}`}>
                        {item.isActive ? "AKTIF" : "SEMBUNYI"}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex items-center justify-end gap-2">
                        <Button variant="outline" size="icon" className="h-8 w-8 text-slate-700 border-slate-300 hover:bg-slate-100">
                          <Edit className="w-4 h-4" />
                        </Button>
                        <form action={async () => { "use server"; await togglePricingPlanStatus(item.id, item.isActive); }}>
                          <Button variant="outline" size="icon" type="submit" className="h-8 w-8 text-amber-500 border-amber-200 hover:bg-amber-50 hover:text-amber-600">
                            <EyeOff className="w-4 h-4" />
                          </Button>
                        </form>
                        <form action={async () => { "use server"; await deletePricingPlan(item.id); }}>
                          <Button variant="outline" size="icon" type="submit" className="h-8 w-8 text-red-500 border-red-200 hover:bg-red-50 hover:text-red-600">
                            <Trash2 className="w-4 h-4" />
                          </Button>
                        </form>
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Form Card */}
      <div id="add-form" className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mt-8">
        <div className="p-4 border-b border-slate-200 bg-slate-50/50">
          <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
            <Plus className="w-5 h-5 text-slate-500" /> Tambah Paket Harga
          </h2>
        </div>
        <div className="p-6">
          <form action={addPricingPlan} className="flex flex-col lg:flex-row gap-8">
            <div className="flex-1 space-y-6">
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="name" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Nama Paket <span className="text-red-500">*</span></Label>
                  <Input id="name" name="name" required className="bg-white border-slate-300" placeholder="Contoh: Basic Store" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="category" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Kategori <span className="text-red-500">*</span></Label>
                  <select id="category" name="category" required className="w-full border border-slate-300 rounded-md bg-white px-3 py-2 text-sm outline-none focus:border-slate-900">
                    <option value="Landing Page">Landing Page</option>
                    <option value="Company Profile">Company Profile</option>
                    <option value="E-Commerce">E-Commerce</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Custom Web App">Custom Web App</option>
                  </select>
                </div>
              </div>

              <div className="space-y-2">
                <Label htmlFor="description" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Deskripsi Singkat <span className="text-red-500">*</span></Label>
                <textarea 
                  id="description" 
                  name="description" 
                  required 
                  className="w-full flex min-h-[80px] rounded-md border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-slate-900"
                  placeholder="Contoh: Mulai jualan online dengan cepat..."
                />
              </div>

              <div className="bg-[#f0f9ff] border border-[#bae6fd] rounded-lg p-5 mt-6">
                <h3 className="text-[#0369a1] font-bold text-sm mb-4 flex items-center gap-2">
                  <span className="w-4 h-4 bg-[#bae6fd] text-[#0369a1] flex items-center justify-center rounded-sm text-[10px]">$</span>
                  Detail Harga & Fitur
                </h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div className="space-y-2">
                    <Label htmlFor="price" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Harga <span className="text-red-500">*</span></Label>
                    <Input id="price" name="price" required className="bg-white border-slate-300" placeholder="Contoh: 399k" />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="period" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Periode (Opsional)</Label>
                    <Input id="period" name="period" className="bg-white border-slate-300" placeholder="Contoh: / project" />
                  </div>
                </div>
                
                <div className="mt-4 space-y-2">
                  <Label htmlFor="features" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Daftar Fitur <span className="text-red-500">*</span></Label>
                  <textarea 
                    id="features" 
                    name="features" 
                    required 
                    className="w-full flex min-h-[120px] rounded-md border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-slate-900"
                    placeholder="Pisahkan dengan koma. Contoh: Katalog hingga 50 Produk, Keranjang Belanja, Integrasi WhatsApp"
                  />
                </div>
              </div>
            </div>

            <div className="w-full lg:w-[320px] space-y-6">
              
              <div className="bg-amber-50 border border-amber-200 rounded-lg p-5">
                <Label className="text-amber-800 font-bold text-sm block mb-2">Paket Populer?</Label>
                <p className="text-xs text-amber-700/80 mb-4">Jika diaktifkan, paket ini akan mendapat badge "Paling Populer" dan memiliki warna highlight (gradasi kuning/hijau) di halaman utama.</p>
                <label className="flex items-center gap-2 cursor-pointer bg-white p-3 rounded border border-amber-200 shadow-sm">
                  <input type="checkbox" name="isPopular" className="rounded border-amber-300 text-amber-500 focus:ring-amber-500 w-4 h-4" />
                  <span className="text-sm font-bold text-amber-700">Tandai Paling Populer</span>
                </label>
              </div>

              <div className="pt-4 border-t border-slate-200 flex items-center justify-between">
                <label className="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" defaultChecked className="rounded border-slate-300 text-slate-900 focus:ring-slate-900 w-4 h-4" />
                  <span className="text-sm font-semibold text-slate-700">Aktifkan Paket</span>
                </label>
                <Button type="submit" className="bg-slate-900 hover:bg-slate-800 text-white font-bold">
                  Simpan Paket
                </Button>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  );
}
