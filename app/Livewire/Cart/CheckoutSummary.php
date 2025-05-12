<?php

namespace App\Livewire\Cart;

use App\Traits\WithCartProcessing;
use Livewire\Component;

class CheckoutSummary extends Component
{
    use WithCartProcessing;

    public function render()
    {
        // We need the full cart data with totals and shipping
        $cartData = $this->getCartData(true);
        
        return view('livewire.cart.checkout-summary', $cartData);
    }
} 