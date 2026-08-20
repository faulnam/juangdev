<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'avatar_url' => 'nullable|string',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $avatarUrl = $request->avatar_url;
        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $avatarUrl = '/uploads/' . $filename;
        }

        Testimonial::create([
            'name' => $request->name,
            'role' => $request->role,
            'company' => $request->company,
            'avatar_url' => $avatarUrl,
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
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'avatar_url' => 'nullable|string',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $avatarUrl = $testimonial->avatar_url;
        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $avatarUrl = '/uploads/' . $filename;
        } elseif ($request->filled('avatar_url')) {
            $avatarUrl = $request->avatar_url;
        }

        $testimonial->update([
            'name' => $request->name,
            'role' => $request->role,
            'company' => $request->company,
            'avatar_url' => $avatarUrl,
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
