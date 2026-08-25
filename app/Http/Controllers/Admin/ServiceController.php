<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('display_order')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'base_price' => 'required|numeric',
            'starting_price' => 'nullable|string|max:100',
            'delivery_time' => 'nullable|string|max:100',
            'popular' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Service::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $features = $request->features;
        if (is_string($features)) {
            $features = array_filter(array_map('trim', explode(',', $features)));
        } elseif (!is_array($features)) {
            $features = [];
        }

        Service::create([
            'slug' => $slug,
            'name' => $request->name,
            'tagline' => $request->tagline,
            'description' => $request->description,
            'icon' => $request->icon ?? 'globe',
            'base_price' => (int)$request->base_price,
            'starting_price' => $request->starting_price ?? ($request->base_price / 1000) . 'K',
            'delivery_time' => $request->delivery_time,
            'popular' => $request->boolean('popular'),
            'features' => array_values($features),
            'technologies' => ['HTML5', 'Tailwind CSS', 'Alpine.js', 'Laravel'],
            'display_order' => (int)($request->display_order ?? 0),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'base_price' => 'required|numeric',
            'starting_price' => 'nullable|string|max:100',
            'delivery_time' => 'nullable|string|max:100',
            'popular' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $features = $request->features;
        if (is_string($features)) {
            $features = array_filter(array_map('trim', explode(',', $features)));
        } elseif (!is_array($features)) {
            $features = [];
        }

        $service->update([
            'name' => $request->name,
            'tagline' => $request->tagline,
            'description' => $request->description,
            'icon' => $request->icon ?? 'globe',
            'base_price' => (int)$request->base_price,
            'starting_price' => $request->starting_price ?? ($request->base_price / 1000) . 'K',
            'delivery_time' => $request->delivery_time,
            'popular' => $request->boolean('popular'),
            'features' => array_values($features),
            'display_order' => (int)($request->display_order ?? 0),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Layanan berhasil dihapus.');
    }
}
