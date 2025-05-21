<?php

namespace App\Traits;

use App\Models\Operation;
use App\Services\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait WithCardOperations
{
    /**
     * Get the card balance for the currently authenticated user
     *
     * @return float|null The card balance or null if user has no card
     */
    public function getCardBalance()
    {
        if (!auth()->check()) {
            return null;
        }

        $user = auth()->user();

        if ($user->card) {
            return $user->card->balance;
        }

        return null;
    }

    /**
     * Check if the authenticated user has a card
     *
     * @return bool
     */
    public function hasCard()
    {
        return auth()->check() && auth()->user()->card !== null;
    }

    /**
     * Get the card number for the authenticated user
     *
     * @return string|null
     */
    public function getCardNumber()
    {
        if (!$this->hasCard()) {
            return null;
        }

        return auth()->user()->card->card_number;
    }

    /**
     * Get card operations history for the authenticated user
     *
     * @param int $limit Number of operations per page
     * @param string|null $type Filter by operation type ('credit' or 'debit')
     * @return \Illuminate\Pagination\LengthAwarePaginator|null
     */
    public function getCardOperations($limit = 10, $type = null)
    {
        if (!$this->hasCard()) {
            return null;
        }

        $query = auth()->user()->card->operations();

        if ($type) {
            $query->where('type', $type);
        }

        return $query->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($limit);
    }

    /**
     * Check if the user's card has sufficient balance for a given amount
     *
     * @param float $amount
     * @return bool
     */
    public function hasSufficientBalance($amount)
    {
        $balance = $this->getCardBalance();

        return $balance !== null && $balance >= $amount;
    }

    /**
     * Perform a general card operation with specific type details
     * 
     * @param string $operationType Main operation type ('debit' or 'credit')
     * @param string $specificType Specific operation type (e.g., 'order', 'membership_fee', 'payment', 'order_cancellation')
     * @param float $amount Amount to debit or credit
     * @param array $attributes Additional attributes for the operation (order_id, payment_type, payment_reference, etc.)
     * @return bool Success state
     */
    public function performCardOperation($operationType, $specificType, $amount, array $attributes = [])
    {
        if (!$this->hasCard()) {
            return false;
        }
        
        if ($operationType === 'debit' && !$this->hasSufficientBalance($amount)) {
            return false;
        }

        $card = auth()->user()->card;

        try {
            DB::transaction(function () use ($card, $operationType, $specificType, $amount, $attributes) {
                // Update card balance
                if ($operationType === 'debit') {
                    $card->balance -= $amount;
                } else {
                    $card->balance += $amount;
                }

                $card->save();

                // Create operation record
                $operationData = array_merge([
                    'card_id' => $card->id,
                    'type' => $operationType,
                    'value' => $amount,
                    'date' => Carbon::now()->toDateString(),
                ], $attributes);
                
                // Add specific type if provided
                if ($operationType === 'debit' && $specificType) {
                    $operationData['debit_type'] = $specificType;
                } elseif ($operationType === 'credit' && $specificType) {
                    $operationData['credit_type'] = $specificType;
                }
                
                Operation::create($operationData);
            });

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Debit card for an order
     *
     * @param float $amount Amount to debit
     * @param int|null $orderId The order ID associated with this debit
     * @param array $attributes Additional attributes for the operation
     * @return bool Success state
     */
    public function debitCardForOrder($amount, $orderId = null, array $attributes = [])
    {
        $operationAttributes = array_merge([
            'order_id' => $orderId
        ], $attributes);
        
        return $this->performCardOperation('debit', 'order', $amount, $operationAttributes);
    }

    /**
     * Debit card for membership fee
     *
     * @param float $amount Membership fee amount
     * @param array $attributes Additional attributes for the operation
     * @return bool Success state
     */
    public function debitCardForMembershipFee($amount, array $attributes = [])
    {
        return $this->performCardOperation('debit', 'membership_fee', $amount, $attributes);
    }

    /**
     * Credit card from payment
     *
     * @param float $amount Amount to credit
     * @param string $paymentType Payment type (Visa, PayPal, MB WAY)
     * @param string $paymentReference Payment reference
     * @param array $attributes Additional attributes for the operation
     * @return bool Success state
     */
    public function creditCardFromPayment($amount, $paymentType, $paymentReference, array $attributes = [])
    {
        $operationAttributes = array_merge([
            'payment_type' => $paymentType,
            'payment_reference' => $paymentReference
        ], $attributes);
        
        return $this->performCardOperation('credit', 'payment', $amount, $operationAttributes);
    }

    /**
     * Credit card from order cancellation
     *
     * @param float $amount Amount to credit
     * @param int $orderId The order ID associated with this credit
     * @param array $attributes Additional attributes for the operation
     * @return bool Success state
     */
    public function creditCardFromOrderCancellation($amount, $orderId, array $attributes = [])
    {
        $operationAttributes = array_merge([
            'order_id' => $orderId
        ], $attributes);
        
        return $this->performCardOperation('credit', 'order_cancellation', $amount, $operationAttributes);
    }

    /**
     * Process a card topup with specific payment method
     *
     * @param float $amount Amount to add to the card
     * @param string $paymentMethod Payment method (Visa, PayPal, MB WAY)
     * @param string $paymentReference Payment reference or card number
     * @param string|null $cvcCode CVC code (for Visa payments)
     * @return array ['success' => bool, 'message' => string]
     */
    public function processCardTopUp($amount, $paymentMethod, $paymentReference, $cvcCode = null)
    {
        // Validate inputs
        try {
            $this->validatePaymentDetails($amount, $paymentMethod, $paymentReference, $cvcCode);
        } catch (ValidationException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        // Process payment through payment service
        $paymentSuccess = false;

        try {
            $paymentSuccess = match ($paymentMethod) {
                'Visa' => Payment::payWithVisa($paymentReference, $cvcCode),
                'PayPal' => Payment::payWithPayPal($paymentReference),
                'MB WAY' => Payment::payWithMBway($paymentReference),
                default => false
            };
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Payment processing error: ' . $e->getMessage()
            ];
        }

        if (!$paymentSuccess) {
            return [
                'success' => false,
                'message' => 'Payment was declined. Please check your payment details.'
            ];
        }

        // If payment successful, credit the card
        $operationAttributes = [
            'payment_type' => $paymentMethod,
            'payment_reference' => $paymentReference
        ];
        
        $transactionSuccess = $this->performCardOperation('credit', 'payment', $amount, $operationAttributes);
        
        if (!$transactionSuccess) {
            return [
                'success' => false,
                'message' => 'Failed to update card balance. Please contact support.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Card successfully recharged with ' . number_format($amount, 2) . ' €.'
        ];
    }

    /**
     * Save payment method as default for current user
     *
     * @param string $paymentMethod Payment method (Visa, PayPal, MB WAY)
     * @param string $paymentReference Payment reference or card number
     * @return bool Success state
     */
    public function saveDefaultPaymentMethod($paymentMethod, $paymentReference)
    {
        if (!auth()->check()) {
            return false;
        }

        try {
            $user = auth()->user();
            $user->default_payment_type = $paymentMethod;
            $user->default_payment_reference = $paymentReference;
            $user->save();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get user's default payment method if available
     *
     * @return array|null ['method' => string, 'reference' => string] or null if no defaults
     */
    public function getDefaultPaymentMethod()
    {
        if (!auth()->check()) {
            return null;
        }

        $user = auth()->user();

        if (empty($user->default_payment_type) || empty($user->default_payment_reference)) {
            return null;
        }

        return [
            'method' => $user->default_payment_type,
            'reference' => $user->default_payment_reference
        ];
    }

    /**
     * Get card statistics for the authenticated user
     *
     * @return array|null Statistics or null if no card
     */
    public function getCardStatistics()
    {
        if (!$this->hasCard()) {
            return null;
        }

        $card = auth()->user()->card;
        $operations = $card->operations()->get();

        $totalCredits = $operations->where('type', 'credit')->sum('value');
        $totalDebits = $operations->where('type', 'debit')->sum('value');

        $lastOperation = $operations->sortByDesc('created_at')->first();

        return [
            'current_balance' => $card->balance,
            'total_credits' => $totalCredits,
            'total_debits' => $totalDebits,
            'total_transactions' => $operations->count(),
            'last_operation' => $lastOperation ? [
                'type' => $lastOperation->type,
                'amount' => $lastOperation->value,
                'date' => $lastOperation->date,
            ] : null
        ];
    }

    /**
     * Validate payment details
     *
     * @param float $amount Amount to add
     * @param string $paymentMethod Payment method
     * @param string $paymentReference Payment reference
     * @param string|null $cvcCode CVC code (for Visa)
     * @throws ValidationException If validation fails
     * @return bool True if validation passes
     */
    protected function validatePaymentDetails($amount, $paymentMethod, $paymentReference, $cvcCode = null)
    {
        $rules = [
            'amount' => 'required|numeric|min:5|max:1000',
            'paymentMethod' => 'required|in:Visa,PayPal,MB WAY',
            'paymentReference' => 'required|string|max:255',
        ];

        // Add specific validation based on payment method
        if ($paymentMethod === 'Visa') {
            $rules['paymentReference'] = ['required', 'string', 'max:255', function ($attr, $value, $fail) {
                if (!preg_match('/^[1-9][0-9]{15}$/', $value) || str_ends_with($value, '2')) {
                    $fail('The Visa card must be 16 digits long, cannot start with 0, and cannot end with 2.');
                }
            }];

            $rules['cvcCode'] = ['required', 'numeric', 'digits:3', function ($attr, $value, $fail) {
                if (str_starts_with($value, '0')) {
                    $fail('CVC code cannot start with 0.');
                }
                if (str_ends_with($value, '2')) {
                    $fail('CVC code cannot end with 2.');
                }
            }];
        } elseif ($paymentMethod === 'PayPal') {
            $rules['paymentReference'] = ['required', 'string', 'max:255', 'email'];
        } elseif ($paymentMethod === 'MB WAY') {
            $rules['paymentReference'] = ['required', 'string', 'max:255', function ($attr, $value, $fail) {
                if (!preg_match('/^9[1236][0-9]{7}$/', $value) || str_ends_with($value, '2')) {
                    $fail('Please enter a valid Portuguese mobile number that doesn\'t end with 2.');
                }
            }];
        }

        $data = [
            'amount' => $amount,
            'paymentMethod' => $paymentMethod,
            'paymentReference' => $paymentReference,
            'cvcCode' => $cvcCode,
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

    /**
     * Credit card for a refund when order creation fails
     *
     * @param float $amount Amount to credit (refund)
     * @param int|null $originalOperationId The ID of the original debit operation
     * @param string $reason Reason for the refund
     * @param array $attributes Additional attributes for the operation
     * @return bool Success state
     */
    public function creditCardForRefund($amount, $originalOperationId = null, $reason = '', array $attributes = [])
    {
        if (!$this->hasCard()) {
            return false;
        }

        $card = auth()->user()->card;

        try {
            DB::transaction(function () use ($card, $amount, $originalOperationId, $reason, $attributes) {
                // Update card balance
                $card->balance += $amount;
                $card->save();

                // Create operation record
                $operationData = array_merge([
                    'card_id' => $card->id,
                    'type' => 'credit',
                    'credit_type' => 'order_cancellation',
                    'value' => $amount,
                    'date' => Carbon::now()->toDateString(),
                    'description' => $reason ?: 'Refund for failed order',
                    'reference_operation_id' => $originalOperationId
                ], $attributes);

                Operation::create($operationData);
            });

            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Refund failed: ' . $e->getMessage());
            return false;
        }
    }
}
