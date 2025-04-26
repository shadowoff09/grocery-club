<?php
namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class AddToCart extends Component
{
    public $productId;
    public $quantity = 1; // Valor inicial
    public $warningMessage;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function increment()
    {
        $this->quantity++;
        $this->checkStockStatus();
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->checkStockStatus();
        }
    }

    public function checkStockStatus()
    {
        $product = Product::find($this->productId);

        if ($product && $this->quantity > $product->stock) {
            $message = "The stock is only {$product->stock}. More than {$product->stock} will result in a slight delay.";
            $this->dispatch('displayMessage', $this->productId, $message, 'warning');
        } else {
            $this->dispatch('displayMessage', $this->productId, '', 'warning');
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
        $this->warningMessage = null;

        session()->flash('success', 'Product added to cart.');
    }

    public function render()
    {
        return view('livewire.cart.add-to-cart');
    }
}
