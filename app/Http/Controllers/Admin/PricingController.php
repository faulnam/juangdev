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
            'period' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:50',
            'popular' => 'nullable|boolean',
            'cta_text' => 'nullable|string|max:100',
            'cta_href' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $features = $request->features;
        if (is_string($features)) {
            $features = array_filter(array_map('trim', explode(',', $features)));
        } elseif (!is_array($features)) {
            $features = [];
        }

        PricingPlan::create([
            'category' => $request->category,
            'name' => $request->name,
            'price' => $request->price,
            'period' => $request->period ?? 'proyek',
            'description' => $request->description,
            'badge' => $request->badge,
            'features' => array_values($features),
            'not_included' => [],
            'popular' => $request->has('popular'),
            'cta_text' => $request->cta_text ?? 'Pilih Paket',
            'cta_href' => $request->cta_href ?? '/contact',
            'display_order' => (int)($request->display_order ?? 0),
            'is_active' => $request->has('is_active'),
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
            'period' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:50',
            'popular' => 'nullable|boolean',
            'cta_text' => 'nullable|string|max:100',
            'cta_href' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $features = $request->features;
        if (is_string($features)) {
            $features = array_filter(array_map('trim', explode(',', $features)));
        } elseif (!is_array($features)) {
            $features = [];
        }

        $pricing->update([
            'category' => $request->category,
            'name' => $request->name,
            'price' => $request->price,
            'period' => $request->period ?? 'proyek',
            'description' => $request->description,
            'badge' => $request->badge,
            'features' => array_values($features),
            'popular' => $request->has('popular'),
            'cta_text' => $request->cta_text ?? 'Pilih Paket',
            'cta_href' => $request->cta_href ?? '/contact',
            'display_order' => (int)($request->display_order ?? 0),
            'is_active' => $request->has('is_active'),
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
