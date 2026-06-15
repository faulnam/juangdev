import { db } from "@/db";
import { designTiers } from "@/db/schema";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { addDesignTier, deleteDesignTier, toggleDesignTierStatus } from "./actions";
import { Edit, Trash2, EyeOff, Plus, Palette } from "lucide-react";

export default async function DesignTiersPage() {
  const allTiers = await db.select().from(designTiers).orderBy(designTiers.createdAt);

  return (
    <div className="space-y-6">
      
      {/* List Card */}
      <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div className="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
            <Palette className="w-5 h-5 text-slate-500" /> Daftar Tingkat Design
          </h2>
          <a href="#add-form" className="bg-slate-900 text-white hover:bg-slate-800 px-4 py-2 rounded-md font-semibold text-sm flex items-center transition-colors">
            <Plus className="w-4 h-4 mr-2" /> Tambah Tingkat Design
          </a>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-sm text-left">
            <thead className="bg-slate-50 text-slate-500 text-xs font-bold uppercase border-b border-slate-200">
              <tr>
                <th className="px-6 py-4">NAMA TINGKAT</th>
                <th className="px-6 py-4">TEMA WARNA</th>
                <th className="px-6 py-4">HARGA TAMBAHAN</th>
                <th className="px-6 py-4">STATUS</th>
                <th className="px-6 py-4 text-right">AKSI</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {allTiers.length === 0 ? (
                <tr>
                  <td colSpan={5} className="px-6 py-8 text-center text-slate-500">Belum ada tingkat design.</td>
                </tr>
              ) : (
                allTiers.map((item) => (
                  <tr key={item.id} className="hover:bg-slate-50/50 transition-colors">
                    <td className="px-6 py-4">
                      <p className="font-bold text-slate-900">{item.name}</p>
                    </td>
                    <td className="px-6 py-4">
                      <span className="bg-slate-100 text-slate-700 font-bold px-3 py-1 rounded text-[10px] uppercase tracking-wider border border-slate-200">
                        {item.colorTheme}
                      </span>
                    </td>
                    <td className="px-6 py-4">
                      <span className="font-bold text-slate-900">Rp {item.price.toLocaleString('id-ID')}</span>
                    </td>
                    <td className="px-6 py-4">
                      <span className={`font-bold text-xs ${item.isActive ? "text-slate-700" : "text-red-500"}`}>
                        {item.isActive ? "AKTIF" : "SEMBUNYI"}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex items-center justify-end gap-2">
                        <form action={async () => { "use server"; await toggleDesignTierStatus(item.id, item.isActive); }}>
                          <Button variant="outline" size="icon" type="submit" className="h-8 w-8 text-amber-500 border-amber-200 hover:bg-amber-50 hover:text-amber-600">
                            <EyeOff className="w-4 h-4" />
                          </Button>
                        </form>
                        <form action={async () => { "use server"; await deleteDesignTier(item.id); }}>
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
            <Plus className="w-5 h-5 text-slate-500" /> Tambah Tingkat Design
          </h2>
        </div>
        <div className="p-6">
          <form action={async (formData) => { "use server"; await addDesignTier(formData); }} className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div className="space-y-2">
              <Label htmlFor="name" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Nama Tingkat Design <span className="text-red-500">*</span></Label>
              <Input id="name" name="name" required className="bg-white border-slate-300" placeholder="Contoh: Premium Custom" />
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="price" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Harga Tambahan (Rp) <span className="text-red-500">*</span></Label>
              <Input id="price" name="price" type="number" required className="bg-white border-slate-300" placeholder="Contoh: 1500000" />
            </div>

            <div className="space-y-2">
              <Label htmlFor="colorTheme" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Tema Warna <span className="text-red-500">*</span></Label>
              <select id="colorTheme" name="colorTheme" required className="w-full border border-slate-300 rounded-md bg-white px-3 py-2 text-sm outline-none focus:border-slate-900">
                <option value="white">Putih (Basic)</option>
                <option value="blue">Biru Gelap (Pro)</option>
                <option value="lime">Hijau Neon (Premium)</option>
              </select>
            </div>

            <div className="col-span-full pt-4 border-t border-slate-200 flex items-center justify-end">
              <Button type="submit" className="bg-slate-900 hover:bg-slate-800 text-white font-bold">
                Simpan Tingkat Design
              </Button>
            </div>
          </form>
        </div>
      </div>

    </div>
  );
}
