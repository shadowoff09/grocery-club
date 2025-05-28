<?php

namespace App\Livewire\Board\Catalog\Products;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Masmerise\Toaster\Toaster;

class CreateProduct extends Component
{
    use WithFileUploads;

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|string')]
    public $description = '';

    #[Rule('required|exists:categories,id')]
    public $category_id = '';

    #[Rule('required|numeric|min:0')]
    public $price = 0;

    #[Rule('required|integer|min:0')]
    public $stock = 0;

    #[Rule('nullable|integer|min:0|max:100')]
    public $discount = null;

    #[Rule('nullable|integer|min:0')]
    public $discount_min_qty = null;

    #[Rule('nullable|image|max:2048')]
    public $photo = null;

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function createProduct()
    {
        $this->validate();

        try {
            $product = new Product();
            $product->name = $this->name;
            $product->description = $this->description;
            $product->category_id = $this->category_id;
            $product->price = $this->price;
            $product->stock = $this->stock;
            $product->discount = $this->discount;
            $product->discount_min_qty = $this->discount_min_qty;

            if ($this->photo) {
                $photoName = time() . '.' . $this->photo->getClientOriginalExtension();
                $this->photo->storeAs('products', $photoName, 'public');
                $product->photo = $photoName;
            }

            $product->save();
            Cache::flush();

            Toaster::success('Product created successfully.');
            return redirect()->route('board.catalog.products.show', $product->id);
        } catch (\Exception $e) {
            Toaster::error('Failed to create product. ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.board.catalog.products.create-product', [
            'categories' => Category::all(),
        ]);
    }
}
