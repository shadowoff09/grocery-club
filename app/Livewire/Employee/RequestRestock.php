<?php

namespace App\Livewire\Employee;

use App\Models\Product;
use App\Models\SupplyOrder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Category;

class RequestRestock extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedProducts = [];
    public $quantities = [];
    public $categoryFilter = '';
    public $stockFilter = 'all'; // all, out_of_stock, low_stock, healthy

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'stockFilter' => ['except' => 'all'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedStockFilter()
    {
        $this->resetPage();
    }

    public function toggleProduct($productId)
    {
        if (in_array($productId, $this->selectedProducts)) {
            $this->selectedProducts = array_filter($this->selectedProducts, function($id) use ($productId) {
                return $id != $productId;
            });
            unset($this->quantities[$productId]);
        } else {
            $this->selectedProducts[] = $productId;
            
            // Set contextual default quantity based on stock level
            $product = Product::find($productId);
            if ($product) {
                if ($product->stock == 0) {
                    $this->quantities[$productId] = 25; // Out of stock
                } elseif ($product->stock <= 5) {
                    $this->quantities[$productId] = 20; // Low stock
                } else {
                    $this->quantities[$productId] = 10; // Healthy stock
                }
            } else {
                $this->quantities[$productId] = 10; // Fallback
            }
        }
    }

    public function incrementQuantity($productId)
    {
        $currentQuantity = $this->quantities[$productId] ?? 1;
        $this->quantities[$productId] = min($currentQuantity + 1, 1000);
    }

    public function decrementQuantity($productId)
    {
        $currentQuantity = $this->quantities[$productId] ?? 1;
        $this->quantities[$productId] = max($currentQuantity - 1, 1);
    }

    public function setQuantity($productId, $quantity)
    {
        $this->quantities[$productId] = max(1, min($quantity, 1000));
    }

    public function updatedQuantities($value, $key)
    {
        // Ensure all quantities are within valid range
        $this->quantities[$key] = max(1, min((int)$value, 1000));
    }

    public function selectAllVisible()
    {
        foreach ($this->products as $product) {
            if (!in_array($product->id, $this->selectedProducts)) {
                $this->selectedProducts[] = $product->id;
                
                // Set contextual default quantity
                if ($product->stock == 0) {
                    $this->quantities[$product->id] = 25;
                } elseif ($product->stock <= 5) {
                    $this->quantities[$product->id] = 20;
                } else {
                    $this->quantities[$product->id] = 10;
                }
            }
        }
    }

    public function clearSelection()
    {
        $this->selectedProducts = [];
        $this->quantities = [];
    }

    public function quickSelectLowStock()
    {
        $lowStockProducts = Product::where('stock', '<=', 5)->get();
        foreach ($lowStockProducts as $product) {
            if (!in_array($product->id, $this->selectedProducts)) {
                $this->selectedProducts[] = $product->id;
                $this->quantities[$product->id] = 20; // Higher default for low stock
            }
        }
    }

    public function quickSelectOutOfStock()
    {
        $outOfStockProducts = Product::where('stock', 0)->get();
        foreach ($outOfStockProducts as $product) {
            if (!in_array($product->id, $this->selectedProducts)) {
                $this->selectedProducts[] = $product->id;
                $this->quantities[$product->id] = 25; // Higher default for out of stock
            }
        }
    }

    public function submitRequest()
    {
        if (empty($this->selectedProducts)) {
            session()->flash('error', 'Please select at least one product.');
            return;
        }

        $createdOrders = 0;
        $userId = auth()->id();

        foreach ($this->selectedProducts as $productId) {
            $quantity = $this->quantities[$productId] ?? 0;
            
            if ($quantity > 0) {
                SupplyOrder::create([
                    'product_id' => $productId,
                    'registered_by_user_id' => $userId,
                    'status' => 'requested',
                    'quantity' => $quantity,
                ]);
                $createdOrders++;
            }
        }

        if ($createdOrders > 0) {
            session()->flash('success', "Successfully requested restock for {$createdOrders} products.");
            
            // Clear selections after successful submission
            $this->selectedProducts = [];
            $this->quantities = [];
            
            $this->dispatch('restock-requested');
        } else {
            session()->flash('error', 'No valid quantities specified.');
        }
    }

    public function getCategoriesProperty()
    {
        return Category::withCount('products')->having('products_count', '>', 0)->get();
    }

    public function getProductsProperty()
    {
        return Product::with('category')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->stockFilter, function ($query) {
                switch ($this->stockFilter) {
                    case 'out_of_stock':
                        $query->where('stock', 0);
                        break;
                    case 'low_stock':
                        $query->whereBetween('stock', [1, 5]);
                        break;
                    case 'healthy':
                        $query->where('stock', '>', 5);
                        break;
                }
            })
            ->orderBy('stock', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(20);
    }

    public function render()
    {
        return view('livewire.employee.request-restock', [
            'products' => $this->products,
            'categories' => $this->categories
        ]);
    }
} 