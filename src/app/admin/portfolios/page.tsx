import { db } from "@/db";
import { portfolios } from "@/db/schema";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { addPortfolio, deletePortfolio, togglePortfolioStatus } from "./actions";
import { ImageUpload } from "@/components/ui/image-upload";
import { Edit, Trash2, EyeOff, Plus, Search, Filter } from "lucide-react";

export default async function PortfoliosPage() {
  const allPortfolios = await db.select().from(portfolios).orderBy(portfolios.createdAt);

  return (
    <div className="space-y-6">
      
      {/* List Card */}
      <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div className="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
            <ImageIcon className="w-5 h-5 text-slate-500" /> Daftar Portfolios
          </h2>
          {/* We will just use an anchor to scroll down to the form for now */}
          <a href="#add-form" className="bg-slate-900 text-white hover:bg-slate-800 px-4 py-2 rounded-md font-semibold text-sm flex items-center transition-colors">
            <Plus className="w-4 h-4 mr-2" /> Tambah Portfolio
          </a>
        </div>

        <div className="p-4 border-b border-slate-100 flex flex-col md:flex-row gap-4 bg-slate-50/50">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <Input className="pl-9 bg-white" placeholder="Cari portfolio..." />
          </div>
          <select className="border border-slate-200 rounded-md bg-white px-3 py-2 text-sm outline-none w-full md:w-48 text-slate-600">
            <option>Semua Kategori</option>
          </select>
          <Button variant="outline" className="bg-white"><Filter className="w-4 h-4 mr-2" /> Filter</Button>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-sm text-left">
            <thead className="bg-slate-50 text-slate-500 text-xs font-bold uppercase border-b border-slate-200">
              <tr>
                <th className="px-6 py-4">GAMBAR</th>
                <th className="px-6 py-4">NAMA PORTFOLIO</th>
                <th className="px-6 py-4">KATEGORI</th>
                <th className="px-6 py-4">STATUS</th>
                <th className="px-6 py-4 text-right">AKSI</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {allPortfolios.length === 0 ? (
                <tr>
                  <td colSpan={5} className="px-6 py-8 text-center text-slate-500">Belum ada portfolio.</td>
                </tr>
              ) : (
                allPortfolios.map((item) => (
                  <tr key={item.id} className="hover:bg-slate-50/50 transition-colors">
                    <td className="px-6 py-4">
                      {item.imageUrl ? (
                        <img src={item.imageUrl} alt={item.title} className="w-16 h-16 object-cover rounded border border-slate-200" />
                      ) : (
                        <div className="w-16 h-16 bg-slate-100 rounded border border-slate-200 flex items-center justify-center text-slate-400 text-xs">No Img</div>
                      )}
                    </td>
                    <td className="px-6 py-4">
                      <p className="font-bold text-slate-900">{item.title}</p>
                      <p className="text-xs text-slate-500 mt-1 line-clamp-1 max-w-[200px]">{item.description}</p>
                    </td>
                    <td className="px-6 py-4">
                      <span className="bg-slate-100 text-slate-700 font-bold px-3 py-1 rounded text-[10px] uppercase tracking-wider border border-slate-200">
                        {item.category || "UMUM"}
                      </span>
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
                        <form action={async () => { "use server"; await togglePortfolioStatus(item.id, item.isActive); }}>
                          <Button variant="outline" size="icon" type="submit" className="h-8 w-8 text-amber-500 border-amber-200 hover:bg-amber-50 hover:text-amber-600">
                            <EyeOff className="w-4 h-4" />
                          </Button>
                        </form>
                        <form action={async () => { "use server"; await deletePortfolio(item.id); }}>
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
            <Plus className="w-5 h-5 text-slate-500" /> Tambah Portfolio Baru
          </h2>
        </div>
        <div className="p-6">
          <form action={async (formData) => { "use server"; await addPortfolio(formData); }} className="flex flex-col lg:flex-row gap-8">
            <div className="flex-1 space-y-6">
              <div className="space-y-2">
                <Label htmlFor="title" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Nama Portfolio <span className="text-red-500">*</span></Label>
                <Input id="title" name="title" required className="bg-white border-slate-300" placeholder="Contoh: E-Commerce Web App" />
              </div>
              <div className="space-y-2">
                <Label htmlFor="description" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Deskripsi <span className="text-red-500">*</span></Label>
                <textarea 
                  id="description" 
                  name="description" 
                  required 
                  className="w-full flex min-h-[120px] rounded-md border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-slate-900"
                  placeholder="Deskripsi singkat mengenai project..."
                />
              </div>

              <div className="bg-[#f0f9ff] border border-[#bae6fd] rounded-lg p-5 mt-6">
                <h3 className="text-[#0369a1] font-bold text-sm mb-4 flex items-center gap-2">
                  <span className="w-4 h-4 bg-[#bae6fd] text-[#0369a1] flex items-center justify-center rounded-sm text-[10px]">P</span>
                  Spesifikasi Portfolio
                </h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div className="space-y-2">
                    <Label htmlFor="category" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Kategori</Label>
                    <select id="category" name="category" className="w-full border border-slate-300 rounded-md bg-white px-3 py-2 text-sm outline-none focus:border-slate-900">
                      <option value="Landing Page">Landing Page</option>
                      <option value="Company Profile">Company Profile</option>
                      <option value="E-Commerce">E-Commerce</option>
                      <option value="Web App">Web App</option>
                      <option value="Mobile App">Mobile App</option>
                    </select>
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="link" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Link Website</Label>
                    <Input id="link" name="link" className="bg-white border-slate-300" placeholder="https://" />
                  </div>
                </div>
              </div>
            </div>

            <div className="w-full lg:w-[320px] space-y-6">
              <div>
                <Label className="text-slate-700 font-bold text-xs uppercase tracking-wider block mb-2">Gambar Portfolio <span className="text-red-500">*</span></Label>
                <p className="text-xs text-slate-500 mb-4">Upload gambar thumbnail portfolio. Format: JPG, PNG. Max: 2MB.</p>
                <ImageUpload name="imageUrl" />
              </div>
              
              <div className="pt-4 border-t border-slate-200 flex items-center justify-between">
                <label className="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" defaultChecked className="rounded border-slate-300 text-slate-900 focus:ring-slate-900" />
                  <span className="text-sm font-semibold text-slate-700">Aktifkan Portfolio</span>
                </label>
                <Button type="submit" className="bg-slate-900 hover:bg-slate-800 text-white font-bold">
                  Simpan Portfolio
                </Button>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  );
}
// Placeholder components since I cannot easily import them all
function ImageIcon({ className }: { className?: string }) { return <svg className={className} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2v12a2 2 0 002 2z" /></svg>; }
