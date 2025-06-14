<div class="min-h-screen">
    <div class="container mx-auto px-2 py-2">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Categories</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your product categories</p>
            </div>
            <div class="flex items-center gap-3">
                <flux:button class="cursor-pointer" variant="primary" icon="plus" href="{{ route('board.catalog.categories.create') }}">
                    New Category
                </flux:button>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="mb-6 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <flux:input
                        type="search"
                        name="search"
                        id="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search by category name..."
                        icon="magnifying-glass"
                        label="Search"
                    />
                </div>

                <!-- Status Filter -->
                <div>
                    <flux:select
                        name="status"
                        id="status"
                        wire:model.live="status"
                        label="Status"
                    >
                        <option value="">All Categories</option>
                        <option value="active">Active</option>
                        <option value="deleted">Deleted</option>
                    </flux:select>
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($categories as $category)
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 overflow-hidden group hover:shadow-lg transition-all duration-300 hover:translate-y-[-2px]">
                    <!-- Image -->
                    <div class="relative aspect-[4/3] overflow-hidden bg-gray-100 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-t-xl">
                        @if($category->image)
                            <img src="{{ asset('storage/categories/' . $category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 dark:text-gray-600">
                                <x-lucide-grid class="w-12 h-12 mb-2" />
                                <span class="text-sm">No image</span>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        @if($category->trashed())
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                                    <x-lucide-archive class="w-3.5 h-3.5" />
                                    Deleted
                                </span>
                            </div>
                        @endif

                        <!-- Products Count Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-white/90 dark:bg-zinc-800/90 text-gray-800 dark:text-white backdrop-blur-sm">
                                <x-lucide-package class="w-3.5 h-3.5" />
                                {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                {{ $category->name }}
                            </h2>
                            @if($category->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">
                                    {{ $category->description }}
                                </p>
                            @endif
                        </div>

                        <div class="flex justify-end">
                            <flux:button 
                                variant="primary" 
                                icon="arrow-right" 
                                class="w-full cursor-pointer"
                                wire:navigate="{{ route('board.catalog.categories.show', $category->id) }}"
                                href="{{ route('board.catalog.categories.show', $category->id) }}"
                            >
                                View Details
                            </flux:button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800">
                        <x-lucide-grid class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No categories yet</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Get started by creating your first category.</p>
                        <flux:button class="cursor-pointer" variant="primary" icon="plus" href="{{ route('board.catalog.categories.create') }}">
                            Create Category
                        </flux:button>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div> 