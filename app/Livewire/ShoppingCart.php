<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\SettingsShippingCost;
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

    public function clearCart()
    {
        session()->forget('cart');
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
            $quantity = $cart[$product->id];
            $unitPrice = $product->price;
            $discount = 0;
            $discountAmount = 0;
            
            // Check if the product has a discount and if the quantity meets the minimum requirement
            if ($product->discount > 0 && $product->discount_min_qty > 0 && $quantity >= $product->discount_min_qty) {
                $discount = $product->discount;
                $discountAmount = ($unitPrice * $quantity) * ($discount / 100);
            }
            
            $total = ($unitPrice * $quantity) - $discountAmount;
            
            return [
                'product' => $product,
                'quantity' => $quantity,
                'unitPrice' => $unitPrice,
                'discount' => $discount,
                'discountAmount' => $discountAmount,
                'total' => $total,
                'originalTotal' => $unitPrice * $quantity,
                'showDiscount' => $discount > 0
            ];
        });

        $total = $cartItems->sum('total');
        $totalDiscount = $cartItems->sum('discountAmount');

        $subTotalSoShippingIsFree = SettingsShippingCost::where('shipping_cost', 0)
            ->value('min_value_threshold');

        $shippingCost = SettingsShippingCost::where('min_value_threshold', '<=', $total)
            ->where('max_value_threshold', '>', $total)
            ->value('shipping_cost');

        $totalWithShipping = $total + $shippingCost;

        return view('livewire.cart.shopping-cart', [
            'cartItems' => $cartItems,
            'total' => $total,
            'totalDiscount' => $totalDiscount,
            'shippingCost' => $shippingCost,
            'totalWithShipping' => $totalWithShipping,
            'minThresholdSoShippingIsFree' => $subTotalSoShippingIsFree,
        ]);
    }
}
