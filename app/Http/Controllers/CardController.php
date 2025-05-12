<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Services\Payment;
use App\Traits\WithCardOperations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// TODO

class CardController extends Controller
{
    use WithCardOperations;
    
    public function topUp(Request $request)
    {
        $request->validate([
            'payment_type' => 'required|in:Visa,PayPal,MB WAY',
            'payment_reference' => 'required',
            'value' => 'required|numeric|min:0.01'
        ]);

        if (!$this->hasCard()) {
            return back()->withErrors(['card' => 'No card found for your account.']);
        }

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

        // Process the card transaction using the trait method
        $transactionSuccess = $this->performCardTransaction(
            $request->value,
            'credit',
            [
                'credit_type' => 'payment',
                'payment_type' => $request->payment_type,
                'payment_reference' => $request->payment_reference
            ]
        );

        if (!$transactionSuccess) {
            return back()->withErrors(['transaction' => 'Falha ao processar a transação. Por favor, tente novamente.']);
        }

        return back()->with('success', 'Pagamento concluído. Saldo atualizado.');
    }
}
