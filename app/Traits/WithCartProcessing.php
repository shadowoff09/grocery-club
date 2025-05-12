<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\SettingsShippingCost;
use Illuminate\Support\Collection;

trait WithCartProcessing
{
    /**
     * Get the cart items with calculated prices and discounts
     * 
     * @param bool $calculateTotals Whether to calculate order totals
     * @return array Cart data including items and optional totals
     */
    public function getCartData($calculateTotals = true)
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

        // Ensure cartItems remains a collection
        if (!($cartItems instanceof Collection)) {
            $cartItems = collect($cartItems);
        }

        $result = ['cartItems' => $cartItems];

        if ($calculateTotals) {
            $total = $cartItems->sum('total');
            $totalDiscount = $cartItems->sum('discountAmount');

            $subTotalSoShippingIsFree = SettingsShippingCost::where('shipping_cost', 0)
                ->value('min_value_threshold');

            $shippingCost = SettingsShippingCost::where('min_value_threshold', '<=', $total)
                ->where('max_value_threshold', '>', $total)
                ->value('shipping_cost');

            $totalWithShipping = $total + $shippingCost;

            $result = array_merge($result, [
                'total' => $total,
                'totalDiscount' => $totalDiscount,
                'shippingCost' => $shippingCost,
                'totalWithShipping' => $totalWithShipping,
                'minThresholdSoShippingIsFree' => $subTotalSoShippingIsFree,
            ]);
        }

        return $result;
    }
}