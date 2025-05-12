<?php

namespace App\Livewire;

use App\Traits\WithCardOperations;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Balance extends Component
{
    use WithPagination;
    use WithCardOperations;

    public bool $showRechargeModal = false;
    public float $rechargeAmount = 50;
    public string $paymentMethod = 'Visa';
    public ?string $paymentReference = '';
    public ?string $cvcCode = '';
    public bool $hasDefaults = false;
    public bool $showDefaultsAlert = false;
    public ?string $defaultPaymentMethod = null;
    public ?string $defaultPaymentReference = null;
    public bool $saveAsDefault = false;
    public bool $showSaveOption = true;

    public function mount(): void
    {
        // Get default payment method from trait
        $defaultPayment = $this->getDefaultPaymentMethod();
        
        if ($defaultPayment) {
            $this->hasDefaults = true;
            $this->defaultPaymentMethod = $defaultPayment['method'];
            $this->defaultPaymentReference = $defaultPayment['reference'];
        }
    }

    public function showRechargeForm(): void
    {
        $this->showRechargeModal = true;
        $this->showDefaultsAlert = $this->hasDefaults;
        $this->showSaveOption = true;
    }

    public function useDefaults(): void
    {
        if ($this->defaultPaymentMethod && $this->defaultPaymentReference) {
            $this->paymentMethod = $this->defaultPaymentMethod;
            $this->paymentReference = $this->defaultPaymentReference;
            $this->showDefaultsAlert = false; // Hide the alert after using defaults
            $this->showSaveOption = false; // Hide save option when using defaults
        }
    }

    public function cancelRecharge(): void
    {
        $this->showRechargeModal = false;
        $this->reset('rechargeAmount', 'paymentMethod', 'paymentReference', 'cvcCode', 'showDefaultsAlert', 'saveAsDefault', 'showSaveOption');
    }

    public function rechargeCard(): void
    {
        // Use trait's processCardTopUp method
        $result = $this->processCardTopUp(
            $this->rechargeAmount,
            $this->paymentMethod,
            $this->paymentReference,
            $this->cvcCode
        );
        
        if (!$result['success']) {
            Toaster::error($result['message']);
            return;
        }
        
        // Save as default if requested
        if ($this->saveAsDefault) {
            $saved = $this->saveDefaultPaymentMethod($this->paymentMethod, $this->paymentReference);
            
            if ($saved) {
                // Update session values
                $this->hasDefaults = true;
                $this->defaultPaymentMethod = $this->paymentMethod;
                $this->defaultPaymentReference = $this->paymentReference;
                
                Toaster::success('Payment preferences saved as default.');
            }
        }
        
        // Close modal and reset form
        $this->showRechargeModal = false;
        $this->reset('rechargeAmount', 'paymentMethod', 'paymentReference', 'cvcCode', 'saveAsDefault', 'showSaveOption');
        
        Toaster::success($result['message']);
    }

    public function render()
    {
        $cardBalance = $this->getCardBalance();
        $cardNumber = $this->getCardNumber();
        $operations = $this->getCardOperations(10);
        $statistics = $this->getCardStatistics();

        return view('livewire.balance', [
            'cardBalance' => $cardBalance,
            'cardNumber' => $cardNumber,
            'operations' => $operations,
            'statistics' => $statistics
        ]);
    }

    private function getPlaceholderForPaymentType(): string
    {
        return match ($this->paymentMethod) {
            'Visa' => 'Enter your Visa card (cannot start with 0 or end with 2)',
            'PayPal' => 'Enter your PayPal email address (must end with .pt or .com)',
            'MB WAY' => 'Enter your Portuguese mobile number (cannot end with 2)',
            default => 'e.g., Visa number, PayPal email, or MB WAY number'
        };
    }

    public function updated($field): void
    {
        // Check if payment info is different from saved defaults
        if (($field === 'paymentMethod' || $field === 'paymentReference') &&
            $this->hasDefaults) {
            
            // If payment method changed or reference changed, show save option
            if ($this->paymentMethod !== $this->defaultPaymentMethod || 
                $this->paymentReference !== $this->defaultPaymentReference) {
                $this->showSaveOption = true;
            } else {
                // Using same payment as defaults, no need to show save option
                $this->showSaveOption = false;
            }
        }
    }
} 