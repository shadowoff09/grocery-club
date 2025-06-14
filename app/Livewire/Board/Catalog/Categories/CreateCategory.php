<?php

namespace App\Livewire\Board\Catalog\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Masmerise\Toaster\Toaster;

class CreateCategory extends Component
{
    use WithFileUploads;
    
    #[Rule('required|string|max:255')]
    public $name = '';
    
    #[Rule('nullable|image|max:2048')]
    public $image = null;

    public function createCategory()
    {
        $this->validate();

        try {
            $category = new Category();
            $category->name = $this->name;

            if ($this->image) {
                $imageName = time() . '.' . $this->image->getClientOriginalExtension();
                $this->image->storeAs('categories', $imageName, 'public');
                $category->image = $imageName;
            }

            $category->save();
            Cache::flush();
            
            Toaster::success('Category created successfully.');
            return redirect()->route('board.catalog.categories.show', $category->id);
        } catch (\Exception $e) {
            Toaster::error('Failed to create category. ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.board.catalog.categories.create-category');
    }
} 