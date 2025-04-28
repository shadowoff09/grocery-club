<?php
 //Isto é onde é gerida a adição dos produtos ao carrinho
namespace App\Livewire;

use Livewire\Component;

class AddToCart extends Component
{
    public $productId;
    public $quantity = 1; // Valor inicial

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function increment()
    {
        $this->quantity++;
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$this->productId])) {
            $cart[$this->productId] += $this->quantity;
        } else {
            $cart[$this->productId] = $this->quantity;
        }

        session()->put('cart', $cart);
        $this->dispatch('cartUpdated');

        // Reset quantity after adding to cart
        $this->quantity = 1;
    }

    public function render()
    {
        return view('livewire.cart.add-to-cart');
    }
}

