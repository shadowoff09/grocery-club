<x-layouts.app.header :title="__('Catalog')">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-4">{{ __('Our Products') }}</h1>

            <!-- Search Bar -->
            <form method="GET" action="{{ url()->current() }}" class="relative mb-6">
                <input
                    type="text"
                    name="search"
                    placeholder="Search products..."
                    value="{{ request('search') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-zinc-500 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                />
                @if(request('search'))
                    <a href="{{ route('catalog.index') }}" class="absolute right-3 top-2.5 text-gray-400">
                        <x-lucide-x class="w-6 h-6 text-red-500 cursor-pointer"/>
                    </a>
                @else
                <button type="submit" class="absolute right-3 top-2.5 text-gray-400">
                        <x-lucide-search class="w-6 h-6"/>
                    </button>
                @endif
            </form>
        </div>



        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div
                    class="bg-white dark:bg-black rounded-lg shadow-md overflow-hidden flex flex-col hover:shadow-lg transition-shadow duration-300">
                    @if ($product->photo)
                        <img src="{{ asset('storage/products/' . $product->photo) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover hover:opacity-90 transition-opacity duration-300">
                    @else
                        <div class="w-full h-48 bg-gray-200 dark:bg-zinc-800 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif

                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="text-lg font-semibold mb-2 text-black dark:text-white hover:text-zinc-600 dark:hover:text-zinc-300">{{ $product->name }}</h3>
                        <p class="text-gray-600 dark:text-zinc-400 text-sm mb-4">{{ Str::limit($product->description, 200) }}</p>
                        <div class="mt-auto space-y-3">
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-2xl font-bold text-black dark:text-white">${{ number_format($product->price, 2) }}</span>
                                @if($product->stock <= 5 && $product->stock > 0)
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Only {{ $product->stock }} left</span>
                                @elseif($product->stock === 0)
                                    <span
                                        class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Out of stock</span>
                                @endif
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
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>
</x-layouts.app.header>
