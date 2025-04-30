<div class="max-w-4xl mx-auto">
    @if($cartItems->isEmpty())
        <div class="text-center py-16 bg-white dark:bg-zinc-900 rounded-xl shadow-sm">
            <flux:icon name="shopping-cart" class="w-20 h-20 mx-auto text-zinc-300 dark:text-zinc-700 mb-6"/>
            <h2 class="text-2xl font-semibold text-zinc-600 dark:text-zinc-400 mb-4">Your cart is empty</h2>
            <a href="/catalog" class="inline-flex items-center text-indigo-600 hover:text-indigo-500 font-medium">
                <flux:icon name="arrow-left" class="w-4 h-4 mr-2"/>
                Continue Shopping
            </a>
        </div>
    @else
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm overflow-hidden">
            <!-- Cart Header -->
            <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800">
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Shopping Cart
                    ({{ $cartItems->count() }} items)</h2>
            </div>

            <!-- Cart Items -->
            <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @foreach($cartItems as $item)
                    <div class="p-4 flex items-center gap-4">
                        <!-- Product Image -->
                        <div class="flex-shrink-0 bg-zinc-100 dark:bg-zinc-800 rounded-lg overflow-hidden">
                            @if($item['product']->photo)
                                <img src="{{ asset('storage/products/' . $item['product']->photo) }}"
                                     alt="{{ $item['product']->name }}"
                                     class="object-cover w-12 h-12">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <flux:icon name="photo" class="w-6 h-6 text-zinc-400"/>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-0.5">
                                {{ $item['product']->name }}
                            </h3>
                            <p class="text-sm text-black dark:text-zinc-400">
                                {{ Str::limit($item['product']->description, 200) }}
                            </p>
                            @if($item['product']->stock < $item['quantity'])
                                <div class="text-sm text-amber-600">
                                    The stock is only {{ $item['product']->stock }}. More
                                    than {{ $item['product']->stock }} will result in a slight delay.
                                </div>
                            @endif
                        </div>

                        <!-- Quantity Controls -->


                        <div
                            class="flex items-center rounded-xl overflow-hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
                            <button
                                wire:click="updateQuantity({{ $item['product']->id }}, 'decrease')"
                                class="w-10 h-10 flex items-center justify-center text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition duration-150 cursor-pointer">
                                <flux:icon name="minus" class="w-3.5 h-3.5"/>
                            </button>
                            <span class="w-12 text-center text-lg font-semibold text-zinc-800 dark:text-zinc-100">
                                    {{ $item['quantity'] }}
                            </span>
                            <button
                                wire:click="updateQuantity({{ $item['product']->id }}, 'increase')"
                                class="w-10 h-10 flex items-center justify-center text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition duration-150 cursor-pointer">
                                <flux:icon name="plus" class="w-3.5 h-3.5"/>
                            </button>
                        </div>

                        <!-- Price -->
                        <div class="text-right min-w-[90px]">
                            <div class="text-base font-semibold text-zinc-900 dark:text-zinc-100">
                                ${{ number_format($item['total'], 2) }}
                                <p>
                                        <span class="text-sm text-zinc-500 dark:text-zinc-400">
                                            ${{ number_format($item['product']->price, 2) }} each
                                        </span>
                                </p>
                            </div>
                            <button wire:click="removeFromCart({{ $item['product']->id }})"
                                    class="text-xs text-red-600 hover:text-red-500 font-medium">
                                Remove
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Footer -->

            <div class="dark:bg-zinc-800/50 px-6 py-4">
                <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Order Summary</h3>
                    </div>

                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-zinc-600 dark:text-zinc-400">Subtotal</span>
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                ${{ number_format($total, 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-zinc-600 dark:text-zinc-400">Shipping</span>
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                ${{ number_format($shippingCost, 2) }}
                            </span>
                        </div>

                        <div
                            class="border-t border-zinc-200 dark:border-zinc-700 pt-4 flex justify-between items-center text-base">
                            <span class="font-semibold text-zinc-800 dark:text-zinc-200">Total</span>
                            <span class="text-xl font-bold text-zinc-900 dark:text-zinc-100">
                                ${{ number_format($totalWithShipping, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-4">
                    <a href="/catalog"
                       class="px-6 py-2 text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-zinc-100 font-medium
                       transition-colors cursor-pointer">
                        Continue Shopping
                    </a>
                    <button wire:click="clearCart" class="flex items-center justify-center px-8 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg
                    transition-colors cursor-pointer">
                        Clear Cart
                        <x-lucide-trash-2 class="w-4 h-4 ml-2"/>
                    </button>
                    @if(!Auth::check())
                        <a href="{{ route('login') }}">
                            <button class="flex items-center justify-center px-8 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg
                        transition-colors cursor-pointer">
                                Checkout
                                <x-lucide-arrow-right class="w-4 h-4 ml-2"/>
                            </button>
                        </a>
                    @else
                        <button class="flex items-center justify-center px-8 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg
                        transition-colors cursor-pointer">
                            Checkout
                            <x-lucide-arrow-right class="w-4 h-4 ml-2"/>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
