<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'image_url' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'author' => 'nullable|string|max:100',
            'read_time' => 'nullable|string|max:50',
            'is_published' => 'nullable|boolean',
        ]);

        $imageUrl = $request->image_url ?? $request->cover_image;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $imageUrl = '/uploads/' . $filename;
        }

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Blog::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        Blog::create([
            'slug' => $slug,
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'image_url' => $imageUrl,
            'category' => $request->category ?? 'Technology',
            'author' => $request->author ?? 'JuangDev Team',
            'read_time' => $request->read_time ?? '5 min read',
            'published_at' => $request->has('is_published') ? now() : null,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Artikel blog berhasil ditambahkan.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'image_url' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'author' => 'nullable|string|max:100',
            'read_time' => 'nullable|string|max:50',
            'is_published' => 'nullable|boolean',
        ]);

        $imageUrl = $blog->image_url;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $imageUrl = '/uploads/' . $filename;
        } elseif ($request->filled('image_url') || $request->filled('cover_image')) {
            $imageUrl = $request->image_url ?? $request->cover_image;
        }

        $blog->update([
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'image_url' => $imageUrl,
            'category' => $request->category ?? 'Technology',
            'author' => $request->author ?? 'JuangDev Team',
            'read_time' => $request->read_time ?? '5 min read',
            'published_at' => $request->has('is_published') ? ($blog->published_at ?? now()) : null,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Artikel blog berhasil diperbarui.');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return back()->with('success', 'Artikel blog berhasil dihapus.');
    }
}
