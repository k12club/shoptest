<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Order;
use App\Product;
use App\Category;
use App\Inventory;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class HomeController extends Controller {

    // Dashboard - URL: /admin
    public function index()
    {
        // Stats
        $stats = [];

        // Stats - Orders - Last User
        $stats['orders']['last_order'] = Order::orderBy('created_at', 'desc')->first();

        // Stats - Orders - Processing
        $stats['orders']['processing'] = Order::where('status', 'processing')->count();

        // Stats - Orders - Processing - Total
        $stats['orders']['processing_total'] = Order::where('status', 'processing')->sum('total');

        // Stats - Orders - Month to Date
        $stats['orders']['month_to_date'] = Order::where('created_at', '>=', Carbon::now(config('custom.timezone'))->startOfMonth())->count();

        // Stats - Orders - Month to Date - Total
        $stats['orders']['month_to_date_total'] = Order::where('created_at', '>=', Carbon::now(config('custom.timezone'))->startOfMonth())->sum('total');

        // Stats - Orders - Lifetime - Count
        $stats['orders']['lifetime'] = Order::count();

        // Stats - Orders - Lifetime - Total
        $stats['orders']['lifetime_total'] = Order::sum('total');

        // Stats - Store - Categories
        $stats['store']['categories'] = Category::count();

        // Stats - Store - Products
        $stats['store']['products'] = Product::count();

        // Stats - Store - Inventory
        $stats['store']['inventory'] = Inventory::count();

        // Stats - Store - Out of Stock
        $stats['store']['out_of_stock'] = Inventory::outOfStock()->count();

        // Stats - Users - Last User
        $stats['users']['last_user'] = User::orderBy('created_at', 'desc')->first();

        // Stats - Users
        $stats['users']['total'] = User::count();

        // Stats - Users - Admins
        $stats['users']['admins'] = User::where('type', 'admin')->count();

        // Stats - Users - Customers
        $stats['users']['customers'] = User::where('type', 'user')->count();

        return view('admin.index', compact('stats'));
    }

}
