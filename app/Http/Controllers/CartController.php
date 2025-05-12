<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }
    
    public function checkout()
    {
        // This will be implemented fully when you develop the checkout functionality
        // For now it just displays the checkout page
        return view('cart.checkout');
    }
}
