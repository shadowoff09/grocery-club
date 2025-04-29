<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Livewire\WithPagination;

class CatalogController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::where('deleted_at', null);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('catalog.index', compact('products'));
    }
}
