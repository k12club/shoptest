<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($products as $product)
        <url>
            <loc>{{ route('shop.product', [$product['uri'], $product['id']]) }}</loc>
            <lastmod>{{ gmdate(DateTime::W3C, strtotime($product['updated_at'])) }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>