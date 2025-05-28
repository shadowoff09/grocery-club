<div class="min-h-screen">
    <div class="container mx-auto px-2 py-2">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Products</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your product catalog</p>
            </div>
            <div class="flex items-center gap-3">
                <flux:button class="cursor-pointer" variant="primary" icon="plus" href="{{ route('board.catalog.products.create') }}">
                    New Product
                </flux:button>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="mb-6 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <flux:input
                        type="search"
                        name="search"
                        id="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search by name or description..."
                        icon="magnifying-glass"
                        label="Search"
                    />
                </div>

                <!-- Category Filter -->
                <div>
                    <flux:select
                        name="category"
                        id="category"
                        wire:model.live="category"
                        label="Category"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <!-- Stock Filter -->
                <div>
                    <flux:select
                        name="stock"
                        id="stock"
                        wire:model.live="stockStatus"
                        label="Stock Status"
                    >
                        <option value="">All</option>
                        <option value="in_stock">In Stock</option>
                        <option value="low_stock">Low Stock (≤ 5)</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </flux:select>
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 overflow-hidden group hover:shadow-lg transition-all duration-300 hover:translate-y-[-2px]">
                    <!-- Image -->
                    <div class="relative aspect-[4/3] overflow-hidden bg-gray-100 dark:bg-zinc-800">
                        @if($product->photo)
                            <img src="{{ asset('storage/products/' . $product->photo) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 dark:text-gray-600">
                                <x-lucide-package class="w-12 h-12 mb-2" />
                                <span class="text-sm">No image</span>
                            </div>
                        @endif

                        <!-- Status Badges -->
                        <div class="absolute top-4 right-4 flex flex-col gap-2">
                            @if($product->trashed())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                                    <x-lucide-archive class="w-3.5 h-3.5" />
                                    Deleted
                                </span>
                            @endif
                            @if($product->stock <= 5 && $product->stock > 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400">
                                    <x-lucide-alert-triangle class="w-3.5 h-3.5" />
                                    Only {{ $product->stock }} left
                                </span>
                            @elseif($product->stock === 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                                    <x-lucide-x-circle class="w-3.5 h-3.5" />
                                    Out of stock
                                </span>
                            @endif
                        </div>

                        <!-- Category Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-white/90 dark:bg-zinc-800/90 text-gray-800 dark:text-white backdrop-blur-sm">
                                @if($product->category)
                                    <x-lucide-tag class="w-3.5 h-3.5" />
                                    {{ $product->category->name }}
                                    @if($product->category->trashed())
                                        <span class="text-yellow-600 dark:text-yellow-400">(Deleted)</span>
                                    @endif
                                @else
                                    <x-lucide-alert-circle class="w-3.5 h-3.5 text-red-500" />
                                    <span class="text-red-600 dark:text-red-400">No Category</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                {{ $product->name }}
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">
                                {{ $product->description }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                ${{ number_format($product->price, 2) }}
                            </span>
                            @if($product->discount)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400">
                                    <x-lucide-tag class="w-3.5 h-3.5" />
                                    {{ $product->discount }}% off
                                </span>
                            @endif
                        </div>

                        <div class="flex justify-end">
                            <flux:button 
                                variant="primary" 
                                icon="arrow-right" 
                                class="w-full cursor-pointer"
                                wire:navigate="{{ route('board.catalog.products.show', $product->id) }}"
                                href="{{ route('board.catalog.products.show', $product->id) }}"
                            >
                                View Details
                            </flux:button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800">
                        <x-lucide-package class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No products yet</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Get started by creating your first product.</p>
                        <flux:button class="cursor-pointer" variant="primary" icon="plus" href="{{ route('board.catalog.products.create') }}">
                            Create Product
                        </flux:button>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div> 