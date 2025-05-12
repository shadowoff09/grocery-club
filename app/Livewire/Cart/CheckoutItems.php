<?php

namespace App\Livewire\Cart;

use App\Traits\WithCartProcessing;
use Livewire\Component;

class CheckoutItems extends Component
{
    use WithCartProcessing;

    public function render()
    {
        // We only need cart items, not the shipping calculations
        $cartData = $this->getCartData(false);
        
        return view('livewire.cart.checkout-items', $cartData);
    }
} 