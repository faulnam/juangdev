<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            'slug' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'image_url' => 'nullable|string',
            'alt_image' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'author' => 'nullable|string|max:100',
            'read_time' => 'nullable|string|max:50',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'status' => 'nullable|string|in:published,draft,scheduled',
            'published_at' => 'nullable',
        ]);

        $imageUrl = $request->image_url;
        if ($request->hasFile('image_file')) {
            $imageUrl = \App\Helpers\ImageCompressor::uploadAndCompress($request->file('image_file'));
        }

        $rawSlug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title);
        $slug = $rawSlug;
        $count = 1;
        while (Blog::where('slug', $slug)->exists()) {
            $slug = "{$rawSlug}-{$count}";
            $count++;
        }

        $status = $request->status ?? ($request->is_published == '1' ? 'published' : 'draft');
        
        $isPublished = true;
        $publishedAt = now();

        if ($status === 'draft') {
            $isPublished = false;
            $publishedAt = $request->filled('published_at') ? Carbon::parse($request->published_at) : null;
        } elseif ($status === 'scheduled') {
            $isPublished = true;
            $publishedAt = $request->filled('published_at') ? Carbon::parse($request->published_at) : now()->addDay();
        } else {
            $isPublished = true;
            $publishedAt = $request->filled('published_at') ? Carbon::parse($request->published_at) : now();
        }

        Blog::create([
            'slug' => $slug,
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'image_url' => $imageUrl,
            'alt_image' => $request->alt_image,
            'category' => $request->category ?? 'Technology',
            'author' => $request->author ?? 'JuangDev Team',
            'read_time' => $request->read_time ?? '5 min read',
            'meta_title' => $request->meta_title ?? Str::limit($request->title, 60),
            'meta_description' => $request->meta_description ?? Str::limit(strip_tags($request->excerpt ?? $request->content), 160),
            'published_at' => $publishedAt,
            'is_published' => $isPublished,
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
            'slug' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'image_url' => 'nullable|string',
            'alt_image' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'author' => 'nullable|string|max:100',
            'read_time' => 'nullable|string|max:50',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'status' => 'nullable|string|in:published,draft,scheduled',
            'published_at' => 'nullable',
        ]);

        $imageUrl = $blog->image_url;
        if ($request->hasFile('image_file')) {
            $imageUrl = \App\Helpers\ImageCompressor::uploadAndCompress($request->file('image_file'));
        } elseif ($request->filled('image_url')) {
            $imageUrl = $request->image_url;
        }

        $rawSlug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title);
        $slug = $rawSlug;
        if ($slug !== $blog->slug) {
            $count = 1;
            while (Blog::where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
                $slug = "{$rawSlug}-{$count}";
                $count++;
            }
        }

        $status = $request->status ?? ($request->is_published == '1' ? 'published' : 'draft');
        
        $isPublished = true;
        $publishedAt = $blog->published_at ?? now();

        if ($status === 'draft') {
            $isPublished = false;
            $publishedAt = $request->filled('published_at') ? Carbon::parse($request->published_at) : null;
        } elseif ($status === 'scheduled') {
            $isPublished = true;
            $publishedAt = $request->filled('published_at') ? Carbon::parse($request->published_at) : ($blog->published_at && $blog->published_at->isFuture() ? $blog->published_at : now()->addDay());
        } else {
            $isPublished = true;
            $publishedAt = $request->filled('published_at') ? Carbon::parse($request->published_at) : ($blog->published_at ?? now());
        }

        $blog->update([
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'image_url' => $imageUrl,
            'alt_image' => $request->alt_image,
            'category' => $request->category ?? 'Technology',
            'author' => $request->author ?? 'JuangDev Team',
            'read_time' => $request->read_time ?? '5 min read',
            'meta_title' => $request->meta_title ?? Str::limit($request->title, 60),
            'meta_description' => $request->meta_description ?? Str::limit(strip_tags($request->excerpt ?? $request->content), 160),
            'published_at' => $publishedAt,
            'is_published' => $isPublished,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Artikel blog berhasil diperbarui.');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return back()->with('success', 'Artikel blog berhasil dihapus.');
    }
}
