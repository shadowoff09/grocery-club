<?php

namespace App\Livewire;

use App\Traits\WithCartProcessing;
use App\Traits\WithCardOperations;
use App\Traits\WithPaymentValidation;
use Livewire\Component;

class Checkout extends Component
{
    use WithCartProcessing;
    use WithCardOperations;
    use WithPaymentValidation;

    public function render()
    {
        $cartData = $this->getCartData(true);
        $cardBalance = $this->getCardBalance();
        
        return view('livewire.checkout.index', array_merge($cartData, [
            'cardBalance' => $cardBalance,
        ]));
    }

    public function processPayment()
    {
        // Validate payment
        if (!$this->validatePayment()) {
            $this->dispatch('checkout-error', 'Payment validation failed');
            return;
        }

        // Process payment logic here
        // Should use card balance to pay for the order
        $this->processCardPayment();

        $this->dispatch('checkout-success', 'Payment processed successfully!');
        
        // Clear the cart after successful payment
        // Redirect to order confirmation
        return redirect()->route('order.confirmation');
    }

    private function validatePayment()
    {
        $cartData = $this->getCartData(true);
        $cardBalance = $this->getCardBalance();
        
        // Check if card balance is sufficient
        return $cardBalance >= $cartData['totalWithShipping'];
    }
} 