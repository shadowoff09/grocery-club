<x-layouts.app.header :title="__('Order Confirmation')">
    <div class="container mx-auto px-4 py-8">




        @if (isset($order))

            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full mb-4">
                    <flux:icon name="check" class="w-8 h-8 text-emerald-600 dark:text-emerald-400" />
                </div>

                <h1 class="text-3xl font-bold text-black dark:text-white mb-2">
                    {{ __('Order Confirmed!') }}
                </h1>

                <p class="text-zinc-600 dark:text-zinc-400">
                    {{ __('Thank you for your purchase! Your order has been successfully placed.') }}
                </p>
            </div>

            <!-- Email Confirmation Notice -->
            <div class="my-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                <div class="flex items-start space-x-3">
                    <flux:icon name="envelope" class="w-6 h-6 text-blue-600 dark:text-blue-400 mt-0.5" />
                    <div>
                        <h3 class="font-medium text-blue-900 dark:text-blue-100 mb-1">
                            {{ __('Confirmation Email Sent') }}
                        </h3>
                        <p class="text-blue-700 dark:text-blue-300 text-sm">
                            {{ __('A detailed confirmation email has been sent to your registered email address. Please check your inbox and spam folder if you don\'t see it within a few minutes.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm overflow-hidden">
                        <!-- Order Header -->
                        <div
                            class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800 flex flex-wrap justify-between items-center gap-4">
                            <div>
                                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                    Order #{{ $order->id }}
                                </h2>
                                <div class="flex items-center gap-3 mt-1 text-sm">
                                    <span class="text-zinc-500 dark:text-zinc-400 font-medium">
                                        <time datetime="{{ $order->created_at->toIso8601String() }}"
                                            class="whitespace-nowrap">
                                            {{ $order->created_at->format('d/m/Y') }}
                                            <span
                                                class="text-zinc-400 dark:text-zinc-500 font-normal">({{ $order->created_at->diffForHumans() }})</span>
                                        </time>
                                    </span>
                                    <span class="text-zinc-300 dark:text-zinc-600">•</span>
                                    <span class="text-zinc-500 dark:text-zinc-400">{{ $order->items->count() }}
                                        {{ Str::plural('item', $order->items->count()) }}</span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="mr-4 text-right">
                                    <div class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                        €{{ number_format($order->total, 2) }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                        Items: €{{ number_format($order->total_items, 2) }} +
                                        Shipping: €{{ number_format($order->shipping_cost, 2) }}
                                    </div>
                                </div>

                                @if ($order->status === 'pending')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                        <svg class="mr-1.5 h-2 w-2 text-amber-400" fill="currentColor"
                                            viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Processing
                                    </span>
                                @elseif($order->status === 'completed')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                        <svg class="mr-1.5 h-2 w-2 text-emerald-400" fill="currentColor"
                                            viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Completed
                                    </span>
                                @elseif($order->status === 'canceled')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        <svg class="mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Canceled
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200">
                                        <svg class="mr-1.5 h-2 w-2 text-zinc-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Order Items -->
                        @if ($order->items && $order->items->count() > 0)
                            <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                @foreach ($order->items as $item)
                                    <div
                                        class="p-5 flex items-start gap-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                        <!-- Product Image -->
                                        <div
                                            class="flex-shrink-0 bg-zinc-100 dark:bg-zinc-800 rounded-lg overflow-hidden w-16 h-16 p-0 shadow-sm">
                                            @if ($item->product && $item->product->photo)
                                                <img src="{{ asset('storage/products/' . $item->product->photo) }}"
                                                    alt="{{ $item->product->name }}"
                                                    class="object-cover w-full h-full">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-6 h-6 text-zinc-400" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Info -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 mb-1">
                                                {{ $item->product->name ?? __('Product not available') }}
                                            </h3>
                                            @if ($item->product && $item->product->category)
                                                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                                                    {{ $item->product->category->name }}
                                                </p>
                                            @endif
                                            <div
                                                class="flex items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400 mb-2">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                    <span>{{ __('Qty') }}: {{ $item->quantity }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>€{{ number_format($item->unit_price, 2) }}
                                                        {{ __('each') }}</span>
                                                </div>
                                            </div>

                                            @if ($item->discount > 0)
                                                <div
                                                    class="inline-flex items-center mt-1 text-xs px-2 py-0.5 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                    </svg>
                                                    {{ number_format($item->discount, 0) }}% off
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Price -->
                                        <div class="text-right">
                                            <div class="text-base font-semibold text-zinc-900 dark:text-zinc-100">
                                                €{{ number_format($item->subtotal, 2) }}</div>
                                            @if ($item->discount > 0)
                                                <div class="text-sm text-zinc-400 line-through">
                                                    €{{ number_format($item->unit_price * $item->quantity, 2) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Order Footer -->
                        <div
                            class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-200 dark:border-zinc-800">
                            <div class="text-sm text-zinc-600 dark:text-zinc-400">
                                <strong>{{ __('Delivery address') }}:</strong> {{ $order->delivery_address }}
                            </div>
                            @if ($order->nif)
                                <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                                    <strong>{{ __('NIF') }}:</strong> {{ $order->nif }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Summary & Actions -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Order Summary -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-6 sticky top-6">
                        <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-6">
                            {{ __('Order Summary') }}</h2>

                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-zinc-600 dark:text-zinc-400">{{ __('Items Subtotal') }}</span>
                                <span
                                    class="font-medium text-zinc-900 dark:text-zinc-100">€{{ number_format($order->total_items, 2) }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-zinc-600 dark:text-zinc-400">{{ __('Shipping Cost') }}</span>
                                @if ($order->shipping_cost > 0)
                                    <span
                                        class="font-medium text-zinc-900 dark:text-zinc-100">€{{ number_format($order->shipping_cost, 2) }}</span>
                                @else
                                    <span class="font-medium text-emerald-600 dark:text-emerald-400 flex items-center">
                                        <flux:icon name="truck" class="w-4 h-4 mr-1" />
                                        {{ __('Free') }}
                                    </span>
                                @endif
                            </div>

                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 mt-4">
                                <div class="flex justify-between items-center">
                                    <span
                                        class="font-semibold text-zinc-800 dark:text-zinc-200">{{ __('Total') }}</span>
                                    <span
                                        class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">€{{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        @if ($order->status == 'pending')
                            <div
                                class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                <div class="flex items-center space-x-2 mb-2">
                                    <flux:icon name="clock" class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                                    <span
                                        class="font-medium text-amber-800 dark:text-amber-200">{{ __('Processing Order') }}</span>
                                </div>
                                <p class="text-sm text-amber-700 dark:text-amber-300">
                                    {{ __('Your order is being processed.') }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <flux:button href="{{ route('orders.index') }}" variant="primary"
                            class="w-full cursor-pointer">
                            <flux:icon name="package" class="w-4 h-4 mr-2" />
                            {{ __('View All Orders') }}
                        </flux:button>

                        @if ($order->pdf_receipt)
                            <flux:button href="{{ asset('storage/receipts/' . $order->pdf_receipt) }}"
                                target="_blank" variant="outline" class="w-full cursor-pointer">
                                <flux:icon name="download" class="w-4 h-4 mr-2" />
                                {{ __('Download Receipt') }}
                            </flux:button>
                        @endif

                        <flux:button href="{{ route('catalog.index') }}" class="w-full cursor-pointer">
                            <flux:icon name="shopping-bag" class="w-4 h-4 mr-2" />
                            {{ __('Continue Shopping') }}
                        </flux:button>
                    </div>
                </div>
            </div>
        @else
            <!-- No Order Found -->
            <div class="min-h-[calc(100vh-16rem)] flex items-center justify-center">
                <div class="max-w-lg w-full bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-8 text-center">
                    <div
                        class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100 dark:bg-red-900">
                        <flux:icon name="exclamation-triangle" class="h-12 w-12 text-red-600 dark:text-red-400" />
                    </div>

                    <h2 class="mt-6 text-xl font-medium text-zinc-900 dark:text-zinc-100">
                        {{ __('Order Not Found') }}
                    </h2>

                    <p class="mt-2 text-zinc-600 dark:text-zinc-400">
                        {{ __('We could not find the order details.') }}
                    </p>

                    <div class="mt-6 space-y-3">
                        <flux:button href="{{ route('orders.index') }}" variant="primary" class="w-full cursor-pointer">
                            <flux:icon name="package" class="w-4 h-4 mr-2" />
                            {{ __('View Your Orders') }}
                        </flux:button>

                        <flux:button href="{{ route('catalog.index') }}" class="w-full cursor-pointer">
                            <flux:icon name="shopping-bag" class="w-4 h-4 mr-2" />
                            {{ __('Continue Shopping') }}
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app.header>
