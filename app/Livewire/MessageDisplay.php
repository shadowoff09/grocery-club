<?php
namespace App\Livewire;

use Livewire\Component;

class MessageDisplay extends Component
{
    public $productId;
    public $messages = [];

    protected $listeners = ['displayMessage' => 'addMessage'];

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function addMessage($productId, $message, $type = 'warning')
    {
        // Only store messages for this specific product
        if ($productId == $this->productId) {
            $this->messages = [
                'message' => $message,
                'type' => $type
            ];
        }
    }

    public function render()
    {
        return view('livewire.cart.message-display');
    }
}
