<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageCompressor;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HeroSectionController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('admin.hero-sections.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_home_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
        ]);

        $data = $request->except(['_token', 'hero_home_image_file']);

        if ($request->hasFile('hero_home_image_file')) {
            $imageUrl = ImageCompressor::uploadAndCompress($request->file('hero_home_image_file'), 'uploads/hero');
            $data['hero_home_image'] = $imageUrl;
        }

        foreach ($data as $key => $value) {
            if ($value !== null) {
                SiteSetting::set($key, $value);
            }
        }

        return back()->with('success', 'Konten Hero Section berhasil diperbarui.');
    }
}
