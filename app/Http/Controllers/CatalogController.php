<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class CatalogController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;
        $categoryId = $request->category;
        $page = $request->get('page', 1);
        $sort = $request->get('sort', 'name');

        // Gera uma chave de cache única para cada combinação de pesquisa, categoria e página
        $cacheKey = "products_index:search={$search}:category={$categoryId}:page={$page}:sort={$sort}";

        $products = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search, $categoryId, $sort) {
            $query = Product::whereNull('deleted_at');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            }

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            // Apply sorting based on the sort parameter
            switch ($sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->orderBy('name');
                    break;
            }

            return $query->paginate(12)
                ->withQueryString();
        });

        $categories = Category::query()->get();
        $activeCategory = $categoryId ? Category::find($categoryId) : null;

        return view('catalog.index', [
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'activeCategory' => $activeCategory,
            'sort' => $sort,
        ]);
    }
}
