<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($urls as $url)
        <url>
            <loc>{{ $url }}</loc>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($projects as $slug)
        <url>
            <loc>{{ route('project.show', $slug) }}</loc>
            <changefreq>monthly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
    @foreach($posts as $slug)
        <url>
            <loc>{{ route('blog.post', $slug) }}</loc>
            <changefreq>monthly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
</urlset>
