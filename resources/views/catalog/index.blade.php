<x-layouts.app.header :title="__('Catalog')">
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <div class="mb-6 text-sm">
            <a href="{{ route('catalog.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-zinc-800 dark:hover:text-zinc-200">{{ __('Catalog') }}</a>
            @if($activeCategory)
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-800 dark:text-gray-200">{{ $activeCategory->name }}</span>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Categories Sidebar -->
            <div class="md:col-span-1 order-2 md:order-1">
                <div class="sticky top-24 p-5 bg-gray-50 dark:bg-zinc-900 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2 dark:border-zinc-700">{{ __('Categories') }}</h2>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('catalog.index') }}"
                               class="block py-2 px-3 rounded-md transition-colors {{ !request('category') ? 'bg-zinc-200 dark:bg-zinc-800 font-medium text-zinc-900 dark:text-white' : 'text-gray-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                                <span class="flex items-center">
                                    <x-lucide-grid class="w-5 h-5 mr-2" />
                                    {{ __('All Categories') }}
                                </span>
                            </a>
                        </li>
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('catalog.index', ['category' => $category->id]) }}"
                                   class="block py-2 px-3 rounded-md transition-colors {{ request('category') == $category->id ? 'bg-zinc-200 dark:bg-zinc-800 font-medium text-zinc-900 dark:text-white' : 'text-gray-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Products Section -->
            <div class="md:col-span-3 order-1 md:order-2">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <h1 class="text-3xl font-bold">
                        @if($activeCategory)
                            {{ $activeCategory->name }}
                        @else
                            {{ __('Our Products') }}
                        @endif
                    </h1>

                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2 md:mt-0">
                        {{ $products->total() }} {{ Str::plural('product', $products->total()) }} {{ request('category') || request('search') ? 'found' : 'available' }}
                    </div>
                </div>

                <!-- Search Bar -->
                <form method="GET" action="{{ url()->current() }}" class="relative mb-8">
                    <div class="flex shadow-sm">
                        <input
                            type="text"
                            name="search"
                            placeholder="Search products..."
                            value="{{ request('search') }}"
                            class="w-full px-4 py-3 border border-r-0 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-zinc-500 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                        />
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('search'))
                            <a href="{{ route('catalog.index', request()->only('category')) }}"
                               class="px-4 bg-white dark:bg-zinc-800 border border-l-0 flex items-center justify-center dark:border-zinc-700">
                                <x-lucide-x class="w-5 h-5 text-red-500"/>
                            </a>
                        @endif
                        <button type="submit" class="bg-zinc-700 hover:bg-zinc-800 text-white px-5 py-2 rounded-r-lg flex items-center transition-colors">
                            <x-lucide-search class="w-5 h-5"/>
                        </button>
                    </div>
                </form>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($products as $product)
                        <div class="bg-white dark:bg-black rounded-lg overflow-hidden flex flex-col border border-gray-100 dark:border-zinc-800 hover:shadow-md transition-all duration-300 hover:translate-y-[-4px]">
                            <div class="relative">
                                @if ($product->photo)
                                    <img src="{{ asset('storage/products/' . $product->photo) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-52 object-cover">
                                @else
                                    <div class="w-full h-52 bg-gray-200 dark:bg-zinc-800 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Stock Badge Overlay -->
                                @if($product->stock <= 5 && $product->stock > 0)
                                    <span class="absolute top-3 right-3 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full shadow-sm">
                                        Only {{ $product->stock }} left
                                    </span>
                                @elseif($product->stock === 0)
                                    <span class="absolute top-3 right-3 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full shadow-sm">
                                        Out of stock
                                    </span>
                                @endif
                            </div>

                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-lg font-semibold mb-2 text-black dark:text-white hover:text-zinc-600 dark:hover:text-zinc-300">{{ $product->name }}</h3>
                                <p class="text-gray-600 dark:text-zinc-400 text-sm mb-4 flex-grow">{{ Str::limit($product->description, 150) }}</p>

                                <div class="mt-auto space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-2xl font-bold text-black dark:text-white">${{ number_format($product->price, 2) }}</span>
                                    </div>
                                    <div class="flex flex-col space-y-2">
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
                        <div class="col-span-3 bg-white dark:bg-zinc-900 rounded-lg p-10 text-center">
                            <svg class="w-20 h-20 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="text-xl text-gray-500 dark:text-gray-400 mb-4">No products found</p>
                            <p class="text-gray-400 dark:text-gray-500 mb-6">Try adjusting your search or filter criteria</p>
                            @if(request('category') || request('search'))
                                <a href="{{ route('catalog.index') }}"
                                   class="inline-block px-6 py-3 bg-zinc-600 text-white rounded-lg hover:bg-zinc-700 transition-colors">
                                    {{ __('View all products') }}
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app.header>
