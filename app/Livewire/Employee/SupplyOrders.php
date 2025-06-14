<?php

namespace App\Livewire\Employee;

use App\Models\Product;
use App\Models\SupplyOrder;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class SupplyOrders extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'requested';
    public $selectedOrders = [];
    public $showCompleteModal = false;
    public $orderToComplete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'requested'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function toggleOrder($orderId)
    {
        if (in_array($orderId, $this->selectedOrders)) {
            $this->selectedOrders = array_filter($this->selectedOrders, function($id) use ($orderId) {
                return $id != $orderId;
            });
        } else {
            $this->selectedOrders[] = $orderId;
        }
    }

    public function selectAllVisible()
    {
        foreach ($this->orders as $order) {
            if (!in_array($order->id, $this->selectedOrders) && $order->status === 'requested') {
                $this->selectedOrders[] = $order->id;
            }
        }
    }

    public function clearSelection()
    {
        $this->selectedOrders = [];
    }

    public function openCompleteModal($orderId)
    {
        $this->orderToComplete = $orderId;
        $this->showCompleteModal = true;
    }

    public function closeCompleteModal()
    {
        $this->orderToComplete = null;
        $this->showCompleteModal = false;
    }

    public function completeOrder($orderId = null)
    {
        $orderIdToComplete = $orderId ?? $this->orderToComplete;
        
        if (!$orderIdToComplete) {
            session()->flash('error', 'No order specified for completion.');
            return;
        }

        try {
            DB::beginTransaction();
            
            $order = SupplyOrder::with('product')->find($orderIdToComplete);
            
            if (!$order) {
                throw new \Exception('Supply order not found.');
            }

            if ($order->status !== 'requested') {
                throw new \Exception('Order is not in requested status.');
            }

            // Update the order status and add stock to product
            $order->markAsCompleted();
            
            DB::commit();
            
            session()->flash('success', "Supply order completed successfully. Added {$order->quantity} units to {$order->product->name}.");
            
            // Remove from selected orders if it was selected
            $this->selectedOrders = array_filter($this->selectedOrders, function($id) use ($orderIdToComplete) {
                return $id != $orderIdToComplete;
            });
            
            $this->closeCompleteModal();
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to complete order: ' . $e->getMessage());
        }
    }

    public function completeBatchOrders()
    {
        if (empty($this->selectedOrders)) {
            session()->flash('error', 'No orders selected for completion.');
            return;
        }

        try {
            DB::beginTransaction();
            
            $completedCount = 0;
            $totalUnits = 0;
            
            foreach ($this->selectedOrders as $orderId) {
                $order = SupplyOrder::with('product')->find($orderId);
                
                if ($order && $order->status === 'requested') {
                    $order->markAsCompleted();
                    $completedCount++;
                    $totalUnits += $order->quantity;
                }
            }
            
            DB::commit();
            
            if ($completedCount > 0) {
                session()->flash('success', "Successfully completed {$completedCount} supply orders, adding {$totalUnits} total units to inventory.");
                $this->selectedOrders = [];
            } else {
                session()->flash('error', 'No valid orders were completed.');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to complete batch orders: ' . $e->getMessage());
        }
    }

    public function getOrdersProperty()
    {
        return SupplyOrder::with(['product.category', 'registeredBy'])
            ->when($this->search, function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('registeredBy', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter !== 'all') {
                    $query->where('status', $this->statusFilter);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function getStatsProperty()
    {
        $pendingOrders = SupplyOrder::where('status', 'requested')->count();
        $completedOrders = SupplyOrder::where('status', 'completed')->count();
        $totalPendingUnits = SupplyOrder::where('status', 'requested')->sum('quantity');
        
        return [
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders,
            'totalPendingUnits' => $totalPendingUnits
        ];
    }

    public function render()
    {
        return view('livewire.employee.supply-orders', [
            'orders' => $this->orders,
            'stats' => $this->stats
        ]);
    }
} 