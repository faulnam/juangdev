<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageCompressor;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AboutSectionController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('admin.about-section.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'about_card1_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'about_card2_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'about_card3_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
        ]);

        $data = $request->except([
            '_token', 
            'about_card1_image_file', 
            'about_card2_image_file', 
            'about_card3_image_file'
        ]);

        // Upload Card 1 Image (Laptop Mockup)
        if ($request->hasFile('about_card1_image_file')) {
            $imageUrl = ImageCompressor::uploadAndCompress($request->file('about_card1_image_file'), 'uploads/about');
            $data['about_card1_image'] = $imageUrl;
        }

        // Upload Card 2 Image (Tablet Dashboard)
        if ($request->hasFile('about_card2_image_file')) {
            $imageUrl = ImageCompressor::uploadAndCompress($request->file('about_card2_image_file'), 'uploads/about');
            $data['about_card2_image'] = $imageUrl;
        }

        // Upload Card 3 Image (Team Collaboration)
        if ($request->hasFile('about_card3_image_file')) {
            $imageUrl = ImageCompressor::uploadAndCompress($request->file('about_card3_image_file'), 'uploads/about');
            $data['about_card3_image'] = $imageUrl;
        }

        foreach ($data as $key => $value) {
            if ($value !== null) {
                SiteSetting::set($key, $value);
            }
        }

        return back()->with('success', 'Konten Bagian Tentang Kami (About) berhasil diperbarui.');
    }
}
