import { db } from "@/db";
import { blogs } from "@/db/schema";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { addBlog, deleteBlog, toggleBlogStatus } from "./actions";
import { Edit, Trash2, EyeOff, Plus, FileText } from "lucide-react";

export default async function BlogsPage() {
  const allBlogs = await db.select().from(blogs).orderBy(blogs.createdAt);

  return (
    <div className="space-y-6">
      
      {/* List Card */}
      <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div className="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
            <FileText className="w-5 h-5 text-slate-500" /> Daftar Blog
          </h2>
          <a href="#add-form" className="bg-slate-900 text-white hover:bg-slate-800 px-4 py-2 rounded-md font-semibold text-sm flex items-center transition-colors">
            <Plus className="w-4 h-4 mr-2" /> Tambah Blog
          </a>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-sm text-left">
            <thead className="bg-slate-50 text-slate-500 text-xs font-bold uppercase border-b border-slate-200">
              <tr>
                <th className="px-6 py-4">INFO BLOG</th>
                <th className="px-6 py-4">KATEGORI</th>
                <th className="px-6 py-4">WAKTU BACA</th>
                <th className="px-6 py-4">STATUS</th>
                <th className="px-6 py-4 text-right">AKSI</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {allBlogs.length === 0 ? (
                <tr>
                  <td colSpan={5} className="px-6 py-8 text-center text-slate-500">Belum ada blog.</td>
                </tr>
              ) : (
                allBlogs.map((item) => (
                  <tr key={item.id} className="hover:bg-slate-50/50 transition-colors">
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-3">
                        {item.imageUrl ? (
                          // eslint-disable-next-line @next/next/no-img-element
                          <img src={item.imageUrl} alt={item.title} className="w-12 h-12 rounded object-cover border border-slate-200 bg-slate-100" />
                        ) : (
                          <div className="w-12 h-12 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 font-bold text-lg">
                            {item.title.charAt(0)}
                          </div>
                        )}
                        <div>
                          <p className="font-bold text-slate-900">{item.title}</p>
                          <p className="text-xs text-slate-500 truncate max-w-xs">{item.excerpt}</p>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <span className="bg-slate-100 text-slate-700 font-bold px-3 py-1 rounded text-[10px] uppercase tracking-wider border border-slate-200">
                        {item.category}
                      </span>
                    </td>
                    <td className="px-6 py-4">
                      <span className="text-slate-600 text-xs font-semibold">{item.readTime || "-"}</span>
                      {item.date && <p className="text-[10px] text-slate-400 mt-1">{item.date}</p>}
                    </td>
                    <td className="px-6 py-4">
                      <span className={`font-bold text-xs ${item.isActive ? "text-emerald-600" : "text-red-500"}`}>
                        {item.isActive ? "AKTIF" : "SEMBUNYI"}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex items-center justify-end gap-2">
                        <form action={async () => { "use server"; await toggleBlogStatus(item.id, item.isActive); }}>
                          <Button variant="outline" size="icon" type="submit" className="h-8 w-8 text-amber-500 border-amber-200 hover:bg-amber-50 hover:text-amber-600">
                            <EyeOff className="w-4 h-4" />
                          </Button>
                        </form>
                        <form action={async () => { "use server"; await deleteBlog(item.id); }}>
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
            <Plus className="w-5 h-5 text-slate-500" /> Tambah Blog Baru
          </h2>
        </div>
        <div className="p-6">
          <form action={async (formData) => { "use server"; await addBlog(formData); }} className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div className="space-y-2 md:col-span-2">
              <Label htmlFor="title" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Judul Artikel <span className="text-red-500">*</span></Label>
              <Input id="title" name="title" required className="bg-white border-slate-300" placeholder="Contoh: Mengapa Next.js Sangat Populer di 2026" />
            </div>

            <div className="space-y-2 md:col-span-2">
              <Label htmlFor="excerpt" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Ringkasan (Excerpt) <span className="text-red-500">*</span></Label>
              <Textarea id="excerpt" name="excerpt" required className="bg-white border-slate-300 h-20 resize-none" placeholder="Tuliskan ringkasan 1-2 kalimat dari artikel ini..." />
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="category" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Kategori <span className="text-red-500">*</span></Label>
              <Input id="category" name="category" required className="bg-white border-slate-300" placeholder="Contoh: Teknologi, E-Commerce" />
            </div>

            <div className="space-y-2">
              <Label htmlFor="readTime" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Waktu Baca</Label>
              <Input id="readTime" name="readTime" className="bg-white border-slate-300" placeholder="Contoh: 5 min read" />
            </div>

            <div className="space-y-2">
              <Label htmlFor="date" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Tanggal</Label>
              <Input id="date" name="date" className="bg-white border-slate-300" placeholder="Contoh: June 15, 2026" />
            </div>

            <div className="space-y-2">
              <Label htmlFor="imageUrl" className="text-slate-700 font-bold text-xs uppercase tracking-wider">URL Gambar (Opsional)</Label>
              <Input id="imageUrl" name="imageUrl" className="bg-white border-slate-300" placeholder="Contoh: https://example.com/image.jpg" />
            </div>

            <div className="col-span-full pt-4 border-t border-slate-200 flex items-center justify-end">
              <Button type="submit" className="bg-slate-900 hover:bg-slate-800 text-white font-bold">
                Simpan Blog
              </Button>
            </div>
          </form>
        </div>
      </div>

    </div>
  );
}
