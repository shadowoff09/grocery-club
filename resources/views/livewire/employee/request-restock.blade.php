<div class="space-y-8">
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex">
                <flux:icon name="check-circle" class="w-5 h-5 text-green-400" />
                <div class="ml-3">
                    <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex">
                <flux:icon name="x-circle" class="w-5 h-5 text-red-400" />
                <div class="ml-3">
                    <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Selection Summary & Quick Actions -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Selection Summary</h2>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ count($selectedProducts) }} products selected
                </span>
                @if(count($selectedProducts) > 0)
                    <flux:button size="sm" variant="outline" wire:click="clearSelection">
                        Clear All
                    </flux:button>
                @endif
            </div>
        </div>

        <!-- Quick Selection Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
            <flux:button 
                variant="outline" 
                icon="exclamation-triangle" 
                wire:click="quickSelectOutOfStock"
                class="cursor-pointer"
            >
                Select Out of Stock
            </flux:button>
            
            <flux:button 
                variant="outline" 
                icon="exclamation-circle" 
                wire:click="quickSelectLowStock"
                class="cursor-pointer"
            >
                Select Low Stock
            </flux:button>
            
            <flux:button 
                variant="outline" 
                icon="check-circle" 
                wire:click="selectAllVisible"
                class="cursor-pointer"
            >
                Select All Visible
            </flux:button>

            @if(count($selectedProducts) > 0)
                <flux:button 
                    variant="primary" 
                    icon="paper-airplane" 
                    wire:click="submitRequest"
                    class="cursor-pointer"
                >
                    Submit Request
                </flux:button>
            @else
                <flux:button 
                    variant="primary" 
                    icon="paper-airplane" 
                    disabled
                    class="cursor-not-allowed"
                >
                    Submit Request
                </flux:button>
            @endif
        </div>

        <!-- Selected Products Preview -->
        @if(count($selectedProducts) > 0)
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-3">
                    Selected Products ({{ count($selectedProducts) }})
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 max-h-32 overflow-y-auto">
                    @foreach($selectedProducts as $productId)
                        @php
                            $product = $products->where('id', $productId)->first();
                        @endphp
                        @if($product)
                        <div class="flex justify-between items-center text-sm bg-white dark:bg-zinc-800 p-2 rounded">
                            <span class="text-blue-700 dark:text-blue-300 truncate">{{ $product->name }}</span>
                            <span class="text-blue-600 dark:text-blue-400 font-medium ml-2">{{ $quantities[$productId] ?? 0 }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filter Products</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <flux:input 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search products..."
                    icon="magnifying-glass"
                    label="Search Products"
                />
            </div>

            <!-- Category Filter -->
            <div>
                <flux:select wire:model.live="categoryFilter" label="Category">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }} ({{ $category->products_count }})
                        </option>
                    @endforeach
                </flux:select>
            </div>

            <!-- Stock Filter -->
            <div>
                <flux:select wire:model.live="stockFilter" label="Stock Level">
                    <option value="all">All Stock Levels</option>
                    <option value="out_of_stock">Out of Stock</option>
                    <option value="low_stock">Low Stock (1-5)</option>
                    <option value="healthy">Healthy Stock (6+)</option>
                </flux:select>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-800">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Products</h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                </span>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-zinc-700">
                @foreach($products as $product)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <input 
                                type="checkbox" 
                                wire:click="toggleProduct({{ $product->id }})"
                                @checked(in_array($product->id, $selectedProducts))
                                class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            >
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</h3>
                                <div class="flex items-center gap-3 mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center gap-1">
                                        <flux:icon name="tag" class="w-4 h-4" />
                                        {{ $product->category ? $product->category->name : 'No Category' }}
                                    </div>
                                    <span>•</span>
                                    <div class="flex items-center gap-1">
                                        <flux:icon name="currency-dollar" class="w-4 h-4" />
                                        ${{ number_format($product->price, 2) }}
                                    </div>
                                    <span>•</span>
                                    <div class="flex items-center gap-1">
                                        <flux:icon name="cube" class="w-4 h-4" />
                                        Current Stock: {{ $product->stock }}
                                    </div>
                                </div>
                                @if($product->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ $product->description }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <!-- Stock Status Badge - Fixed Position -->
                            <div class="flex-shrink-0 pt-1">
                                @if($product->stock == 0)
                                    <span class="inline-flex items-center bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap">
                                        Out of Stock
                                    </span>
                                @elseif($product->stock <= 5)
                                    <span class="inline-flex items-center bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap">
                                        Low Stock
                                    </span>
                                @else
                                    <span class="inline-flex items-center bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap">
                                        In Stock
                                    </span>
                                @endif
                            </div>

                            <!-- Quantity Input Container - Fixed Width -->
                            <div class="min-w-[300px]">
                                @if(in_array($product->id, $selectedProducts))
                                    <div class="flex flex-col gap-2">
                                        <label class="text-sm text-gray-600 dark:text-gray-400 font-medium">Quantity to Order:</label>
                                        <div class="flex items-center gap-2">
                                            <!-- Decrement Button -->
                                            <button 
                                                type="button"
                                                wire:click="decrementQuantity({{ $product->id }})"
                                                class="w-8 h-8 flex items-center justify-center rounded-md border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                                @disabled((($quantities[$product->id] ?? 10) <= 1))
                                                title="Decrease quantity"
                                            >
                                                <flux:icon name="minus" class="w-4 h-4" />
                                            </button>

                                            <!-- Quantity Input -->
                                            <input 
                                                type="number" 
                                                wire:model.blur="quantities.{{ $product->id }}"
                                                min="1"
                                                max="1000"
                                                step="1"
                                                placeholder="1"
                                                class="w-20 px-3 py-2 text-center text-sm border border-gray-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 font-medium"
                                                x-data=""
                                                x-on:keydown.arrow-up.prevent="$wire.call('incrementQuantity', {{ $product->id }})"
                                                x-on:keydown.arrow-down.prevent="$wire.call('decrementQuantity', {{ $product->id }})"
                                            >

                                            <!-- Increment Button -->
                                            <button 
                                                type="button"
                                                wire:click="incrementQuantity({{ $product->id }})"
                                                class="w-8 h-8 flex items-center justify-center rounded-md border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:ring-2 focus:ring-blue-500 transition-colors"
                                                title="Increase quantity"
                                            >
                                                <flux:icon name="plus" class="w-4 h-4" />
                                            </button>
                                        </div>

                                        <!-- Quick Preset Buttons -->
                                        <div class="flex gap-1 flex-wrap">
                                            @php
                                                $currentStock = $product->stock;
                                                $presets = [];
                                                
                                                if ($currentStock == 0) {
                                                    $presets = [10, 25, 50];
                                                } elseif ($currentStock <= 5) {
                                                    $presets = [10, 20, 30];
                                                } else {
                                                    $presets = [5, 10, 20];
                                                }
                                            @endphp

                                            @foreach($presets as $preset)
                                                <button 
                                                    type="button"
                                                    wire:click="setQuantity({{ $product->id }}, {{ $preset }})"
                                                    class="px-2 py-1 text-xs rounded border transition-all duration-200 
                                                        {{ ($quantities[$product->id] ?? 0) == $preset 
                                                            ? 'border-blue-500 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium' 
                                                            : 'border-gray-300 dark:border-zinc-600 bg-gray-50 dark:bg-zinc-700 text-gray-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-600'
                                                        }}"
                                                    title="Set quantity to {{ $preset }}"
                                                >
                                                    {{ $preset }}
                                                </button>
                                            @endforeach
                                        </div>

                                        <!-- Contextual Suggestion -->
                                        @if($product->stock == 0)
                                            <p class="text-xs text-red-600 dark:text-red-400">⚠️ Out of stock - consider ordering 25+ units</p>
                                        @elseif($product->stock <= 5)
                                            <p class="text-xs text-yellow-600 dark:text-yellow-400">⚠️ Low stock - consider ordering 20+ units</p>
                                        @else
                                            <p class="text-xs text-green-600 dark:text-green-400">✓ Stock healthy - order as needed</p>
                                        @endif
                                    </div>
                                @else
                                    <!-- Placeholder to maintain layout consistency -->
                                    <div class="h-6"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                <flux:icon name="magnifying-glass" class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No products found</h3>
                <p>Try adjusting your search criteria or filters.</p>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="flex justify-center">
            {{ $products->links() }}
        </div>
    @endif

    <!-- Bottom Action Bar -->
    @if(count($selectedProducts) > 0)
        <div class="fixed bottom-6 right-6 bg-white dark:bg-zinc-900 rounded-lg shadow-lg border border-gray-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-4">
                <div class="text-sm">
                    <span class="font-medium text-gray-900 dark:text-white">{{ count($selectedProducts) }} products selected</span>
                    <p class="text-gray-500 dark:text-gray-400">Total units: {{ array_sum($quantities) }}</p>
                </div>
                <flux:button 
                    variant="primary" 
                    icon="paper-airplane" 
                    wire:click="submitRequest"
                    class="cursor-pointer"
                >
                    Submit Restock Request
                </flux:button>
            </div>
        </div>
    @endif
</div> 