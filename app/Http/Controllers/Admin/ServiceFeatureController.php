<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceFeature;
use Illuminate\Http\Request;

class ServiceFeatureController extends Controller
{
    public function index()
    {
        $features = ServiceFeature::orderBy('display_order')->get();
        $services = Service::orderBy('display_order')->get();
        return view('admin.service-features.index', compact('features', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'popular' => 'nullable',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $isActive = $request->has('is_active') ? $request->boolean('is_active') : true;
        $isPopular = $request->boolean('popular');

        ServiceFeature::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => 'addon',
            'price' => (int)$request->price,
            'popular' => $isPopular,
            'display_order' => (int)($request->display_order ?? 0),
            'is_active' => $isActive,
        ]);

        return back()->with('success', 'Fitur Add-on berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $feature = ServiceFeature::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'popular' => 'nullable',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $isActive = $request->has('is_active') ? $request->boolean('is_active') : true;
        $isPopular = $request->boolean('popular');

        $feature->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => (int)$request->price,
            'popular' => $isPopular,
            'display_order' => (int)($request->display_order ?? 0),
            'is_active' => $isActive,
        ]);

        return back()->with('success', 'Fitur Add-on berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $feature = ServiceFeature::findOrFail($id);
        $feature->delete();
        return back()->with('success', 'Fitur Add-on berhasil dihapus.');
    }
}
