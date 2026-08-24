{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach ($staticPages as $page)
    <url>
        <loc>{{ $page['url'] }}</loc>
        <lastmod>{{ $page['lastmod'] }}</lastmod>
        <changefreq>{{ $page['changefreq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
    </url>
    @endforeach

    @foreach ($blogs as $blog)
    <url>
        <loc>{{ route('blog.show', $blog->slug) }}</loc>
        <lastmod>{{ ($blog->published_at ?? $blog->updated_at)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        @if($blog->image_url)
        <image:image>
            <image:loc>{{ str_starts_with($blog->image_url, 'http') ? $blog->image_url : url($blog->image_url) }}</image:loc>
            <image:title>{{ $blog->title }}</image:title>
        </image:image>
        @endif
    </url>
    @endforeach

    @foreach ($portfolios as $portfolio)
    <url>
        <loc>{{ route('portfolio.show', $portfolio->slug) }}</loc>
        <lastmod>{{ $portfolio->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
        @if($portfolio->image_url)
        <image:image>
            <image:loc>{{ str_starts_with($portfolio->image_url, 'http') ? $portfolio->image_url : url($portfolio->image_url) }}</image:loc>
            <image:title>{{ $portfolio->title }}</image:title>
        </image:image>
        @endif
    </url>
    @endforeach
</urlset>
