<?php

namespace App\Livewire;

use App\Services\BalanceService;
use App\Traits\WithDefaultPaymentHandling;
use App\Traits\WithPaymentValidation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Balance extends Component
{
    use WithPagination, WithPaymentValidation, WithDefaultPaymentHandling;

    public bool $showRechargeModal = false;
    public float $rechargeAmount = 50;
    public string $paymentMethod = 'Visa';
    public ?string $paymentReference = '';
    public ?string $cvcCode = '';

    // Services
    protected BalanceService $balanceService;

    public function boot(BalanceService $balanceService): void
    {
        $this->balanceService = $balanceService;
    }

    public function mount(): void
    {
        // Initialization logic if needed
    }

    public function showRechargeForm(): void
    {
        $this->showRechargeModal = true;

        // Check if user has defaults using the trait
        $this->checkForDefaultPaymentMethod();
    }

    public function cancelRecharge(): void
    {
        $this->showRechargeModal = false;
        $this->reset('rechargeAmount', 'paymentMethod', 'paymentReference', 'cvcCode', 'hasDefaults', 'showDefaultsAlert', 'defaultPaymentMethod', 'defaultPaymentReference', 'saveAsDefault');
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
        } else {
            $this->showRechargeModal = false;
            $this->reset('rechargeAmount', 'paymentMethod', 'paymentReference', 'cvcCode', 'saveAsDefault');
            Toaster::success('Card recharged successfully!');
        }
    }

    public function updated($field): void
    {
        // Use the trait to check if payment info changed
        $this->checkPaymentInfoChanged($field);
    }

    public function render()
    {
        $user = Auth::user();
        $card = $this->balanceService->getUserCard($user);

        // Get operations and statistics using the BalanceService
        $operations = $this->balanceService->getCardOperations($card);
        $statistics = $this->balanceService->getCardStatistics($card);

        return view('livewire.balance.index', [
            'cardBalance' => $card->balance,
            'cardNumber' => $card->card_number,
            'operations' => $operations,
            'statistics' => $statistics
        ]);
    }
}
