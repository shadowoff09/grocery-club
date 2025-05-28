<div class="min-h-screen">
    <div class="container mx-auto px-2 py-2">
        <!-- Breadcrumb & Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('board.catalog.categories') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Categories</a>
                <x-lucide-chevron-right class="w-4 h-4" />
                <span class="text-gray-900 dark:text-white font-medium">{{ $name }}</span>
            </div>
            <div class="flex items-center gap-3">
                @if($this->isDeleted())
                    <flux:modal.trigger name="restore-category">	
                        <flux:button variant="primary" icon="arrow-path" class="cursor-pointer">
                            Restore Category
                        </flux:button>
                    </flux:modal.trigger>
                @else
                    <flux:modal.trigger name="delete-category">	
                        <flux:button variant="danger" icon="trash" class="cursor-pointer">
                            Delete Category
                        </flux:button>
                    </flux:modal.trigger>
                @endif
            </div>
        </div>

        <form wire:submit="updateCategory">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Category Details -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Status</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Category visibility and state</p>
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
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Category Image</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload a category photo</p>
                                </div>
                                @if($photo && !$this->isDeleted())
                                    <flux:modal.trigger name="delete-image">
                                        <flux:button variant="danger" square icon="trash" size="xs" class="cursor-pointer" />
                                    </flux:modal.trigger>
                                @endif
                            </div>

                            <div class="relative group">
                                <div class="aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-zinc-800 border-2 border-dashed border-gray-200 dark:border-zinc-700">
                                    @if($photo)
                                        <img src="{{ asset('storage/categories/' . $photo) }}"
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
                                                :label="__('Choose Image')" />
                                        <div class="text-xs text-gray-500 mt-1">{{ __('JPG, PNG or GIF. 2MB max.') }}</div>
                                        @error('newPhoto') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                @endunless
                            </div>

                            <!-- Category Name -->
                            <div class="mt-6">
                                <flux:label>Name</flux:label>
                                <flux:input type="text" wire:model.live="name" required :disabled="$this->isDeleted()" class="mt-1" placeholder="Enter category name" />
                                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Save Button -->
                            @unless($this->isDeleted())
                                <div class="flex justify-end mt-6 pt-6 border-t border-gray-200 dark:border-zinc-700">
                                    <flux:button type="submit" variant="primary" icon="check-circle" class="cursor-pointer">
                                        {{ __('Save Changes') }}
                                    </flux:button>
                                </div>
                            @endunless
                        </div>
                    </div>
                </div>

                <!-- Right Column - Products List -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Products</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $productsCount }} {{ Str::plural('product', $productsCount) }} in this category</p>
                                </div>
                                <a href="{{ route('board.catalog.products') }}" class="inline-flex items-center gap-2 text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">
                                    <span>View All Products</span>
                                    <x-lucide-arrow-right class="w-4 h-4" />
                                </a>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Product</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stock</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                                        @forelse($products as $product)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        @if($product->photo)
                                                            <img class="h-10 w-10 rounded-lg object-cover" src="{{ asset('storage/products/' . $product->photo) }}" alt="{{ $product->name }}">
                                                        @else
                                                            <div class="h-10 w-10 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center">
                                                                <x-lucide-package class="h-5 w-5 text-gray-400" />
                                                            </div>
                                                        @endif
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white">${{ number_format($product->price, 2) }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($product->stock > 5)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            {{ $product->stock }} in stock
                                                        </span>
                                                    @elseif($product->stock > 0)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Only {{ $product->stock }} left
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            Out of stock
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($product->trashed())
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            Deleted
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Active
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                    <a href="{{ route('board.catalog.products.show', $product->id) }}" 
                                                       class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
                                                        View Details
                                                        <x-lucide-arrow-right class="w-4 h-4" />
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <x-lucide-package class="w-8 h-8 mb-2 text-gray-400" />
                                                        <p>No products found in this category</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modals -->
    <flux:modal name="delete-image" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Image</flux:heading>
                <flux:text class="mt-2">Are you sure you want to delete this image? This action cannot be undone.</flux:text>
            </div>
            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deleteImage" class="cursor-pointer">Delete Image</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="delete-category" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Category</flux:heading>
                <flux:text class="mt-2">Are you sure you want to delete this category? Associated products will remain but won't be visible in the catalog.</flux:text>
            </div>
            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deleteCategory" class="cursor-pointer">Delete Category</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="restore-category" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Restore Category</flux:heading>
                <flux:text class="mt-2">Are you sure you want to restore this category? Associated products will become visible in the catalog again.</flux:text>
            </div>
            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="ghost" class="cursor-pointer">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" wire:click="restoreCategory" class="cursor-pointer">Restore Category</flux:button>
            </div>
        </div>
    </flux:modal>
</div> 