<?php

namespace App\Services;

use App\DTOs\PaymentDetails;
use App\Models\Card;
use App\Models\Operation;
use App\Models\User;
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
                'date' => now(),
                'type' => 'credit',
                'credit_type' => 'payment',
                'value' => $amount,
                'payment_type' => $paymentDetails->method,
                'payment_reference' => $paymentDetails->reference,
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
        return Card::where('id', $user->id)->first();
    }

    /**
     * Get operations for a card with pagination
     *
     * @param Card $card
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getCardOperations(Card $card, int $perPage = 10): LengthAwarePaginator
    {
        return Operation::where('card_id', $card->id)
            ->orderBy('date', 'desc')
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

        return [
            'total_credits' => $operations->where('type', 'credit')->sum('value'),
            'total_debits' => $operations->where('type', 'debit')->sum('value'),
            'total_transactions' => $operations->count()
        ];
    }
}
