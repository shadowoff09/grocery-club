<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Trait WithDefaultPaymentHandling
 *
 * Provides functionality for handling default payment methods
 */
trait WithDefaultPaymentHandling
{
    public bool $hasDefaults = false;
    public bool $showDefaultsAlert = false;
    public ?string $defaultPaymentMethod = null;
    public ?string $defaultPaymentReference = null;
    public bool $saveAsDefault = false;

    /**
     * Check if the user has default payment information
     *
     * @return void
     */
    public function checkForDefaultPaymentMethod(): void
    {
        $user = Auth::user();
        if ($user && !empty($user->default_payment_type) && !empty($user->default_payment_reference)) {
            $this->hasDefaults = true;
            $this->showDefaultsAlert = true;
            $this->defaultPaymentMethod = $user->default_payment_type;
            $this->defaultPaymentReference = $user->default_payment_reference;
        }
    }

    /**
     * Use the saved default payment information
     *
     * @return void
     */
    public function useDefaults(): void
    {
        if ($this->defaultPaymentMethod && $this->defaultPaymentReference) {
            $this->paymentMethod = $this->defaultPaymentMethod;
            $this->paymentReference = $this->defaultPaymentReference;
            // Don't set hasDefaults to false, so we can detect changes later
            $this->showDefaultsAlert = false; // Hide the alert after using defaults
        }
    }

    /**
     * Check if the payment method or reference has been changed from defaults
     *
     * @param string $field The name of the updated field
     * @return void
     */
    public function checkPaymentInfoChanged(string $field): void
    {
        if (($field === 'paymentMethod' || $field === 'paymentReference') &&
            $this->hasDefaults &&
            ($this->paymentMethod !== $this->defaultPaymentMethod ||
                $this->paymentReference !== $this->defaultPaymentReference)) {
            // Different payment info provided, allow saving as default
            $this->saveAsDefault = false;
        }
    }
}
