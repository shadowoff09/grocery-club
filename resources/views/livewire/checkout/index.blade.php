<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Items -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Order Items') }}</h2>
                
                <!-- Cart Items Section -->
                <div class="space-y-6">
                    @foreach ($cartItems as $item)
                        <div class="flex items-center space-x-4 py-4 border-b border-zinc-100 dark:border-zinc-800 last:border-0">
                            <div class="flex-shrink-0 w-20 h-20 bg-zinc-100 dark:bg-zinc-800 rounded-md overflow-hidden">
                                @if($item['product']->image_url)
                                    <img src="{{ $item['product']->image_url }}" 
                                         alt="{{ $item['product']->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-zinc-400">
                                        <flux:icon name="photo" class="w-8 h-8" />
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $item['product']->name }}
                                </h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                    Quantity: {{ $item['quantity'] }}
                                </p>
                            </div>
                            
                            <div class="text-right">
                                @if ($item['showDiscount'])
                                    <div class="flex flex-col items-end">
                                        <span class="text-sm line-through text-zinc-500 dark:text-zinc-400">
                                            ${{ number_format($item['originalTotal'], 2) }}
                                        </span>
                                        <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                            ${{ number_format($item['total'], 2) }}
                                        </span>
                                        <span class="text-xs px-1.5 py-0.5 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full">
                                            -{{ $item['discount'] }}%
                                        </span>
                                    </div>
                                @else
                                    <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                        ${{ number_format($item['total'], 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Order Summary & Payment -->
        <div class="lg:col-span-1 space-y-4">
            <!-- Order Summary -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-6 sticky top-6">
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Order Summary') }}</h2>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-600 dark:text-zinc-400">Subtotal</span>
                        <span class="font-medium text-zinc-900 dark:text-zinc-100">
                            ${{ number_format($total + $totalDiscount, 2) }}
                        </span>
                    </div>

                    @if($totalDiscount > 0)
                    <div class="flex justify-between items-center">
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
                            @if($shippingCost == 0)
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
                                @if($shippingCost == 0)
                                    <span class="text-sm text-emerald-600 dark:text-emerald-400 font-medium flex items-center">
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
                    <a href="{{ route('cart.index') }}" 
                       class="inline-flex items-center px-6 py-3 text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-zinc-100 font-medium
                       transition-colors rounded-lg border border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600">
                        <x-lucide-arrow-left class="w-4 h-4 mr-2"/>
                        {{ __('Back to Cart') }}
                    </a>
                </div>
            </div>
            
            <!-- Payment Section -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-6 sticky top-6">
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Payment') }}</h2>

                <div class="space-y-5">
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800 flex items-center">
                        <flux:icon name="credit-card" class="w-5 h-5 text-blue-500 mr-3"/>
                        <div>
                            <span class="font-medium text-blue-700 dark:text-blue-300">Card Balance:</span>
                            <span class="font-semibold text-zinc-900 dark:text-zinc-100">${{ number_format($cardBalance, 2) }}</span>
                        </div>
                    </div>
                    
                    <button wire:click="processPayment" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-4 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2">
                        <flux:icon name="credit-card" class="w-5 h-5"/>
                        <span>Complete Purchase</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 