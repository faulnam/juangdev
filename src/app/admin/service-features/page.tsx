import { db } from "@/db";
import { serviceFeatures, services } from "@/db/schema";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { addServiceFeature, deleteServiceFeature, toggleServiceFeatureStatus } from "./actions";
import { Edit, Trash2, EyeOff, Plus, Layers } from "lucide-react";
import { eq } from "drizzle-orm";

export default async function ServiceFeaturesPage() {
  const allFeatures = await db
    .select({
      feature: serviceFeatures,
      serviceTitle: services.title,
    })
    .from(serviceFeatures)
    .leftJoin(services, eq(serviceFeatures.serviceId, services.id))
    .orderBy(serviceFeatures.createdAt);

  const allServices = await db.select().from(services);

  return (
    <div className="space-y-6">
      
      {/* List Card */}
      <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div className="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
            <Layers className="w-5 h-5 text-slate-500" /> Daftar Fitur Tambahan
          </h2>
          <a href="#add-form" className="bg-slate-900 text-white hover:bg-slate-800 px-4 py-2 rounded-md font-semibold text-sm flex items-center transition-colors">
            <Plus className="w-4 h-4 mr-2" /> Tambah Fitur
          </a>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-sm text-left">
            <thead className="bg-slate-50 text-slate-500 text-xs font-bold uppercase border-b border-slate-200">
              <tr>
                <th className="px-6 py-4">NAMA FITUR</th>
                <th className="px-6 py-4">UNTUK LAYANAN</th>
                <th className="px-6 py-4">HARGA TAMBAHAN</th>
                <th className="px-6 py-4">STATUS</th>
                <th className="px-6 py-4 text-right">AKSI</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {allFeatures.length === 0 ? (
                <tr>
                  <td colSpan={5} className="px-6 py-8 text-center text-slate-500">Belum ada fitur tambahan.</td>
                </tr>
              ) : (
                allFeatures.map((item) => (
                  <tr key={item.feature.id} className="hover:bg-slate-50/50 transition-colors">
                    <td className="px-6 py-4">
                      <p className="font-bold text-slate-900">{item.feature.name}</p>
                    </td>
                    <td className="px-6 py-4">
                      <span className="bg-blue-50 text-blue-700 font-bold px-3 py-1 rounded text-[10px] uppercase tracking-wider border border-blue-200">
                        {item.serviceTitle || "Unknown"}
                      </span>
                    </td>
                    <td className="px-6 py-4">
                      <span className="font-bold text-slate-900">Rp {item.feature.price.toLocaleString('id-ID')}</span>
                    </td>
                    <td className="px-6 py-4">
                      <span className={`font-bold text-xs ${item.feature.isActive ? "text-slate-700" : "text-red-500"}`}>
                        {item.feature.isActive ? "AKTIF" : "SEMBUNYI"}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex items-center justify-end gap-2">
                        <form action={async () => { "use server"; await toggleServiceFeatureStatus(item.feature.id, item.feature.isActive); }}>
                          <Button variant="outline" size="icon" type="submit" className="h-8 w-8 text-amber-500 border-amber-200 hover:bg-amber-50 hover:text-amber-600">
                            <EyeOff className="w-4 h-4" />
                          </Button>
                        </form>
                        <form action={async () => { "use server"; await deleteServiceFeature(item.feature.id); }}>
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
            <Plus className="w-5 h-5 text-slate-500" /> Tambah Fitur Tambahan
          </h2>
        </div>
        <div className="p-6">
          <form action={addServiceFeature} className="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div className="space-y-2">
              <Label htmlFor="serviceId" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Untuk Layanan <span className="text-red-500">*</span></Label>
              <select id="serviceId" name="serviceId" required className="w-full border border-slate-300 rounded-md bg-white px-3 py-2 text-sm outline-none focus:border-slate-900">
                <option value="">-- Pilih Layanan Utama --</option>
                {allServices.map((svc) => (
                  <option key={svc.id} value={svc.id}>{svc.title}</option>
                ))}
              </select>
            </div>

            <div className="space-y-2">
              <Label htmlFor="name" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Nama Fitur <span className="text-red-500">*</span></Label>
              <Input id="name" name="name" required className="bg-white border-slate-300" placeholder="Contoh: Payment Gateway" />
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="price" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Harga Tambahan (Rp) <span className="text-red-500">*</span></Label>
              <Input id="price" name="price" type="number" required className="bg-white border-slate-300" placeholder="Contoh: 1500000" />
            </div>

            <div className="col-span-full pt-4 border-t border-slate-200 flex items-center justify-end">
              <Button type="submit" className="bg-slate-900 hover:bg-slate-800 text-white font-bold">
                Simpan Fitur
              </Button>
            </div>
          </form>
        </div>
      </div>

    </div>
  );
}
