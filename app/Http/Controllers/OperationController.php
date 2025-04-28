<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// TODO

class OperationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->card) {
            abort(404, 'Card not found.');
        }

        $operations = $user->card->operations()->orderByDesc('date')->paginate(10);

        return view('operations.index', compact('operations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'card_id' => 'required|integer|exists:cards,id',
            'type' => 'required|in:credit,debit',
            'value' => 'required|numeric|min:0.01',
            'debit_type' => 'nullable|string|in:membership_fee,order',
            'credit_type' => 'nullable|string|in:payment,order_cancellation',
            'payment_type' => 'nullable|string|in:Visa,PayPal,MB WAY',
            'payment_reference' => 'nullable|string',
            'order_id' => 'nullable|integer|exists:orders,id',
        ]);

        $user = Auth::user();
        $card = $user->card;

        if (!$card) {
            return redirect()->route('operations.index')
                ->with('error', 'You do not have a card.');
        }

        DB::beginTransaction();
        try {
            // Check if we have enough balance for a purchase
            if ($validated['type'] === 'purchase' && $card->balance < $validated['value']) {
                throw new \RuntimeException('Insufficient balance for this purchase.');
            }

            // Create the operation
            $operation = new Operation();
            $operation->card_id = $card->id;
            $operation->type = $validated['type'];
            $operation->value = $validated['value'];
            $operation->description = $validated['description'] ?? null;
            $operation->date = now();
            $operation->save();

            // Update card balance
            if ($validated['type'] === 'purchase') {
                $card->balance -= $validated['value'];
            } else { // deposit
                $card->balance += $validated['value'];
            }
            $card->save();

            DB::commit();

            return redirect()->route('operations.index')
                ->with('success', ucfirst($validated['type']) . ' operation completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('operations.create')
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

}
