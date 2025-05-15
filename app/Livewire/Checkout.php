<?php

namespace App\Livewire;

use App\Traits\WithCartProcessing;
use App\Traits\WithCardOperations;
use App\Traits\WithOrderCreation;
use App\Traits\WithPaymentValidation;
use Livewire\Component;

class Checkout extends Component
{
    use WithCartProcessing;
    use WithCardOperations;
    use WithOrderCreation;
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
            $this->dispatch('checkout-error', 'Insufficient card balance');
            return;
        }

        $cartData = $this->getCartData(true);
        $amount = $cartData['totalWithShipping'];
        
        // Process payment using the specialized debit function
        // Only use standard columns, no custom data
        $transactionSuccess = $this->debitCardForOrder(
            $amount,
            null // Order ID will be set after order creation
        );
        
        if (!$transactionSuccess) {
            $this->dispatch('checkout-error', 'Transaction failed. Please try again or contact support.');
            return;
        }

        // Get the latest operation (the one we just created)
        $latestOperation = auth()->user()->card->operations()
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Create the order and its items
        $order = $this->createOrder($cartData, $latestOperation->id ?? null);
        
        if (!$order) {
            $this->dispatch('checkout-error', 'Order creation failed. Your card has been debited, please contact support.');
            return;
        }

        // Clear the cart
        $this->clearCart();
        
        $this->dispatch('checkout-success', 'Payment processed successfully!');
        
        // Redirect to order confirmation
        return redirect()->route('order.confirmation', ['order_id' => $order->id]);
    }

    private function validatePayment()
    {
        $cartData = $this->getCartData(true);
        $cardBalance = $this->getCardBalance();
        
        // Check if card balance is sufficient
        return $cardBalance >= $cartData['totalWithShipping'];
    }
} 