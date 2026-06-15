import { db } from "@/db";
import { portfolios } from "@/db/schema";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { updatePortfolio } from "../actions";
import { ImageUpload } from "@/components/ui/image-upload";
import { ArrowLeft, Save } from "lucide-react";
import Link from "next/link";
import { eq } from "drizzle-orm";
import { redirect } from "next/navigation";

export default async function EditPortfolioPage({ params }: { params: { id: string } }) {
  const id = parseInt(params.id, 10);
  if (isNaN(id)) {
    redirect("/admin/portfolios");
  }

  const [portfolio] = await db.select().from(portfolios).where(eq(portfolios.id, id));

  if (!portfolio) {
    redirect("/admin/portfolios");
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-4">
        <Link href="/admin/portfolios" className="text-slate-500 hover:text-slate-900 transition-colors">
          <ArrowLeft className="w-5 h-5" />
        </Link>
        <h1 className="text-2xl font-bold text-slate-800">Edit Portfolio</h1>
      </div>

      <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div className="p-6">
          <form action={async (formData) => { "use server"; await updatePortfolio(id, formData); redirect("/admin/portfolios"); }} className="flex flex-col lg:flex-row gap-8">
            <div className="flex-1 space-y-6">
              <div className="space-y-2">
                <Label htmlFor="title" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Nama Portfolio <span className="text-red-500">*</span></Label>
                <Input id="title" name="title" defaultValue={portfolio.title} required className="bg-white border-slate-300" placeholder="Contoh: E-Commerce Web App" />
              </div>
              <div className="space-y-2">
                <Label htmlFor="description" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Deskripsi <span className="text-red-500">*</span></Label>
                <textarea 
                  id="description" 
                  name="description" 
                  required 
                  defaultValue={portfolio.description}
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
                    <select id="category" name="category" defaultValue={portfolio.category || "Landing Page"} className="w-full border border-slate-300 rounded-md bg-white px-3 py-2 text-sm outline-none focus:border-slate-900">
                      <option value="Landing Page">Landing Page</option>
                      <option value="Company Profile">Company Profile</option>
                      <option value="E-Commerce">E-Commerce</option>
                      <option value="Web App">Web App</option>
                      <option value="Custome Web">Custome Web</option>
                    </select>
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="link" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Link Website</Label>
                    <Input id="link" name="link" defaultValue={portfolio.link || ""} className="bg-white border-slate-300" placeholder="https://" />
                  </div>
                </div>
              </div>
            </div>

            <div className="w-full lg:w-[320px] space-y-6">
              <div>
                <Label className="text-slate-700 font-bold text-xs uppercase tracking-wider block mb-2">Gambar Portfolio <span className="text-red-500">*</span></Label>
                <p className="text-xs text-slate-500 mb-4">Upload gambar thumbnail portfolio. Format: JPG, PNG. Max: 15MB.</p>
                <ImageUpload name="imageUrl" defaultValue={portfolio.imageUrl || ""} />
              </div>
              
              <div className="pt-4 border-t border-slate-200 flex items-center justify-between">
                <Button type="submit" className="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold flex justify-center items-center gap-2">
                  <Save className="w-4 h-4" /> Simpan Perubahan
                </Button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
