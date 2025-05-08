<?php

namespace App\Livewire;

use App\Models\Card;
use App\Models\Operation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Balance extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $user = Auth::user();
        $card = Card::where('id', $user->id)->first();
        
        // Get all operations related to this card with pagination
        $operations = Operation::where('card_id', $card->id)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        return view('livewire.balance', [
            'card' => $card,
            'operations' => $operations
        ]);
    }
} 