<div class="min-h-screen">
    <div class="container mx-auto px-2 py-2">
        <!-- Breadcrumb & Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('board.catalog.products') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Products</a>
                <x-lucide-chevron-right class="w-4 h-4" />
                <span class="text-gray-900 dark:text-white font-medium">{{ $name }}</span>
            </div>
            <div class="flex items-center gap-3">
                @if($this->isDeleted())
                    <flux:modal.trigger name="restore-product">	
                        <flux:button variant="primary" icon="arrow-path" class="cursor-pointer">
                            Restore Product
                        </flux:button>
                    </flux:modal.trigger>
                @else
                    <flux:modal.trigger name="delete-product">	
                        <flux:button variant="danger" icon="trash" class="cursor-pointer">
                            Delete Product
                        </flux:button>
                    </flux:modal.trigger>
                @endif
            </div>
        </div>

        <form wire:submit="updateProduct">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Product Image -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Status</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Product visibility and state</p>
                            </div>
                            @if($this->isDeleted())
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                                    <x-lucide-archive class="w-4 h-4 mr-2" />
                                    Deleted
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400">
                                    <x-lucide-check-circle class="w-4 h-4 mr-2" />
                                    Active
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Image Card -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Product Image</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload a product photo</p>
                                </div>
                                @if($photo && !$this->isDeleted())
                                    <flux:modal.trigger name="delete-photo">
                                        <flux:button variant="danger" square icon="trash" size="xs" class="cursor-pointer" />
                                    </flux:modal.trigger>
                                @endif
                            </div>

                            <div class="relative group">
                                <div class="aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-zinc-800 border-2 border-dashed border-gray-200 dark:border-zinc-700">
                                    @if($photo)
                                        <img src="{{ asset('storage/products/' . $photo) }}"
                                             alt="{{ $name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-600">
                                            <x-lucide-image class="w-12 h-12 mb-2" />
                                            <span class="text-sm">No image available</span>
                                        </div>
                                    @endif
                                </div>

                                @unless($this->isDeleted())
                                    <div class="mt-4">
                                        <flux:input type="file" 
                                                wire:model.defer="newPhoto" 
                                                id="photo" 
                                                accept="image/*"
                                                :label="__('Choose Photo')" />
                                        <div class="text-xs text-gray-500 mt-1">{{ __('JPG, PNG or GIF. 2MB max.') }}</div>
                                        @error('newPhoto') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                @endunless
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Product Details -->
                <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Orders History</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View all orders containing this product</p>
                                </div>
                                <a href="{{ route('board.catalog.products.orders', $product->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                                    <x-lucide-eye class="w-4 h-4 mr-2" />
                                    View Orders
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Product name and description</p>
                                </div>
                            </div>

                            <div class="grid gap-6">
                                <div>
                                    <flux:label>Name</flux:label>
                                    <flux:input type="text" wire:model.live="name" required :disabled="$this->isDeleted()" class="mt-1" placeholder="Enter product name" />
                                    @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <flux:label>Category</flux:label>
                                    <div class="space-y-2">
                                        <flux:select wire:model.live="category_id" required :disabled="$this->isDeleted()" class="mt-1">
                                            <option value="">Select a category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ $category->name }}{{ $category->trashed() ? ' (Deleted)' : '' }}
                                                </option>
                                            @endforeach
                                        </flux:select>
                                        @if($this->isCategoryDeleted())
                                            <div class="text-sm text-yellow-600 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/20 px-4 py-3 rounded-lg">
                                                <div class="flex items-center gap-2">
                                                    <x-lucide-alert-triangle class="w-5 h-5 flex-shrink-0" />
                                                    <span>This product belongs to a deleted category. The product is still valid but won't be shown in the catalog.</span>
                                                </div>
                                            </div>
                                        @endif
                                        @if($this->isCategoryMissing())
                                            <div class="text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-4 py-3 rounded-lg">
                                                <div class="flex items-center gap-2">
                                                    <x-lucide-alert-circle class="w-5 h-5 flex-shrink-0" />
                                                    <span>This product has no category assigned. Please select a category to make the product visible in the catalog.</span>
                                                </div>
                                            </div>
                                        @endif
                                        @error('category_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <flux:label>Description</flux:label>
                                    <flux:textarea wire:model.live="description" rows="4" required :disabled="$this->isDeleted()" class="mt-1" placeholder="Enter product description" />
                                    @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing and Inventory -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pricing & Inventory</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage product price, stock, and discounts</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <flux:label>Price ($)</flux:label>
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400">$</span>
                                        </div>
                                        <flux:input type="number" step="0.01" wire:model.live="price" required :disabled="$this->isDeleted()" class="!pl-7" placeholder="0.00" />
                                    </div>
                                    @error('price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <flux:label>Stock</flux:label>
                                    <div class="mt-1">
                                        <flux:input type="number" wire:model.live="stock" required :disabled="$this->isDeleted()" placeholder="0" />
                                    </div>
                                    @error('stock') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <flux:label>Discount (%)</flux:label>
                                    <div class="relative mt-1">
                                        <flux:input type="number" wire:model.live="discount" :disabled="$this->isDeleted()" placeholder="0" />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400">%</span>
                                        </div>
                                    </div>
                                    @error('discount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <flux:label>Minimum Quantity for Discount</flux:label>
                                    <div class="mt-1">
                                        <flux:input type="number" wire:model.live="discount_min_qty" :disabled="$this->isDeleted()" placeholder="0" />
                                    </div>
                                    @error('discount_min_qty') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    @unless($this->isDeleted())
                        <div class="flex justify-end">
                            <flux:button type="submit" variant="primary" icon="check-circle" class="cursor-pointer">
                                {{ __('Save') }}
                            </flux:button>
                        </div>
                    @endunless

                    <!-- Orders Quick Link -->
                    
                </div>
            </div>
        </form>
    </div>

    <!-- Modals -->
    <flux:modal name="delete-photo" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Photo</flux:heading>
                <flux:text class="mt-2">Are you sure you want to delete this photo? This action cannot be undone.</flux:text>
            </div>
            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deletePhoto" class="cursor-pointer">Delete Photo</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="delete-product" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Product</flux:heading>
                <flux:text class="mt-2">Are you sure you want to delete this product? You can restore it later if needed.</flux:text>
            </div>
            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deleteProduct" class="cursor-pointer">Delete Product</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="restore-product" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Restore Product</flux:heading>
                <flux:text class="mt-2">Are you sure you want to restore this product? It will be visible in the catalog again.</flux:text>
            </div>
            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" wire:click="restoreProduct" class="cursor-pointer">Restore Product</flux:button>
            </div>
        </div>
    </flux:modal>
</div>