<?php namespace App\Http\Controllers;

use App\Category;
use App\Product;

class SitemapController extends Controller {

    public function index()
    {
        $categories = Category::select([
                'id',
                'name',
                'uri',
                'updated_at'
            ])
            ->orderBy('name', 'asc')
            ->take(50000) // each Sitemap file must have no more than 50,000 URLs and must be no larger than 10MB
            ->get();

        $content = view('sitemap.index', [
            'categories' => $categories
        ]);

        return response($content)->header('Content-Type', 'text/xml;charset=utf-8');
    }

    public function pages() {
        $content = view('sitemap.pages');

        return response($content)->header('Content-Type', 'text/xml;charset=utf-8');
    }

    public function category($uri) {
        $category = Category::where('uri', $uri)->first();

        $products = Product::select([
                'id',
                'name',
                'uri',
                'updated_at'
            ])
            ->where('category_id', $category->id)
            ->orderBy('order', 'asc')
            ->take(50000) // each Sitemap file must have no more than 50,000 URLs and must be no larger than 10MB
            ->get();

        $content = view('sitemap.category', [
            'products' => $products
        ]);

        return response($content)->header('Content-Type', 'text/xml;charset=utf-8');
    }

}
