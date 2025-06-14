<?php

namespace App\Livewire\Board\Catalog\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Masmerise\Toaster\Toaster;

class CategoryDetail extends Component
{
    use WithFileUploads;

    public Category $category;
    
    #[Rule('required|string|max:255')]
    public $name = '';

    public $photo = null;
    public $newPhoto = null;

    public function mount($category_id)
    {
        $this->category = Category::withTrashed()->findOrFail($category_id);
        $this->fill([
            'name' => $this->category->name,
        ]);
        $this->photo = $this->category->image;
    }

    public function updateCategory()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'newPhoto' => 'nullable|image|max:2048'
        ]);

        try {
            if ($this->newPhoto) {
                if ($this->category->image) {
                    // Delete old photo
                    Storage::disk('public')->delete('categories/' . $this->category->image);
                }

                // Store new photo
                $photoName = time() . '.' . $this->newPhoto->getClientOriginalExtension();
                $this->newPhoto->storeAs('categories', $photoName, 'public');
                $this->category->image = $photoName;
                $this->photo = $photoName;
            }

            $this->category->update([
                'name' => $this->name,
            ]);

            Cache::flush();
            $this->newPhoto = null;
            Toaster::success('Category updated successfully.');
        } catch (\Exception $e) {
            Toaster::error('Failed to update category. ' . $e->getMessage());
        }
    }

    public function deleteCategory()
    {
        try {
            $this->category->delete();
            Cache::flush();
            Toaster::success('Category deleted successfully.');
            return redirect()->route('board.catalog.categories');
        } catch (\Exception $e) {
            Toaster::error('Failed to delete category. ' . $e->getMessage());
        }
    }

    public function restoreCategory()
    {
        try {
            $this->category->restore();
            Cache::flush();
            Toaster::success('Category restored successfully.');
            $this->modal('restore-category')->close();
        } catch (\Exception $e) {
            Toaster::error('Failed to restore category. ' . $e->getMessage());
        }
    }

    public function isDeleted()
    {
        return $this->category->trashed();
    }

    public function deleteImage()
    {
        try {
            if ($this->category->image) {
                Storage::disk('public')->delete('categories/' . $this->category->image);
                $this->category->image = null;
                $this->category->save();
                $this->photo = null;
            }

            Cache::flush();
            Toaster::success('Category image deleted successfully.');
        } catch (\Exception $e) {
            Toaster::error('Failed to delete image. ' . $e->getMessage());
        }
    }

    public function render()
    {
        $products = $this->category->products()
            ->withTrashed()
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.board.catalog.categories.category-detail', [
            'products' => $products,
            'productsCount' => $this->category->products()->count()
        ]);
    }
}