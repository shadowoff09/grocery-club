<div class="min-h-screen">
    <div class="container mx-auto px-2 py-2">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-8">
            <a href="{{ route('board.catalog.products') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Products</a>
            <x-lucide-chevron-right class="w-4 h-4" />
            <span class="text-gray-900 dark:text-white font-medium">New Product</span>
        </div>

        <form wire:submit="createProduct">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Product Image -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Image Card -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Product Image</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload a product photo</p>
                                </div>
                            </div>

                            <div class="relative group">
                                <div class="aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-zinc-800 border-2 border-dashed border-gray-200 dark:border-zinc-700">
                                    @if($photo)
                                        <img src="{{ $photo->temporaryUrl() }}"
                                             alt="Product preview"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-600">
                                            <x-lucide-image class="w-12 h-12 mb-2" />
                                            <span class="text-sm">No image available</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-4">
                                    <flux:input type="file" 
                                            wire:model.live="photo" 
                                            id="photo" 
                                            accept="image/*"
                                            :label="__('Choose Photo')" />
                                    <div class="text-xs text-gray-500 mt-1">{{ __('JPG, PNG or GIF. 2MB max.') }}</div>
                                    @error('photo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Product Details -->
                <div class="lg:col-span-2 space-y-6">
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
                                    <flux:input type="text" wire:model.live="name" required class="mt-1" placeholder="Enter product name" />
                                    @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <flux:label>Category</flux:label>
                                    <flux:select wire:model.live="category_id" required class="mt-1">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </flux:select>
                                    @error('category_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <flux:label>Description</flux:label>
                                    <flux:textarea wire:model.live="description" rows="4" required class="mt-1" placeholder="Enter product description" />
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
                                        <flux:input type="number" step="0.01" wire:model.live="price" required class="!pl-7" placeholder="0.00" />
                                    </div>
                                    @error('price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <flux:label>Stock</flux:label>
                                    <div class="mt-1">
                                        <flux:input type="number" wire:model.live="stock" required placeholder="0" />
                                    </div>
                                    @error('stock') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <flux:label>Discount (%)</flux:label>
                                    <div class="relative mt-1">
                                        <flux:input type="number" wire:model.live="discount" placeholder="0" />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400">%</span>
                                        </div>
                                    </div>
                                    @error('discount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <flux:label>Minimum Quantity for Discount</flux:label>
                                    <div class="mt-1">
                                        <flux:input type="number" wire:model.live="discount_min_qty" placeholder="0" />
                                    </div>
                                    @error('discount_min_qty') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary" icon="plus" class="cursor-pointer">
                            {{ __('Create Product') }}
                        </flux:button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div> 