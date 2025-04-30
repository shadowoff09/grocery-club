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
        $page = $request->get('page', 1);

        // Gera uma chave de cache única para cada combinação de pesquisa e página
        $cacheKey = "products_index:search={$search}:page={$page}";

        $products = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search) {
            $query = Product::whereNull('deleted_at');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            }

            return $query->orderBy('name')
                ->paginate(12)
                ->withQueryString();
        });

        $categories = Category::query()->get();

        return view('catalog.index', compact('products'));
    }
}
