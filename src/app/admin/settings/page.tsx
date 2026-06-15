import { db } from "@/db";
import { siteSettings } from "@/db/schema";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { updateSetting } from "./actions";
import { Settings, Save } from "lucide-react";
import { ImageUpload } from "@/components/ui/image-upload";

export default async function SettingsPage() {
  const settings = await db.select().from(siteSettings);
  
  const getSetting = (key: string) => settings.find(s => s.key === key)?.value || '';

  return (
    <div className="space-y-6">
      
      <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div className="p-4 border-b border-slate-200 bg-slate-50/50">
          <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
            <Settings className="w-5 h-5 text-slate-500" /> Pengaturan Website & Hero
          </h2>
        </div>
        
        <div className="p-6">
          <div className="grid grid-cols-1 xl:grid-cols-2 gap-8">
            
            {/* Hero Text Settings */}
            <div className="space-y-6 bg-[#f0f9ff] border border-[#bae6fd] p-5 rounded-xl">
              <h3 className="text-[#0369a1] font-bold text-sm mb-4">Hero Section Text</h3>
              
              <form action={async (formData) => { "use server"; await updateSetting(formData); }} className="space-y-4 bg-white p-4 rounded-lg border border-slate-200">
                <input type="hidden" name="key" value="hero_title" />
                <div className="space-y-2">
                  <Label htmlFor="hero_title" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Judul Utama (Hero Title)</Label>
                  <Input 
                    id="hero_title" 
                    name="value" 
                    defaultValue={getSetting('hero_title')} 
                    className="bg-white border-slate-300" 
                  />
                  <p className="text-[10px] text-slate-500 font-medium">Contoh: Build Websites & Custom Software That Grow Your Business.</p>
                </div>
                <div className="flex justify-end">
                  <Button type="submit" size="sm" className="bg-slate-900 hover:bg-slate-800 text-white">Simpan Judul</Button>
                </div>
              </form>

              <form action={async (formData) => { "use server"; await updateSetting(formData); }} className="space-y-4 bg-white p-4 rounded-lg border border-slate-200">
                <input type="hidden" name="key" value="hero_subtitle" />
                <div className="space-y-2">
                  <Label htmlFor="hero_subtitle" className="text-slate-700 font-bold text-xs uppercase tracking-wider">Sub-judul (Hero Subtitle)</Label>
                  <Input 
                    id="hero_subtitle" 
                    name="value" 
                    defaultValue={getSetting('hero_subtitle')} 
                    className="bg-white border-slate-300" 
                  />
                </div>
                <div className="flex justify-end">
                  <Button type="submit" size="sm" className="bg-slate-900 hover:bg-slate-800 text-white">Simpan Sub-judul</Button>
                </div>
              </form>
            </div>

            {/* Hero Image & About Text */}
            <div className="space-y-6">
              
              <div className="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <h3 className="text-slate-800 font-bold text-sm mb-4">Gambar Utama (Hero Image)</h3>
                <form action={async (formData) => { "use server"; await updateSetting(formData); }} className="space-y-4 flex gap-4">
                  <input type="hidden" name="key" value="hero_image" />
                  <div className="flex-1">
                    <p className="text-xs text-slate-500 mb-2">Upload gambar orang di hero section (Format transparan PNG disarankan).</p>
                    <ImageUpload name="value" defaultValue={getSetting('hero_image')} />
                  </div>
                  <div className="flex flex-col justify-end">
                    <Button type="submit" size="sm" className="bg-slate-900 hover:bg-slate-800 text-white">Simpan Gambar</Button>
                  </div>
                </form>
              </div>

              <div className="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <h3 className="text-slate-800 font-bold text-sm mb-4">Tentang Perusahaan (About Text)</h3>
                <form action={async (formData) => { "use server"; await updateSetting(formData); }} className="space-y-4">
                  <input type="hidden" name="key" value="about_text" />
                  <div className="space-y-2">
                    <textarea 
                      id="about_text" 
                      name="value" 
                      defaultValue={getSetting('about_text')} 
                      className="w-full flex min-h-[120px] rounded-md border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-slate-900" 
                      placeholder="JuangDev helps startups..."
                    />
                  </div>
                  <div className="flex justify-end">
                    <Button type="submit" size="sm" className="bg-slate-900 hover:bg-slate-800 text-white">Simpan Deskripsi</Button>
                  </div>
                </form>
              </div>

            </div>

          </div>
        </div>
      </div>

    </div>
  );
}
