<?php

namespace App\Livewire\Board\Catalog\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductOrders extends Component
{
    use WithPagination;

    public Product $product;

    public function mount($product_id)
    {
        $this->product = Product::withTrashed()->findOrFail($product_id);
    }

    public function getOrdersProperty()
    {
        return $this->product->orders()
            ->with(['member', 'items' => function($query) {
                $query->where('product_id', $this->product->id);
            }])
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.board.catalog.products.product-orders', [
            'orders' => $this->orders,
        ]);
    }
} 