<?php

namespace App\Services;

use App\DTOs\PaymentDetails;
use App\Models\Card;
use App\Models\Operation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BalanceService
{
    protected PaymentValidator $paymentValidator;

    public function __construct(PaymentValidator $paymentValidator)
    {
        $this->paymentValidator = $paymentValidator;
    }

    /**
     * Recharge a user's card with the specified amount
     *
     * @param User $user
     * @param float $amount
     * @param PaymentDetails $paymentDetails
     * @param bool $saveAsDefault
     * @return bool
     */
    public function rechargeCard(User $user, float $amount, PaymentDetails $paymentDetails, bool $saveAsDefault = false): bool
    {
        // Validate payment
        $result = $this->paymentValidator->validatePayment($paymentDetails);

        if (!$result) {
            return false;
        }

        $userCard = $user->card;
        if (!$userCard) {
            return false;
        }

        DB::transaction(function () use ($userCard, $user, $amount, $paymentDetails, $saveAsDefault) {
            // Add credit operation
            Operation::create([
                'card_id' => $userCard->id,
                'date' => now()->toDateString(),
                'type' => 'credit',
                'credit_type' => 'payment',
                'value' => $amount,
                'payment_type' => $paymentDetails->method,
                'payment_reference' => $paymentDetails->reference,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update card balance
            $userCard->balance += $amount;
            $userCard->save();

            // Save payment info as default if checkbox is checked
            if ($saveAsDefault) {
                $user->default_payment_type = $paymentDetails->method;
                $user->default_payment_reference = $paymentDetails->reference;
                $user->save();
            }
        });

        return true;
    }

    /**
     * Get a user's card
     *
     * @param User $user
     * @return Card|null
     */
    public function getUserCard(User $user): ?Card
    {
        return $user->card;
    }

    /**
     * Get operations for a card with pagination
     *
     * @param Card $card
     * @param int $perPage
     * @param string|null $type Filter by operation type ('credit' or 'debit')
     * @return LengthAwarePaginator
     */
    public function getCardOperations(Card $card, int $perPage = 10, ?string $type = null): LengthAwarePaginator
    {
        $query = Operation::where('card_id', $card->id);
        
        if ($type) {
            $query->where('type', $type);
        }
        
        return $query->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get statistics for a card
     *
     * @param Card $card
     * @return array
     */
    public function getCardStatistics(Card $card): array
    {
        $operations = Operation::where('card_id', $card->id)->get();
        
        $lastOperation = $operations->sortByDesc('created_at')->first();

        return [
            'current_balance' => $card->balance,
            'total_credits' => $operations->where('type', 'credit')->sum('value'),
            'total_debits' => $operations->where('type', 'debit')->sum('value'),
            'total_transactions' => $operations->count(),
            'last_operation' => $lastOperation ? [
                'type' => $lastOperation->type,
                'amount' => $lastOperation->value,
                'date' => $lastOperation->date,
            ] : null
        ];
    }
    
    /**
     * Debit card for an order
     *
     * @param User $user
     * @param float $amount
     * @param int|null $orderId
     * @return bool
     */
    public function debitCardForOrder(User $user, float $amount, ?int $orderId = null): bool
    {
        $card = $user->card;
        
        if (!$card || $card->balance < $amount) {
            return false;
        }
        
        try {
            DB::transaction(function () use ($card, $amount, $orderId) {
                // Update card balance
                $card->balance -= $amount;
                $card->save();
                
                // Create operation record
                Operation::create([
                    'card_id' => $card->id,
                    'type' => 'debit',
                    'debit_type' => 'order',
                    'value' => $amount,
                    'date' => Carbon::now()->toDateString(),
                    'order_id' => $orderId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            });
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Credit card from order cancellation
     *
     * @param User $user
     * @param float $amount
     * @param int $orderId
     * @return bool
     */
    public function creditCardFromOrderCancellation(User $user, float $amount, int $orderId): bool
    {
        $card = $user->card;
        
        if (!$card) {
            return false;
        }
        
        try {
            DB::transaction(function () use ($card, $amount, $orderId) {
                // Update card balance
                $card->balance += $amount;
                $card->save();
                
                // Create operation record
                Operation::create([
                    'card_id' => $card->id,
                    'type' => 'credit',
                    'credit_type' => 'order_cancellation',
                    'value' => $amount,
                    'date' => Carbon::now()->toDateString(),
                    'order_id' => $orderId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            });
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Debit card for membership fee
     *
     * @param User $user
     * @param float $amount
     * @return bool
     */
    public function debitCardForMembershipFee(User $user, float $amount): bool
    {
        $card = $user->card;
        
        if (!$card || $card->balance < $amount) {
            return false;
        }
        
        try {
            DB::transaction(function () use ($card, $amount) {
                // Update card balance
                $card->balance -= $amount;
                $card->save();
                
                // Create operation record
                Operation::create([
                    'card_id' => $card->id,
                    'type' => 'debit',
                    'debit_type' => 'membership_fee',
                    'value' => $amount,
                    'date' => Carbon::now()->toDateString(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            });
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get card-specific operations collection
     * 
     * @param Card $card
     * @return Collection
     */
    public function getCardOperationsCollection(Card $card): Collection
    {
        return Operation::where('card_id', $card->id)
            ->orderBy('date', 'desc')
            ->get();
    }
} 