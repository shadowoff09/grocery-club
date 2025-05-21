<?php

namespace App\Livewire\Employee;

use App\Traits\WithOrderOperations;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Orders extends Component
{
    use WithOrderOperations;
    use WithPagination;

    public $statusFilter = 'pending';
    public $perPage = 10;

    public function render()
    {
        $orders = $this->getAllOrders($this->perPage, $this->statusFilter);

        return view('livewire.employee.orders', [
            'orders' => $orders,
        ]);
    }

	public function filterByStatus($status)
    {
        $this->statusFilter = $status === 'all' ? null : $status;
        $this->resetPage();
    }

    public function markAsCompleted($orderId)
    {
        if ($this->updateOrderStatus($orderId, 'completed')) {
            Toaster::success('Order marked as completed');
        } else {
            Toaster::error('Failed to mark order as completed');
        }
    }

	}