<div class="max-w-4xl mx-auto">
    @if($cartItems->isEmpty())
        <div
            class="text-center py-20 bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-800">
            <flux:icon name="shopping-cart"
                       class="w-24 h-24 mx-auto text-zinc-300 dark:text-zinc-700 mb-8 animate-bounce"/>
            <h2 class="text-3xl font-bold text-zinc-800 dark:text-zinc-200 mb-4">Your cart is empty</h2>
            <p class="text-zinc-600 dark:text-zinc-400 mb-8 max-w-md mx-auto">Looks like you haven't added any items to
                your cart yet. Start shopping to fill it up!</p>
            <a href="/catalog"
               class="inline-flex items-center px-6 py-3 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg font-medium transition-colors duration-200">
                <flux:icon name="arrow-left" class="w-5 h-5 mr-2"/>
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
                    <div class="p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <!-- Product Image and Info -->
                        <div class="flex items-start gap-4 w-full sm:w-auto">
                            <div class="flex-shrink-0 bg-zinc-100 dark:bg-zinc-800 rounded-lg overflow-hidden">
                                @if($item['product']->photo)
                                    <img src="{{ asset('storage/products/' . $item['product']->photo) }}"
                                         alt="{{ $item['product']->name }}"
                                         class="object-cover w-16 sm:w-12 h-16 sm:h-12">
                                @else
                                    <div class="w-16 sm:w-12 h-16 sm:h-12 flex items-center justify-center">
                                        <flux:icon name="photo" class="w-6 h-6 text-zinc-400"/>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-0.5">
                                        {{ $item['product']->name }}
                                    </h3>
                                    <button
                                        wire:click="removeFromCart({{ $item['product']->id }})"
                                        class="p-1.5 text-zinc-400 cursor-pointer hover:text-red-500 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors sm:hidden">
                                        <flux:icon name="trash" class="w-4 h-4"/>
                                    </button>
                                </div>
                                <p class="text-sm text-black dark:text-zinc-400">
                                    {{ Str::limit($item['product']->description, 100) }}
                                </p>
                                @if($item['product']->stock < $item['quantity'])
                                    <div class="text-sm text-amber-600 mt-1">
                                        The stock is only {{ $item['product']->stock }}. More
                                        than {{ $item['product']->stock }} will result in a slight delay.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Controls Container -->
                        <div class="flex items-center justify-between gap-4 w-full sm:w-auto mt-4 sm:mt-0">
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
                                <button
                                    wire:click="removeFromCart({{ $item['product']->id }})"
                                    wire:confirm="Are you sure you want to remove this item?"
                                    class="hidden sm:inline-flex cursor-pointer items-center gap-1.5 text-xs text-red-600 hover:text-red-500 font-medium mt-1">
                                    <flux:icon name="trash" class="w-3.5 h-3.5"/>
                                    Remove
                                </button>
                            </div>
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

                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between">
                                <span class="text-zinc-600 dark:text-zinc-400">Shipping</span>
                                @if($shippingCost == 0)
                                    <span class="font-medium text-emerald-600 dark:text-emerald-400">
                                        Free
                                    </span>
                                @else
                                    <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                        ${{ number_format($shippingCost, 2) }}
                                    </span>
                                @endif
                            </div>

                            @if($shippingCost != 0)
                                <div
                                    class="p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-900">
                                    <div class="flex items-center text-sm text-emerald-600 dark:text-emerald-400">
                                        <x-lucide-truck class="w-4 h-4 mr-2"/>
                                        <span>
                                            Add ${{ number_format($minThresholdSoShippingIsFree - $total, 2) }} more to your cart to get free shipping!
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                <span class="font-semibold text-zinc-800 dark:text-zinc-200 text-lg">Total Amount</span>
                                <div class="flex flex-col items-end gap-1">
                                    @if($shippingCost == 0)
                                        <span class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                                            You are qualified for free shipping!
                                        </span>
                                    @endif
                                    <span class="text-3xl font-bold tracking-tight text-indigo-600 dark:text-indigo-400">
                                        ${{ number_format($totalWithShipping, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <a href="{{ route('catalog.index') }}"
                       class="inline-flex items-center px-6 py-3 text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-zinc-100 font-medium
                       transition-colors rounded-lg border border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600">
                        <x-lucide-arrow-left class="w-4 h-4 mr-2"/>
                        Continue Shopping
                    </a>
                    <div class="flex gap-4">
                        <button wire:click="clearCart"
                                class="cursor-pointer inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg
                                transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Clear Cart
                            <x-lucide-trash-2 class="w-4 h-4 ml-2"/>
                        </button>

                        @if(!Auth::check())
                            <a href="{{ route('login') }}" class="inline-flex items-center">
                                <button class="cursor-pointer inline-flex items-center px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg
        transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Proceed to Checkout
                                    <x-lucide-arrow-right class="w-4 h-4 ml-2"/>
                                </button>
                            </a>
                        @else
                            {{--Would go to checkout here WORK IN PROGRESS--}}
                            <a href="{{ route('login') }}" class="inline-flex items-center">
                                <button class="cursor-pointer inline-flex items-center px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg
                                    transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Proceed to Checkout
                                    <x-lucide-arrow-right class="w-4 h-4 ml-2"/>
                                </button>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
