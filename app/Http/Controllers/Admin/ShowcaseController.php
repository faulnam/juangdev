<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class ShowcaseController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('admin.showcase.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'feature_showcase_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
        ]);

        $data = $request->except(['_token', 'feature_showcase_image_file']);

        if ($request->hasFile('feature_showcase_image_file')) {
            $imageUrl = \App\Helpers\ImageCompressor::uploadAndCompress($request->file('feature_showcase_image_file'), 'uploads/showcase');
            $data['feature_showcase_image'] = $imageUrl;
        }

        foreach ($data as $key => $value) {
            if ($value !== null) {
                SiteSetting::set($key, $value);
            }
        }

        return back()->with('success', 'Konten Showcase Layanan berhasil diperbarui.');
    }
}
