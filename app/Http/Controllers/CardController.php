<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Services\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// TODO

class CardController extends Controller
{
    public function topUp(Request $request)
    {
        $request->validate([
            'payment_type' => 'required|in:Visa,PayPal,MB WAY',
            'payment_reference' => 'required',
            'value' => 'required|numeric|min:0.01'
        ]);

        $card = auth()->user()->card;
        $success = false;

        switch ($request->payment_type) {
            case 'Visa':
                [$cardNumber, $cvc] = explode('|', $request->payment_reference); // Ex: "1234567812345678|123"
                $success = Payment::payWithVisa($cardNumber, $cvc);
                break;

            case 'PayPal':
                $success = Payment::payWithPaypal($request->payment_reference);
                break;

            case 'MB WAY':
                $success = Payment::payWithMBway($request->payment_reference);
                break;
        }

        if (!$success) {
            return back()->withErrors(['payment_reference' => 'Pagamento recusado. Verifica os dados.']);
        }

        // Se correr bem, grava operação e credita o cartão
        DB::transaction(function () use ($card, $request) {
            $card->balance += $request->value;
            $card->save();

            Operation::create([
                'card_id' => $card->id,
                'type' => 'credit',
                'value' => $request->value,
                'date' => now()->toDateString(),
                'credit_type' => 'payment',
                'payment_type' => $request->payment_type,
                'payment_reference' => $request->payment_reference,
            ]);
        });

        return back()->with('success', 'Pagamento concluído. Saldo atualizado.');
    }
}
