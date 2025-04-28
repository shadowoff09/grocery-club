<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $products = Product::where('deleted_at', Null)
            ->orderBy('name')
            ->paginate(12);

        return view('catalog.index', compact('products'));

    }
}
