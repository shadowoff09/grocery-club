<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Items -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Order Items') }}
                        ({{ count($cartItems) }})</h2>
                </div>

                <!-- Cart Items Section -->
                <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @foreach ($cartItems as $item)
                        <div
                            class="p-5 flex flex-col sm:flex-row items-start gap-5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <!-- Product Image and Info -->
                            <div class="flex items-start gap-4 w-full sm:w-auto">
                                <div
                                    class="flex-shrink-0 bg-zinc-100 dark:bg-zinc-800 rounded-lg overflow-hidden w-20 h-20 p-0 shadow-sm">
                                    @if ($item['product']->photo)
                                        <img src="{{ asset('storage/products/' . $item['product']->photo) }}"
                                             alt="{{ $item['product']->name }}" class="object-cover w-full h-full">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <flux:icon name="photo" class="w-8 h-8 text-zinc-400"/>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 mb-1">
                                            {{ $item['product']->name }}
                                        </h3>
                                    </div>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                                        {{ Str::limit($item['product']->description, 100) }}
                                    </p>
                                    <div class="flex items-center text-sm text-zinc-500 dark:text-zinc-400">
                                        <x-lucide-package class="w-4 h-4 mr-1.5"/>
                                        <span>Quantity: <span class="font-medium">{{ $item['quantity'] }}</span></span>
                                    </div>

                                    <div class="min-h-[24px] mt-2">
                                        @if ($item['product']->stock < $item['quantity'])
                                            @if ($item['product']->stock > 0)
                                                <div
                                                    class="text-sm text-amber-600 dark:text-amber-500 flex items-center">
                                                    <flux:icon name="exclamation-triangle" class="w-4 h-4 mr-1.5"/>
                                                    <span>The stock is only {{ $item['product']->stock }}. More than
                                                        {{ $item['product']->stock }} will result in a slight
                                                        delay.</span>
                                                </div>
                                            @else
                                                <div class="text-sm text-red-600 dark:text-red-400 flex items-center">
                                                    <flux:icon name="x-circle" class="w-4 h-4 mr-1.5"/>
                                                    <span>The product is out of stock, so it will result in a slight
                                                        delay.</span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="text-right flex flex-col min-w-[140px] mt-4 sm:mt-0 sm:ml-auto">
                                <div class="text-base font-semibold text-zinc-900 dark:text-zinc-100">
                                    @if ($item['showDiscount'])
                                        <div class="flex flex-col">
                                            <div class="flex items-center justify-end gap-2">
                                                <span
                                                    class="line-through text-zinc-500 dark:text-zinc-400 text-sm font-normal">
                                                    ${{ number_format($item['originalTotal'], 2) }}
                                                </span>
                                                <span class="text-emerald-600 dark:text-emerald-400 font-semibold">
                                                    ${{ number_format($item['total'], 2) }}
                                                </span>
                                            </div>
                                            <div class="inline-flex items-center justify-end mt-1">
                                                <span
                                                    class="text-xs px-2 py-0.5 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full flex items-center">
                                                    <flux:icon name="tag" class="w-3 h-3 mr-1"/>
                                                    {{ number_format($item['discount'], 0) }}% off for
                                                    {{ $item['quantity'] }}+ items
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-right text-lg">${{ number_format($item['total'], 2) }}</div>
                                    @endif
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-1.5">
                                        <div class="flex items-center justify-end">
                                            <flux:icon name="credit-card" class="w-3.5 h-3.5 mr-1"/>
                                            ${{ number_format($item['unitPrice'], 2) }} each
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary & Payment -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Order Summary -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-6 sticky top-6">
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-6">{{ __('Order Summary') }}</h2>

                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-600 dark:text-zinc-400">Subtotal</span>
                        <span class="font-medium text-zinc-900 dark:text-zinc-100">
                            ${{ number_format($total + $totalDiscount, 2) }}
                        </span>
                    </div>

                    @if ($totalDiscount > 0)
                        <div class="flex justify-between items-center py-1">
                            <span class="text-zinc-600 dark:text-zinc-400">Quantity Discounts</span>
                            <span class="font-medium text-emerald-600 dark:text-emerald-400 flex items-center">
                                <flux:icon name="tag" class="w-4 h-4 mr-1"/>
                                -${{ number_format($totalDiscount, 2) }}
                            </span>
                        </div>
                    @endif

                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between">
                            <span class="text-zinc-600 dark:text-zinc-400">Shipping</span>
                            @if ($shippingCost == 0)
                                <span class="font-medium text-emerald-600 dark:text-emerald-400 flex items-center">
                                    <flux:icon name="truck" class="w-4 h-4 mr-1"/>
                                    Free
                                </span>
                            @else
                                <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                    ${{ number_format($shippingCost, 2) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 mt-4">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                            <span class="font-semibold text-zinc-800 dark:text-zinc-200">Total Amount</span>
                            <div class="flex flex-col items-end gap-1">
                                @if ($shippingCost == 0)
                                    <span
                                        class="text-sm text-emerald-600 dark:text-emerald-400 font-medium flex items-center">
                                        <flux:icon name="check-circle" class="w-4 h-4 mr-1"/>
                                        Free shipping included!
                                    </span>
                                @endif
                                <span class="text-2xl font-bold tracking-tight text-indigo-600 dark:text-indigo-400">
                                    ${{ number_format($totalWithShipping, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex mt-6">
                    <flux:button icon="arrow-left" href="{{ route('cart.index') }}" class="cursor-pointer">
                        {{ __('Back to Cart') }}
                    </flux:button>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-6 sticky top-6">
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Payment') }}</h2>

                <div class="space-y-5">
                    <div
                        class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800 flex items-center">
                        <flux:icon name="credit-card" class="w-5 h-5 text-blue-500 mr-3"/>
                        <div>
                            <span class="font-medium text-blue-700 dark:text-blue-300">Card Balance:</span>
                            <span
                                class="font-semibold text-zinc-900 dark:text-zinc-100">${{ number_format($cardBalance, 2) }}</span>
                        </div>
                    </div>

                    @if ($totalWithShipping > $cardBalance)
                        <div
                            class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-800">
                            <div class="flex items-center mb-2">
                                <flux:icon name="exclamation-triangle" class="w-5 h-5 text-red-500 mr-2"/>
                                <span class="text-red-700 dark:text-red-300 font-medium">Insufficient Balance</span>
                            </div>
                            <p class="text-red-600 dark:text-red-400 text-sm mb-3">
                                Your card balance is insufficient for this purchase. You need
                                ${{ number_format($totalWithShipping - $cardBalance, 2) }} more.
                            </p>
                            <a href="{{ route('balance.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-200 dark:hover:bg-red-700 transition-colors cursor-pointer">
                                Recharge Card
                            </a>
                        </div>
                    @else
                        <flux:button 
                            wire:click="processPayment"
                            wire:loading.attr="disabled"
                            icon="credit-card"
                            class="w-full cursor-pointer !bg-indigo-600 hover:!bg-indigo-700 text-white font-medium py-4 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                            loading-text="Processing..."
                        >
                            Complete Purchase
                        </flux:button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
