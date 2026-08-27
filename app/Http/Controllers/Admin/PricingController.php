<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $query = PricingPlan::orderBy('display_order');

        if ($category) {
            $query->where('category', $category);
        }

        $plans = $query->get();
        $categories = [
            'landing-page' => 'Landing Page',
            'company-profile' => 'Company Profile',
            'ecommerce' => 'E-Commerce',
            'sistem-informasi' => 'Sistem Informasi',
            'custom-app' => 'Custom Web App',
        ];

        return view('admin.pricing.index', compact('plans', 'categories', 'category'));
    }

    public function create()
    {
        $categories = [
            'landing-page' => 'Landing Page',
            'company-profile' => 'Company Profile',
            'ecommerce' => 'E-Commerce',
            'sistem-informasi' => 'Sistem Informasi',
            'custom-app' => 'Custom Web App',
        ];
        return view('admin.pricing.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:100',
            'original_price' => 'nullable|string|max:100',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'period' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:50',
            'popular' => 'nullable',
            'cta_text' => 'nullable|string|max:100',
            'cta_href' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable',
            'custom_features' => 'nullable|string',
        ]);

        $features = $request->features;
        if (is_string($features)) {
            $features = array_filter(array_map('trim', explode(',', $features)));
        } elseif (!is_array($features)) {
            $features = [];
        }

        if ($request->filled('custom_features')) {
            $custom = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $request->custom_features)));
            $features = array_merge($features, $custom);
        }

        $isActive = $request->has('is_active') ? $request->boolean('is_active') : true;
        $isPopular = $request->boolean('popular');

        PricingPlan::create([
            'category' => $request->category,
            'name' => $request->name,
            'price' => $request->price,
            'original_price' => $request->original_price,
            'discount_percent' => $request->filled('discount_percent') ? (int)$request->discount_percent : null,
            'period' => $request->period ?? 'proyek',
            'description' => $request->description,
            'badge' => $request->badge,
            'features' => array_values(array_unique($features)),
            'not_included' => [],
            'popular' => $isPopular,
            'cta_text' => $request->cta_text ?? 'Pilih Paket',
            'cta_href' => $request->cta_href ?? '/contact',
            'display_order' => (int)($request->display_order ?? 0),
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.pricing.index', ['category' => $request->category])->with('success', 'Paket Harga berhasil ditambahkan.');
    }

    public function edit(PricingPlan $pricing)
    {
        $categories = [
            'landing-page' => 'Landing Page',
            'company-profile' => 'Company Profile',
            'ecommerce' => 'E-Commerce',
            'sistem-informasi' => 'Sistem Informasi',
            'custom-app' => 'Custom Web App',
        ];
        return view('admin.pricing.edit', compact('pricing', 'categories'));
    }

    public function update(Request $request, PricingPlan $pricing)
    {
        $request->validate([
            'category' => 'required|string',
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:100',
            'original_price' => 'nullable|string|max:100',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'period' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:50',
            'popular' => 'nullable',
            'cta_text' => 'nullable|string|max:100',
            'cta_href' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable',
            'custom_features' => 'nullable|string',
        ]);

        $features = $request->features;
        if (is_string($features)) {
            $features = array_filter(array_map('trim', explode(',', $features)));
        } elseif (!is_array($features)) {
            $features = [];
        }

        if ($request->filled('custom_features')) {
            $custom = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $request->custom_features)));
            $features = array_merge($features, $custom);
        }

        $isActive = $request->has('is_active') ? $request->boolean('is_active') : true;
        $isPopular = $request->boolean('popular');

        $pricing->update([
            'category' => $request->category,
            'name' => $request->name,
            'price' => $request->price,
            'original_price' => $request->original_price,
            'discount_percent' => $request->filled('discount_percent') ? (int)$request->discount_percent : null,
            'period' => $request->period ?? 'proyek',
            'description' => $request->description,
            'badge' => $request->badge,
            'features' => array_values(array_unique($features)),
            'popular' => $isPopular,
            'cta_text' => $request->cta_text ?? 'Pilih Paket',
            'cta_href' => $request->cta_href ?? '/contact',
            'display_order' => (int)($request->display_order ?? 0),
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.pricing.index', ['category' => $pricing->category])->with('success', 'Paket Harga berhasil diperbarui.');
    }

    public function destroy(PricingPlan $pricing)
    {
        $cat = $pricing->category;
        $pricing->delete();
        return redirect()->route('admin.pricing.index', ['category' => $cat])->with('success', 'Paket Harga berhasil dihapus.');
    }
}
