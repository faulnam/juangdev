<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class BlogPageController extends Controller
{
    public function index(Request $request)
    {
        // Only show published articles where published_at is in the past or now
        $query = Blog::where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $totalCount = (clone $query)->count();
        $blogs = $query->orderBy('published_at', 'desc')->orderBy('created_at', 'desc')->paginate(6)->withQueryString();
        
        $allCategories = Blog::where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->pluck('category')
            ->unique()
            ->filter()
            ->values();

        $settings = SiteSetting::pluck('value', 'key')->toArray();

        // Featured blog (only on page 1 without search or category filter)
        $featuredBlog = null;
        if ($blogs->currentPage() == 1 && !$request->filled('q') && !$request->filled('category')) {
            $featuredBlog = $blogs->first();
        }

        return view('pages.blog', compact('blogs', 'featuredBlog', 'totalCount', 'allCategories', 'settings'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->firstOrFail();

        // Increment view count on each visit
        $blog->increment('views');

        // Top articles ordered by views
        $relatedBlogs = Blog::where('id', '!=', $blog->id)
            ->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->orderBy('views', 'desc')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $settings = SiteSetting::pluck('value', 'key')->toArray();

        return view('pages.blog-detail', compact('blog', 'relatedBlogs', 'settings'));
    }
}
