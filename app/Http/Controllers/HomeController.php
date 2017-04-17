<?php

namespace App\Http\Controllers;

use App\Product;

class HomeController extends Controller {

    /**
     * Homepage
     * URL: /
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Retrieve the last 10 Products
        $products = Product::orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Retrieve the last 5 special Products
        $specialProducts = Product::where('special', true)
            ->take(5)
            ->get();

        return view('home.index', compact('products', 'specialProducts'));
    }

}
