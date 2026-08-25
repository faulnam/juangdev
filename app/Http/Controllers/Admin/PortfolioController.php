<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderBy('display_order')->get();
        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create()
    {
        return view('admin.portfolios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'client' => 'nullable|string|max:255',
            'client_industry' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'package_tier' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'overview' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'image_url' => 'nullable|string',
            'live_url' => 'nullable|string',
            'featured' => 'nullable|boolean',
            'is_boilerplate' => 'nullable|boolean',
            'sold_count' => 'nullable|integer|min:0',
            'display_order' => 'nullable|integer',
            'pinned_image_index' => 'nullable|integer|min:0|max:4',
            'custom_technologies' => 'nullable|string',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Portfolio::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $techs = $request->technologies;
        if (is_string($techs)) {
            $techs = array_filter(array_map('trim', explode(',', $techs)));
        } elseif (!is_array($techs)) {
            $techs = [];
        }

        if ($request->filled('custom_technologies')) {
            $customArr = array_filter(array_map('trim', explode(',', $request->custom_technologies)));
            $techs = array_merge($techs, $customArr);
        }

        $techs = array_values(array_unique($techs));

        $keyFeatures = $request->key_features;
        if (is_string($keyFeatures)) {
            $keyFeatures = array_filter(array_map('trim', explode("\n", $keyFeatures)));
        } elseif (!is_array($keyFeatures)) {
            $keyFeatures = [];
        }

        // Process 5 gallery images
        $gallery = [];
        $pinnedIndex = $request->input('pinned_image_index', 0);
        $pinnedImageUrl = $request->image_url;

        if ($request->hasFile('image_file')) {
            $pinnedImageUrl = \App\Helpers\ImageCompressor::uploadAndCompress($request->file('image_file'));
        }

        $galleryTitles = $request->input('gallery_titles', []);
        $galleryUrls = $request->input('gallery_urls', []);
        $galleryFiles = $request->file('gallery_files', []);

        for ($i = 0; $i < 5; $i++) {
            $imgUrl = null;
            if (isset($galleryFiles[$i]) && $galleryFiles[$i]->isValid()) {
                $imgUrl = \App\Helpers\ImageCompressor::uploadAndCompress($galleryFiles[$i]);
            } elseif (!empty($galleryUrls[$i])) {
                $imgUrl = $galleryUrls[$i];
            }

            if ($imgUrl) {
                $isPinned = ((int)$pinnedIndex === $i);
                if ($isPinned) {
                    $pinnedImageUrl = $imgUrl;
                }
                $gallery[] = [
                    'title' => $galleryTitles[$i] ?? '',
                    'image_url' => $imgUrl,
                    'is_pinned' => $isPinned,
                ];
            }
        }

        if (!$pinnedImageUrl && count($gallery) > 0) {
            $pinnedImageUrl = $gallery[0]['image_url'];
        }

        Portfolio::create([
            'slug' => $slug,
            'title' => $request->title,
            'client' => $request->client,
            'client_industry' => $request->client_industry,
            'duration' => $request->duration,
            'category' => $request->category,
            'package_tier' => $request->package_tier,
            'description' => $request->description,
            'overview' => $request->overview,
            'key_features' => array_values($keyFeatures),
            'gallery' => array_values($gallery),
            'image_url' => $pinnedImageUrl,
            'live_url' => $request->live_url,
            'technologies' => array_values($techs),
            'featured' => $request->has('featured'),
            'is_boilerplate' => $request->has('is_boilerplate'),
            'sold_count' => (int)($request->sold_count ?? 0),
            'display_order' => (int)($request->display_order ?? 0),
        ]);

        return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio berhasil ditambahkan.');
    }

    public function edit(Portfolio $portfolio)
    {
        return view('admin.portfolios.edit', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'client' => 'nullable|string|max:255',
            'client_industry' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'package_tier' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'overview' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'image_url' => 'nullable|string',
            'live_url' => 'nullable|string',
            'featured' => 'nullable|boolean',
            'is_boilerplate' => 'nullable|boolean',
            'sold_count' => 'nullable|integer|min:0',
            'display_order' => 'nullable|integer',
            'pinned_image_index' => 'nullable|integer|min:0|max:4',
            'custom_technologies' => 'nullable|string',
        ]);

        $techs = $request->technologies;
        if (is_string($techs)) {
            $techs = array_filter(array_map('trim', explode(',', $techs)));
        } elseif (!is_array($techs)) {
            $techs = [];
        }

        if ($request->filled('custom_technologies')) {
            $customArr = array_filter(array_map('trim', explode(',', $request->custom_technologies)));
            $techs = array_merge($techs, $customArr);
        }

        $techs = array_values(array_unique($techs));

        $keyFeatures = $request->key_features;
        if (is_string($keyFeatures)) {
            $keyFeatures = array_filter(array_map('trim', explode("\n", $keyFeatures)));
        } elseif (!is_array($keyFeatures)) {
            $keyFeatures = [];
        }

        $pinnedIndex = $request->input('pinned_image_index', 0);
        $pinnedImageUrl = $portfolio->image_url;

        if ($request->hasFile('image_file')) {
            $pinnedImageUrl = \App\Helpers\ImageCompressor::uploadAndCompress($request->file('image_file'));
        } elseif ($request->filled('image_url')) {
            $pinnedImageUrl = $request->image_url;
        }

        $gallery = [];
        $galleryTitles = $request->input('gallery_titles', []);
        $galleryUrls = $request->input('gallery_urls', []);
        $galleryFiles = $request->file('gallery_files', []);
        $existingGallery = is_array($portfolio->gallery) ? $portfolio->gallery : [];

        for ($i = 0; $i < 5; $i++) {
            $imgUrl = null;
            if (isset($galleryFiles[$i]) && $galleryFiles[$i]->isValid()) {
                $imgUrl = \App\Helpers\ImageCompressor::uploadAndCompress($galleryFiles[$i]);
            } elseif (!empty($galleryUrls[$i])) {
                $imgUrl = $galleryUrls[$i];
            } elseif (isset($existingGallery[$i]['image_url'])) {
                $imgUrl = $existingGallery[$i]['image_url'];
            }

            if ($imgUrl) {
                $isPinned = ((int)$pinnedIndex === $i);
                if ($isPinned) {
                    $pinnedImageUrl = $imgUrl;
                }
                $gallery[] = [
                    'title' => $galleryTitles[$i] ?? ($existingGallery[$i]['title'] ?? ''),
                    'image_url' => $imgUrl,
                    'is_pinned' => $isPinned,
                ];
            }
        }

        if (!$pinnedImageUrl && count($gallery) > 0) {
            $pinnedImageUrl = $gallery[0]['image_url'];
        }

        $portfolio->update([
            'title' => $request->title,
            'client' => $request->client,
            'client_industry' => $request->client_industry,
            'duration' => $request->duration,
            'category' => $request->category,
            'package_tier' => $request->package_tier,
            'description' => $request->description,
            'overview' => $request->overview,
            'key_features' => array_values($keyFeatures),
            'gallery' => array_values($gallery),
            'image_url' => $pinnedImageUrl,
            'live_url' => $request->live_url,
            'technologies' => array_values($techs),
            'featured' => $request->has('featured'),
            'is_boilerplate' => $request->has('is_boilerplate'),
            'sold_count' => (int)($request->sold_count ?? 0),
            'display_order' => (int)($request->display_order ?? 0),
        ]);

        return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio berhasil diperbarui.');
    }

    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();
        return back()->with('success', 'Portfolio berhasil dihapus.');
    }
}
