<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Static core pages --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/about') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/services') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/scholarships') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/blog') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/events') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/testimonials') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ url('/awards') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ url('/contact') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- Dynamic service pages --}}
    @foreach($services as $service)
    <url>
        <loc>{{ url('/services/' . $service->slug) }}</loc>
        <lastmod>{{ $service->updated_at->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

    {{-- Dynamic blog post pages --}}
    @foreach($posts as $post)
    <url>
        <loc>{{ url('/blog/' . $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- Dynamic scholarship pages --}}
    @foreach($scholarships as $scholarship)
    <url>
        <loc>{{ url('/scholarships/' . $scholarship->id) }}</loc>
        <lastmod>{{ $scholarship->updated_at->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

</urlset>
