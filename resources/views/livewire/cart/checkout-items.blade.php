<div>
    @if($cartItems->isEmpty())
        <div class="text-center py-6">
            <p class="text-zinc-600 dark:text-zinc-400">{{ __('Your cart is empty') }}</p>
        </div>
    @else
        <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
            @foreach($cartItems as $item)
                <div class="py-4 flex items-start gap-4">
                    <!-- Product Image -->
                    <div class="flex-shrink-0 bg-zinc-100 dark:bg-zinc-800 rounded-lg overflow-hidden">
                        @if($item['product']->photo)
                            <img src="{{ asset('storage/products/' . $item['product']->photo) }}"
                                 alt="{{ $item['product']->name }}"
                                 class="object-cover w-16 h-16">
                        @else
                            <div class="w-16 h-16 flex items-center justify-center">
                                <flux:icon name="photo" class="w-6 h-6 text-zinc-400"/>
                            </div>
                        @endif
                    </div>

                    <!-- Product Details -->
                    <div class="flex-1">
                        <h3 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-1">
                            {{ $item['product']->name }}
                        </h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                            {{ Str::limit($item['product']->description, 60) }}
                        </p>
                        
                        <div class="flex justify-between items-end">
                            <div class="text-sm text-zinc-500 dark:text-zinc-500">
                                {{ $item['quantity'] }} x ${{ number_format($item['unitPrice'], 2) }}
                            </div>
                            
                            <div class="text-right">
                                @if($item['showDiscount'])
                                    <div class="flex flex-col items-end">
                                        <div class="flex items-center gap-2">
                                            <span class="line-through text-zinc-500 dark:text-zinc-400 text-sm">
                                                ${{ number_format($item['originalTotal'], 2) }}
                                            </span>
                                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">
                                                ${{ number_format($item['total'], 2) }}
                                            </span>
                                        </div>
                                        <span class="text-xs text-emerald-600 dark:text-emerald-400">
                                            {{ number_format($item['discount'], 0) }}% off
                                        </span>
                                    </div>
                                @else
                                    <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                        ${{ number_format($item['total'], 2) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div> 