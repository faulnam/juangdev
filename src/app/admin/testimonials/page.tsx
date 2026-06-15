import { db } from "@/db";
import { testimonials } from "@/db/schema";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { addTestimonial, deleteTestimonial, toggleTestimonialStatus } from "./actions";
import { ImageUpload } from "@/components/ui/image-upload";
import { Edit, Trash2, EyeOff, Plus, Search, Filter, Users, Star } from "lucide-react";

export default async function TestimonialsPage() {
  const allTestimonials = await db.select().from(testimonials).orderBy(testimonials.createdAt);

  return (
    <div className="space-y-6">
      
      {/* List Card */}
      <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div className="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
            <Users className="w-5 h-5 text-slate-500" /> Daftar Testimoni
          </h2>
          <a href="#add-form" className="bg-slate-900 text-white hover:bg-slate-800 px-4 py-2 rounded-md font-semibold text-sm flex items-center transition-colors">
            <Plus className="w-4 h-4 mr-2" /> Tambah Testimoni
          </a>
        </div>

        <div className="p-4 border-b border-slate-100 flex flex-col md:flex-row gap-4 bg-slate-50/50">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <Input className="pl-9 bg-white" placeholder="Cari klien..." />
          </div>
          <select className="border border-slate-200 rounded-md bg-white px-3 py-2 text-sm outline-none w-full md:w-48 text-slate-600">
            <option>Semua Rating</option>
            <option>5 Bintang</option>
            <option>4 Bintang</option>
          </select>
          <Button variant="outline" className="bg-white"><Filter className="w-4 h-4 mr-2" /> Filter</Button>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-sm text-left">
            <thead className="bg-slate-50 text-slate-500 text-xs font-bold uppercase border-b border-slate-200">
              <tr>
                <th className="px-6 py-4">FOTO</th>
                <th className="px-6 py-4">KLIEN & REVIEW</th>
                <th className="px-6 py-4">RATING</th>
                <th className="px-6 py-4">STATUS</th>
                <th className="px-6 py-4 text-right">AKSI</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {allTestimonials.length === 0 ? (
                <tr>
                  <td colSpan={5} className="px-6 py-8 text-center text-slate-500">Belum ada testimoni.</td>
                </tr>
              ) : (
                allTestimonials.map((item) => (
                  <tr key={item.id} className="hover:bg-slate-50/50 transition-colors">
                    <td className="px-6 py-4">
                      {item.imageUrl ? (
                        <img src={item.imageUrl} alt={item.name} className="w-12 h-12 object-cover rounded-full border border-slate-200" />
                      ) : (
                        <div className="w-12 h-12 bg-slate-100 rounded-full border border-slate-200 flex items-center justify-center text-slate-500 font-bold">
                          {item.name.charAt(0)}
                        </div>
                      )}
                    </td>
                    <td className="px-6 py-4">
                      <p className="font-bold text-slate-900">{item.name}</p>
                      <p className="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">{item.role}</p>
                      <p className="text-xs text-slate-600 mt-2 line-clamp-2 max-w-[300px] italic">"{item.content}"</p>
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex gap-0.5 text-yellow-400">
                        {Array.from({ length: item.rating || 5 }).map((_, i) => (
                          <Star key={i} className="w-3 h-3 fill-current" />
                        ))}
                      </div>
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
                        <form action={async () => { "use server"; await toggleTestimonialStatus(item.id, item.isActive); }}>
                          <Button variant="outline" size="icon" type="submit" className="h-8 w-8 text-amber-500 border-amber-200 hover:bg-amber-50 hover:text-amber-600">
                            <EyeOff className="w-4 h-4" />
                          </Button>
                        </form>
                        <form action={async () => { "use server"; await deleteTestimonial(item.id); }}>
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
            <Plus className="w-5 h-5 text-slate-500" /> Tambah Testimoni Baru
          </h2>
        </div>
        <div className="p-6">
          <form action={async (formData) => { "use server"; await addTestimonial(formData); }} className="flex flex-col lg:flex-row gap-8">
            <div className="flex-1 space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="name" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Nama Klien <span className="text-red-500">*</span></Label>
                  <Input id="name" name="name" required className="bg-white border-slate-300" placeholder="Contoh: John Doe" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="role" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Peran / Perusahaan</Label>
                  <Input id="role" name="role" className="bg-white border-slate-300" placeholder="Contoh: CEO Tokopedia" />
                </div>
              </div>
              
              <div className="space-y-2">
                <Label htmlFor="content" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Isi Testimoni <span className="text-red-500">*</span></Label>
                <textarea 
                  id="content" 
                  name="content" 
                  required 
                  className="w-full flex min-h-[120px] rounded-md border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-slate-900"
                  placeholder="Review yang diberikan klien..."
                />
              </div>

              <div className="bg-[#f0f9ff] border border-[#bae6fd] rounded-lg p-5 mt-6">
                <h3 className="text-[#0369a1] font-bold text-sm mb-4 flex items-center gap-2">
                  <span className="w-4 h-4 bg-[#bae6fd] text-[#0369a1] flex items-center justify-center rounded-sm text-[10px]"><Star className="w-3 h-3"/></span>
                  Penilaian Klien
                </h3>
                <div className="grid grid-cols-1 gap-4">
                  <div className="space-y-2 w-1/3">
                    <Label htmlFor="rating" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Bintang (1-5)</Label>
                    <Input id="rating" name="rating" type="number" min="1" max="5" defaultValue="5" className="bg-white border-slate-300" />
                  </div>
                </div>
              </div>
            </div>

            <div className="w-full lg:w-[320px] space-y-6">
              <div>
                <Label className="text-slate-700 font-bold text-xs uppercase tracking-wider block mb-2">Foto Klien (Opsional)</Label>
                <p className="text-xs text-slate-500 mb-4">Upload foto profil klien agar testimoni lebih meyakinkan.</p>
                <ImageUpload name="imageUrl" />
              </div>
              
              <div className="pt-4 border-t border-slate-200 flex items-center justify-between">
                <label className="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" defaultChecked className="rounded border-slate-300 text-slate-900 focus:ring-slate-900" />
                  <span className="text-sm font-semibold text-slate-700">Tampilkan Testimoni</span>
                </label>
                <Button type="submit" className="bg-slate-900 hover:bg-slate-800 text-white font-bold">
                  Simpan Testimoni
                </Button>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  );
}
