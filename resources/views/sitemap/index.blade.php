<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ route('sitemap.pages') }}</loc>
    </sitemap>
    @foreach($categories as $category)
        <sitemap>
            <loc>{{ route('sitemap.category', ['uri' => $category->uri]) }}</loc>
        </sitemap>
    @endforeach
</sitemapindex>