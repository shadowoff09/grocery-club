<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function index(): View
    {
        // Cache the statistics for 1 hour to improve performance
        $stats = Cache::remember('landing_page_stats', 3600, function () {
            return [
                'customers' => User::where('type', 'member')->count(),
                'products' => Product::count(),
                'categories' => Category::count(),
            ];
        });

        // Get featured categories with their product counts
        $categories = Cache::remember('landing_page_categories', 3600, function () {
            return Category::withCount('products')
                ->orderBy('products_count', 'desc')
                ->take(4)
                ->get();
        });

        // Get featured products (newest with stock)
        $featuredProducts = Cache::remember('landing_page_featured_products', 3600, function () {
            return Product::with('category')
                ->where('stock', '>', 0)
                ->latest()
                ->take(4)
                ->get();
        });

        return view('welcome', compact('stats', 'categories', 'featuredProducts'));
    }
}
