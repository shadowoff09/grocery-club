<?php

namespace App\Livewire;

use App\Traits\WithCartProcessing;
use Livewire\Component;

class ShoppingCart extends Component
{
    use WithCartProcessing;

    public function render()
    {
        $cartData = $this->getCartData(true);
        return view('livewire.cart.shopping-cart', $cartData);
    }
}
