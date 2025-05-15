<?php

namespace App\Livewire;

use App\DTOs\PaymentDetails;
use App\Services\BalanceService;
use App\Traits\WithPaymentValidation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Balance extends Component
{
    use WithPagination;
    use WithPaymentValidation;

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

    protected BalanceService $balanceService;

    public function __construct()
    {
        $this->balanceService = app(BalanceService::class);
    }

    public function mount(): void
    {
        // Check if user has defaults
        $user = Auth::user();
        if ($user && !empty($user->default_payment_type) && !empty($user->default_payment_reference)) {
            $this->hasDefaults = true;
            $this->defaultPaymentMethod = $user->default_payment_type;
            $this->defaultPaymentReference = $user->default_payment_reference;
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
        // Validate the input using the WithPaymentValidation trait
        $this->validate($this->getPaymentValidationRules($this->paymentMethod));

        // Create a PaymentDetails DTO using the trait helper method
        $paymentDetails = $this->createPaymentDetails('paymentMethod', 'paymentReference', 'cvcCode');

        // Process the recharge using the BalanceService
        $result = $this->balanceService->rechargeCard(
            Auth::user(),
            $this->rechargeAmount,
            $paymentDetails,
            $this->saveAsDefault
        );
        
        if (!$result) {
            Toaster::error('Payment failed. Please try again.');
            return;
        }
        
        // Close modal and reset form
        $this->showRechargeModal = false;
        $this->reset('rechargeAmount', 'paymentMethod', 'paymentReference', 'cvcCode', 'saveAsDefault', 'showSaveOption');
        
        Toaster::success('Card recharged successfully!');
    }

    public function render()
    {
        $user = Auth::user();
        $card = $this->balanceService->getUserCard($user);
        
        if (!$card) {
            return view('livewire.balance', [
                'cardBalance' => 0,
                'cardNumber' => null,
                'operations' => null,
                'statistics' => null
            ]);
        }
        
        $operations = $this->balanceService->getCardOperations($card);
        $statistics = $this->balanceService->getCardStatistics($card);

        return view('livewire.balance', [
            'cardBalance' => $card->balance,
            'cardNumber' => $card->card_number,
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