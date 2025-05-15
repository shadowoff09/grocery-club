<?php

namespace App\Traits;

use App\Models\Operation;
use App\Services\Payment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
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
     * Perform a transaction on the user's card
     * 
     * @param float $amount Amount to debit or credit
     * @param string $type Transaction type ('debit' or 'credit')
     * @param array $attributes Additional attributes for the operation
     * @return bool Success state
     */
    public function performCardTransaction($amount, $type, array $attributes = [])
    {
        if (!$this->hasCard()) {
            return false;
        }
        
        if ($type === 'debit' && !$this->hasSufficientBalance($amount)) {
            return false;
        }
        
        $card = auth()->user()->card;
        
        try {
            DB::transaction(function () use ($card, $amount, $type, $attributes) {
                // Update card balance
                if ($type === 'debit') {
                    $card->balance -= $amount;
                } else {
                    $card->balance += $amount;
                }
                
                $card->save();
                
                // Create operation record
                $operationData = array_merge([
                    'card_id' => $card->id,
                    'type' => $type,
                    'value' => $amount,
                    'date' => Carbon::now()->toDateString(),
                ], $attributes);
                
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
        if (!$this->hasCard() || !$this->hasSufficientBalance($amount)) {
            return false;
        }
        
        $card = auth()->user()->card;
        
        try {
            DB::transaction(function () use ($card, $amount, $orderId, $attributes) {
                // Update card balance
                $card->balance -= $amount;
                $card->save();
                
                // Create operation record
                $operationData = array_merge([
                    'card_id' => $card->id,
                    'type' => 'debit',
                    'debit_type' => 'order',
                    'value' => $amount,
                    'date' => Carbon::now()->toDateString(),
                    'order_id' => $orderId
                ], $attributes);
                
                Operation::create($operationData);
            });
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
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
        if (!$this->hasCard() || !$this->hasSufficientBalance($amount)) {
            return false;
        }
        
        $card = auth()->user()->card;
        
        try {
            DB::transaction(function () use ($card, $amount, $attributes) {
                // Update card balance
                $card->balance -= $amount;
                $card->save();
                
                // Create operation record
                $operationData = array_merge([
                    'card_id' => $card->id,
                    'type' => 'debit',
                    'debit_type' => 'membership_fee',
                    'value' => $amount,
                    'date' => Carbon::now()->toDateString(),
                ], $attributes);
                
                Operation::create($operationData);
            });
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
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
        if (!$this->hasCard()) {
            return false;
        }
        
        $card = auth()->user()->card;
        
        try {
            DB::transaction(function () use ($card, $amount, $paymentType, $paymentReference, $attributes) {
                // Update card balance
                $card->balance += $amount;
                $card->save();
                
                // Create operation record
                $operationData = array_merge([
                    'card_id' => $card->id,
                    'type' => 'credit',
                    'credit_type' => 'payment',
                    'payment_type' => $paymentType,
                    'payment_reference' => $paymentReference,
                    'value' => $amount,
                    'date' => Carbon::now()->toDateString(),
                ], $attributes);
                
                Operation::create($operationData);
            });
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
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
        if (!$this->hasCard()) {
            return false;
        }
        
        $card = auth()->user()->card;
        
        try {
            DB::transaction(function () use ($card, $amount, $orderId, $attributes) {
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
                    'order_id' => $orderId
                ], $attributes);
                
                Operation::create($operationData);
            });
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
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
        $transactionSuccess = $this->creditCardFromPayment(
            $amount,
            $paymentMethod,
            $paymentReference
        );
        
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
} 