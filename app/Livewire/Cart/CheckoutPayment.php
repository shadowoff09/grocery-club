<?php

namespace App\Livewire\Cart;

use App\Traits\WithCartProcessing;
use App\Traits\WithCardOperations;
use Livewire\Component;

class CheckoutPayment extends Component
{
    use WithCartProcessing;
    use WithCardOperations;

    public function render()
    {
        // We only need cart items, not totals and shipping calculations
        $cartData = $this->getCartData(false);

        $cardBalance = $this->getCardBalance();
        
        return view('livewire.cart.checkout-payment', $cartData, [
            'cardBalance' => $cardBalance,
        ]);
    }
} 