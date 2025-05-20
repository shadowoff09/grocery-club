<?php

namespace App\Http\Controllers;

use App\Models\Order;

class CheckoutController extends Controller
{
    public function index()
    {
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
