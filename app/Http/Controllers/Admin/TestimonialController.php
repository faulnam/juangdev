<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageCompressor;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('display_order')->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        Testimonial::create([
            'name' => $request->name,
            'role' => $request->role,
            'company' => $request->company,
            'content' => $request->content,
            'rating' => (int)$request->rating,
            'featured' => $request->has('featured'),
            'display_order' => (int)($request->display_order ?? 0),
        ]);

        return back()->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $testimonial->update([
            'name' => $request->name,
            'role' => $request->role,
            'company' => $request->company,
            'content' => $request->content,
            'rating' => (int)$request->rating,
            'featured' => $request->has('featured'),
            'display_order' => (int)($request->display_order ?? 0),
        ]);

        return back()->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}
