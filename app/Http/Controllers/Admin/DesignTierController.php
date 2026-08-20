<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignTier;
use Illuminate\Http\Request;

class DesignTierController extends Controller
{
    public function index()
    {
        $tiers = DesignTier::orderBy('display_order')->get();
        return view('admin.design-tiers.index', compact('tiers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'badge' => 'nullable|string|max:50',
            'features' => 'nullable|string',
            'is_popular' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $featuresArray = array_filter(array_map('trim', explode("\n", $request->features ?? '')));

        DesignTier::create([
            'name' => $request->name,
            'tagline' => $request->tagline,
            'description' => $request->description,
            'price' => (int)$request->price,
            'badge' => $request->badge,
            'features' => array_values($featuresArray),
            'is_popular' => $request->has('is_popular'),
            'display_order' => (int)($request->display_order ?? 0),
        ]);

        return back()->with('success', 'Design Tier berhasil ditambahkan.');
    }

    public function update(Request $request, DesignTier $tier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'badge' => 'nullable|string|max:50',
            'features' => 'nullable|string',
            'is_popular' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $featuresArray = array_filter(array_map('trim', explode("\n", $request->features ?? '')));

        $tier->update([
            'name' => $request->name,
            'tagline' => $request->tagline,
            'description' => $request->description,
            'price' => (int)$request->price,
            'badge' => $request->badge,
            'features' => array_values($featuresArray),
            'is_popular' => $request->has('is_popular'),
            'display_order' => (int)($request->display_order ?? 0),
        ]);

        return back()->with('success', 'Design Tier berhasil diperbarui.');
    }

    public function destroy(DesignTier $tier)
    {
        $tier->delete();
        return back()->with('success', 'Design Tier berhasil dihapus.');
    }
}
