<x-layouts.app.header :title="__('Catalog')">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">{{ __('Our Products') }}</h1>

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
                                <button class="bg-zinc-900 text-white px-4 py-2 rounded hover:bg-zinc-700 cursor-pointer">
                                    Add to Cart
                                </button>
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
    {{-- Para poder aceder ás imagens da base de dados, foi necessário criar um link para o storage onde elas estão
     guardadas [storage/app/public/products/] (não acessíveis ao browser). O comando é: php artisan storage:link --}}
</x-layouts.app.header>
