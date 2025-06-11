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

class ProductDetail extends Component
{
    use WithFileUploads;

    public Product $product;
    
    #[Rule('required|string|max:255')]
    public $name = '';
    
    #[Rule('required|numeric|min:0')]
    public $price = 0;
    
    #[Rule('required|exists:categories,id')]
    public $category_id = 0;
    
    #[Rule('required|string')]
    public $description = '';
    
    #[Rule('required|integer|min:0')]
    public $stock = 0;
    
    #[Rule('nullable|numeric|min:0|max:100')]
    public $discount = null;
    
    #[Rule('nullable|integer|min:0')]
    public $discount_min_qty = null;

    public $photo = null;
    public $newPhoto = null;

    public function mount($product_id)
    {
        $this->product = Product::withTrashed()->findOrFail($product_id);
        $this->fill([
            'name' => $this->product->name,
            'price' => $this->product->price,
            'category_id' => $this->product->category_id,
            'description' => $this->product->description,
            'stock' => $this->product->stock,
            'discount' => $this->product->discount,
            'discount_min_qty' => $this->product->discount_min_qty,
        ]);
        $this->photo = $this->product->photo;
    }

    public function updateProduct()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'discount_min_qty' => 'nullable|integer|min:0',
            'newPhoto' => 'nullable|image|max:2048'
        ]);

        try {
            if ($this->newPhoto) {
                if ($this->product->photo) {
                    // Delete old photo
                    Storage::disk('public')->delete('products/' . $this->product->photo);
                }

                // Store new photo
                $photoName = time() . '.' . $this->newPhoto->getClientOriginalExtension();
                $this->newPhoto->storeAs('products', $photoName, 'public');
                $this->product->photo = $photoName;
                $this->photo = $photoName;
            }

            $this->product->update([
                'name' => $this->name,
                'price' => $this->price,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'stock' => $this->stock,
                'discount' => $this->discount,
                'discount_min_qty' => $this->discount_min_qty,
            ]);

            Cache::flush();
            $this->newPhoto = null;
            Toaster::success('Product updated successfully.');
        } catch (\Exception $e) {
            Toaster::error('Failed to update product. ' . $e->getMessage());
        }
    }

    public function deleteProduct()
    {
        try {
            $this->product->delete();
            Cache::flush();
            Toaster::success('Product deleted successfully.');
            return redirect()->route('board.catalog.products');
        } catch (\Exception $e) {
            Toaster::error('Failed to delete product. ' . $e->getMessage());
        }
    }

    public function restoreProduct()
    {
        try {
            $this->product->restore();
            Cache::flush();
            Toaster::success('Product restored successfully.');
			$this->modal('restore-product')->close();
        } catch (\Exception $e) {
            Toaster::error('Failed to restore product. ' . $e->getMessage());
        }
    }

    public function isDeleted()
    {
        return $this->product->trashed();
    }

    public function deletePhoto()
    {
        try {
            if ($this->product->photo) {
                Storage::disk('public')->delete('products/' . $this->product->photo);
                $this->product->photo = null;
                $this->product->save();
                $this->photo = null;
            }

            Cache::forget('products');
            Toaster::success('Product photo deleted successfully.');
        } catch (\Exception $e) {
            Toaster::error('Failed to delete photo. ' . $e->getMessage());
        }
    }

    public function isCategoryDeleted()
    {
        return $this->product->category && $this->product->category->trashed();
    }

    public function isCategoryMissing()
    {
        return !$this->product->category;
    }



    public function render()
    {
        return view('livewire.board.catalog.products.product-detail', [
            'categories' => Category::withTrashed()->get()
        ]);
    }
}