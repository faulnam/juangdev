<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\PricingPlan;
use Illuminate\Http\Response;

class LlmsTxtController extends Controller
{
    /**
     * Serve llms.txt — concise overview for LLM crawlers.
     */
    public function index(): Response
    {
        $content = $this->generateLlmsTxt(false);

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Serve llms-full.txt — detailed version with all content.
     */
    public function full(): Response
    {
        $content = $this->generateLlmsTxt(true);

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Generate the llms.txt content.
     */
    private function generateLlmsTxt(bool $isFull): string
    {
        $baseUrl = 'https://juangdev.my.id';
        $lines = [];

        // === HEADER ===
        $lines[] = '# JuangDev';
        $lines[] = '';
        $lines[] = '> JuangDev adalah studio teknologi & web developer profesional yang membantu bisnis, startup, dan UMKM membangun website profesional, aplikasi web kustom, toko online (e-commerce), dan sistem informasi digital berkualitas tinggi dengan harga transparan.';
        $lines[] = '';
        $lines[] = '## Informasi Umum';
        $lines[] = '';
        $lines[] = '- **Website**: ' . $baseUrl;
        $lines[] = '- **Bahasa**: Bahasa Indonesia';
        $lines[] = '- **Lokasi**: Indonesia';
        $lines[] = '- **Kontak**: hello@juangdev.com';
        $lines[] = '- **Telepon/WA**: +6283852174877';
        $lines[] = '';

        // === HALAMAN UTAMA ===
        $lines[] = '## Halaman Utama';
        $lines[] = '';
        $lines[] = '- [Beranda](' . $baseUrl . '/)';
        $lines[] = '- [Layanan](' . $baseUrl . '/services)';
        $lines[] = '- [Portfolio](' . $baseUrl . '/portfolio)';
        $lines[] = '- [Blog](' . $baseUrl . '/blog)';
        $lines[] = '- [Kontak](' . $baseUrl . '/contact)';
        $lines[] = '';

        // === LAYANAN ===
        $lines[] = '## Layanan';
        $lines[] = '';

        try {
            $services = Service::where('is_active', true)->orderBy('display_order')->get();
            if ($services->isNotEmpty()) {
                foreach ($services as $service) {
                    $lines[] = '### ' . $service->name;
                    if ($service->tagline) {
                        $lines[] = '';
                        $lines[] = $service->tagline;
                    }
                    if ($isFull && $service->description) {
                        $lines[] = strip_tags($service->description);
                    }
                    $lines[] = '';
                }
            } else {
                $lines[] = '- Pembuatan Website Company Profile';
                $lines[] = '- Pembuatan Toko Online / E-Commerce';
                $lines[] = '- Aplikasi Web Kustom';
                $lines[] = '- Sistem Informasi Digital';
                $lines[] = '- Landing Page & Sales Funnel';
                $lines[] = '- Maintenance & Support Website';
                $lines[] = '';
            }
        } catch (\Exception $e) {
            $lines[] = '- Pembuatan Website Company Profile';
            $lines[] = '- Pembuatan Toko Online / E-Commerce';
            $lines[] = '- Aplikasi Web Kustom';
            $lines[] = '- Sistem Informasi Digital';
            $lines[] = '- Landing Page & Sales Funnel';
            $lines[] = '- Maintenance & Support Website';
            $lines[] = '';
        }

        // === PRICING ===
        $lines[] = '## Paket Harga';
        $lines[] = '';

        try {
            $plans = PricingPlan::where('is_active', true)->orderBy('display_order')->get();
            if ($plans->isNotEmpty()) {
                foreach ($plans as $plan) {
                    $price = $plan->price
                        ? 'Rp ' . number_format($plan->price, 0, ',', '.')
                        : 'Custom / Negosiasi';
                    $lines[] = '- **' . $plan->name . '**: ' . $price;
                    if ($isFull && $plan->description) {
                        $lines[] = '  ' . strip_tags($plan->description);
                    }
                }
                $lines[] = '';
            }
        } catch (\Exception $e) {
            // pricing table mungkin belum ada
        }

        // === PORTFOLIO ===
        $lines[] = '## Portfolio';
        $lines[] = '';

        try {
            $portfolios = Portfolio::orderBy('display_order')->orderBy('id', 'desc')->take(20)->get();
            if ($portfolios->isNotEmpty()) {
                foreach ($portfolios as $portfolio) {
                    $lines[] = '- [' . $portfolio->title . '](' . $baseUrl . '/portfolio/' . $portfolio->slug . ')';
                    if ($isFull && $portfolio->description) {
                        $desc = strip_tags($portfolio->description);
                        if (strlen($desc) > 200) {
                            $desc = substr($desc, 0, 200) . '...';
                        }
                        $lines[] = '  ' . $desc;
                    }
                }
                $lines[] = '';
            }
        } catch (\Exception $e) {
            // skip
        }

        // === BLOG ===
        $lines[] = '## Blog / Artikel';
        $lines[] = '';

        try {
            $blogs = Blog::where('is_published', true)
                ->orderBy('published_at', 'desc')
                ->take($isFull ? 50 : 20)
                ->get();

            if ($blogs->isNotEmpty()) {
                foreach ($blogs as $blog) {
                    $lines[] = '- [' . $blog->title . '](' . $baseUrl . '/blog/' . $blog->slug . ')';
                    if ($isFull && $blog->excerpt) {
                        $excerpt = strip_tags($blog->excerpt);
                        if (strlen($excerpt) > 200) {
                            $excerpt = substr($excerpt, 0, 200) . '...';
                        }
                        $lines[] = '  ' . $excerpt;
                    }
                }
                $lines[] = '';
            }
        } catch (\Exception $e) {
            // skip
        }

        // === FULL: BLOG CONTENT ===
        if ($isFull && isset($blogs) && $blogs->isNotEmpty()) {
            $lines[] = '---';
            $lines[] = '';
            $lines[] = '## Konten Blog Lengkap';
            $lines[] = '';

            foreach ($blogs as $blog) {
                $lines[] = '### ' . $blog->title;
                $lines[] = '';
                if ($blog->published_at) {
                    $lines[] = '*Dipublikasikan: ' . $blog->published_at->format('d M Y') . '*';
                    $lines[] = '';
                }
                $content = strip_tags($blog->content);
                // Limit each blog to 1000 chars in full mode
                if (strlen($content) > 1000) {
                    $content = substr($content, 0, 1000) . '...';
                }
                $lines[] = $content;
                $lines[] = '';
                $lines[] = 'Baca selengkapnya: ' . $baseUrl . '/blog/' . $blog->slug;
                $lines[] = '';
                $lines[] = '---';
                $lines[] = '';
            }
        }

        // === FOOTER ===
        $lines[] = '## Meta';
        $lines[] = '';
        $lines[] = '- **Sitemap XML**: ' . $baseUrl . '/sitemap.xml';
        $lines[] = '- **Robots.txt**: ' . $baseUrl . '/robots.txt';
        $lines[] = '- **LLMs.txt**: ' . $baseUrl . '/llms.txt';
        $lines[] = '- **LLMs Full**: ' . $baseUrl . '/llms-full.txt';
        $lines[] = '- **Terakhir diperbarui**: ' . now()->format('Y-m-d');
        $lines[] = '';

        return implode("\n", $lines);
    }
}
