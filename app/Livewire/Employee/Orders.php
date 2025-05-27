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
    public $cancel_reason;

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

    public function cancelOrder($orderId, $cancel_reason = null)
    {
        $reason = $cancel_reason ?: 'Order cancelled by a board member';
        
        if ($this->updateOrderStatus($orderId, 'canceled', $reason)) {
            if ($this->refundOrder($orderId)) {
                Toaster::success('Order cancelled and refunded');
            } else {
                Toaster::error('Failed to cancel order and refund');
            }
        } else {
            Toaster::error('Failed to cancel order');
        }
    }

}