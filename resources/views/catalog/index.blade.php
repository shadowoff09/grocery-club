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
                <button type="submit" class="absolute right-3 top-2.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </form>
        </div>



        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="bg-white dark:bg-black rounded-lg shadow-md overflow-hidden flex flex-col">
                    @if ($product->photo)
                        <img src="{{ asset('storage/products/' . $product->photo) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 dark:bg-black-200 flex items-center justify-center">
                            <span class="text-gray-400">No image</span>
                        </div>
                    @endif

                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="text-lg font-semibold mb-2 text-black dark:text-white">{{ $product->name }}</h3>
                        <p class="text-gray-600 dark:text-white text-sm mb-4">{{ Str::limit($product->description, 200) }}</p>
                        <div class="mt-auto">
                            <span class="text-xl font-bold block mb-3 text-black dark:text-white">${{ number_format($product->price, 2) }}</span>
                            <div class="flex justify-end">
                                <div class="w-full">
                                    <div class="flex justify-end items-center mb-1">
                                        <livewire:add-to-cart :product-id="$product->id" />
                                    </div>
                                    <div class="min-h-[24px]">
                                        <livewire:message-display :product-id="$product->id" />
                                    </div>
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
