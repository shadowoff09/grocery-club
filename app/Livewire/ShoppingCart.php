<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ShoppingCart extends Component
{
    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);
        $this->dispatch('cartUpdated');
    }

    public function updateQuantity($productId, $action)
    {
        $cart = session()->get('cart', []);

        if ($action === 'increase') {
            $cart[$productId]++;
        } else if ($action === 'decrease') {
            $cart[$productId]--;
            if ($cart[$productId] <= 0) {
                unset($cart[$productId]);
            }
        }

        session()->put('cart', $cart);
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        $cart = session()->get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get();

        $cartItems = $products->map(function ($product) use ($cart) {
            return [
                'product' => $product,
                'quantity' => $cart[$product->id],
                'total' => $product->price * $cart[$product->id]
            ];
        });

        $total = $cartItems->sum('total');

        return view('livewire.cart.shopping-cart', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }
}
