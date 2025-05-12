<?php

namespace App\Livewire\Cart;

use App\Models\Product;
use App\Models\SettingsShippingCost;
use App\Traits\WithCartProcessing;
use Livewire\Component;

class Checkout extends Component
{
    use WithCartProcessing;

    public function render()
    {
        $cartData = $this->getCartData();
        
        return view('livewire.cart.checkout', $cartData);
    }
} 