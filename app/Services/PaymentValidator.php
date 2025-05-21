<?php

namespace App\Services;

use App\DTOs\PaymentDetails;
use Illuminate\Validation\Rule;

class PaymentValidator
{
    /**
     * Get validation rules for payment details
     *
     * @param string|null $paymentMethod If provided, only returns rules for that method
     * @return array
     */
    public function getValidationRules(?string $paymentMethod = null): array
    {
        $rules = [
            'rechargeAmount' => 'required|numeric|min:5|max:1000',
            'paymentMethod' => 'required|in:Visa,PayPal,MB WAY',
            'paymentReference' => [
                'required', 'string', 'max:255',
                function ($attr, $value, $fail) use ($paymentMethod) {
                    $method = $paymentMethod ?? request('paymentMethod');

                    match ($method) {
                        'Visa' => preg_match('/^[1-9][0-9]{15}$/', $value) && !str_ends_with($value, '2')
                            ?: $fail('The Visa card must be 16 digits long, cannot start with 0, and cannot end with 2.'),
                        'PayPal' => filter_var($value, FILTER_VALIDATE_EMAIL)
                            ?: $fail('Please enter a valid PayPal email address.'),
                        'MB WAY' => preg_match('/^9[1236][0-9]{7}$/', $value) && !str_ends_with($value, '2')
                            ?: $fail('Please enter a valid Portuguese mobile number that doesn\'t end with 2.'),
                        default => $fail('Invalid payment type selected.')
                    };
                }
            ]
        ];

        // Add CVC validation for Visa
        if ($paymentMethod === 'Visa' || request('paymentMethod') === 'Visa') {
            $rules['cvcCode'] = [
                'required', 'numeric', 'digits:3',
                function ($attr, $value, $fail) {
                    if (str_starts_with($value, '0')) {
                        $fail('CVC code cannot start with 0.');
                    }
                    if (str_ends_with($value, '2')) {
                        $fail('CVC code cannot end with 2.');
                    }
                }
            ];
        }

        return $rules;
    }

    /**
     * Validate payment details directly
     *
     * @param PaymentDetails $paymentDetails
     * @return bool
     */
    public function validatePayment(PaymentDetails $paymentDetails): bool
    {
        return match ($paymentDetails->method) {
            'Visa' => Payment::payWithVisa($paymentDetails->reference, $paymentDetails->cvc),
            'PayPal' => Payment::payWithPayPal($paymentDetails->reference),
            'MB WAY' => Payment::payWithMBway($paymentDetails->reference),
            default => false
        };
    }
}
