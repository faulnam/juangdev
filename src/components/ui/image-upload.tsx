"use client";

import { useState } from "react";
import { UploadCloud, Loader2 } from "lucide-react";

export function ImageUpload({ name = "imageUrl", defaultValue = "" }: { name?: string, defaultValue?: string }) {
  const [url, setUrl] = useState(defaultValue);
  const [uploading, setUploading] = useState(false);

  const handleFileChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    if (!e.target.files || e.target.files.length === 0) return;
    
    const file = e.target.files[0];
    
    // Check if file size is > 15MB
    if (file.size > 15 * 1024 * 1024) {
      alert("Ukuran gambar maksimal 15MB");
      return;
    }

    setUploading(true);
    const formData = new FormData();
    formData.append("file", file);

    try {
      const res = await fetch("/api/upload", {
        method: "POST",
        body: formData,
      });
      const data = await res.json();
      if (data.success) {
        setUrl(data.url);
      } else {
        alert("Upload failed");
      }
    } catch (error) {
      console.error(error);
      alert("Upload error");
    } finally {
      setUploading(false);
    }
  };

  return (
    <div className="space-y-3">
      {url ? (
        <div className="relative rounded-md overflow-hidden border border-slate-200 bg-slate-50 h-32 w-32 group">
          <img src={url} alt="Uploaded preview" className="w-full h-full object-cover" />
          <div className="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
            <label className="cursor-pointer text-white text-xs font-semibold px-2 py-1 bg-black/50 rounded">
              Change
              <input type="file" className="hidden" accept="image/*" onChange={handleFileChange} />
            </label>
          </div>
        </div>
      ) : (
        <label className="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100">
          <div className="flex flex-col items-center justify-center pt-5 pb-6">
            {uploading ? <Loader2 className="w-8 h-8 mb-2 text-slate-400 animate-spin" /> : <UploadCloud className="w-8 h-8 mb-2 text-slate-400" />}
            <p className="mb-2 text-sm text-slate-500"><span className="font-semibold">Click to upload</span></p>
          </div>
          <input type="file" className="hidden" accept="image/*" onChange={handleFileChange} disabled={uploading} />
        </label>
      )}
      <input type="hidden" name={name} value={url} />
    </div>
  );
}
