<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Traits\WithOrderOperations;

class Dashboard extends Component
{
    use WithPagination;
    use WithOrderOperations;
    
    public $orderCount = 0;
    public $totalSpent = 0;
    public $recentOrder = null;
    public $statusFilter = null;
    public $perPage = 5;
    
    public function mount()
    {
        $this->loadOrderData();
    }
    
    public function loadOrderData()
    {
        $user = Auth::user();
        
        if (!$user) {
            return;
        }

		$orders = $this->getUserOrders($this->perPage, $this->statusFilter, false);
        
        $this->orderCount = $orders->count();
        $this->totalSpent = $orders->sum('total');
        
        $this->recentOrder = $orders->first();
    }
    
    public function filterByStatus($status)
    {
        $this->statusFilter = $status === 'all' ? null : $status;
        $this->resetPage();
    }
    
    public function render()
    {
        $orders = $this->getUserOrders($this->perPage, $this->statusFilter);
        
        return view('livewire.dashboard', [
            'orders' => $orders,
        ])->layout('components.layouts.app', [
            'title' => 'Dashboard'
        ]);
    }
} 