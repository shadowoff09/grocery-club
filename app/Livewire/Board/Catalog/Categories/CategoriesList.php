<?php

namespace App\Livewire\Board\Catalog\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Category::withTrashed()
            ->withCount('products')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function ($query) {
                switch ($this->status) {
                    case 'active':
                        $query->whereNull('deleted_at');
                        break;
                    case 'deleted':
                        $query->whereNotNull('deleted_at');
                        break;
                }
            });

        return view('livewire.board.catalog.categories.categories-list', [
            'categories' => $query->paginate(24)
        ]);
    }
} 