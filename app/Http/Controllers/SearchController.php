<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class SearchController extends Controller {

    /**
     * Search
     * URL: /search?q=<keyword>
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->input('q');

        // Retrieved Products matched the given keyword
        $products = Product::where('name', 'like', '%' . $keyword . '%')
            ->orWhere('description', 'like', '%' . $keyword . '%')
            ->orderBy('created_at', 'desc')
            ->with(['photos' => function($query)  {
                $query->where('default', true);
            }])
            ->take(100)
            ->get();

        return view('shop.search', compact('products'));
    }

}
