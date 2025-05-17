<?php

namespace App\Livewire;

use App\Traits\WithOrderOperations;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithOrderOperations;
    use WithPagination;

    public $statusFilter = null;
    public $perPage = 10;

    public function render()
    {
        $orders = $this->getUserOrders($this->perPage, $this->statusFilter);

        return view('livewire.orders.index', [
            'orders' => $orders,
        ]);
    }

    public function filterByStatus($status)
    {
        $this->statusFilter = $status === 'all' ? null : $status;
        $this->resetPage();
    }
}
