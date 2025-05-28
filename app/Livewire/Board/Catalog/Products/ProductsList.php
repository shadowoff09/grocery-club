<?php

namespace App\Livewire\Board\Catalog\Products;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $stockStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'stockStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::withTrashed()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->when($this->stockStatus, function ($query) {
                switch ($this->stockStatus) {
                    case 'in_stock':
                        $query->where('stock', '>', 5);
                        break;
                    case 'low_stock':
                        $query->whereBetween('stock', [1, 5]);
                        break;
                    case 'out_of_stock':
                        $query->where('stock', 0);
                        break;
                }
            });

        return view('livewire.board.catalog.products.products-list', [
            'products' => $query->paginate(24),
            'categories' => Category::all(),
        ]);
    }
} 