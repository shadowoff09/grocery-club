<?php

namespace App\Traits;

use App\DTOs\PaymentDetails;
use App\Services\PaymentValidator;
use Illuminate\Support\Facades\App;

/**
 * Trait WithPaymentValidation
 *
 * Provides payment validation functionality for components
 */
trait WithPaymentValidation
{
    /**
     * Get the payment validator instance
     *
     * @return PaymentValidator
     */
    protected function getPaymentValidator(): PaymentValidator
    {
        return App::make(PaymentValidator::class);
    }

    /**
     * Get validation rules for payment details
     *
     * @param string|null $paymentMethod If provided, only returns rules for that method
     * @return array
     */
    protected function getPaymentValidationRules(?string $paymentMethod = null): array
    {
        return $this->getPaymentValidator()->getValidationRules($paymentMethod);
    }

    /**
     * Validate payment details directly
     *
     * @param PaymentDetails $paymentDetails
     * @return bool
     */
    protected function validatePayment(PaymentDetails $paymentDetails): bool
    {
        return $this->getPaymentValidator()->validatePayment($paymentDetails);
    }

    /**
     * Create a PaymentDetails DTO from component properties
     *
     * @param string $methodProperty Name of the property containing the payment method
     * @param string $referenceProperty Name of the property containing the payment reference
     * @param string|null $cvcProperty Name of the property containing the CVC code (for Visa)
     * @return PaymentDetails
     */
    protected function createPaymentDetails(string $methodProperty, string $referenceProperty, ?string $cvcProperty = null): PaymentDetails
    {
        $method = $this->{$methodProperty};
        $reference = $this->{$referenceProperty};
        $cvc = $cvcProperty && $method === 'Visa' ? $this->{$cvcProperty} : null;

        return new PaymentDetails($method, $reference, $cvc);
    }

    /**
     * Get placeholder text for payment input based on payment type
     *
     * @param string $paymentMethod The payment method
     * @return string
     */
    protected function getPlaceholderForPaymentType(string $paymentMethod): string
    {
        return match ($paymentMethod) {
            'Visa' => 'Enter your Visa card (cannot start with 0 or end with 2)',
            'PayPal' => 'Enter your PayPal email address (must end with .pt or .com)',
            'MB WAY' => 'Enter your Portuguese mobile number (cannot end with 2)',
            default => 'e.g., Visa number, PayPal email, or MB WAY number'
        };
    }
}
