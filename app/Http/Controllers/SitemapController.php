<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Portfolio;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML Sitemap for JuangDev.
     */
    public function index(): Response
    {
        $baseUrl = config('app.url', url('/'));

        $staticPages = [
            [
                'url' => route('home'),
                'lastmod' => now()->startOfWeek()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'url' => route('services'),
                'lastmod' => now()->startOfWeek()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ],
            [
                'url' => route('portfolio'),
                'lastmod' => now()->startOfWeek()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ],
            [
                'url' => route('blog'),
                'lastmod' => now()->startOfWeek()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            [
                'url' => route('contact'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
        ];

        // Published Blogs
        $blogs = Blog::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->get();

        // Portfolios
        $portfolios = Portfolio::orderBy('display_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $content = view('sitemap', compact('staticPages', 'blogs', 'portfolios'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
