<x-layouts.app.header :title="__('Catalog')">
    <div class="container mx-auto px-4 py-12">
        <!-- Breadcrumbs -->
        <div class="mb-8 text-sm">
            <a href="{{ route('catalog.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-zinc-800 dark:hover:text-zinc-200 transition-colors">{{ __('Catalog') }}</a>
            @if($activeCategory)
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-800 dark:text-gray-200">{{ $activeCategory->name }}</span>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <!-- Categories Sidebar -->
            <div class="md:col-span-1 order-2 md:order-1">
                <div class="sticky top-24 p-6 bg-white dark:bg-zinc-900 rounded-xl shadow-lg border border-gray-100 dark:border-zinc-800">
                    <h2 class="text-xl font-semibold mb-6 border-b pb-3 dark:border-zinc-700">{{ __('Categories') }}</h2>
                    <ul class="space-y-2.5">
                        <li>
                            <a href="{{ route('catalog.index') }}"
                               class="block py-2.5 px-4 rounded-lg transition-all duration-200 {{ !request('category') ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-900 dark:text-emerald-100 font-medium' : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                                <span class="flex items-center">
                                    <x-lucide-grid class="w-5 h-5 mr-3" />
                                    {{ __('All Categories') }}
                                </span>
                            </a>
                        </li>
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('catalog.index', ['category' => $category->id]) }}"
                                   class="block py-2.5 px-4 rounded-lg transition-all duration-200 {{ request('category') == $category->id ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-900 dark:text-emerald-100 font-medium' : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Products Section -->
            <div class="md:col-span-3 order-1 md:order-2">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                    <div class="flex items-center gap-5">
                        @if($activeCategory)
                            @if($activeCategory->image)
                                <img src="{{ asset('storage/categories/' . $activeCategory->image) }}"
                                     alt="{{ $activeCategory->name }}"
                                     class="w-20 h-20 object-cover rounded-xl shadow-md">
                            @else
                                <div
                                    class="w-20 h-20 bg-gray-100 dark:bg-zinc-800 rounded-xl shadow-md flex items-center justify-center">
                                    <x-lucide-folder class="w-10 h-10 text-gray-400"/>
                                </div>
                            @endif
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Category</span>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $activeCategory->name }}</h1>
                            </div>
                        @else
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Browse</span>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Our Products') }}</h1>
                            </div>
                        @endif
                    </div>

                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-4 md:mt-0 flex items-center gap-2 bg-gray-50 dark:bg-zinc-800/50 px-4 py-2 rounded-full">
                        <x-lucide-package class="w-4 h-4"/>
                        <span>
                            {{ $products->total() }} {{ Str::plural('product', $products->total()) }}
                            {{ request('category') || request('search') ? 'found' : 'available' }}
                        </span>
                    </div>
                </div>

                <!-- Search and Sort Bar -->
                <div class="mb-10 flex flex-col sm:flex-row gap-4">
                    <!-- Search Bar -->
                    <form method="GET" action="{{ url()->current() }}" class="relative flex-grow">
                        <div class="flex shadow-lg rounded-xl">
                            <input
                                type="text"
                                name="search"
                                placeholder="Search products..."
                                value="{{ request('search') }}"
                                class="w-full px-6 py-4 border border-r-0 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-zinc-800 dark:text-white dark:border-zinc-700 text-base"
                            />
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            @if(request('search'))
                                <a href="{{ route('catalog.index', request()->except('search')) }}"
                                   class="px-4 bg-white dark:bg-zinc-800 border border-l-0 flex items-center justify-center dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                                    <x-lucide-x class="w-5 h-5 text-red-500"/>
                                </a>
                            @endif
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-2 rounded-r-xl flex items-center transition-colors">
                                <x-lucide-search class="w-5 h-5"/>
                            </button>
                        </div>
                    </form>

                    <!-- Sort Dropdown -->
                    <div class="w-full sm:w-auto">
                        <form method="GET" action="{{ url()->current() }}" class="flex items-center">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            <div class="relative shadow-lg rounded-xl overflow-hidden">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-500 dark:text-gray-400">
                                    <x-lucide-filter class="w-5 h-5" />
                                </div>
                                <select 
                                    name="sort" 
                                    onchange="this.form.submit()" 
                                    class="w-full h-full py-4 pl-12 pr-10 bg-transparent border border-gray-100 dark:border-zinc-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-gray-700 dark:text-white appearance-none cursor-pointer"
                                >
                                    <option value="name" {{ $sort == 'name' ? 'selected' : '' }}>{{ __('Name (A-Z)') }}</option>
                                    <option value="price_low" {{ $sort == 'price_low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                                    <option value="price_high" {{ $sort == 'price_high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-emerald-600 dark:text-emerald-400">
                                    <x-lucide-chevron-down class="w-5 h-5" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($products as $product)
                        <div wire:loading.remove class="bg-white dark:bg-black rounded-xl overflow-hidden flex flex-col border border-gray-100 dark:border-zinc-800 hover:shadow-xl transition-all duration-300 hover:translate-y-[-4px] group">
                            <div class="relative">
                                @if ($product->photo)
                                    <img src="{{ asset('storage/products/' . $product->photo) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-60 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-60 bg-gray-100 dark:bg-zinc-800 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Stock Badge Overlay -->
                                @if($product->stock <= 5 && $product->stock > 0)
                                    <span class="absolute top-4 right-4 text-xs bg-yellow-100 text-yellow-800 px-3 py-1.5 rounded-full shadow-lg font-medium">
                                        Only {{ $product->stock }} left
                                    </span>
                                @elseif($product->stock === 0)
                                    <span class="absolute top-4 right-4 text-xs bg-red-100 text-red-800 px-3 py-1.5 rounded-full shadow-lg font-medium">
                                        Out of stock
                                    </span>
                                @endif

                                <!-- Category Badge -->
                                <span class="absolute top-4 left-4 text-xs bg-white/90 dark:bg-zinc-800/90 text-gray-800 dark:text-white px-3 py-1.5 rounded-full shadow-lg backdrop-blur-sm">
                                    {{ $product->category->name }}
                                </span>
                            </div>

                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-xl font-semibold mb-3 text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ $product->name }}</h3>
                                <p class="text-gray-600 dark:text-zinc-400 text-sm mb-6 flex-grow leading-relaxed">{{ Str::limit($product->description, 150) }}</p>

                                <div class="mt-auto space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($product->price, 2) }}</span>
                                        
                                        <!-- Discount Badge -->
                                        @if($product->discount > 0 && $product->discount_min_qty > 0)
                                            <span class="text-xs bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300 px-3 py-1.5 rounded-full shadow-sm font-medium flex items-center gap-1">
                                                <x-lucide-tag class="w-3.5 h-3.5" />
                                                {{ number_format($product->discount, 0) }}% off {{ $product->discount_min_qty }}+ items
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex flex-col space-y-3">
                                        <div class="flex justify-end items-center">
                                            <livewire:add-to-cart :product-id="$product->id"/>
                                        </div>
                                        <div class="min-h-[24px]">
                                            <livewire:message-display :product-id="$product->id"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div wire:loading.remove class="col-span-3 bg-white dark:bg-zinc-900 rounded-xl p-12 text-center">
                            <svg class="w-24 h-24 text-gray-200 dark:text-gray-700 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="text-2xl text-gray-500 dark:text-gray-400 mb-4 font-medium">No products found</p>
                            <p class="text-gray-400 dark:text-gray-500 mb-8">Try adjusting your search or filter criteria</p>
                            @if(request('category') || request('search'))
                                <a href="{{ route('catalog.index') }}"
                                   class="inline-block px-8 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors font-medium">
                                    {{ __('View all products') }}
                                </a>
                            @endif
                        </div>
                    @endforelse

                    @for($i = 0; $i < 6; $i++)
                        <div wire:loading class="bg-white dark:bg-black rounded-xl overflow-hidden flex flex-col border border-gray-100 dark:border-zinc-800 animate-pulse">
                            <!-- Image Skeleton -->
                            <div class="relative">
                                <div class="w-full h-60 bg-gray-200 dark:bg-zinc-800"></div>
                                <!-- Category Badge Skeleton -->
                                <div class="absolute top-4 left-4 h-7 w-24 bg-gray-200 dark:bg-zinc-700 rounded-full"></div>
                            </div>
                            
                            <!-- Content Skeleton -->
                            <div class="p-6 flex flex-col flex-grow space-y-4">
                                <!-- Title Skeleton -->
                                <div class="h-7 bg-gray-200 dark:bg-zinc-800 rounded-md w-3/4"></div>
                                
                                <!-- Description Skeleton -->
                                <div class="space-y-2">
                                    <div class="h-4 bg-gray-200 dark:bg-zinc-800 rounded w-full"></div>
                                    <div class="h-4 bg-gray-200 dark:bg-zinc-800 rounded w-5/6"></div>
                                </div>
                                
                                <!-- Price and Button Skeleton -->
                                <div class="mt-auto space-y-4">
                                    <div class="h-8 bg-gray-200 dark:bg-zinc-800 rounded w-1/3"></div>
                                    <div class="h-10 bg-gray-200 dark:bg-zinc-800 rounded"></div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Pagination -->
                <div class="mt-12" wire:loading.class="opacity-50">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app.header>