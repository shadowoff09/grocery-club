<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Traits\WithCardOperations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// TODO

class OperationController extends Controller
{
    use WithCardOperations;

    public function index()
    {
        if (!$this->hasCard()) {
            abort(404, 'Card not found.');
        }
        
        $operations = $this->getCardOperations(10, null);
        
        return view('operations.index', compact('operations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:credit,debit',
            'value' => 'required|numeric|min:0.01',
            'debit_type' => 'nullable|string|in:membership_fee,order',
            'credit_type' => 'nullable|string|in:payment,order_cancellation',
            'payment_type' => 'nullable|string|in:Visa,PayPal,MB WAY',
            'payment_reference' => 'nullable|string',
            'order_id' => 'nullable|integer|exists:orders,id',
        ]);

        if (!$this->hasCard()) {
            return to_route('operations.index')
                ->with('error', 'You do not have a card.');
        }

        // Create additional attributes array from validated data
        $additionalAttributes = array_filter([
            'debit_type' => $validated['debit_type'] ?? null,
            'credit_type' => $validated['credit_type'] ?? null,
            'payment_type' => $validated['payment_type'] ?? null,
            'payment_reference' => $validated['payment_reference'] ?? null,
            'order_id' => $validated['order_id'] ?? null,
        ]);

        // Use the trait method to perform the transaction
        $success = $this->performCardTransaction(
            $validated['value'],
            $validated['type'],
            $additionalAttributes
        );

        if (!$success) {
            return to_route('operations.create')
                ->with('error', 'Failed to process the operation. Please try again.')
                ->withInput();
        }

        return to_route('operations.index')
            ->with('success', ucfirst($validated['type']) . ' operation completed successfully.');
    }
}
