<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\WithCartProcessing;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{

    use WithCartProcessing;
    
    public function index()
    {
        if ($this->cartIsEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to checkout.');
        }

        return view('checkout.index');
    }

    public function confirmation($order_id = null)
    {
        $order = null;
        if ($order_id) {
            $order = Order::where('id', $order_id)
                ->where('member_id', auth()->id())
                ->first();
        }

        return view('checkout.confirmation', ['order' => $order]);
    }
}
