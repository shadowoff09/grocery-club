<div>
    @if($cartItems->isEmpty())
        <div class="text-center py-6">
            <p class="text-zinc-600 dark:text-zinc-400">{{ __('Your cart is empty') }}</p>
        </div>
    @else
        <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
            @foreach($cartItems as $item)
                <div class="p-4 flex flex-col sm:flex-row items-start gap-4">
                    <!-- Product Image and Info -->
                    <div class="flex items-start gap-4 w-full sm:w-auto">
                        <div class="flex-shrink-0 bg-zinc-100 dark:bg-zinc-800 rounded-lg overflow-hidden w-16 sm:w-12 h-16 sm:h-12 p-0">
                            @if($item['product']->photo)
                                <img src="{{ asset('storage/products/' . $item['product']->photo) }}"
                                     alt="{{ $item['product']->name }}"
                                     class="object-cover w-full h-full">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <flux:icon name="photo" class="w-6 h-6 text-zinc-400"/>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <h3 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-0.5">
                                    {{ $item['product']->name }}
                                </h3>
                            </div>
                            <p class="text-sm text-black dark:text-zinc-400">
                                {{ Str::limit($item['product']->description, 100) }}
                            </p>
                        </div>
                    </div>

                    <!-- Controls Container -->
                    <div class="flex items-center gap-6 w-full sm:w-auto mt-4 sm:mt-0 sm:ml-auto">
                        <!-- Quantity Controls -->
                        <div class="flex items-center rounded-xl overflow-hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
                            <span class="w-10 h-10 flex items-center justify-center text-zinc-700 dark:text-zinc-200">
                                ×
                            </span>
                            <span class="w-12 text-center text-lg font-semibold text-zinc-800 dark:text-zinc-100">
                                {{ $item['quantity'] }}
                            </span>
                        </div>

                        <!-- Price -->
                        <div class="text-right flex flex-col min-w-[120px]">
                            <div class="text-base font-semibold text-zinc-900 dark:text-zinc-100">
                                @if($item['showDiscount'])
                                    <div class="flex flex-col">
                                        <div class="flex items-center justify-end gap-2">
                                            <span class="line-through text-zinc-500 dark:text-zinc-400 text-sm font-normal">
                                                ${{ number_format($item['originalTotal'], 2) }}
                                            </span>
                                            <span class="text-emerald-600 dark:text-emerald-400">
                                                ${{ number_format($item['total'], 2) }}
                                            </span>
                                        </div>
                                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                                            {{ number_format($item['discount'], 0) }}% off for {{ $item['quantity'] }}+ items
                                        </span>
                                    </div>
                                @else
                                    <div class="text-right mb-1">${{ number_format($item['total'], 2) }}</div>
                                @endif
                                <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5 mb-2">
                                    ${{ number_format($item['unitPrice'], 2) }} each
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div> 