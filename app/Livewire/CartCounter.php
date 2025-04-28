<?php

namespace App\Livewire;

use Livewire\Component;

class CartCounter extends Component
{
    public $cartCount = 0;

    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function mount()
    {
        $this->updateCartCount();
    }

    public function updateCartCount()
    {
        // You'll need to implement your cart counting logic here
        // This is just an example - adjust according to your cart implementation
        $this->cartCount = session('cart', []) ? count(session('cart')) : 0;
    }

    public function render()
    {
        return view('livewire.cart.cart-counter');
    }
}
